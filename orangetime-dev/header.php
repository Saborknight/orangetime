<?php
/**
 * Kujunduspõhja päis
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id$
 *
 */
    defined('ABSPATH') or die();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8" />
<meta name="generator" content="OKIA" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?php
    /* Generate favicons with http://iconogen.com/
    <link rel="shortcut icon" type="image/ico" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/apple-touch-icon-144x144.png" />
    <meta name="msapplication-square70x70logo" content="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/smalltile.png" />
    <meta name="msapplication-square150x150logo" content="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/mediumtile.png" />
    <meta name="msapplication-wide310x150logo" content="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/widetile.png" />
    <meta name="msapplication-square310x310logo" content="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/largetile.png" />
    */
?>
<link href='http://fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css' />
<?php wp_head(); ?>
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
/* <![CDATA[ */
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

 ga('create', 'UA-7560837-1', 'auto');
 ga('send', 'pageview');
/* ]]> */
</script>
</head>
<body <?php body_class() ?>>
    <div class="wrapper">

        <header class="main-header">
            <div class="container">
                <h1 class="main-header__logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr(get_bloginfo('name')); ?>" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/assets/dist/gfx/orangetime_logo.svg" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="90" height="90" /><span class="sr-only"><?php bloginfo('name'); ?></span></a></h1>
                <nav class="main-header__mainnav hidden-xs">
                    <h2 class="sr-only"><?php _e('Peamenüü', 'orangetime'); ?></h2>
                    <?php wp_nav_menu( array( 'theme_location' => 'mainnav', 'container' => '', 'menu_class' => 'mainnav', 'depth' => 1 ) ); ?>
                    <?php oi_the_lang_switch(); ?>
                </nav>
                <nav class="mobile-mainnav__section visible-xs-block">
                    <h2 class="sr-only"><?php _e('Mobiili Peamenüü', 'orangetime'); ?></h2>
                    <button class="dropdown-toggle x" type="button" data-toggle="dropdown" title="<?php esc_attr_e('Menüü', 'orangetime'); ?>"><span class="lines"><span class="sr-only"><?php _e('Menüü', 'orangetime'); ?></span></span></button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-menu__content">
                            <?php oi_the_lang_switch(); ?>
                            <?php wp_nav_menu( array( 'theme_location' => 'mainnav', 'container_class' => 'mainnav-section__mobile', 'menu_class' => 'mainnav-mobile', 'depth' => 2 ) ); ?>
                        </div>
                    </div>
                </nav>
            </div>
        </header>
