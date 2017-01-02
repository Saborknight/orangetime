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

                // Kui tegemist on koverentsi kategooriaga ja viimane postitus on kuvatud,
                // muuda selle linki ja viita see Portfelli lehele
                if( 4 == icl_object_id($current_cat_object->cat_ID, 'category') && $found_posts-- == 1 ) {
                    $last = true;
                }

                array_push($output,
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
                    )
                );
            }
        }
        ?>
        <ul class="row row-no-gutter">
            <?php echo implode("\n", $output); ?>
        </ul>
    </article>
</main>
<?php get_footer(); ?>
