<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Adds a simple options page under Appearance.
 */
function dadecore_add_theme_page() {
    add_theme_page(
        __( 'DadeCore Theme Options', 'dadecore' ),
        __( 'DadeCore Theme', 'dadecore' ),
        'manage_options',
        'dadecore-options',
        'dadecore_options_page_html'
    );
}
add_action( 'admin_menu', 'dadecore_add_theme_page' );

/**
 * Renders the theme options page.
 */
function dadecore_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'DadeCore Theme Options', 'dadecore' ); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields( 'dadecore_options_group' );
            do_settings_sections( 'dadecore-options' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Registers settings, sections and fields.
 */
function dadecore_settings_init() {
    register_setting( 'dadecore_options_group', 'dadecore_options', [ 'default' => [] ] );

    add_settings_section(
        'dadecore_section_integration',
        __( 'Integraciones', 'dadecore' ),
        '__return_false',
        'dadecore-options'
    );

    add_settings_field(
        'dadecore_gtm_id',
        __( 'Google Tag Manager ID', 'dadecore' ),
        'dadecore_gtm_id_cb',
        'dadecore-options',
        'dadecore_section_integration'
    );

    add_settings_section(
        'dadecore_section_security',
        __( 'Seguridad', 'dadecore' ),
        '__return_false',
        'dadecore-options'
    );

    add_settings_field(
        'dadecore_login_slug',
        __( 'Login Slug', 'dadecore' ),
        'dadecore_login_slug_cb',
        'dadecore-options',
        'dadecore_section_security'
    );
}
add_action( 'admin_init', 'dadecore_settings_init' );

function dadecore_gtm_id_cb() {
    $options = get_option( 'dadecore_options' );
    ?>
    <input type="text" name="dadecore_options[gtm_id]" value="<?php echo esc_attr( $options['gtm_id'] ?? '' ); ?>" class="regular-text" />
    <?php
}

function dadecore_login_slug_cb() {
    $options = get_option( 'dadecore_options' );
    ?>
    <input type="text" name="dadecore_options[login_slug]" value="<?php echo esc_attr( $options['login_slug'] ?? 'login' ); ?>" class="regular-text" />
    <?php
}
