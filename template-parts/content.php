<article id="post-<?php the_ID(); ?>" <?php post_class( is_singular() ? 'entry-single' : 'entry-summary' ); ?>>

    <?php do_action( 'dadecore_before_post_content_box' ); ?>

    <?php if ( is_singular() ) : ?>
        <?php do_action( 'dadecore_before_single_header' ); ?>
        <header class="entry-header">
            <?php do_action( 'dadecore_before_single_title' ); ?>
            <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <?php do_action( 'dadecore_after_single_title' ); ?>

            <div class="entry-meta">
                <?php do_action( 'dadecore_before_single_meta' ); ?>
                <span class="post-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
                <span class="post-author">
                    <?php esc_html_e( 'Por', 'dadecore' ); ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
                <?php do_action( 'dadecore_after_single_meta' ); ?>
            </div><!-- .entry-meta -->
        </header><!-- .entry-header -->
        <?php do_action( 'dadecore_after_single_header' ); ?>
    <?php else : ?>
        <?php // Para cuando content.php se usa en listados (ej. contenido completo) ?>
        <?php do_action( 'dadecore_before_list_entry_header' ); ?>
        <header class="entry-header">
            <?php do_action( 'dadecore_before_list_entry_title' ); ?>
            <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <?php do_action( 'dadecore_after_list_entry_title' ); ?>
             <?php // Podríamos añadir meta aquí también si se usa para contenido completo en listados ?>
        </header>
        <?php do_action( 'dadecore_after_list_entry_header' ); ?>
    <?php endif; ?>


    <?php if ( is_singular() && has_post_thumbnail() && get_theme_mod('dadecore_single_display_featured_image', true) ) : ?>
        <?php do_action( 'dadecore_before_single_featured_image' ); ?>
        <div class="post-thumbnail single-featured-image">
            <?php the_post_thumbnail( 'full' ); // Usar 'full' o un tamaño grande para single ?>
        </div>
        <?php do_action( 'dadecore_after_single_featured_image' ); ?>
    <?php endif; ?>

    <?php do_action( 'dadecore_before_entry_content' ); ?>
    <div class="entry-content">
        <?php
        the_content( sprintf(
            wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                __( 'Continue reading<span class="sr-only"> "%s"</span>', 'dadecore' ),
                [
                    'span' => [
                        'class' => [],
                    ],
                ]
            ),
            get_the_title()
        ) );

        wp_link_pages( [
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'dadecore' ),
            'after'  => '</div>',
        ] );
        ?>
    </div><!-- .entry-content -->
    <?php do_action( 'dadecore_after_entry_content' ); ?>

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

        <?php
        // Mostrar entradas relacionadas
        if ( function_exists( 'dadecore_display_related_posts' ) ) {
            dadecore_display_related_posts();
        }

        // Si los comentarios están abiertos o tenemos al menos un comentario, cargar el template de comentarios.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        ?>
    <?php else : // Para cuando content.php se usa en listados y tiene footer ?>
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
