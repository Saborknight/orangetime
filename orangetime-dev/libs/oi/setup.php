<?php
defined('ABSPATH') or die();

/**
 * Toimingud, mida tehakse kujunduspõhja aktiveerimisel ja peale laadimist.
 *
 * @since 1.0.0
 *
*/
function oi_the_theme_setup()
{

    $the_theme_status = get_option('theme_setup_status');

    if ($the_theme_status !== '1')
    {
        $core_settings = array(
            'permalink_structure' => '/%category%/%postname%/',
            'start_of_week' => 1,
            'date_format' => __('d.m.Y'),
            'timezone_string' => 'Europe/Tallinn',
            'time_format' => __('H:i'),
            'avatar_default' => 'mystery',
            'avatar_rating' => 'G',
            'comments_per_page' => 20,
            'default_comment_status' => 'closed',
            'blogdescription' => '',
            'gzipcompression' => 1,
            'image_default_link_type' => 'none',
            'use_smilies' => 0
        );

        foreach ( $core_settings as $k => $v )
        {
            update_option( $k, $v );
        }

        /**
        * Paneme kirja, et seda kujunduspõhja on juba ükskord aktiveeritud
        *
        * @since 1.0.0
        *
        */
        update_option('theme_setup_status', '1');

    }

    /**
     * Lisame tunnuspildi funktsionaalsuse.
     *
     * @since 1.0.0
     *
     */
    add_theme_support('post-thumbnails');

    /**
     * Lisame lehtedele väljavõtte kirjutamise välja.
     *
     * @since 1.0.0
     *
     */
    add_post_type_support('page', 'excerpt');

    /**
     * Lisame lehtedele title-tag tugi.
     * Source: https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
     *
     * @since 1.0.0
     *
     */
    add_theme_support('title-tag');

    /**
     * Registreerib veebilehe üldmenüü
     *
     * @since 1.0.0
     *
    */
    if (function_exists('register_nav_menus'))
    {
        register_nav_menus(
            array(
              'mainnav' => __('Primary navigation')
            )
        );
    }

    /**
     * Genereerime automaatselt menüü "Primary navigation"
     *
     * @since 1.0.0
     *
    */
    if ( !is_nav_menu('Primary navigation') )
    {
        $menu_id = wp_create_nav_menu('Primary navigation');
        $menu = array(
            'menu-item-type' => 'custom',
            'menu-item-url' => get_home_url('/'),
            'menu-item-title' => 'Avaleht'
        );
        set_theme_mod('nav_menu_locations' , array('Primary navigation' => $menu_id));
        wp_update_nav_menu_item($menu_id, 0, $menu);
    }

    /**
     * Lisa editori lehe tegelikku tüpograafiat kirjeldavad laadid.
     *
     * @since 1.0.0
     *
    */
    add_editor_style(get_template_directory_uri() . '/assets/dist/css/styles_editor.css');

}
add_action('after_setup_theme', 'oi_the_theme_setup');
