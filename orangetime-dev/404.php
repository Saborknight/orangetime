<?php
/**
 * Kujunduspõhja 404 vealehe mall
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id: 404.php 35144 2015-06-08 12:48:30Z tauno $
 *
 */
    defined('ABSPATH') or die();
    get_header();

    $oi_theme_data = get_option('APF_ThemeOptions', array() );
    $theme_data = $oi_theme_data['main_404_' . ICL_LANGUAGE_CODE];
?>
<main role="main" class="main-content__helper">
    <article class="article">
        <h1 class="article-title"><?php echo $theme_data['404_title']; ?></h1>
        <?php echo apply_filters('the_content', $theme_data['404_content']); ?>
    </article>
</main>
<?php get_footer(); ?>
