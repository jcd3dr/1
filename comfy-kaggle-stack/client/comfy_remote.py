#!/usr/bin/env python3
"""Cliente de PC para el servidor ComfyUI que corre en Kaggle (túnel Cloudflare).

Variables de entorno:
  COMFY_URL                 https://tu-hostname.ejemplo.com  (sin barra final)
  CF_ACCESS_CLIENT_ID       opcional: service token de Cloudflare Access
  CF_ACCESS_CLIENT_SECRET   opcional

Los workflows (formato API) los exporta la Fase 4 del notebook a /kaggle/working/workflows/.
Descárgalos con:  kaggle kernels output jcdcruz/comfyui-kaggle-t4-v5 -p ./kaggle_out
y pasa su carpeta con --workflows.

Uso:
  python comfy_remote.py status
  python comfy_remote.py t2i   --prompt "..." [--w 1280 --h 720 --seed 7]
  python comfy_remote.py i2v   --image foto.png --prompt "..." [--length 81]
  python comfy_remote.py video --prompt-image "..." --prompt-video "..." [--length 81]
"""
import argparse
import json
import os
import sys
import time
import uuid
from pathlib import Path

import requests

URL = os.environ.get("COMFY_URL", "").rstrip("/")
HEADERS = {}
if os.environ.get("CF_ACCESS_CLIENT_ID") and os.environ.get("CF_ACCESS_CLIENT_SECRET"):
    HEADERS = {
        "CF-Access-Client-Id": os.environ["CF_ACCESS_CLIENT_ID"],
        "CF-Access-Client-Secret": os.environ["CF_ACCESS_CLIENT_SECRET"],
    }

RETRY_STATUS = {502, 503, 504, 520, 521, 522, 523, 524}


def api(method, path, retries=5, **kw):
    """Petición con reintentos: el túnel puede soltar una conexión puntual."""
    kw.setdefault("timeout", 60)
    kw["headers"] = {**HEADERS, **kw.get("headers", {})}
    last = None
    for attempt in range(retries):
        try:
            r = requests.request(method, f"{URL}{path}", **kw)
            if r.status_code not in RETRY_STATUS:
                return r
            last = f"HTTP {r.status_code}"
        except requests.RequestException as e:
            last = str(e)
        time.sleep(min(2 ** attempt, 20))
    raise RuntimeError(f"{method} {path} falló tras {retries} intentos: {last}")


def load_workflow(folder, name):
    p = Path(folder) / name
    if not p.exists():
        sys.exit(f"No encuentro {p}. Descarga los workflows del notebook (ver cabecera).")
    return json.loads(p.read_text())


def upload_image(path):
    with open(path, "rb") as f:
        r = api("POST", "/upload/image", files={"image": (Path(path).name, f)}, data={"overwrite": "true"})
    r.raise_for_status()
    return r.json()["name"]


def submit(wf):
    r = api("POST", "/prompt", json={"prompt": wf, "client_id": str(uuid.uuid4())})
    if r.status_code != 200:
        sys.exit(f"ComfyUI rechazó el workflow: {r.text[:2000]}")
    return r.json()["prompt_id"]


def wait(pid, timeout=7200, poll=5):
    t0 = time.time()
    while True:
        h = api("GET", f"/history/{pid}").json()
        if pid in h:
            entry = h[pid]
            st = entry.get("status", {})
            if st.get("status_str") == "error":
                for m in st.get("messages", []):
                    if m[0] == "execution_error":
                        print(json.dumps(m[1], indent=1)[:2000], file=sys.stderr)
                sys.exit("Error de ejecución en ComfyUI")
            return entry
        if time.time() - t0 > timeout:
            sys.exit(f"Tiempo agotado esperando {pid}")
        print(f"\r  esperando... {int(time.time() - t0)} s", end="", flush=True)
        time.sleep(poll)


def download(entry, outdir):
    Path(outdir).mkdir(parents=True, exist_ok=True)
    files = []
    for out in entry.get("outputs", {}).values():
        for items in out.values():
            if not isinstance(items, list):
                continue
            for it in items:
                if isinstance(it, dict) and "filename" in it:
                    r = api("GET", "/view", params={
                        "filename": it["filename"],
                        "subfolder": it.get("subfolder", ""),
                        "type": it.get("type", "output"),
                    }, timeout=600)
                    r.raise_for_status()
                    dest = Path(outdir) / it["filename"]
                    dest.write_bytes(r.content)
                    files.append(dest)
    return files


# --- rellenado de workflows (ids de nodo de los workflows del notebook v4/v5) ---
def fill_zimage(wf, prompt, w, h, seed, steps):
    wf["5"]["inputs"]["text"] = prompt          # CLIPTextEncode
    wf["7"]["inputs"].update(width=w, height=h)  # EmptySD3LatentImage
    wf["8"]["inputs"].update(seed=seed, steps=steps)  # KSampler
    return wf


def fill_wan(wf, image_name, prompt, neg, length, seed, w, h):
    wf["9"]["inputs"]["text"] = prompt           # CLIPTextEncode positivo
    wf["10"]["inputs"]["text"] = neg             # CLIPTextEncode negativo
    wf["11"]["inputs"]["image"] = image_name     # LoadImage
    wf["12"]["inputs"].update(length=length, width=w, height=h)  # WanImageToVideo
    wf["13"]["inputs"]["noise_seed"] = seed      # KSamplerAdvanced (alto ruido)
    wf["14"]["inputs"]["noise_seed"] = seed      # KSamplerAdvanced (bajo ruido)
    return wf


def run(wf, outdir):
    pid = submit(wf)
    print(f"prompt_id: {pid}")
    entry = wait(pid)
    print()
    files = download(entry, outdir)
    for f in files:
        print("guardado:", f)
    return files


def cmd_status(a):
    s = api("GET", "/system_stats").json()
    for d in s.get("devices", []):
        print(f"{d['name']}: {d['vram_total'] / 2**30:.1f} GiB totales, {d['vram_free'] / 2**30:.1f} GiB libres")
    q = api("GET", "/queue").json()
    print(f"cola: {len(q.get('queue_running', []))} en curso, {len(q.get('queue_pending', []))} pendientes")


def cmd_t2i(a):
    wf = fill_zimage(load_workflow(a.workflows, "zimage_t2i.json"), a.prompt, a.w, a.h, a.seed, a.steps)
    return run(wf, a.out)


def cmd_i2v(a):
    name = upload_image(a.image)
    wf = fill_wan(load_workflow(a.workflows, "wan_i2v_81f.json"), name, a.prompt, a.neg, a.length, a.seed, a.vw, a.vh)
    return run(wf, a.out)


def cmd_video(a):
    imgs = cmd_t2i(argparse.Namespace(**{**vars(a), "prompt": a.prompt_image, "w": 1280, "h": 720}))
    png = next((f for f in imgs if f.suffix.lower() in (".png", ".jpg", ".jpeg", ".webp")), None)
    if not png:
        sys.exit("La fase de imagen no devolvió ninguna imagen")
    return cmd_i2v(argparse.Namespace(**{**vars(a), "image": str(png), "prompt": a.prompt_video}))


def main():
    p = argparse.ArgumentParser(description=__doc__, formatter_class=argparse.RawDescriptionHelpFormatter)
    p.add_argument("--workflows", default="./workflows", help="carpeta con zimage_t2i.json y wan_i2v_81f.json")
    p.add_argument("--out", default="./salidas")
    sub = p.add_subparsers(dest="cmd", required=True)

    sub.add_parser("status").set_defaults(fn=cmd_status)

    t = sub.add_parser("t2i")
    t.add_argument("--prompt", required=True)
    t.add_argument("--w", type=int, default=1280)
    t.add_argument("--h", type=int, default=720)
    t.add_argument("--seed", type=int, default=42)
    t.add_argument("--steps", type=int, default=8)
    t.set_defaults(fn=cmd_t2i)

    def video_args(x):
        x.add_argument("--neg", default="")
        x.add_argument("--length", type=int, default=81, help="frames (33 = 2 s, 81 = 5 s a 16 fps)")
        x.add_argument("--vw", type=int, default=832)
        x.add_argument("--vh", type=int, default=480)
        x.add_argument("--seed", type=int, default=42)

    i = sub.add_parser("i2v")
    i.add_argument("--image", required=True)
    i.add_argument("--prompt", required=True)
    video_args(i)
    i.set_defaults(fn=cmd_i2v)

    v = sub.add_parser("video", help="texto -> imagen (Z-Image) -> video (Wan 2.2 I2V)")
    v.add_argument("--prompt-image", required=True)
    v.add_argument("--prompt-video", required=True)
    v.add_argument("--steps", type=int, default=8)
    video_args(v)
    v.set_defaults(fn=cmd_video)

    a = p.parse_args()
    if not URL:
        sys.exit("Define COMFY_URL (p.ej. https://comfy.tudominio.com)")
    a.fn(a)


if __name__ == "__main__":
    main()
