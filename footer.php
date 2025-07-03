</main><!-- #content -->
<footer class="site-footer">
    <div class="container">
        <?php
        $footer_widget_columns = get_theme_mod( 'dadecore_footer_widget_columns', 3 );
        $active_footer_sidebars = 0;
        for ( $i = 1; $i <= $footer_widget_columns; $i++ ) {
            if ( is_active_sidebar( 'footer-' . $i ) ) {
                $active_footer_sidebars++;
            }
        }

        if ( $active_footer_sidebars > 0 ) :
            $footer_widgets_class = 'footer-widgets footer-widgets-cols-' . esc_attr( $footer_widget_columns );
        ?>
            <div class="<?php echo $footer_widgets_class; ?>">
                <?php for ( $i = 1; $i <= $footer_widget_columns; $i++ ) : ?>
                    <?php if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                        <div class="footer-column widget-area">
                            <?php dynamic_sidebar( 'footer-' . $i ); ?>
                        </div>
                    <?php else : ?>
                        <?php // Opcional: renderizar un div vacío para mantener el layout grid/flex si es necesario ?>
                        <div class="footer-column widget-area empty"></div>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        <p class="site-info">&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php // Se podría añadir más info aquí o hacerla personalizable ?>
        </p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
