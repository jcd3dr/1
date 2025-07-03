<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Import sample pages and posts once after theme activation.
 */
function dadecore_import_sample_content() {
    if ( get_option( 'dadecore_sample_content_imported' ) ) {
        return;
    }

    $dir = get_template_directory() . '/sample-content';
    if ( ! file_exists( $dir ) ) {
        return;
    }

    // Import pages
    foreach ( glob( $dir . '/pages/*.html' ) as $file ) {
        $slug = basename( $file, '.html' );
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( [
                'post_title'   => ucwords( str_replace( '-', ' ', $slug ) ),
                'post_name'    => $slug,
                'post_content' => file_get_contents( $file ),
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ] );
        }
    }

    // Import posts
    foreach ( glob( $dir . '/posts/*.html' ) as $file ) {
        $slug = basename( $file, '.html' );
        $title = preg_replace( '/^[0-9]+-/', '', $slug );
        if ( ! get_page_by_path( $title, OBJECT, 'post' ) ) {
            wp_insert_post( [
                'post_title'   => ucwords( str_replace( '-', ' ', $title ) ),
                'post_content' => file_get_contents( $file ),
                'post_status'  => 'publish',
                'post_type'    => 'post',
            ] );
        }
    }

    update_option( 'dadecore_sample_content_imported', 1 );
}
add_action( 'after_switch_theme', 'dadecore_import_sample_content' );
