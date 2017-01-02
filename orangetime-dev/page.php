<?php
/**
 * Tavalise lehe kujunduspõhi
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id: page.php 35191 2015-06-09 12:53:00Z tauno $
 *
 */
	defined('ABSPATH') or die();
	get_header();
	the_post();
?>
<main role="main" class="main-content__helper">
	<div class="container">
		<article class="article">
			<h1 class="article-title"><?php the_title(); ?></h1>
			<?php the_content(); ?>
		</article>
	</div>
    <?php if( is_page(icl_object_id(2, 'page', true))) : ?>
        <?php
            $featured_image_id = get_post_meta(get_the_ID(), '_cmb_featured_image_id', true);
            $featured_image = wp_get_attachment_image($featured_image_id, array(1280, 808));
        ?>
        <div class="footer-featured-image"><?php echo $featured_image; ?></div>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
