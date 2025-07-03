<?php get_header(); ?>

<div class="container content-with-sidebar">
    <main class="content-area">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'template-parts/content', get_post_type() ); ?>
    <?php endwhile; else : ?>
        <p><?php esc_html_e( 'No posts found.', 'dadecore' ); ?></p>
    <?php endif; ?>
    </main>
    <?php if ( get_theme_mod( 'dadecore_display_sidebar', true ) ) : ?>
        <?php get_sidebar(); ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
