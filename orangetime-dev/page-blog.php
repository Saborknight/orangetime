<?php
/**
 * Template Name: Blog
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 2.0.0
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
					'post_type' => 'oi_blog',
					'posts_per_page' => -1,
				);
				$query = new WP_Query($args);
				$output = array();

				if( $query->have_posts() )
				{
					while( $query->have_posts() )
					{
						$query->the_post();

						// Blog Post metadata
						$title = get_the_title();
						$title_attr = the_title_attribute('echo=0');
						$post_link = get_permalink();
						$author = get_the_author();
						/* translators: this is regarding author links. %s = Author name */
						$author_attr = sprintf(esc_attr__('See more posts by %s', 'orangetime'), $author);
						$author_link = get_author_posts_url( get_the_author_meta('ID'), get_the_author_meta( 'user_nicename' ));
						$terms = wp_get_post_terms($post->ID, 'oi_blog_categories', array('fields' => 'all'));
						/* translators: this is regarding blog category links */
						$term_pre_attr = esc_attr__('Find more posts related to', 'orangetime');

						$excerpt_length = 250; // Can be replaced with another function or global variable in the future

						// Finding the position of the last "." (if it exists) and getting rid of anything after it
						$excerpt = substr( esc_attr__( get_the_excerpt(), 'orangetime' ), 0, $excerpt_length );

						if( strlen( strrchr( $excerpt, "." ) ) > 0 ) {
							$excerpt_length = strrpos( $excerpt, "." ) + 1;
							$excerpt = substr( $excerpt, 0, $excerpt_length);
						}

						$read_more = '<span class="blog_post-read-more"></span>';
						/* translators: %s is the name of the blog post */
						$read_more_attr = sprintf(esc_attr__('Read more about %s', 'orangetime'), $title);

						// Loop to get the Categories of the Post
						$term_names = array();

						foreach ($terms as $key => $value)
						{
							array_push($term_names, sprintf('<a href="%4$s" title="%3$s %1$s">%1$s</a>', $value->name, $value->slug, $term_pre_attr, get_category_link( $value->term_id )));
						}

						array_push($output,
							oi_template(
								'<li class="row">
									<article class="blog_post">
										<div class="col-sm-12 col-md-6 blog_post-image_container">
											<a href="%(image_link)s"><figure class="blog_post-image">%(featured_image)s</figure></a>
										</div>
										<div class="col-sm-12 col-md-6 blog_post-content">
											<div>
												<h2 class="blog_post-name">%(title)s</h2>
												<p class="blog_post-excerpt">
													%(excerpt)s
												</p>
											</div>
											<div>
												<p class="blog_post-meta">%(read_more)s %(date)s %(separator)s %(categories)s</p>
											</div>
										</div>
									</article>
								</li>',

								array(
									'featured_image' => get_the_post_thumbnail(get_the_ID(), 'large'),
									'image_link' => $post_link,
									'title' => sprintf('<a href="%3$s" title="%2$s">%1$s</a>', $title, $title_attr, $post_link),
									// Author not on designs. Keeping just in case though
									// 'author' => sprintf('<a href="%2$s" title="%3$s">%1$s</a>', $author, $author_link, $author_attr),
									'date' => get_the_date('d. F Y'),
									'categories' => implode(", ", $term_names),
									'separator' => !empty($term_names) ? '<span class="separator">|</span>' : '',
									'excerpt' => $excerpt,
									'read_more' => sprintf('<a class="blog_post-read-more-link" href="%3$s" title="%2$s">%1$s</a>', $read_more, $read_more_attr, $post_link)
								)
							)
						);
					}
				} else {
					array_push($output, __('Oops, nothing here!', 'orangetime'));
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
