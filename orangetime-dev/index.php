<?php
/**
 * Kujunduspõhja avaleht
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

	$oi_theme_data = get_option('APF_ThemeOptions', array() );
    $oi_front_data = @$oi_theme_data['main_front_' . ICL_LANGUAGE_CODE];

?>
<main role="main" class="main-content__helper">
	<article class="jumbotron">
		<div class="jumbotron-bg">
			<img class="jumbotron-bg-item" src="<?php echo get_template_directory_uri() . '/assets/dist/gfx/bg02.jpg'; ?>" width="100%" alt="" />
			<div class="jumbotron-bg-item jumbotron-bg-video hidden-xs">
				<video class="jumbotron-bg-item" autoplay poster="<?php echo get_template_directory_uri() . '/assets/dist/gfx/bg02.jpg'; ?>" src="<?php echo get_template_directory_uri() . '/assets/dist/video/Eas_Vabalava_timelapse.mp4'; ?>"></video>
			</div>
		</div>
		<div class="jumbotron-article">
			<div class="container">
				<div class="hidden-xs">
					<?php echo apply_filters('the_content', @$oi_front_data['content']); ?>
				</div>
				<h1><?php echo @$oi_front_data['title']; ?></h1>
			</div>
		</div>
	</article>
</main>
<?php get_footer(); ?>
