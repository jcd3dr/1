<?php
/**
 * Template part for displaying posts with the 'quote' post format.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package DadeCore
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-format-quote' . ( is_singular() ? ' entry-single' : ' entry-summary' ) ); ?>>
    <?php do_action( 'dadecore_before_post_content_box' ); ?>

    <?php if ( ! is_singular() ) : // En listados, el título puede ser útil como enlace ?>
        <?php do_action( 'dadecore_before_list_entry_header' ); ?>
        <header class="entry-header">
            <?php do_action( 'dadecore_before_list_entry_title' ); ?>
            <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            <?php do_action( 'dadecore_after_list_entry_title' ); ?>
        </header>
        <?php do_action( 'dadecore_after_list_entry_header' ); ?>
    <?php endif; ?>

    <?php do_action( 'dadecore_before_entry_content' ); ?>
    <div class="entry-content">
        <?php
        // Si hay un bloque de cita, usarlo. Si no, todo el contenido.
        if ( has_block( 'core/quote', get_the_content() ) ) {
            $content = get_the_content(); // Obtener todo el contenido
            // Extraer y mostrar solo el primer bloque de cita.
            // Esto es simplificado; un parseo más robusto sería mejor.
            $blocks = parse_blocks( $content );
            $quote_block_html = '';
            foreach ( $blocks as $block ) {
                if ( 'core/quote' === $block['blockName'] ) {
                    $quote_block_html = render_block( $block );
                    break;
                }
            }
            if ( ! empty( $quote_block_html ) ) {
                echo wp_kses_post( $quote_block_html ); // Muestra el bloque de cita
            } else {
                // Fallback a mostrar el contenido completo si no se pudo extraer el bloque de cita
                the_content( sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="sr-only"> "%s"</span>', 'dadecore' ),
                        [ 'span' => [ 'class' => [] ] ]
                    ),
                    get_the_title()
                ) );
            }
        } else {
            // Si no hay bloque de cita, mostrar el contenido como una cita
            echo '<blockquote>';
            the_content( sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Continue reading<span class="sr-only"> "%s"</span>', 'dadecore' ),
                    [ 'span' => [ 'class' => [] ] ]
                ),
                get_the_title()
            ) );
            echo '</blockquote>';

            // Intentar encontrar una etiqueta <cite> o el autor del post para la cita
            $citation = '';
            if ( preg_match( '/<cite>(.*?)<\/cite>/is', get_the_content(), $matches ) ) {
                $citation = $matches[1];
            }

            if ( ! empty( $citation ) ) {
                echo '<cite>' . esc_html( $citation ) . '</cite>';
            } elseif ( is_singular() ) { // Solo mostrar autor del post como fallback en single view
                echo '<cite class="quote-author-fallback">&mdash; ' . esc_html( get_the_author() ) . '</cite>';
            }
        }

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
            <div class="entry-meta"> <?php // Usamos entry-meta para consistencia de estilos ?>
                <span class="post-date"><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></span>
                <span class="post-author">
                    <?php esc_html_e( 'Publicado por', 'dadecore' ); ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
                        <?php echo esc_html( get_the_author() ); ?>
                    </a>
                </span>
                 <span class="cat-links"><?php esc_html_e( 'en ', 'dadecore' ); ?><?php the_category( ', ' ); ?></span>
            </div>
            <?php the_tags( '<div class="tags-links">' . esc_html__( 'Etiquetas: ', 'dadecore' ), ', ', '</div>' ); ?>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <span class="comments-link">
                    <?php comments_popup_link( __( 'Dejar un comentario', 'dadecore' ), __( '1 Comentario', 'dadecore' ), __( '% Comentarios', 'dadecore' ) ); ?>
                </span>
            <?php endif; ?>
            <?php do_action( 'dadecore_single_footer_bottom' ); ?>
        </footer><!-- .entry-footer -->
        <?php do_action( 'dadecore_after_single_footer' ); ?>
    <?php else : // Para listados, un enlace a la entrada es útil ?>
        <?php do_action( 'dadecore_before_list_entry_footer' ); ?>
         <footer class="entry-footer">
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php esc_html_e( 'Ver entrada', 'dadecore' ); ?> <span class="sr-only"> <?php echo esc_html(get_the_title()); ?></span> &raquo;
            </a>
        </footer>
        <?php do_action( 'dadecore_after_list_entry_footer' ); ?>
    <?php endif; ?>
    <?php do_action( 'dadecore_after_post_content_box' ); ?>
</article>
