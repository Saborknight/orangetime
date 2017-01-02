<?php
defined('ABSPATH') or die();

/**
 * Muudame lisatud Embed koodid responsiivseks
 * Read more: http://getbootstrap.com/components/#responsive-embed
 *
 * @return String
 * @since 1.0.0
 *
 */
function oi_embed_oembed_html($html)
{
  return '<figure class="embed-responsive embed-responsive-16by9">' . $html . '</figure>';
}
add_filter('embed_oembed_html', 'oi_embed_oembed_html', 99, 1);

/**
 * Kui menüüs on aktiivne link, too menüü nähtavale terve hierarhia ulatuses.
 * Probleem esineb siis, kui avada postitus kategooriast, mis asub wp_nav_menu
 * sügavamal astmel kui 1.
 *
 *
 * @since 1.0.0
 *
*/
function oi_display_hierarchy( $nav_menu, $args )
{
    if( ! is_single() )
    {
        return $nav_menu;
    }

    $menuXML = new SimpleXMLElement( $nav_menu );
    list($current) = $menuXML->xpath( "//li[contains(@class,'current-menu-parent')]" );
    if( !empty( $current ) )
    {
        $node = dom_import_simplexml($current);
        while($node)
        {
            $node = $node->parentNode;
            if( $node->tagName == 'li' )
            {
                $classes = $node->getAttribute('class');
                $node->setAttribute('class', $classes . ' current-menu-ancestor current-menu-parent current_page_parent current_page_ancestor');
            }
        }
    }

    return str_replace('<?xml version="1.0"?>', '', $menuXML->asXML());
}
add_filter('wp_nav_menu', 'oi_display_hierarchy', 11, 2);

/**
 * Lisab "body_class" funktsioonile täiendavaid klasse
 *
 * @since 1.0.0
 *
 */
function oi_class_names($classes)
{

    $browser = $_SERVER['HTTP_USER_AGENT'];

    if(preg_match('/MSIE 7.0/i', $browser))
    {
        $classes[] = "lt9 ie7";
    }
    elseif(preg_match('/MSIE 8.0/i', $browser))
    {
        $classes[] = "lt9 ie8";
    }
    elseif(preg_match('/MSIE 9.0/i', $browser))
    {
        $classes[] = "ie9";
    }

    if( defined('ICL_LANGUAGE_CODE') )
        $classes[] = ICL_LANGUAGE_CODE;

    return $classes;
}
add_filter( 'body_class' , 'oi_class_names' );

/**
 * Määrame vaikimisi galerii piltide linkiks "file"
 *
 * @since 1.0.0
 *
 */
function oi_set_gallery_link_type($out)
{
    $out['link'] = 'file';
    return $out;
}
add_filter('shortcode_atts_gallery', 'oi_set_gallery_link_type');

/**
 * Breadcrumb NavXT HTML5 microdata lisamine
 *
 * @since 1.0.0
 *
 */
function bcn_new_settings($opts)
{
    $opts['hseparator'] = '<span class="sep">/</span>';
    $opts['Hmainsite_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hhome_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hblog_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hpost_page_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hpost_post_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hpost_attachment_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hpost_tag_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hpost_format_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hauthor_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hcategory_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    $opts['Hdate_template'] = '<span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a title="Mine %title%." href="%link%" class="%type%" itemprop="url"><span itemprop="title">%htitle%</span></a></span>';
    return $opts;
}
add_filter('bcn_settings_init', 'bcn_new_settings');
