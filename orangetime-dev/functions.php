<?php
/**
 * Orangetime kujunduspõhja funktsioonid ja definitsioonid
 *
 * Täiendava teabe saamiseks külasta aadressit: http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id$
 *
 */

defined('ABSPATH') or die();

require_once('libs/oi/init.php');

/**
 * Täiendavalt on võimalik määrata tunnuspildi mõõdud.
 * Lisa vajadusel juurde teisi mõõte.
 *
 * @since 1.0.0
 *
 */
function oi_image_sizes()
{
	add_image_size('people-image', 270, 375, true);
    add_image_size('category-image', 320, 320, true);
}
add_action('after_setup_theme', 'oi_image_sizes');

/**
 * Muudame olemasolevate metakastide pealkirju.
 * Mõjutab administreerimisliidest.
 * Eriti hea on see tunnuspiltide (featured image) kastidele
 * õigete pealkirjade ja pildisuuruste märkimiseks
 *
 * @since 1.0.0
 *
 */
function oi_alter_default_metaboxes()
{
    global $typenow;

    remove_meta_box( 'postimagediv', 'oi_contact', 'side' );
    add_meta_box('postimagediv', esc_attr__('Kontakti tunnuspilt (270x375px)'), 'post_thumbnail_meta_box', 'oi_contact', 'side');

    remove_meta_box( 'postimagediv', 'post', 'side' );
    add_meta_box('postimagediv', esc_attr__('Tunnuspilt (320x320px)'), 'post_thumbnail_meta_box', 'post', 'side');

    // Disable editor
    if( is_admin() && 'page' == $typenow )
    {
        $current_page_template = get_post_meta($_GET['post'], '_wp_page_template', true);

        if( 'page-people_page.php' == $current_page_template )
        {
            remove_post_type_support('page', 'editor');
        }

        if( $_GET['post'] == 2 ) {
            remove_meta_box( 'postimagediv', 'page', 'side' );
        }
    }
}
add_action('do_meta_boxes', 'oi_alter_default_metaboxes');

/**
 * Kujunduspõhjas rakendavate postituste tüüpide registreerimine
 *
 * @since 1.0.0
 *
 */
function oi_register_post_types()
{
	/**
	 * See on näide, kuidas võiks välja näha sisutüübi ja taksonoomia defineerimine
	 *
	 * @since 1.0.0
	 *
	*/

	register_post_type( 'oi_contact',
		array(
			'labels' => array(
				'name' => esc_attr_x('Inimesed', 'oi_contact', 'orangetime'),
				'singular_name' => esc_attr_x('Inimene', 'oi_contact', 'orangetime'),
				'add_new' => esc_attr_x('Lisa inimene', 'oi_contact', 'orangetime'),
				'edit_item' => esc_attr_x('Muuda inimest', 'oi_contact', 'orangetime'),
				'add_new_item' => esc_attr_x('Lisa uus inimene', 'oi_contact', 'orangetime'),
				'not_found' => esc_attr_x('Inimesi pole', 'oi_contact', 'orangetime'),
				'search_items' => esc_attr_x('Otsi', 'oi_contact', 'orangetime')
			),
    		'public' => true,
    		'menu_position' => 10,
    		'show_in_nav_menus' => false,
    		'rewrite' => array('slug' => esc_attr_x('kontakt', 'URL slug', 'orangetime')),
    		'supports' => array('title', 'thumbnail'),
		)
	);

    register_post_type( 'oi_blog',
        array(
            'labels' => array(
                'name' => esc_attr_x('Blog', 'oi_blog', 'orangetime'),
                'singular_name' => esc_attr_x('Blog Post', 'oi_blog', 'orangetime'),
                'edit_item' =>  esc_attr_x('Edit Blog Post', 'oi_blog', 'orangetime'),
                'add_new_item' => esc_attr_x('Add New Blog Post', 'oi_blog', 'orangetime'),
                'not_found' => esc_attr_x('No blog posts found', 'oi_blog', 'orangetime'),
                'search_items' => esc_attr_x('Search Blog Posts', 'oi_blog', 'orangetime'),
                'new_item' => esc_attr_x('New Blog Post', 'oi_blog', 'orangetime'),
                'view_item' => esc_attr_x('View Blog Post', 'oi_blog', 'orangetime'),
                'menu_name' => esc_attr_x('Blog Posts', 'oi_blog', 'orangetime')
            ),
            'public' => true,
            'menu_position' => 10,
            'query_vars' => true,
            'rewrite' => array('slug' => esc_attr_x('blog', 'URL slug', 'orangetime')),
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'revisions')
        )
    );
}
add_action('init', 'oi_register_post_types');

/**
 * Register Custom Categories for the blog
 */
function oi_register_custom_taxonomies() {
    register_taxonomy( 'oi_blog_categories', 'oi_blog',
        array(
            array(
                'labels' => array(
                    'name' => esc_attr_x('Blog Categories', 'oi_blog_categories', 'orangetime'),
                    'singular_name' => esc_attr_x('Blog Category', 'oi_blog_categories', 'orangetime'),
                    'all_items' => esc_attr_x('All Blog Categories', 'oi_blog_categories', 'orangetime'),
                    'edit_item' => esc_attr_x('Edit Blog Category', 'oi_blog_categories', 'orangetime'),
                    'view_item' => esc_attr_x('View Blog Category', 'oi_blog_categories', 'orangetime'),
                    'update_item' => esc_attr_x('Update Blog Category', 'oi_blog_categories', 'orangetime'),
                    'add_new_item' => esc_attr_x('Add New Blog Category', 'oi_blog_categories', 'orangetime'),
                    'new_item_name' => esc_attr_x('New Blog Category Name', 'oi_blog_categories', 'orangetime'),
                    'parent_item' => esc_attr_x('Parent Blog Category', 'oi_blog_categories', 'orangetime'),
                    'parent_item_colon' => esc_attr_x('Parent Blog Category: ', 'oi_blog_categories', 'orangetime'),
                    'search_items' => esc_attr_x('Search Blog Category', 'oi_blog_categories', 'orangetime'),
                    'not_found' => esc_attr_x('No Blog Categories found', 'oi_blog_categories', 'orangetime')
                )
            ),
            'show_ui' => true,
            'show_in_menu' => true,
            'show_admin_column' => true,
            'query_vars' => true,
            'rewrite' => array('slug' => 'categories'),
        )
    );
    register_taxonomy_for_object_type( 'oi_blog_categories', 'oi_blog' );
}
add_action('init', 'oi_register_custom_taxonomies');

/**
 * Rewrite for Custom category slugs
 */
// function oi_rewrite_rules($rules) {
//     $newRules = array();
//     $newRules = ['/(.+)/?$'] 'index.php?taxonomy_name=$matches[1]';
// }

/**
 * Sisu pärimise reeglid
 *
 */
function oi_pre_posts($query)
{
	if( is_category() && $query->is_main_query() ) {
		$query->set( 'posts_per_page', -1 );
	}
}
add_action( 'pre_get_posts', 'oi_pre_posts' );


/**
 * Eemalda wp_head hookist mittevajalikud skriptid
 * Lisame juurde vajalikud stiililehed ja skriptid
 *
 * @since 1.0.0
 *
 */
function oi_add_remove_scripts()
{
	if( ! is_admin() )
	{
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'l10n' );
	}

	$css_time = ( strpos($_SERVER['SERVER_NAME'], 'prelive.') !== false ) ? filemtime(get_template_directory() . '/assets/dist/css/styles_screen.min.css') : null;
    $css_blog_time = ( strpos($_SERVER['SERVER_NAME'], 'prelive.') !== false ) ? filemtime(get_template_directory() . '/assets/dist/css/styles_blog_screen.css') : null;
    $js_vendors_time = ( strpos($_SERVER['SERVER_NAME'], 'prelive.') !== false ) ? filemtime(get_template_directory() . '/assets/dist/scripts/vendors.min.js') : null;
    $js_custom_time = ( strpos($_SERVER['SERVER_NAME'], 'prelive.') !== false ) ? filemtime(get_template_directory() . '/assets/dist/scripts/custom.js') : null;

	wp_enqueue_style('oi-screen', get_template_directory_uri() . '/assets/dist/css/styles_screen.min.css', false, $css_time, 'screen');

    //                               Any blog pages                        Conference Categories (Estonian and English)
    if( is_page_template( 'page-blog.php' ) || get_post_type() === 'oi_blog' || is_category(4) || is_category(7) ) {
        wp_enqueue_style('oi-screen_mod', get_template_directory_uri() . '/assets/dist/css/styles_screen_mod.css', array( 'oi-screen' ), $css_blog_time, 'screen');
    }

    wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/dist/scripts/vendors.min.js', false, $js_vendors_time);
    wp_enqueue_script('oi-app', get_template_directory_uri() . '/assets/dist/scripts/custom.js', false, $js_custom_time);


    wp_localize_script('oi-app', 'oi_vars',
        array(
            'ajax_url'   => admin_url('admin-ajax.php'),
            'lang'       => ( defined(ICL_LANGUAGE_CODE) ) ? ICL_LANGUAGE_CODE : get_bloginfo('language'),
            'nonce'      => wp_create_nonce('oi_main_nonce')
        )
    );

}
add_action('wp_enqueue_scripts', 'oi_add_remove_scripts');


/**
 * Võta kasutusele cmb_Meta_Box klass
 * Rakendamiseks eemalda 'add_action' eest trellid.
 * @link https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
 *
 * @since 1.0.0
 *
 */
function cmb_initialize_cmb_meta_boxes()
{
	if ( !class_exists('cmb_Meta_Box') )
	{
		require_once('libs/Custom-Metaboxes-and-Fields/init.php');
        require_once('libs/cmb-field-select2/cmb-field-select2.php');
        require_once('libs/cmb-field-map/cmb-field-map.php');
	}

}
add_action('init', 'cmb_initialize_cmb_meta_boxes', 9999);

/**
 * Kirjelda ja seadista metaboxid.
 * Rakendamiseks eemalda 'add_filter' eest trellid.
 *
 * @param  array $meta_boxes
 * @return array
 *
 * @since 1.0.0
 *
 */
function cmb_oi_metaboxes() {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_cmb_';

	if(function_exists('icl_get_languages')){
		$langs = icl_get_languages('skip_missing=0&orderby=code');
		$lang_check = function($lang) {
			return $lang['language_code'] = $lang['translated_name'];
		};
	}

    $cmb_contact = new_cmb2_box( array(
            'id' => $prefix . 'contact_meta',
            'title' => esc_attr__('Kontakti andmed', 'cmb2' ),
            'object_types' => array( 'oi_contact', ), // Post type
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
            // 'cmb_styles' => false, // false to disable the CMB stylesheet
            // 'closed' => true, // true to keep the metabox closed by default
        )
    );

    $cmb_contact->add_field( array(
            'name' => esc_attr__('Amet'),
            'id' => $prefix . 'contact_prof',
            'type' => 'text'
        )
    );

    $cmb_contact->add_field( array(
            'name' => esc_attr__('Positsioon'),
            'id' => $prefix . 'contact_fn',
            'type' => 'text'
        )
    );
    $cmb_contact->add_field( array(
            'name' => esc_attr__('Telefon'),
            'id' => $prefix . 'contact_phone',
            'type' => 'text'
        )
    );
    $cmb_contact->add_field( array(
            'name' => esc_attr__('E-post'),
            'id' => $prefix . 'contact_email',
            'type' => 'text'
        )
    );

    // Lehe "Agentuurist" meta
    $cmb_agenture = new_cmb2_box( array(
            'id' => $prefix . 'agenture_page_meta',
            'title' => esc_attr__('Agentuuri lehe seaded', 'cmb2' ),
            'object_types' => array('page'), // Post type
            'show_on' => array( 'key' => 'id', 'value' => 2 ),
            'context' => 'normal',
            'priority' => 'high',
            'show_names' => true, // Show field names on the left
        )
    );

    $cmb_agenture->add_field( array(
            'name' => esc_attr__('Tunnuspilt'),
            'id' => $prefix . 'featured_image',
            'desc' => esc_attr__('Eeldatakse, et üleslaetav pilt on suuruses 1280x808px'),
            'type'    => 'file',
            'options' => array(
                'url' => false
            ),
        )
    );

}
add_filter('cmb2_init', 'cmb_oi_metaboxes');

/**
 * Võta kasutusele Taxonomy_MetaData. Eemalda // märk funktsiooni eest.
 *
 * Tegemist on CMB abiklassiga
 * @link  https://github.com/jtsternberg/Taxonomy_MetaData#to-use-taxonomy_metadata-with-custom-metaboxes-and-fields
 *
 * Lisaks suurte andmemahtude salvestamise abiline:
 * @link https://github.com/voceconnect/wp-large-options/
 *
 * @since 1.0.0
 *
 */

function cmb2_taxonomy_meta_initiate()
{

    require_once('libs/Taxonomy_MetaData/Taxonomy_MetaData_CMB2.php');
    require_once('libs/wp-large-options/wp-large-options.php');

    $prefix = '_cmb_';
    /**
     * Semi-standard CMB metabox/fields array
     */
    $meta_box = array(
        'id'         => 'oi_cat_options',
        'show_on'    => array( 'key' => 'options-page', 'value' => array( 'unknown', ), ),
        'show_names' => true, // Show field names on the left
        'fields'     => array(
            array(
                'name'    => esc_attr__('Kirjeldus', 'orangetime'),
                'id'      => $prefix . 'cat_desc',
                'type'    => 'wysiwyg',
                'options' => array(
                    'textarea_rows' => 5,
                    'media_buttons' => false,
                    'teeny' => true
                ),
            ),
        )
    );

    $overrides = array(
        'get_option'    => 'wlo_get_option',
        'update_option' => 'wlo_update_option',
        'delete_option' => 'wlo_delete_option',
    );

    /**
     * Instantiate our taxonomy meta class
     */
    $cats = new Taxonomy_MetaData_CMB2( 'category', $meta_box, esc_attr__( 'Kontakti rubriigi täiendavad seaded'), $overrides );
}

cmb2_taxonomy_meta_initiate();


/**
 * Võta kasutusele AdminPageFramework klassi.
 * See võimaldab tekitada täiesti eraldiseisva seadete lehe.
 * Docs http://en.michaeluno.jp/admin-page-framework/
 *
 * Themes saad kätte andmed nii (keelepõhiseks kirjete kuvamiseks kasuta ICL_LANGUAGE_CODE konstanti):
 *
 *   $oi_theme_data = get_option('APF_ThemeOptions', array() );
 *   pre($oi_theme_data);
 *
 *   $oi_footer_data = $oi_theme_data['main_footer_' . ICL_LANGUAGE_CODE];
 *   pre($oi_footer_data);
 *
 *
 * @since 1.0.0
 *
 */

if ( !class_exists( 'AdminPageFramework' ) ) {
	include_once('libs/admin-page-framework/library/admin-page-framework.min.php' );
}

class APF_ThemeOptions extends AdminPageFramework {

     // Define the setUp() method to set how many pages, page titles and icons etc.
     public function setUp() {

        // Create the root menu
        $this->setRootMenuPage('Settings');
        $settings_page_title = sprintf(__('%s seaded', 'orangetime'), wp_get_theme());

        // Add the sub menus and the pages.
        // The third parameter accepts screen icon url that appears at the top of the page.
        $this->addSubMenuItems(
            array(
                'title' => $settings_page_title, // page title
                'page_slug' => 'oi_theme_options', // page slug
            )
        );


        if( function_exists('icl_get_languages') )
        {
            $languages = icl_get_languages();
        }

        if( isset($languages) )
        {
            foreach($languages as $key => $lang)
            {
                // Pages
                $this->addInPageTabs(
                    'oi_theme_options',
                    array(
                        'tab_slug' => $key,
                        'title'    => $lang['translated_name']
                    )
                );

                // Sections
                $this->addSettingSections(
                    'oi_theme_options',
                    array(
                        'section_id'    => 'options_' . $key,
                        'tab_slug'      => $lang['language_code'],
                    ),
                    array(
                        'section_id'    => 'main_footer_' . $key,
                        'title'         => esc_attr__('Jaluse seaded')
                    ),
                    array(
                        'section_id'    => 'main_front_' . $key,
                        'title'         => esc_attr__('Avalehe seaded')
                    ),
                    array(
                        'section_id'    => 'main_404_' . $key,
                        'title'         => esc_attr__('404 lehe seaded')
                    )
                );

                // Fields
                $this->addSettingFields(
                    'options_' . $key,
                    array(
                        'field_id'      => '404_title',
                        'section_id'    => 'main_404_' . $key,
                        'title'         => esc_attr__('404 lehe pealkiri'),
                        'type'          => 'text',
                        'attributes' => array(
                            'class' => 'regular-text'
                        )
                    ),
                    array(
                        'field_id'      => '404_content',
                        'section_id'    => 'main_404_' . $key,
                        'title'         => esc_attr__('404 lehe sisu'),
                        'type'          => 'textarea',
                        'rich'          => true
                    ),
                    array(
                        'field_id'      => 'title',
                        'section_id'    => 'main_front_' . $key,
                        'title'         => esc_attr__('Avalehe pealkiri'),
                        'type'          => 'text',
                        'attributes' => array(
                            'class' => 'regular-text'
                        )
                    ),
                    array(
                        'field_id'      => 'content',
                        'section_id'    => 'main_front_' . $key,
                        'title'         => esc_attr__('Avalehe tekst'),
                        'type'          => 'textarea',
                        'rich'          => array(
                            'teeny' => true,
                            'media_buttons' => false
                        )
                    )
                );
            }
        }

    }

    public function do_oi_theme_options() {
        submit_button();
        //$data = get_option('APF_ThemeOptions', array() );
        //pre($data);
    }
}

if ( is_admin() ) {
    new APF_ThemeOptions;
}

/**
 * Registreerimie päringu muutujad
 *
 *
 * @since 1.0.0
 *
 */
function oi_query_vars($qvars)
{
	$qvars[] = 'oi_post_id';
	return $qvars;
}
#add_filter('query_vars', 'oi_query_vars');

/**
 * Kujunduspõhja määramine
 *
 * "template_include"   => https://codex.wordpress.org/Plugin_API/Filter_Reference/template_include
 * "single_template"    => https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template
 * "template_redirect"  => https://codex.wordpress.org/Plugin_API/Action_Reference/template_redirect
 *
 *
 * @since 1.0.0
 *
 */

function oi_page_template_redirect()
{
    if( is_singular('oi_contact') )
    {
        wp_redirect(get_permalink(icl_object_id(7, 'page')));
        exit();
    }
}
add_action('template_redirect', 'oi_page_template_redirect');

/**
 * Kirjutame üle WordPressi enda galerii HTML-i
 * Uueks väljundiks on järjestamata loendi kujul galerii
 *
 * @since 1.0.0
 *
 */

function oi_gallery_shortcode($output = '', $atts)
{
    static $instance = 0;
    $instance++;

    extract($atts, EXTR_SKIP);

    $attachments = array();
    $output = array();
    $attachments = get_posts( array('include' => explode(',', $ids), 'post_in' => explode(',', $ids), 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

    if( !empty($ids) )
    {
        if ( is_feed() )
        {
            $output[]= " ";
            foreach ( $attachments as $key => $attachment ) {
                $output[]= wp_get_attachment_link($attachment->ID, 'thumbnail', true);
            }
            return implode("\n", $output);
        }

        $output[]= '<ul class="row oi-gallery oi-gallery-' . $instance . '" >';
        foreach ( $attachments as $key => $attachment )
        {
            $image_data_thumb = wp_get_attachment_image_src($attachment->ID);
            $image_data_full = wp_get_attachment_image_src($attachment->ID, 'large');

            array_push($output,
                oi_template(
                    '<li class="col-xs-6 col-sm-4 col-md-3">
                        <a href="%(full_url)s" class="gallery-thumb__url">
                            <span class="gallery-thumb__image"><img src="%(thumb_url)s" alt="%(title)s" width="%(width)s" height="%(height)s" /></span>
                            %(excerpt)s
                        </a>
                    </li>',

                    array(
                        'full_url' => $image_data_full[0],
                        'thumb_url' => $image_data_thumb[0],
                        'title' => $attachment->post_title,
                        'excerpt' => ( !empty($attachment->post_excerpt) ) ? sprintf('<span class="gallery-thumb__caption">%s</span>', wptexturize($attachment->post_excerpt)) : '',
                        'width' => $image_data_thumb[1],
                        'height' => $image_data_thumb[2]
                    )
                )
            );
        }
        $output[]= '</ul>';

        return implode("\n", $output);
    }
}
add_filter('post_gallery', 'oi_gallery_shortcode', 10, 2);

/**
 * Lehe üldise TITLE väärtuse juhtimine
 *
 * @since 1.0.0
 *
 */
function oi_wp_title($title, $sep, $seplocation)
{
	return str_replace('|', '&mdash;', $title);
}
add_filter('wp_title', 'oi_wp_title', 10, 3);
