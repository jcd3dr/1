<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    </header>

    <div class="entry-content">
        <?php the_content(); ?>
    </div>

    <footer class="entry-footer">
        <?php if ( comments_open() || get_comments_number() ) : ?>
            <span class="comments-link">
                <?php comments_popup_link( __( 'Leave a comment', 'dadecore' ), __( '1 Comment', 'dadecore' ), __( '% Comments', 'dadecore' ) ); ?>
            </span>
        <?php endif; ?>
    </footer>
</article>
