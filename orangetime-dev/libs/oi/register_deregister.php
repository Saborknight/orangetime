<?php
/**
 * Eemaldamised ja lisamised. Asjad, mida pole vaja väljundis näha või kasutada, eemaldame.
 *
 *
 * @since 1.0.0
 *
 */
defined('ABSPATH') or die();

remove_action('wp_head', 'feed_links_extra', 3); // Removes the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Removes links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Removes the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Removes the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'parent_post_rel_link'); // Removes the prev link
remove_action('wp_head', 'start_post_rel_link'); // Removes the start link
remove_action('wp_head', 'adjacent_posts_rel_link'); // Removes the relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator');

if ( ! empty ( $GLOBALS['sitepress'] ) ) {
    add_action( 'wp_head', function() {
        remove_action(current_filter(), array ( $GLOBALS['sitepress'], 'meta_generator_tag' ));
    }, 0);
}
remove_action('wp_head', 'wp_generator');

define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
define('ICL_DONT_LOAD_LANGUAGES_JS', true);

/**
 * Plugin Name: Disable Emojis
 * Plugin URI: https://geek.hellyer.kiwi/plugins/disable-emojis/
 * Description: Disable Emojis
 * Version: 1.5
 * Author: Ryan Hellyer
 * Author URI: https://geek.hellyer.kiwi/
 *
 */
function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

/**
 * Eemalda administraatorivaates menüüst elemente
 *
 * @since 1.0.0
 *
 */
function oi_admin_menu()
{
    remove_menu_page('link-manager.php');
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'oi_admin_menu');

/**
 * Eemalda wp_head hookist mittevajalikud stiililehed
 *
 * @since 1.0.0
 *
 */
function oi_remove_styles()
{
    wp_deregister_style('wpBannerizeStyleDefault.css');
    wp_deregister_style('wp-paginate');
    wp_deregister_style('NextGEN');
}
add_action('wp_print_styles', 'oi_remove_styles');

/**
 * Administreerimisliidese CSS
 *
 * @since 1.0.0
 *
 */
function oi_add_remove_admin_scripts()
{
    $css_time = filemtime(get_template_directory() . '/assets/dist/css/styles_admin.css');

    wp_dequeue_style('cmb2-styles');
    wp_enqueue_style('oi-admin', get_template_directory_uri() . '/assets/dist/css/styles_admin.css', false, $css_time);
}
add_action('admin_enqueue_scripts', 'oi_add_remove_admin_scripts', 11);
