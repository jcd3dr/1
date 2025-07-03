<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Setup theme defaults and registers support for various WordPress features.
 */
function dadecore_setup() {
    // Make theme available for translation.
    load_theme_textdomain( 'dadecore', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus.
    register_nav_menus( [
        'menu-1' => __( 'Primary', 'dadecore' ),
    ] );

    // Switch default core markup for search form, comment form, and comments to output valid HTML5.
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );

    // Add support for custom logo.
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 80,
        'flex-width'  => true,
        'flex-height' => true,
    ] );

    // Editor styles to match frontend.
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor.css' );

    // Wide and full align images.
    add_theme_support( 'align-wide' );
}
add_action( 'after_setup_theme', 'dadecore_setup' );

/**
 * Enqueue scripts and styles.
 */
function dadecore_scripts() {
    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style( 'dadecore-style', get_stylesheet_uri(), [], $theme_version );
    wp_enqueue_style( 'dadecore-main', get_template_directory_uri() . '/assets/css/main.css', [], $theme_version );

    wp_enqueue_script( 'dadecore-main', get_template_directory_uri() . '/assets/js/main.js', [], $theme_version, true );

    // Enqueue Google Fonts
    wp_enqueue_style( 'dadecore-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Poppins:wght@400;700&display=swap', [], null );
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );

/**
 * Enqueue styles for the block editor.
 */
function dadecore_editor_assets() {
    // Enqueue Google Fonts for the editor
    wp_enqueue_style( 'dadecore-google-fonts-editor', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Poppins:wght@400;700&display=swap', [], null );
}
add_action( 'enqueue_block_editor_assets', 'dadecore_editor_assets' );

/**
 * Register widget areas.
 */
function dadecore_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Primary Sidebar', 'dadecore' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Main sidebar for the blog and other pages.', 'dadecore' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">', // Cambiado a h3 por semántica, h2 podría ser el título de la página/sección
        'after_title'   => '</h3>',
    ] );

    // Área de Widgets para el CTA Final en la página del blog
    register_sidebar( [
        'name'          => __( 'Blog CTA Final', 'dadecore' ),
        'id'            => 'cta-final-widget-area',
        'description'   => __( 'Widget area for the final Call to Action section on the blog page.', 'dadecore' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">', // Usar div para más flexibilidad en esta sección
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title sr-only">', // Título del widget oculto para accesibilidad si el diseño no lo requiere visible
        'after_title'   => '</h2>',
    ] );

    for ( $i = 1; $i <= 3; $i++ ) {
        register_sidebar( [
            'name'          => sprintf( __( 'Footer Column %d', 'dadecore' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => __( 'Widget area in the footer.', 'dadecore' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ] );
    }
}
add_action( 'widgets_init', 'dadecore_widgets_init' );

/**
 * Add theme options page.
 */
require get_template_directory() . '/inc/options.php';
require get_template_directory() . '/inc/customizer.php';
require get_template_directory() . '/inc/sample-content.php';

/**
 * Output basic meta tags for SEO and Open Graph.
 */
function dadecore_meta_tags() {
    if ( is_singular() ) {
        global $post;
        $description = get_the_excerpt( $post );
        $title = get_the_title( $post );
        $image = get_the_post_thumbnail_url( $post, 'full' );
        $url = get_permalink( $post );
    } else {
        $description = get_bloginfo( 'description' );
        $title = get_bloginfo( 'name' );
        $image = get_site_icon_url();
        $url = home_url();
    }
    ?>
    <meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
    <meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
    <?php if ( $image ) : ?>
    <meta property="og:image" content="<?php echo esc_url( $image ); ?>" />
    <?php endif; ?>
    <meta property="og:type" content="<?php echo is_singular() ? 'article' : 'website'; ?>" />
    <meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
    <meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <?php
}
add_action( 'wp_head', 'dadecore_meta_tags' );

/**
 * Change login URL slug.
 */
function dadecore_custom_login_rewrite() {
    $options = get_option( 'dadecore_theme_options' ); // Updated option name
    $slug = $options['login_slug'] ?? 'login'; // Key within the new options array

    // Ensure the slug is not empty and is a string before adding the rule
    if ( !empty( $slug ) && is_string( $slug ) ) {
        // Remove the old rule if it exists, to prevent conflicts if the slug changes
        // This requires knowing the old slug, which might be tricky if not stored
        // For simplicity now, we rely on flushing rewrite rules after changing the slug.
        // A more robust solution might involve storing the 'old_slug' temporarily.

        add_rewrite_rule( '^' . trim( $slug, '/' ) . '/?$', 'wp-login.php', 'top' );
    }
}
add_action( 'init', 'dadecore_custom_login_rewrite' );

/**
 * Flush rewrite rules if the login slug has been changed.
 * This is important for the new login URL to work immediately.
 */
function dadecore_flush_rewrite_rules_on_slug_change() {
    $option_name = 'dadecore_theme_options';
    $options = get_option( $option_name );

    // Get the value of the setting as it's stored in the database.
    $transient_name = 'dadecore_login_slug_changed';

    if ( get_transient( $transient_name ) ) {
        flush_rewrite_rules();
        delete_transient( $transient_name );
    }
}
add_action( 'admin_init', 'dadecore_flush_rewrite_rules_on_slug_change' );

// We need to set a transient when the specific option is updated.
// This is typically done within the sanitize callback or by hooking into `update_option_{option_name}`.
// Let's modify the sanitize callback in inc/options.php slightly for this.
// This part is conceptual for now as direct modification of options.php has already happened.
// The better way is to use the `update_option_{$option}` hook.

add_action( 'update_option_dadecore_theme_options', function( $old_value, $value ) {
    if ( ( $old_value['login_slug'] ?? 'login' ) !== ( $value['login_slug'] ?? 'login' ) ) {
        set_transient( 'dadecore_login_slug_changed', 1, HOUR_IN_SECONDS );
    }
}, 10, 2 );
