<?php
defined('ABSPATH') or die();

/**
 * Tagastab defineeritud piltide suuruste: $width, $height, $crop
 *
 * Allikas: http://codex.wordpress.org/Function_Reference/get_intermediate_image_sizes
 *
 * @since 1.0.0
 *
 */
function get_image_sizes( $size = '' )
{
    global $_wp_additional_image_sizes;

    $sizes = array();
    $get_intermediate_image_sizes = get_intermediate_image_sizes();

    // Create the full array with sizes and crop info
    foreach( $get_intermediate_image_sizes as $_size )
    {
        if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) )
        {
            $sizes[ $_size ]['width'] = get_option( $_size . '_size_w' );
            $sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
            $sizes[ $_size ]['crop'] = (bool) get_option( $_size . '_crop' );
        } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
            $sizes[ $_size ] = array(
                'width' => $_wp_additional_image_sizes[ $_size ]['width'],
                'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                'crop' =>  $_wp_additional_image_sizes[ $_size ]['crop']
            );
        }
    }

    // Get only 1 size if found
    if ( $size )
    {
        if( isset( $sizes[ $size ] ) )
        {
            return $sizes[ $size ];
        } else {
            return false;
        }
    }

    return $sizes;
}

/**
 * Muudetakse piltide kuvamise parameetreid
 * Selle funktsiooni kasutamisel eemalda "#" JPEG kvaliteeti määravate filtrite eest.
 *
 * Why?
 * Read this: http://www.netvlies.nl/blog/design-interactie/retina-revolution
 *
 * @since 1.0.0
 *
 */
function oi_wp_get_attachment_image($attachment_id = '', $size = 'thumbnail', $classes = '')
{
    global $post;

    $html = '';

    if( empty($attachment_id) )
    {
        $attachment_id = get_post_thumbnail_id($post->ID);
    }

    $image_attributes = wp_get_attachment_image_src($attachment_id, $size);

    if ( $image_attributes )
    {
        if( ! is_array($size) ) {
            $size_data = get_image_sizes($size);
        } else {
            $size_data['width'] = $size[0];
            $size_data['height'] = $size[1];
        }

        list($src) = $image_attributes;
        $hwstring = sprintf('width="%d" height="%d"', $size_data['width'] / 2, $size_data['height'] / 2);
        $attachment = get_post($attachment_id);

        if( is_array($size) ) {
            $classes.= ' attachment-' . implode('x', $size);
        } else {
            $classes.= ' attachment-' . $size;
        }

        $default_attr = array(
            'src'   => $src,
            'class' => $classes,
            'alt'   => trim(strip_tags( get_post_meta($attachment_id, '_wp_attachment_image_alt', true) )), // Use Alt field first
        );
        if ( empty($default_attr['alt']) )
            $default_attr['alt'] = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
        if ( empty($default_attr['alt']) )
            $default_attr['alt'] = trim(strip_tags( $attachment->post_title )); // Finally, use the title

        $attr = wp_parse_args($attr, $default_attr);
        $attr = array_map( 'esc_attr', $attr );
        $html = rtrim("<img $hwstring");
        foreach ( $attr as $name => $value ) {
            $html .= " $name=" . '"' . $value . '"';
        }
        $html .= ' />';
    }

    return $html;
}

#add_filter('jpeg_quality', function($arg){ return 45; });
#add_filter('wp_editor_set_quality', function($arg){ return 45; });

/**
 * Asendame kõik artiklitesse pandud pildid retina sõbralike versioonidega.
 * See tähendab, et lehele laaditakse 2x pildid võrreldes sellega, mida välja kuvatakse
 *
 * @since 1.0.0
 *
 */
function oi_content_retina_images($content)
{
    if( !is_admin() && is_main_query() )
    {
        $content = '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . $content;
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->substituteEntities = true;
        @$dom->loadHTML($content);
        $dom->formatOutput = false;
        $images = $dom->getElementsByTagName("img");

        foreach( $images as $image )
        {
            $classes = $image->getAttribute('class');
            if( preg_match("/wp-image-(\d*)/i", $classes, $matches) )
            {
                if( isset($matches[1]) && is_numeric($matches[1]) )
                {
                    preg_match("/size-(\w*)/i", $classes, $matches2);

                    if( isset($matches2[1]) && 'large' == $matches2[1] ) {
                        $size = 'large';
                    } else {
                        $image_width = $image->getAttribute('width') * 2;
                        $image_height = $image->getAttribute('height') * 2;
                        $size = array($image_width, $image_height);
                    }

                    $content_image = oi_wp_get_attachment_image($matches[1], $size, $classes);
                    $fragment = $dom->createDocumentFragment();
                    $fragment->appendXML($content_image);
                    $image->parentNode->replaceChild($fragment, $image);
                }
            }
        }

        $output = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
        $output = str_replace('<body>', '', $output);
        $output = str_replace('</body>', '', $output);
        return $output;
    }

    return $content;
}
#add_filter('the_content', 'oi_content_retina_images');
