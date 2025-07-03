<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
    <div class="container">
        <?php if ( has_custom_logo() ) : ?>
            <div class="site-logo"><?php the_custom_logo(); ?></div>
        <?php endif; ?>
        <nav id="site-navigation" class="main-navigation">
            <?php
            wp_nav_menu( [
                'theme_location' => 'menu-1',
                'menu_id'        => 'primary-menu',
            ] );
            ?>
        </nav><!-- #site-navigation -->
    </div>
</header>
<main id="content" class="site-content">
