<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Creates the top-level admin menu page for DadeCore Theme Customizations.
 */
function dadecore_create_admin_menu() {
    add_menu_page(
        __( 'Personalización DadeCore', 'dadecore' ), // Page Title
        __( 'DadeCore', 'dadecore' ),                // Menu Title (shorter)
        'manage_options',                             // Capability
        'dadecore_settings',                          // Menu Slug
        'dadecore_general_settings_page_html',        // Function to display content for the first submenu page
        'dashicons-admin-customizer',                 // Icon URL
        25                                            // Position (just below Comments)
    );

    // Submenu: General Settings (this will also be the default page for the main menu)
    add_submenu_page(
        'dadecore_settings',                          // Parent Slug
        __( 'Ajustes Generales', 'dadecore' ),        // Page Title
        __( 'Ajustes Generales', 'dadecore' ),        // Menu Title
        'manage_options',                             // Capability
        'dadecore_settings',                          // Menu Slug (same as parent for the first item)
        'dadecore_general_settings_page_html'         // Function to display content
    );

    // Submenu: Blog Settings
    add_submenu_page(
        'dadecore_settings',
        __( 'Ajustes del Blog', 'dadecore' ),
        __( 'Blog', 'dadecore' ),
        'manage_options',
        'dadecore_blog_settings',
        'dadecore_blog_settings_page_html'
    );

    // Submenu: Header/Footer Settings
    add_submenu_page(
        'dadecore_settings',
        __( 'Ajustes de Cabecera/Pie', 'dadecore' ),
        __( 'Header/Footer', 'dadecore' ),
        'manage_options',
        'dadecore_header_footer_settings',
        'dadecore_header_footer_settings_page_html'
    );

    // Add more submenus as needed, for example, for Security and Integrations if preferred as separate pages
    // For now, Security and Integrations will be sections within "Ajustes Generales"
}
add_action( 'admin_menu', 'dadecore_create_admin_menu' );

/**
 * Renders the General Settings page.
 * This page will contain the GTM ID and Login Slug options.
 */
function dadecore_general_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'dadecore_general_options_group' ); // Unique group for general settings
            do_settings_sections( 'dadecore_settings' );     // Page slug used in add_settings_section
            submit_button( __( 'Guardar Cambios', 'dadecore' ) );
            ?>
        </form>
    </div>
    <?php
}

/**
 * Renders the Blog Settings page (placeholder).
 */
function dadecore_blog_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <p><?php esc_html_e( 'Aquí se configurarán las opciones funcionales del blog.', 'dadecore' ); ?></p>
        <?php
        // Example:
        // settings_fields( 'dadecore_blog_options_group' );
        // do_settings_sections( 'dadecore_blog_settings' );
        // submit_button();
        ?>
    </div>
    <?php
}

/**
 * Renders the Header/Footer Settings page (placeholder).
 */
function dadecore_header_footer_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <p><?php esc_html_e( 'Aquí se configurarán las opciones funcionales de la cabecera y el pie de página.', 'dadecore' ); ?></p>
    </div>
    <?php
}


/**
 * Registers settings, sections, and fields for the General Settings page.
 */
function dadecore_settings_init() {
    // Register a single setting option to store all general settings
    register_setting( 'dadecore_general_options_group', 'dadecore_theme_options', 'dadecore_theme_options_sanitize' );

    // Section: Integrations
    add_settings_section(
        'dadecore_section_integration',
        __( 'Integraciones', 'dadecore' ),
        '__return_false', // Callback for section description (optional)
        'dadecore_settings' // Page slug on which to show this section
    );

    add_settings_field(
        'gtm_id', // Field ID (used in the options array)
        __( 'Google Tag Manager ID', 'dadecore' ),
        'dadecore_gtm_id_cb', // Callback to render the field
        'dadecore_settings',  // Page slug
        'dadecore_section_integration' // Section ID
    );

    // Section: Security
    add_settings_section(
        'dadecore_section_security',
        __( 'Seguridad', 'dadecore' ),
        '__return_false',
        'dadecore_settings'
    );

    add_settings_field(
        'login_slug',
        __( 'Login Slug (URL de acceso)', 'dadecore' ),
        'dadecore_login_slug_cb',
        'dadecore_settings',
        'dadecore_section_security',
        [ 'label_for' => 'login_slug_field' ] // Connects label to input for accessibility
    );
}
add_action( 'admin_init', 'dadecore_settings_init' );

/**
 * Sanitize theme options.
 *
 * @param array $input The input array.
 * @return array The sanitized array.
 */
function dadecore_theme_options_sanitize( $input ) {
    $sanitized_input = [];
    if ( isset( $input['gtm_id'] ) ) {
        $sanitized_input['gtm_id'] = sanitize_text_field( $input['gtm_id'] );
    }
    if ( isset( $input['login_slug'] ) ) {
        $new_slug = sanitize_title( $input['login_slug'] );
        // Prevent empty slug or common problematic slugs
        if ( empty( $new_slug ) || in_array( $new_slug, ['wp-admin', 'wp-login', 'login', 'dashboard', 'admin'] ) ) {
            // Add an error message
            add_settings_error(
                'dadecore_theme_options',
                'invalid_login_slug',
                __( 'El Login Slug proporcionado no es válido o está reservado. Se ha restaurado el valor anterior o uno por defecto.', 'dadecore' ),
                'error'
            );
            // Revert to old value or default if old value isn't set
            $options = get_option('dadecore_theme_options');
            $sanitized_input['login_slug'] = $options['login_slug'] ?? 'login';
        } else {
            $sanitized_input['login_slug'] = $new_slug;
        }
    }
    // Add sanitization for other options as they are added

    return $sanitized_input;
}


// Callback functions for rendering fields (GTM ID and Login Slug)

function dadecore_gtm_id_cb() {
    $options = get_option( 'dadecore_theme_options' );
    ?>
    <input type="text" id="gtm_id_field" name="dadecore_theme_options[gtm_id]" value="<?php echo esc_attr( $options['gtm_id'] ?? '' ); ?>" class="regular-text" />
    <p class="description"><?php esc_html_e( 'Introduce tu ID de Google Tag Manager (ej: GTM-XXXXXX).', 'dadecore' ); ?></p>
    <?php
}

function dadecore_login_slug_cb() {
    $options = get_option( 'dadecore_theme_options' );
    ?>
    <input type="text" id="login_slug_field" name="dadecore_theme_options[login_slug]" value="<?php echo esc_attr( $options['login_slug'] ?? 'login' ); ?>" class="regular-text" />
    <p class="description">
        <?php esc_html_e( 'Personaliza la URL de acceso. Ejemplo: si pones "acceso", la URL será tuweb.com/acceso/.', 'dadecore' ); ?>
        <br><strong><?php esc_html_e( 'Importante:', 'dadecore' ); ?></strong> <?php esc_html_e( 'Después de cambiar esto, ve a Ajustes > Enlaces Permanentes y haz clic en "Guardar Cambios" (sin necesidad de hacer cambios allí) para actualizar las reglas de reescritura.', 'dadecore' ); ?>
    </p>
    <?php
}

// It's important to update how the login slug is retrieved in functions.php
// The old code was: $options = get_option( 'dadecore_options' ); $slug = $options['login_slug'] ?? 'login';
// It should now be: $options = get_option( 'dadecore_theme_options' ); $slug = $options['login_slug'] ?? 'login';
// This change will be made in functions.php in a subsequent step if not already handled.

?>
