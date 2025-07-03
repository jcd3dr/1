<?php get_header(); ?>

<?php
$sidebar_position = get_theme_mod( 'dadecore_sidebar_position', 'derecha' );
$container_class = 'container content-with-sidebar';
if ( $sidebar_position === 'izquierda' && is_active_sidebar( 'sidebar-1' ) ) {
    $container_class .= ' sidebar-left';
}
// No necesitamos una clase específica para sidebar-right ya que es el orden por defecto de flex.
?>

<div class="<?php echo esc_attr( $container_class ); ?>">
    <?php
    // Si el sidebar está a la izquierda y activo, mostrarlo primero.
    if ( $sidebar_position === 'izquierda' && is_active_sidebar( 'sidebar-1' ) ) {
        get_sidebar();
    }
    ?>
    <main id="main" class="site-main"> <?php // Usar site-main como en home.php para consistencia ?>
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php
                // Determinar qué template part usar (content.php o content-format.php)
                // Para index.php, generalmente es para 'page' o fallback de 'single' o archivos.
                // Usaremos 'content' y dejaremos que get_template_part maneje los formatos.
                $template_slug = 'content';
                // Si es un CPT y tiene un content-cptslug.php, se usará.
                // Si tiene formato, se usará content-format.php.
                // Si no, content.php.
                get_template_part( 'template-parts/' . $template_slug, get_post_format() ?: get_post_type() );
                ?>
            <?php endwhile; ?>
            <?php
            // Argumentos de paginación desde el Customizer (si aplica a index.php, ej. si es un archivo)
            // En un single/page, the_posts_pagination no hace nada.
            $pagination_args_index = [
                'mid_size'           => get_theme_mod( 'dadecore_pagination_mid_size', 2 ),
                'end_size'           => get_theme_mod( 'dadecore_pagination_end_size', 1 ),
                'prev_next'          => get_theme_mod( 'dadecore_pagination_show_prev_next', true ),
                'prev_text'          => get_theme_mod( 'dadecore_pagination_prev_text', __( '&laquo; Anterior', 'dadecore' ) ),
                'next_text'          => get_theme_mod( 'dadecore_pagination_next_text', __( 'Siguiente &raquo;', 'dadecore' ) ),
                'screen_reader_text' => __( 'Navegación de entradas', 'dadecore' ),
            ];
            the_posts_pagination( $pagination_args_index );
            ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content', 'none' ); ?>
        <?php endif; ?>
    </main>
    <?php
    // Si el sidebar está a la derecha y activo, mostrarlo después.
    if ( $sidebar_position === 'derecha' && is_active_sidebar( 'sidebar-1' ) ) {
        get_sidebar();
    }
    ?>
</div>

<?php get_footer(); ?>
