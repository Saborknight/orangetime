<?php
defined('ABSPATH') or die();
/**
 * Lisab editorile Styles valiku
 *
 * @since 1.0.0
 *
 */
function oi_mce_editor_buttons( $buttons )
{
    array_unshift( $buttons, 'styleselect' );

    if ( ( $key = array_search('alignjustify',$buttons) ) !== false ) {
        unset($buttons[$key]);
    }
    if ( ( $key = array_search('wp_more',$buttons) ) !== false ) {
        unset($buttons[$key]);
    }

    $buttons[] = 'table';
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';
    $buttons[] = 'anchor';
    return $buttons;
}
add_filter('mce_buttons_2', 'oi_mce_editor_buttons');
/**
 * Lisab redaktorile sisu lehekülgedeks jagamise nupu.
 * Saad postituse sisu jagada lehekülgedeks.
 *
 * Kujunduspõhjas väljakutsumiseks kasuta "wp_link_pages" funktsiooni.
 *
 * @since 1.0.0
 *
 */
function oi_mce_buttons($buttons)
{
    if ( ( $key = array_search('wp_more',$buttons) ) !== false ) {
        unset($buttons[$key]);
    }
   array_push($buttons, 'separator');
   return $buttons;
}
add_filter('mce_buttons', 'oi_mce_buttons');

function oi_mce_options($settings)
{
    $settings['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5';
    $style_formats = array(
        array(
            'title' => __('Sissejuhatuse'),
            'selector' => 'p',
            'classes' => 'lead'
        ),
    );

    $settings['style_formats'] = json_encode( $style_formats );
    $settings['theme_advanced_disable'] = 'justifyfull,wp_more,wp_page';

    return $settings;
}
add_filter('tiny_mce_before_init', 'oi_mce_options');

function oi_mce_external_plugins($plugins)
{
   $plugins['anchor'] = get_template_directory_uri() . '/assets/dist/scripts/tinymce4/plugins/anchor/plugin.min.js';
   $plugins['table'] = get_template_directory_uri() . '/assets/dist/scripts/tinymce4/plugins/table/plugin.min.js';
   return $plugins;
}
add_filter('mce_external_plugins', 'oi_mce_external_plugins');
