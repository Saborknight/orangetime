<?php
/**
 * Kujunduspõhja ühe postituse mall
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id: single.php 35152 2015-06-08 12:56:12Z tauno $
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
		<article class="comments">
			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>
		</article>
	</div>
</main>
<?php get_footer(); ?>
