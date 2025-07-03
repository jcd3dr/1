<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<?php
$body_classes = [];
if ( get_theme_mod( 'dadecore_enable_animated_borders', false ) ) {
    $body_classes[] = 'has-animated-borders';
}
if ( get_theme_mod( 'dadecore_enable_glassmorphism', false ) ) {
    $body_classes[] = 'has-glassmorphism';
}
?>
<body <?php body_class( $body_classes ); ?>>
<?php wp_body_open(); ?>
<?php
$header_layout = get_theme_mod( 'dadecore_header_layout', 'logo-left-menu-right' );
$header_classes = 'site-header layout-' . esc_attr( $header_layout );
?>
<header class="<?php echo $header_classes; ?>">
    <div class="container">
        <div class="site-branding">
            <?php if ( has_custom_logo() ) : ?>
                <div class="site-logo"><?php the_custom_logo(); ?></div>
            <?php else : ?>
                <?php if ( is_front_page() && is_home() ) : ?>
                    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php else : ?>
                    <p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php endif; ?>
                <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
                    ?>
                    <p class="site-description"><?php echo $description; ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div><!-- .site-branding -->

        <nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu( [
                'theme_location' => 'menu-1',
                'menu_id'        => 'primary-menu',
                'container'      => false, // No queremos un div contenedor extra por defecto
            ] );
            ?>
        </nav><!-- #site-navigation -->
    </div>
</header>
<main id="content" class="site-content">
