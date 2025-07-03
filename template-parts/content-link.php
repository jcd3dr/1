<?php
/**
 * Template part for displaying posts with the 'link' post format.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DadeCore
 */

// Intenta encontrar el primer enlace en el contenido del post.
$content = get_the_content();
$has_url = get_url_in_content( $content );
$first_url = $has_url ? $has_url : '';

// Si no hay URL en el contenido, usa el permalink como fallback para el título.
$link_title_url = $first_url ? $first_url : get_permalink();
$link_target = $first_url ? '_blank' : '_self'; // Abrir enlaces externos en nueva pestaña

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-format-link' . ( is_singular() ? ' entry-single' : ' entry-summary' ) ); ?>>
    <?php do_action( 'dadecore_before_post_content_box' ); ?>

    <?php do_action( 'dadecore_before_link_header' ); ?>
    <header class="entry-header">
        <?php do_action( 'dadecore_before_link_title' ); ?>
        <?php
        // Usar el título del post, pero enlazarlo al $link_title_url
        the_title(
            sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark" target="%s">', esc_url( $link_title_url ), esc_attr( $link_target ) ),
            sprintf( ' <span class="link-arrow">&rarr;</span></a></h2>' )
        );
        ?>
        <?php do_action( 'dadecore_after_link_title' ); ?>

        <?php if ( is_singular() || ! $first_url ) : // Mostrar meta si es single o si no hay un enlace claro para "ir a" ?>
            <div class="entry-meta">
                <?php do_action( 'dadecore_before_link_meta' ); ?>
                <span class="post-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
                <span class="post-author">
                    <?php esc_html_e( 'Por', 'dadecore' ); ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
                <?php if ($first_url && !is_singular()) : // Mostrar el enlace de origen si estamos en un listado y hay un enlace externo ?>
                <span class="link-source">
                    <?php esc_html_e( 'Fuente:', 'dadecore' ); ?> <a href="<?php echo esc_url($first_url); ?>" target="_blank"><?php echo esc_html( wp_parse_url($first_url, PHP_URL_HOST) ); ?></a>
                </span>
                <?php endif; ?>
                <?php do_action( 'dadecore_after_link_meta' ); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->
    <?php do_action( 'dadecore_after_link_header' ); ?>

    <?php
    // En la vista 'single', mostrar el contenido del post si existe (podría ser una descripción del enlace).
    // En listados, generalmente no se muestra el contenido para el formato 'link', solo el título/enlace.
    ?>
    <?php if ( is_singular() && ! empty( trim( $content ) ) ) : ?>
        <?php do_action( 'dadecore_before_entry_content' ); ?>
        <div class="entry-content">
            <?php
            // Quitar el primer enlace del contenido si ya lo usamos para el título, para no duplicarlo.
            // Esto es una simplificación. Un reemplazo más robusto podría ser necesario.
            if ( $first_url ) {
                $content_without_first_url = preg_replace( '{<a[^>]*>' . preg_quote( $first_url, '/' ) . '.*?</a>}is', '', $content, 1 );
                echo apply_filters( 'the_content', $content_without_first_url );
            } else {
                the_content();
            }

            wp_link_pages( [
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dadecore' ),
                'after'  => '</div>',
            ] );
            ?>
        </div><!-- .entry-content -->
        <?php do_action( 'dadecore_after_entry_content' ); ?>
    <?php endif; ?>


    <?php if ( is_singular() ) : ?>
        <?php do_action( 'dadecore_before_single_footer' ); ?>
        <footer class="entry-footer">
            <?php do_action( 'dadecore_single_footer_top' ); ?>
             <div class="entry-meta-footer">
                <span class="cat-links"><?php esc_html_e( 'Categorías: ', 'dadecore' ); ?><?php the_category( ', ' ); ?></span>
                <span class="tags-links"><?php the_tags( esc_html__( 'Etiquetas: ', 'dadecore' ), ', ', '' ); ?></span>
            </div>
            <?php if ( comments_open() || get_comments_number() ) : ?>
                <span class="comments-link">
                    <?php comments_popup_link( __( 'Dejar un comentario', 'dadecore' ), __( '1 Comentario', 'dadecore' ), __( '% Comentarios', 'dadecore' ) ); ?>
                </span>
            <?php endif; ?>
            <?php do_action( 'dadecore_single_footer_bottom' ); ?>
        </footer><!-- .entry-footer -->
        <?php do_action( 'dadecore_after_single_footer' ); ?>
    <?php elseif ( ! $first_url ) : // Si no hay un enlace claro, proporcionar un "leer más" en listados ?>
        <?php do_action( 'dadecore_before_list_entry_footer' ); ?>
         <footer class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php esc_html_e( 'Leer más', 'dadecore' ); ?> <span class="sr-only"> <?php echo esc_html(get_the_title()); ?></span> &raquo;
            </a>
        </footer>
        <?php do_action( 'dadecore_after_list_entry_footer' ); ?>
    <?php endif; ?>
    <?php do_action( 'dadecore_after_post_content_box' ); ?>
</article>
