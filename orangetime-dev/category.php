<?php
/**
 * Kujunduspõhja kategooria mall
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id$
 *
 */
    defined('ABSPATH') or die();
    get_header();

    global $post, $wp_query;
?>


<main role="main" class="main-content__helper">
    <article class="category-article">
        <header class="article">
            <h1 class="article-title"><?php single_cat_title(); ?></h1>
            <?php
                $current_cat_object = get_queried_object();
                $terms = wp_get_post_terms($post->ID, 'category');
                $category_description = Taxonomy_MetaData::get('category', $current_cat_object->cat_ID, '_cmb_cat_desc');
                echo apply_filters('the_content', $category_description);
            ?>
        </header>
        <?php

        $output = array();
        $found_posts = $wp_query->found_posts;
        $last = false;

        if( have_posts() )
        {
            while( have_posts() )
            {
                the_post();

                // Specify Templates
                if(
                    4 == icl_object_id($current_cat_object->cat_ID, 'category')
                    ||
                    7 == icl_object_id($current_cat_object->cat_ID, 'category')
                    ||
                    5 == icl_object_id($current_cat_object->cat_ID, 'category')
                    ||
                    8 == icl_object_id($current_cat_object->cat_ID, 'category')
                ) { // Conference or Events
                    $post_index = $wp_query->current_post + 1;
                    $highlighted = false;

                    // Kui tegemist on koverentsi kategooriaga ja viimane postitus on kuvatud,
                    // muuda selle linki ja viita see Portfelli lehele
                    if( $found_posts-- == 1 ) {
                        $last = true;
                    }

                    if( $post_index <= 2 ) {
                        $highlighted = true;
                    }

                    $cat_template = 
                        oi_template(
                            '<li class="col-xs-6 col-sm-4 col-md-%(width)s work-item">
                                <article class="work %(highlighted_style)s">
                                    <a href="%(url)s" class="work-url conference-url">
                                        <figure class="work-image %(highlighted_style_image)s">%(featured_image)s</figure>
                                        <div class="work-content conference-content">
                                            <div class="work-title-container">
                                                <h2 class="work-title conference-title"><span>%(title)s</span></h2>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            </li>',

                            array(
                                'width' => ( $highlighted ) ? '6' : '3',
                                'highlighted_style' => ( $highlighted ) ? 'conference' : '',
                                'highlighted_style_image' => ( $highlighted ) ? 'conference-image' : '',
                                'featured_image' => get_the_post_thumbnail(get_the_ID(), ( $highlighted ) ? 'large' : 'category-image'),
                                'title' => get_the_title(),
                                'url' => ( $last ) ? get_category_link(icl_object_id(5, 'category')) : get_permalink()
                            )
                        );
                } else { // Default (Not used now that "Events" cat used above)
                    $cat_template =
                        oi_template(
                            '<li class="col-xs-6 col-sm-4 col-md-3">
                                <article class="work">
                                    <a href="%(url)s" class="work-url">
                                        <figure class="work-image">%(featured_image)s</figure>
                                        <div class="work-content">
                                            <h2 class="work-title"><span>%(title)s</span></h2>
                                        </div>
                                    </a>
                                </article>
                            </li>',

                            array(
                                'featured_image' => get_the_post_thumbnail(get_the_ID(), 'category-image'),
                                'title' => get_the_title(),
                                'url' => ( $last ) ? get_category_link(icl_object_id(5, 'category')) : get_permalink()
                            )
                        );
                }

                array_push($output, $cat_template);
            }
        }
        ?>
        <ul class="row row-no-gutter">
            <?php echo implode("\n", $output); ?>
        </ul>
    </article>
</main>
<?php get_footer(); ?>
