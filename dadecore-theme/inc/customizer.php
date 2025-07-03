<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Customizer settings for colors, fonts and blog options.
 */
function dadecore_customize_register( $wp_customize ) {
    // Colors section
    $wp_customize->add_section( 'dadecore_colors', [
        'title'    => __( 'Colores y Fuentes', 'dadecore' ),
        'priority' => 30,
    ] );

    $wp_customize->add_setting( 'dadecore_primary_color', [
        'default'           => '#2fd072',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_primary_color', [
        'label'   => __( 'Color primario', 'dadecore' ),
        'section' => 'dadecore_colors',
    ] ) );

    $wp_customize->add_setting( 'dadecore_header_bg', [
        'default'           => '#0a2540',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_header_bg', [
        'label'   => __( 'Fondo del encabezado', 'dadecore' ),
        'section' => 'dadecore_colors',
    ] ) );

    $wp_customize->add_setting( 'dadecore_footer_bg', [
        'default'           => '#0a2540',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_footer_bg', [
        'label'   => __( 'Fondo del pie', 'dadecore' ),
        'section' => 'dadecore_colors',
    ] ) );

    $font_choices = [ 'Inter' => 'Inter', 'Poppins' => 'Poppins' ];

    $wp_customize->add_setting( 'dadecore_body_font', [
        'default'           => 'Inter',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'dadecore_body_font', [
        'type'    => 'select',
        'label'   => __( 'Fuente del cuerpo', 'dadecore' ),
        'section' => 'dadecore_colors',
        'choices' => $font_choices,
    ] );

    $wp_customize->add_setting( 'dadecore_heading_font', [
        'default'           => 'Poppins',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'dadecore_heading_font', [
        'type'    => 'select',
        'label'   => __( 'Fuente de títulos', 'dadecore' ),
        'section' => 'dadecore_colors',
        'choices' => $font_choices,
    ] );

    // Blog section
    $wp_customize->add_section( 'dadecore_blog', [
        'title'    => __( 'Ajustes del Blog', 'dadecore' ),
        'priority' => 35,
    ] );

    $wp_customize->add_setting( 'dadecore_excerpt_length', [
        'default'           => 55,
        'sanitize_callback' => 'absint',
    ] );
    $wp_customize->add_control( 'dadecore_excerpt_length', [
        'label'   => __( 'Longitud del extracto', 'dadecore' ),
        'section' => 'dadecore_blog',
        'type'    => 'number',
    ] );

    $wp_customize->add_setting( 'dadecore_display_sidebar', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ] );
    $wp_customize->add_control( 'dadecore_display_sidebar', [
        'label'   => __( 'Mostrar barra lateral', 'dadecore' ),
        'section' => 'dadecore_blog',
        'type'    => 'checkbox',
    ] );
}
add_action( 'customize_register', 'dadecore_customize_register' );

/**
 * Output custom styles based on Customizer settings.
 */
function dadecore_customizer_css() {
    $primary     = get_theme_mod( 'dadecore_primary_color', '#2fd072' );
    $header_bg   = get_theme_mod( 'dadecore_header_bg', '#0a2540' );
    $footer_bg   = get_theme_mod( 'dadecore_footer_bg', '#0a2540' );
    $body_font   = get_theme_mod( 'dadecore_body_font', 'Inter' );
    $heading_font = get_theme_mod( 'dadecore_heading_font', 'Poppins' );
    ?>
    <style type="text/css">
        body { font-family: '<?php echo esc_attr( $body_font ); ?>', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: '<?php echo esc_attr( $heading_font ); ?>', sans-serif; }
        a { color: <?php echo esc_attr( $primary ); ?>; }
        .site-header { background-color: <?php echo esc_attr( $header_bg ); ?>; }
        .site-footer { background-color: <?php echo esc_attr( $footer_bg ); ?>; }
    </style>
    <?php
}
add_action( 'wp_head', 'dadecore_customizer_css' );

/**
 * Adjust excerpt length based on Customizer setting.
 */
function dadecore_excerpt_length( $length ) {
    return absint( get_theme_mod( 'dadecore_excerpt_length', 55 ) );
}
add_filter( 'excerpt_length', 'dadecore_excerpt_length' );

/**
 * Body class to hide sidebar when disabled.
 */
function dadecore_body_classes( $classes ) {
    if ( ! get_theme_mod( 'dadecore_display_sidebar', true ) ) {
        $classes[] = 'no-sidebar';
    }
    return $classes;
}
add_filter( 'body_class', 'dadecore_body_classes' );
