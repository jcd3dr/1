/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

    // Site Title and Description (Example, if you had these)
    // wp.customize( 'blogname', function( value ) {
    //  value.bind( function( to ) {
    //      $( '.site-title a' ).text( to );
    //  } );
    // } );
    // wp.customize( 'blogdescription', function( value ) {
    //  value.bind( function( to ) {
    //      $( '.site-description' ).text( to );
    //  } );
    // } );

    // Helper function to generate CSS styles for :root variables
    function dadecore_update_css_variable( setting, variableName ) {
        wp.customize( setting, function( value ) {
            value.bind( function( to ) {
                document.documentElement.style.setProperty( variableName, to );
            } );
        } );
    }

    // Helper function to update style tag content (less preferred than CSS variables for colors/fonts)
    // function dadecore_update_dynamic_style( setting, selector, property, unit = '' ) {
    //  wp.customize( setting, function( value ) {
    //      value.bind( function( to ) {
    //          $( selector ).css( property, to + unit );
    //      } );
    //  } );
    // }

    // Helper function to update font family variable
    function dadecore_update_font_variable( setting, variableName, defaultValueKey ) {
        wp.customize( setting, function( value ) {
            value.bind( function( to ) {
                let fontStack = dadecoreCustomizerPreview.fontChoices[to] || dadecoreCustomizerPreview[defaultValueKey];
                document.documentElement.style.setProperty( variableName, fontStack );
            } );
        } );
    }


    // -- Global Colors & Fonts --
    dadecore_update_css_variable( 'dadecore_primary_color', '--dadecore-primary-color' );
    dadecore_update_css_variable( 'dadecore_text_color', '--dadecore-text-color' );
    dadecore_update_css_variable( 'dadecore_background_color', '--dadecore-background-color' );

    dadecore_update_font_variable( 'dadecore_body_font', '--dadecore-body-font-family', 'bodyFontDefault' );
    dadecore_update_font_variable( 'dadecore_heading_font', '--dadecore-heading-font-family', 'headingFontDefault' );

    // -- Header --
    dadecore_update_css_variable( 'dadecore_header_bg_color', '--dadecore-header-bg-color' );
    dadecore_update_css_variable( 'dadecore_header_text_color', '--dadecore-header-text-color' );

    // -- Footer --
    dadecore_update_css_variable( 'dadecore_footer_bg_color', '--dadecore-footer-bg-color' );
    dadecore_update_css_variable( 'dadecore_footer_text_color', '--dadecore-footer-text-color' );

    // -- Blog Hero Subtitle --
    wp.customize( 'blog_hero_subtitle', function( value ) {
        value.bind( function( to ) {
            var subtitleElement = $( '.hero-section .page-subtitle' );
            if ( to ) {
                if ( subtitleElement.length ) {
                    subtitleElement.html( to ).show(); // Use .html() if wp_kses_post was used for sanitize_callback
                } else {
                    // If the element doesn't exist, create it (might be needed if it's initially empty and not rendered)
                    // This depends on the exact HTML structure in home.php
                    var titleElement = $( '.hero-section .page-title' );
                    if (titleElement.length) {
                         $( '<p class="page-subtitle">' + to + '</p>' ).insertAfter( titleElement );
                    } else {
                         // Fallback if even title isn't there, though less likely
                         $( '.hero-section .container' ).append( '<p class="page-subtitle">' + to + '</p>' );
                    }
                }
            } else {
                subtitleElement.hide().empty();
            }
        } );
    } );

    // Note: Sidebar position ('dadecore_sidebar_position') has transport 'refresh',
    // so it doesn't need JS handling here for live preview. Changes will cause a full refresh.
    // Same for excerpt length.

    // -- Related Posts Title --
    wp.customize( 'dadecore_related_posts_title', function( value ) {
        value.bind( function( to ) {
            var $titleElement = $( '.related-posts .related-posts-title' );
            if ( to ) {
                if ( $titleElement.length ) {
                    $titleElement.text( to ).show();
                } else if ( $( '.related-posts ul' ).length ) { // Si la lista existe, pero el título no
                    $( '<h3 class="related-posts-title">' + to + '</h3>' ).prependTo( '.related-posts' );
                }
            } else {
                $titleElement.hide().text( '' );
            }
        } );
    } );

    // -- Show/Hide Post Meta Elements --
    function dadecore_toggle_post_meta_element( setting, selector, parentSelector = null ) {
        wp.customize( setting, function( value ) {
            value.bind( function( to ) {
                var $element = $( selector );
                var $parentElement = parentSelector ? $element.closest( parentSelector ) : $element;

                if ( to ) {
                    $parentElement.show();
                } else {
                    $parentElement.hide();
                }
            } );
        } );
    }

    dadecore_toggle_post_meta_element( 'dadecore_display_post_date', '.entry-meta .post-date', '.entry-meta' );
    dadecore_toggle_post_meta_element( 'dadecore_display_post_author', '.entry-meta .post-author', '.entry-meta' );
    // For date and author, if both are hidden, the parent .entry-meta should also be hidden.
    // This requires a slightly more complex logic, checking both settings.
    // We can listen to both and then check.

    wp.customize( 'dadecore_display_post_date', function( value ) {
        value.bind( function( to ) {
            var $el = $( '.entry-meta .post-date' );
            to ? $el.show() : $el.hide();
            // Check parent .entry-meta visibility
            var authorVisible = wp.customize.instance('dadecore_display_post_author') ? wp.customize.instance('dadecore_display_post_author').get() : true;
            if (!to && !authorVisible) {
                $( '.entry-meta' ).hide();
            } else {
                $( '.entry-meta' ).show();
            }
        } );
    } );
     wp.customize( 'dadecore_display_post_author', function( value ) {
        value.bind( function( to ) {
            var $el = $( '.entry-meta .post-author' );
            to ? $el.show() : $el.hide();
            // Check parent .entry-meta visibility
            var dateVisible = wp.customize.instance('dadecore_display_post_date') ? wp.customize.instance('dadecore_display_post_date').get() : true;
             if (!to && !dateVisible) {
                $( '.entry-meta' ).hide();
            } else {
                $( '.entry-meta' ).show();
            }
        } );
    } );


    dadecore_toggle_post_meta_element( 'dadecore_display_post_summary', '.entry-summary' );
    dadecore_toggle_post_meta_element( 'dadecore_display_post_read_more', '.entry-footer' ); // Assuming read more is the only thing in footer

} )( jQuery );
