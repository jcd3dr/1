<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-summary' ); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail( 'large' ); // 'large' o el tamaño que prefieras ?>
            </a>
        </div>
    <?php endif; ?>

    <header class="entry-header">
        <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

        <div class="entry-meta">
            <?php if ( get_theme_mod( 'dadecore_display_post_date', true ) ) : ?>
                <span class="post-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
            <?php endif; ?>

            <?php if ( get_theme_mod( 'dadecore_display_post_author', true ) ) : ?>
                <span class="post-author">
                    <?php esc_html_e( 'Por', 'dadecore' ); ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
            <?php endif; ?>
        </div><!-- .entry-meta -->
    </header>

    <?php if ( get_theme_mod( 'dadecore_display_post_summary', true ) ) : ?>
        <div class="entry-summary">
            <?php the_excerpt(); ?>
        </div>
    <?php endif; ?>

    <?php if ( get_theme_mod( 'dadecore_display_post_read_more', true ) ) : ?>
        <footer class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php esc_html_e( 'Leer más', 'dadecore' ); ?> <span class="sr-only"> <?php echo esc_html(get_the_title()); ?></span> &raquo;
            </a>
        </footer>
    <?php endif; ?>
</article>
