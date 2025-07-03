</main><!-- #content -->
<footer class="site-footer">
    <div class="container">
        <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
            <div class="footer-widgets">
                <?php for ( $i = 1; $i <= 3; $i++ ) : ?>
                    <div class="footer-column">
                        <?php dynamic_sidebar( 'footer-' . $i ); ?>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
