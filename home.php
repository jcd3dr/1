<?php get_header(); ?>

<div id="blog-page" class="blog-page-container">

    <section class="hero-section">
        <div class="container">
            <h1 class="page-title">
                <?php
                // Si es la página de inicio del blog (últimas entradas)
                if ( is_home() && ! is_front_page() ) {
                    single_post_title(); // Muestra el título de la página asignada como "Página de Entradas"
                } elseif ( is_category() ) {
                    single_cat_title(); // Muestra el título de la categoría
                } elseif ( is_tag() ) {
                    single_tag_title(); // Muestra el título de la etiqueta
                } elseif ( is_author() ) {
                    the_archive_title(); // Muestra el título del autor
                } elseif ( is_day() || is_month() || is_year() ) {
                    the_archive_title(); // Muestra el título del archivo por fecha
                } else {
                    echo esc_html__( 'Blog', 'dadecore' ); // Título genérico
                }
                ?>
            </h1>
            <?php
            // Subtítulo opcional - se podría añadir un campo en el Customizer para esto
            $blog_subtitle = get_theme_mod( 'blog_hero_subtitle', '' );
            if ( ! empty( $blog_subtitle ) ) :
                ?>
                <p class="page-subtitle"><?php echo esc_html( $blog_subtitle ); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <div class="container main-area-container">
        <div class="content-with-sidebar">
            <?php
            $sidebar_position = get_theme_mod( 'dadecore_sidebar_position', 'derecha' );
            $sidebar_class = '';
            if ( $sidebar_position === 'izquierda' ) {
                $sidebar_class = 'sidebar-left';
            } elseif ( $sidebar_position === 'derecha' ) {
                $sidebar_class = 'sidebar-right'; // Aunque 'sidebar-right' podría no ser necesaria si es el comportamiento por defecto sin clase
            }
            // Si es 'ninguno', no se añade clase y el sidebar no se muestra.
            ?>
            <?php
            $blog_view = get_theme_mod( 'dadecore_blog_view', 'lista' );
            $main_class = 'site-main view-' . esc_attr( $blog_view );
            ?>
            <div class="content-with-sidebar <?php echo esc_attr( $sidebar_class ); ?>">
                <main id="main" class="<?php echo $main_class; ?>">
                    <?php if ( have_posts() ) : ?>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php get_template_part( 'template-parts/content', get_post_type() !== 'post' ? get_post_type() : 'excerpt' ); ?>
                        <?php endwhile; ?>
                        <?php the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => esc_html__( '&laquo; Anterior', 'dadecore' ),
                            'next_text' => esc_html__( 'Siguiente &raquo;', 'dadecore' ),
                        ) ); ?>
                    <?php else : ?>
                        <?php get_template_part( 'template-parts/content', 'none' ); ?>
                    <?php endif; ?>
                </main>

                <?php
                if ( $sidebar_position !== 'ninguno' ) {
                    get_sidebar();
                }
                ?>
            </div><!-- .content-with-sidebar -->
        </div><!-- .container.main-area-container -->

    <section class="cta-final-section">
        <div class="container">
            <?php
            // Placeholder para el CTA Final. Se podría popular con un widget, shortcode o contenido del Customizer.
            // Ejemplo: echo do_shortcode( get_theme_mod('final_cta_shortcode', '') );
            if ( is_active_sidebar( 'cta-final-widget-area' ) ) {
                dynamic_sidebar( 'cta-final-widget-area' );
            } else {
                echo '<p>' . esc_html__( 'Aquí va un llamado a la acción importante. ¡Configúralo!', 'dadecore' ) . '</p>';
            }
            ?>
        </div>
    </section>

</div><!-- #blog-page -->

<?php get_footer(); ?>
