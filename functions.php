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
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );

/**
 * Register widget areas.
 */
function dadecore_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Primary Sidebar', 'dadecore' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Main sidebar that appears on the right.', 'dadecore' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ] );

    for ( $i = 1; $i <= 3; $i++ ) {
        register_sidebar( [
            'name'          => sprintf( __( 'Footer Column %d', 'dadecore' ), $i ),
            'id'            => 'footer-' . $i,
            'description'   => __( 'Widget area in the footer.', 'dadecore' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
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
    $options = get_option( 'dadecore_options' );
    $slug = $options['login_slug'] ?? 'login';
    add_rewrite_rule( '^' . $slug . '/?$', 'wp-login.php', 'top' );
}
add_action( 'init', 'dadecore_custom_login_rewrite' );
