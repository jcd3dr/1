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
        'Arial' => 'Arial, Helvetica, sans-serif',
        'Verdana' => 'Verdana, Geneva, sans-serif',
        'Tahoma' => 'Tahoma, Geneva, sans-serif',
        'Times New Roman' => '"Times New Roman", Times, serif',
        'Georgia' => 'Georgia, serif',
        'Inter' => 'Inter, sans-serif', // Google Font
        'Poppins' => 'Poppins, sans-serif', // Google Font
        'Lato' => 'Lato, sans-serif', // Google Font
        'Montserrat' => 'Montserrat, sans-serif', // Google Font
        'Open Sans' => '"Open Sans", sans-serif', // Google Font
        'Roboto' => 'Roboto, sans-serif', // Google Font
        'Oswald' => 'Oswald, sans-serif', // Google Font
        'Noto Sans' => '"Noto Sans", sans-serif', // Google Font
        // Podríamos añadir más fuentes de Google aquí.
        // Importante: las fuentes de Google necesitarán ser encoladas dinámicamente.
    ];

    $wp_customize->add_setting( 'dadecore_body_font', [
        'default'           => 'System Default',
        'sanitize_callback' => 'dadecore_sanitize_font_choice',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'dadecore_body_font_control', [
        'type'    => 'select',
        'label'   => __( 'Fuente del Cuerpo', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
        'choices' => $font_choices, // Usamos el array completo para las opciones
        'settings' => 'dadecore_body_font'
    ] );

    $wp_customize->add_setting( 'dadecore_heading_font', [
        'default'           => 'System Default',
        'sanitize_callback' => 'dadecore_sanitize_font_choice',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'dadecore_heading_font_control', [
        'type'    => 'select',
        'label'   => __( 'Fuente de Títulos', 'dadecore' ),
        'section' => 'dadecore_global_colors_section',
        'choices' => $font_choices, // Usamos el array completo para las opciones
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

    // -- Control: Header Layout --
    $wp_customize->add_setting( 'dadecore_header_layout', [
        'default'           => 'logo-left-menu-right',
        'sanitize_callback' => 'dadecore_sanitize_header_layout',
        'transport'         => 'refresh', // Refresh es más seguro para cambios de layout complejos
    ] );
    $wp_customize->add_control( 'dadecore_header_layout_control', [
        'label'   => __( 'Diseño de Cabecera', 'dadecore' ),
        'section' => 'dadecore_header_section',
        'type'    => 'select',
        'choices' => [
            'logo-left-menu-right' => __( 'Logo Izquierda - Menú Derecha', 'dadecore' ),
            'logo-center-menu-below' => __( 'Logo Centrado - Menú Debajo', 'dadecore' ),
            'logo-right-menu-left' => __( 'Logo Derecha - Menú Izquierda', 'dadecore' ),
        ],
        'settings' => 'dadecore_header_layout',
    ] );


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

    // -- Control: Footer Widget Columns --
    $wp_customize->add_setting( 'dadecore_footer_widget_columns', [
        'default'           => 3,
        'sanitize_callback' => 'dadecore_sanitize_footer_widget_columns',
        'transport'         => 'refresh', // Refresh es adecuado para cambios de layout
    ] );
    $wp_customize->add_control( 'dadecore_footer_widget_columns_control', [
        'label'   => __( 'Columnas de Widgets en Pie de Página', 'dadecore' ),
        'section' => 'dadecore_footer_section',
        'type'    => 'select',
        'choices' => [
            1 => __( '1 Columna', 'dadecore' ),
            2 => __( '2 Columnas', 'dadecore' ),
            3 => __( '3 Columnas', 'dadecore' ),
            4 => __( '4 Columnas', 'dadecore' ),
        ],
        'settings' => 'dadecore_footer_widget_columns',
    ] );

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
            'lista' => __( 'Lista ( tradicional )', 'dadecore' ), // Corregido typo
            'grid'  => __( 'Cuadrícula (Grid)', 'dadecore' ),
        ],
    ] );

    // -- Control: Blog Content Display (Excerpt vs Full) --
    $wp_customize->add_setting( 'dadecore_blog_content_display', [
        'default'           => 'excerpt',
        'sanitize_callback' => 'dadecore_sanitize_blog_content_display',
        'transport'         => 'refresh', // Necesita refresh para cambiar el template part
    ] );
    $wp_customize->add_control( 'dadecore_blog_content_display_control', [
        'label'   => __( 'Mostrar en Listado de Entradas', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'radio',
        'choices' => [
            'excerpt' => __( 'Extracto del Post', 'dadecore' ),
            'full'    => __( 'Contenido Completo del Post', 'dadecore' ),
        ],
        'settings' => 'dadecore_blog_content_display',
    ] );

    // -- Controles para Mostrar/Ocultar Metadatos --
    // (Estos controles podrían tener más sentido si se selecciona "Extracto")
    // Se podría añadir lógica en JS en el Customizer para mostrar/ocultar estos controles
    // basado en la selección de 'dadecore_blog_content_display', pero eso es más avanzado.
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

    // -- Controles de Paginación --
    $wp_customize->add_setting( 'dadecore_pagination_heading', [
        'sanitize_callback' => 'sanitize_text_field', // No se guarda, solo para UI
    ] );
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dadecore_pagination_heading_control', [
        'label'       => __( 'Ajustes de Paginación', 'dadecore' ),
        'section'     => 'dadecore_blog_layout_section',
        'type'        => 'hidden', // Usar 'hidden' y CSS para mostrar como un título, o crear una clase de control personalizada
        'description' => '<hr style="margin-top:1em; margin-bottom:1em;"><h3>' . __( 'Ajustes de Paginación', 'dadecore' ) . '</h3>', // HTML en description
    ] ) );


    $wp_customize->add_setting( 'dadecore_pagination_mid_size', [
        'default'           => 2,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_pagination_mid_size_control', [
        'label'       => __( 'Tamaño Medio (páginas a cada lado de la actual)', 'dadecore' ),
        'section'     => 'dadecore_blog_layout_section',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 0, 'max' => 5 ],
    ] );

    $wp_customize->add_setting( 'dadecore_pagination_end_size', [
        'default'           => 1,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_pagination_end_size_control', [
        'label'       => __( 'Tamaño Final (páginas al inicio/final)', 'dadecore' ),
        'section'     => 'dadecore_blog_layout_section',
        'type'        => 'number',
        'input_attrs' => [ 'min' => 0, 'max' => 3 ],
    ] );

    $wp_customize->add_setting( 'dadecore_pagination_show_prev_next', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_pagination_show_prev_next_control', [
        'label'   => __( 'Mostrar enlaces "Anterior/Siguiente"', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'checkbox',
    ] );

    $wp_customize->add_setting( 'dadecore_pagination_prev_text', [
        'default'           => __( '&laquo; Anterior', 'dadecore' ),
        'sanitize_callback' => 'wp_kses_post', // Permite HTML básico como &laquo;
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_pagination_prev_text_control', [
        'label'   => __( 'Texto para "Anterior"', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'text',
        'active_callback' => function() { return get_theme_mod('dadecore_pagination_show_prev_next', true); }
    ] );

    $wp_customize->add_setting( 'dadecore_pagination_next_text', [
        'default'           => __( 'Siguiente &raquo;', 'dadecore' ),
        'sanitize_callback' => 'wp_kses_post', // Permite HTML básico como &raquo;
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_pagination_next_text_control', [
        'label'   => __( 'Texto para "Siguiente"', 'dadecore' ),
        'section' => 'dadecore_blog_layout_section',
        'type'    => 'text',
        'active_callback' => function() { return get_theme_mod('dadecore_pagination_show_prev_next', true); }
    ] );


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


    // 2.6. Sección: Entradas Individuales (Single Post)
    $wp_customize->add_section( 'dadecore_single_post_section', [
        'title'    => __( 'Entradas Individuales (Single)', 'dadecore' ),
        'panel'    => 'dadecore_main_panel',
        'priority' => 45, // Entre Blog y Sidebar
    ] );

    $wp_customize->add_setting( 'dadecore_single_display_featured_image', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_single_display_featured_image_control', [
        'label'   => __( 'Mostrar Imagen Destacada en Entradas Individuales', 'dadecore' ),
        'section' => 'dadecore_single_post_section',
        'type'    => 'checkbox',
    ] );

    // -- Related Posts Settings --
    $wp_customize->add_setting( 'dadecore_show_related_posts', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_show_related_posts_control', [
        'label'   => __( 'Mostrar Entradas Relacionadas', 'dadecore' ),
        'section' => 'dadecore_single_post_section',
        'type'    => 'checkbox',
    ] );

    $wp_customize->add_setting( 'dadecore_related_posts_title', [
        'default'           => __( 'También te podría interesar', 'dadecore' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage', // O refresh si el JS es complicado
    ] );
    $wp_customize->add_control( 'dadecore_related_posts_title_control', [
        'label'   => __( 'Título para Entradas Relacionadas', 'dadecore' ),
        'section' => 'dadecore_single_post_section',
        'type'    => 'text',
        'active_callback' => function() { // Solo mostrar si dadecore_show_related_posts está activo
            return get_theme_mod( 'dadecore_show_related_posts', true );
        },
    ] );

    $wp_customize->add_setting( 'dadecore_related_posts_count', [
        'default'           => 3,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_related_posts_count_control', [
        'label'   => __( 'Número de Entradas Relacionadas a Mostrar', 'dadecore' ),
        'section' => 'dadecore_single_post_section',
        'type'    => 'number',
        'input_attrs' => [
            'min' => 1,
            'max' => 10, // Un máximo razonable
            'step' => 1,
        ],
        'active_callback' => function() {
            return get_theme_mod( 'dadecore_show_related_posts', true );
        },
    ] );

    $wp_customize->add_setting( 'dadecore_related_posts_show_thumbnail', [
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ] );
    $wp_customize->add_control( 'dadecore_related_posts_show_thumbnail_control', [
        'label'   => __( 'Mostrar Miniaturas en Entradas Relacionadas', 'dadecore' ),
        'section' => 'dadecore_single_post_section',
        'type'    => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod( 'dadecore_show_related_posts', true );
        },
    ] );
    // Aquí se podrían añadir más opciones para single, como mostrar/ocultar meta, etc.

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
 * Sanitize callback for header layout.
 */
function dadecore_sanitize_header_layout( $input ) {
    $valid_layouts = [
        'logo-left-menu-right',
        'logo-center-menu-below',
        'logo-right-menu-left',
    ];
    if ( in_array( $input, $valid_layouts, true ) ) {
        return $input;
    }
    return 'logo-left-menu-right'; // Default fallback
}

/**
 * Sanitize callback for footer widget columns.
 */
function dadecore_sanitize_footer_widget_columns( $input ) {
    $input = absint( $input ); // Asegura que es un entero positivo
    if ( $input >= 1 && $input <= 4 ) { // Asumiendo un máximo de 4 columnas
        return $input;
    }
    return 3; // Default fallback
}

/**
 * Sanitize callback for blog content display (excerpt or full).
 */
function dadecore_sanitize_blog_content_display( $input ) {
    $valid_options = [ 'excerpt', 'full' ];
    if ( in_array( $input, $valid_options, true ) ) {
        return $input;
    }
    return 'excerpt'; // Default fallback
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
    // Esta función ahora necesita acceso al array $font_choices definido en dadecore_customize_register.
    // Podríamos pasarlo como argumento, hacerlo global (no ideal), o redefinirlo aquí.
    // Por simplicidad, lo redefiniremos aquí, pero para DRY, considera una clase o un helper.
    $font_choices = [
        'System Default' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'Arial' => 'Arial, Helvetica, sans-serif',
        'Verdana' => 'Verdana, Geneva, sans-serif',
        'Tahoma' => 'Tahoma, Geneva, sans-serif',
        'Times New Roman' => '"Times New Roman", Times, serif',
        'Georgia' => 'Georgia, serif',
        'Inter' => 'Inter, sans-serif',
        'Poppins' => 'Poppins, sans-serif',
        'Lato' => 'Lato, sans-serif',
        'Montserrat' => 'Montserrat, sans-serif',
        'Open Sans' => '"Open Sans", sans-serif',
        'Roboto' => 'Roboto, sans-serif',
        'Oswald' => 'Oswald, sans-serif',
        'Noto Sans' => '"Noto Sans", sans-serif',
    ];
    if ( array_key_exists( $input_key, $font_choices ) ) {
        return $input_key;
    }
    return 'System Default';
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
    // Este array debe ser idéntico al usado en dadecore_customize_register para las $font_choices
    $font_mappings = [
        'System Default' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'Arial' => 'Arial, Helvetica, sans-serif',
        'Verdana' => 'Verdana, Geneva, sans-serif',
        'Tahoma' => 'Tahoma, Geneva, sans-serif',
        'Times New Roman' => '"Times New Roman", Times, serif',
        'Georgia' => 'Georgia, serif',
        'Inter' => 'Inter, sans-serif',
        'Poppins' => 'Poppins, sans-serif',
        'Lato' => 'Lato, sans-serif',
        'Montserrat' => 'Montserrat, sans-serif',
        'Open Sans' => '"Open Sans", sans-serif',
        'Roboto' => 'Roboto, sans-serif',
        'Oswald' => 'Oswald, sans-serif',
        'Noto Sans' => '"Noto Sans", sans-serif',
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
    // Este array debe ser idéntico al usado en dadecore_customize_register y dadecore_customizer_css
    $font_mappings_for_js = [
        'System Default' => '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
        'Arial' => 'Arial, Helvetica, sans-serif',
        'Verdana' => 'Verdana, Geneva, sans-serif',
        'Tahoma' => 'Tahoma, Geneva, sans-serif',
        'Times New Roman' => '"Times New Roman", Times, serif',
        'Georgia' => 'Georgia, serif',
        'Inter' => 'Inter, sans-serif',
        'Poppins' => 'Poppins, sans-serif',
        'Lato' => 'Lato, sans-serif',
        'Montserrat' => 'Montserrat, sans-serif',
        'Open Sans' => '"Open Sans", sans-serif',
        'Roboto' => 'Roboto, sans-serif',
        'Oswald' => 'Oswald, sans-serif',
        'Noto Sans' => '"Noto Sans", sans-serif',
    ];
    $default_font_key_for_js = 'System Default';

    wp_localize_script('dadecore-customizer-preview', 'dadecoreCustomizerPreview', [
        'bodyFontDefault' => $font_mappings_for_js[$default_font_key_for_js],
        'headingFontDefault' => $font_mappings_for_js[$default_font_key_for_js],
        'fontChoices' => $font_mappings_for_js // Pasar todos los mapeos de fuentes
    ]);
}
add_action( 'customize_preview_init', 'dadecore_customize_preview_js' );

?>
