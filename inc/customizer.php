<?php
// inc/customizer.php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Customizer settings for DadeCore Theme.
 */
function dadecore_customize_register( $wp_customize ) {

    // 1. PANEL PRINCIPAL DADECORE
    //=============================================
    $wp_customize->add_panel( 'dadecore_main_panel', [
        'title'       => __( 'Opciones DadeCore Theme', 'dadecore' ),
        'description' => __( 'Panel principal para todas las opciones de DadeCore Theme.', 'dadecore' ),
        'priority'    => 10, // Baja prioridad para que aparezca arriba
    ] );

    // 2. SECCIONES DENTRO DEL PANEL
    //=============================================

    // 2.1. Sección: Colores Globales (antes dadecore_colors)
    $wp_customize->add_section( 'dadecore_global_colors_section', [
        'title'    => __( 'Colores Globales y Fuentes', 'dadecore' ),
        'panel'    => 'dadecore_main_panel',
        'priority' => 10,
    ] );

    // -- Controles existentes de color y fuentes (movidos a esta sección) --
    $wp_customize->add_setting( 'dadecore_primary_color', [
        'default'           => '#007bff', // Actualizado a un azul más estándar
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_primary_color', [
        'label'   => __( 'Color Primario', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
    ] ) );

    $wp_customize->add_setting( 'dadecore_text_color', [
        'default'           => '#212529',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_text_color', [
        'label'   => __( 'Color de Texto Principal', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
    ] ) );

    $wp_customize->add_setting( 'dadecore_background_color', [
        'default'           => '#f8f9fa',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_background_color', [
        'label'   => __( 'Color de Fondo del Sitio', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
    ] ) );

    // Fuentes (como estaban, pero en la nueva sección)
    $font_choices = [
        'System Default' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'Inter' => 'Inter, sans-serif',
        'Poppins' => 'Poppins, sans-serif',
        // Se podrían añadir más fuentes aquí y encolarlas condicionalmente si no son del sistema
    ];

    $wp_customize->add_setting( 'dadecore_body_font', [
        'default'           => 'System Default',
        'sanitize_callback' => 'dadecore_sanitize_font_choice',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'dadecore_body_font_control', [ // Nombre de control único
        'type'    => 'select',
        'label'   => __( 'Fuente del Cuerpo', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
        'choices' => $font_choices,
        'settings' => 'dadecore_body_font'
    ] );

    $wp_customize->add_setting( 'dadecore_heading_font', [
        'default'           => 'System Default',
        'sanitize_callback' => 'dadecore_sanitize_font_choice',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'dadecore_heading_font_control', [ // Nombre de control único
        'type'    => 'select',
        'label'   => __( 'Fuente de Títulos', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
        'choices' => $font_choices,
        'settings' => 'dadecore_heading_font'
    ] );


    // 2.2. Sección: Header
    $wp_customize->add_section( 'dadecore_header_section', [
        'title'    => __( 'Cabecera (Header)', 'dadecore' ),
        'panel'    => 'dadecore_main_panel',
        'priority' => 20,
    ] );
    // -- Controles de Header (movidos y adaptados) --
    $wp_customize->add_setting( 'dadecore_header_bg_color', [ // Renombrado desde dadecore_header_bg
        'default'           => '#ffffff', // Blanco por defecto como en style.css
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_header_bg_color_control', [ // Nombre de control único
        'label'   => __( 'Color de Fondo del Encabezado', 'dadecore' ),
        'section' => 'dadecore_header_section',
        'settings' => 'dadecore_header_bg_color'
    ] ) );

    $wp_customize->add_setting( 'dadecore_header_text_color', [
        'default'           => '#212529',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_header_text_color_control', [ // Nombre de control único
        'label'   => __( 'Color de Texto del Encabezado', 'dadecore' ),
        'section' => 'dadecore_header_section',
        'settings' => 'dadecore_header_text_color'
    ] ) );


    // 2.3. Sección: Footer
    $wp_customize->add_section( 'dadecore_footer_section', [
        'title'    => __( 'Pie de Página (Footer)', 'dadecore' ),
        'panel'    => 'dadecore_main_panel',
        'priority' => 30,
    ] );
    // -- Controles de Footer (movidos y adaptados) --
    $wp_customize->add_setting( 'dadecore_footer_bg_color', [ // Renombrado desde dadecore_footer_bg
        'default'           => '#343a40', // Oscuro por defecto como en style.css
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_footer_bg_color_control', [ // Nombre de control único
        'label'   => __( 'Color de Fondo del Pie de Página', 'dadecore' ),
        'section' => 'dadecore_footer_section',
        'settings' => 'dadecore_footer_bg_color'
    ] ) );

    $wp_customize->add_setting( 'dadecore_footer_text_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_footer_text_color_control', [ // Nombre de control único
        'label'   => __( 'Color de Texto del Pie de Página', 'dadecore' ),
        'section' => 'dadecore_footer_section',
        'settings' => 'dadecore_footer_text_color'
    ] ) );

    // 2.4. Sección: Blog (antes dadecore_blog)
    $wp_customize->add_section( 'dadecore_blog_layout_section', [ // Renombrado para más claridad
        'title'    => __( 'Blog (Listado de Entradas)', 'dadecore' ),
        'panel'    => 'dadecore_main_panel',
        'priority' => 40,
    ] );

    // -- Control: Blog Hero Subtitle --
    $wp_customize->add_setting( 'blog_hero_subtitle', [
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'blog_hero_subtitle_control', [
        'label'   => __( 'Subtítulo del Hero del Blog', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'textarea',
    ] );

    // -- Control: Longitud del Extracto (existente) --
    $wp_customize->add_setting( 'dadecore_excerpt_length', [
        'default'           => 30,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_excerpt_length_control', [
        'label'   => __( 'Longitud del Extracto (palabras)', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'number',
    ] );

    // -- Control: Blog View (List/Grid) --
    $wp_customize->add_setting( 'dadecore_blog_view', [
        'default'           => 'lista',
        'sanitize_callback' => 'dadecore_sanitize_blog_view',
        'transport'         => 'refresh', // Refresh is fine as it changes layout significantly
    ] );
    $wp_customize->add_control( 'dadecore_blog_view_control', [
        'label'   => __( 'Vista del Listado de Entradas', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'radio', // Radio buttons are often good for few, distinct choices
        'choices' => [
            'lista' => __( 'Lista ( традиционный )', 'dadecore' ),
            'grid'  => __( 'Cuadrícula (Grid)', 'dadecore' ),
        ],
    ] );

    // -- Controles para Mostrar/Ocultar Metadatos --
    $meta_elements = [
        'display_post_date'     => __( 'Mostrar Fecha de Publicación', 'dadecore' ),
        'display_post_author'   => __( 'Mostrar Autor', 'dadecore' ),
        'display_post_summary'  => __( 'Mostrar Resumen (extracto)', 'dadecore' ),
        'display_post_read_more' => __( 'Mostrar Enlace "Leer más"', 'dadecore' ),
    ];

    foreach ( $meta_elements as $setting_id => $label ) {
        $wp_customize->add_setting( 'dadecore_' . $setting_id, [
            'default'           => true,
            'sanitize_callback' => 'wp_validate_boolean',
            'transport'         => 'postMessage',
        ] );
        $wp_customize->add_control( 'dadecore_' . $setting_id . '_control', [
            'label'   => $label,
            'section' => 'dadecore_blog_layout_section',
            'type'    => 'checkbox',
            'settings' => 'dadecore_' . $setting_id,
        ] );
    }

    // 2.5. Sección: Sidebar
    $wp_customize->add_section( 'dadecore_sidebar_section', [
        'title'    => __( 'Barra Lateral (Sidebar)', 'dadecore' ),
        'panel'    => 'dadecore_main_panel',
        'priority' => 50,
    ] );

    // -- Control: Posición del Sidebar --
    $wp_customize->add_setting( 'dadecore_sidebar_position', [
        'default'           => 'derecha',
        'sanitize_callback' => 'dadecore_sanitize_sidebar_position',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_sidebar_position_control', [
        'label'   => __( 'Posición del Sidebar en el Blog', 'dadecore' ),
        'section' => 'dadecore_sidebar_section',
        'type'    => 'select',
        'choices' => [
            'derecha'   => __( 'Derecha', 'dadecore' ),
            'izquierda' => __( 'Izquierda', 'dadecore' ),
            'ninguno'   => __( 'Ninguno (sin sidebar)', 'dadecore' ),
        ],
    ] );

}
add_action( 'customize_register', 'dadecore_customize_register', 20 );

/**
 * Sanitize callback for sidebar position.
 */
function dadecore_sanitize_sidebar_position( $input ) {
    $valid = [ 'izquierda', 'derecha', 'ninguno' ];
    if ( in_array( $input, $valid, true ) ) {
        return $input;
    }
    return 'derecha';
}

/**
 * Sanitize callback for blog view.
 */
function dadecore_sanitize_blog_view( $input ) {
    $valid = [ 'lista', 'grid' ];
    if ( in_array( $input, $valid, true ) ) {
        return $input;
    }
    return 'lista'; // Default fallback
}

/**
 * Sanitize callback for font choices.
 */
function dadecore_sanitize_font_choice( $input_key ) {
    $font_choices_keys = [
        'System Default',
        'Inter',
        'Poppins'
    ];
    // The $font_choices array in add_control has keys like 'Inter' and values like 'Inter, sans-serif'
    // The setting will store the KEY e.g. 'Inter'.
    if ( in_array( $input_key, $font_choices_keys, true ) ) {
        return $input_key; // Return the sanitized key
    }
    return 'System Default'; // Default key
}


/**
 * Output custom styles based on Customizer settings using CSS Custom Properties.
 */
function dadecore_customizer_css() {
    ob_start();

    $primary_color     = get_theme_mod( 'dadecore_primary_color', '#007bff' );
    $text_color        = get_theme_mod( 'dadecore_text_color', '#212529' );
    $background_color  = get_theme_mod( 'dadecore_background_color', '#f8f9fa' );

    // Font handling: get the key, then map to the full font stack.
    $font_mappings = [
        'System Default' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'Inter' => 'Inter, sans-serif',
        'Poppins' => 'Poppins, sans-serif'
    ];
    $default_font_key = 'System Default';

    $body_font_key  = get_theme_mod( 'dadecore_body_font', $default_font_key );
    $heading_font_key = get_theme_mod( 'dadecore_heading_font', $default_font_key );

    $body_font_stack = $font_mappings[$body_font_key] ?? $font_mappings[$default_font_key];
    $heading_font_stack = $font_mappings[$heading_font_key] ?? $font_mappings[$default_font_key];

    $header_bg_color   = get_theme_mod( 'dadecore_header_bg_color', '#ffffff' );
    $header_text_color = get_theme_mod( 'dadecore_header_text_color', '#212529' );

    $footer_bg_color   = get_theme_mod( 'dadecore_footer_bg_color', '#343a40' );
    $footer_text_color = get_theme_mod( 'dadecore_footer_text_color', '#ffffff' );

    ?>
    :root {
        --dadecore-primary-color: <?php echo esc_attr( $primary_color ); ?>;
        --dadecore-text-color: <?php echo esc_attr( $text_color ); ?>;
        --dadecore-background-color: <?php echo esc_attr( $background_color ); ?>;

        --dadecore-body-font-family: <?php echo esc_attr( $body_font_stack ); ?>;
        --dadecore-heading-font-family: <?php echo esc_attr( $heading_font_stack ); ?>;

        --dadecore-header-bg-color: <?php echo esc_attr( $header_bg_color ); ?>;
        --dadecore-header-text-color: <?php echo esc_attr( $header_text_color ); ?>;

        --dadecore-footer-bg-color: <?php echo esc_attr( $footer_bg_color ); ?>;
        --dadecore-footer-text-color: <?php echo esc_attr( $footer_text_color ); ?>;
    }

    body {
        background-color: var(--dadecore-background-color);
        color: var(--dadecore-text-color);
        font-family: var(--dadecore-body-font-family);
    }
    h1, h2, h3, h4, h5, h6, .entry-title, .page-title, .widget-title {
        font-family: var(--dadecore-heading-font-family);
    }
    a {
        color: var(--dadecore-primary-color);
    }
    .button, button, input[type="button"], input[type="reset"], input[type="submit"], .read-more-link, .wp-block-button__link {
        background-color: var(--dadecore-primary-color);
        color: #ffffff;
        border-color: var(--dadecore-primary-color);
    }
    .button:hover, button:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:hover, .read-more-link:hover, .wp-block-button__link:hover {
        opacity: 0.85;
    }

    .site-header {
        background-color: var(--dadecore-header-bg-color);
    }
    .site-header, .site-header a, .main-navigation ul li a { /* Ensure nav link color is also set */
        color: var(--dadecore-header-text-color);
    }
    .main-navigation ul li a:hover,
    .main-navigation ul li.current-menu-item > a { /* Specificity for current menu item */
        color: var(--dadecore-primary-color);
    }

    .site-footer {
        background-color: var(--dadecore-footer-bg-color);
        color: var(--dadecore-footer-text-color);
    }
    .site-footer a {
         color: var(--dadecore-primary-color);
    }
    .site-footer a:hover {
        opacity: 0.85;
    }

    <?php
    $css = ob_get_clean();
    if ( !empty( trim( $css ) ) ) {
        // Remove comments and extra whitespace for cleaner output
        $css = preg_replace( '/\/\*(.*?)\*\//s', '', $css ); // Remove /* ... */ comments
        $css = preg_replace( '/\s*([\{\}:;,])\s*/', '$1', $css ); // Remove whitespace around { } : ; ,
        $css = preg_replace( '/\s+/', ' ', $css ); // Collapse multiple spaces/newlines into single space
        echo '<style type="text/css" id="dadecore-customizer-css">' . trim( $css ) . '</style>';
    }
}
add_action( 'wp_head', 'dadecore_customizer_css', 99 );


/**
 * Adjust excerpt length based on Customizer setting.
 */
function dadecore_excerpt_length_customizer( $length ) {
    return absint( get_theme_mod( 'dadecore_excerpt_length', 30 ) );
}
add_filter( 'excerpt_length', 'dadecore_excerpt_length_customizer' );


/**
 * Enqueue scripts for Customizer live preview.
 */
function dadecore_customize_preview_js() {
    wp_enqueue_script(
        'dadecore-customizer-preview',
        get_template_directory_uri() . '/assets/js/customizer-preview.js',
        [ 'jquery', 'customize-preview' ],
        wp_get_theme()->get( 'Version' ),
        true
    );
    // Pass data to JS if needed, e.g., default font stacks
    $default_fonts_stack = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
    wp_localize_script('dadecore-customizer-preview', 'dadecoreCustomizerPreview', [
        'bodyFontDefault' => $default_fonts_stack,
        'headingFontDefault' => $default_fonts_stack,
        'fontChoices' => [ // Pass the same choices as in PHP to JS for easier mapping
            'System Default' => $default_fonts_stack,
            'Inter' => 'Inter, sans-serif',
            'Poppins' => 'Poppins, sans-serif',
        ]
    ]);
}
add_action( 'customize_preview_init', 'dadecore_customize_preview_js' );

?>
