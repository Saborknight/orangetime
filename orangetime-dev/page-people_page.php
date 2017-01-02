<?php
/**
 * Template Name: People
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
	the_post();
?>
<main role="main" class="main-content__helper">
	<div class="container">
		<article>
			<header class="article">
				<h1 class="article-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</header>
			<?php
				$args = array(
					'post_type' => 'oi_contact',
					'posts_per_page' => -1
				);
				$query = new WP_Query($args);
				$output = array();

				if( $query->have_posts() )
				{
					while( $query->have_posts() )
					{
						$query->the_post();

						$phone = get_post_meta(get_the_ID(), '_cmb_contact_phone', true);
						$email = get_post_meta(get_the_ID(), '_cmb_contact_email', true);
						$amet = trim(get_post_meta(get_the_ID(), '_cmb_contact_prof', true));
						$position = trim(get_post_meta(get_the_ID(), '_cmb_contact_fn', true));
						$prof = array($amet, $position);

						array_push($output,
							oi_template(
								'<li class="col-xs-6 col-sm-4 col-md-3">
									<article class="people">
										<figure class="people-image">%(featured_image)s</figure>
										<div class="people-content">
											<h2 class="people-name">%(title)s</h2>
											<p class="people-meta">%(prof)s</p>
											<ul class="people-contact-list">
												%(email)s
												%(phone)s
											</ul>
										</div>
									</article>
								</li>',

								array(
									'featured_image' => get_the_post_thumbnail(get_the_ID(), 'people-image'),
									'title' => get_the_title(),
									'prof' => implode(', ', array_filter($prof)),
									'email' => ( empty($email) ) ? '' : sprintf('<li><a href="mailto:%s" class="ico ico-email" title="%s" data-toggle="tooltip" title="%s"><span class="sr-only">%s</span></a></li>', antispambot($email), antispambot($email), esc_attr__('Saada e-kiri', 'orangetime'), antispambot($email)),
									'phone' => ( empty($phone) ) ? '' : sprintf('<li><a href="tel:%s" class="ico ico-phone" title="%s" data-toggle="tooltip" title="%s"><span class="sr-only">%s</span></a></li>', $phone, $phone, esc_attr__('Helista', 'orangetime'), $phone)
								)
							)
						);
					}
				}

				wp_reset_postdata();
			?>
			<ul class="row">
				<?php echo implode("\n", $output); ?>
			</ul>
		</article>
	</div>
</main>
<?php get_footer(); ?>
