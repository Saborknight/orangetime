<?php
/**
 * Kujunduspõhja jalus
 *
 * @package WordPress
 * @subpackage Orangetime
 * @since 1.0.0
 *
 * @version $Id$
 *
 */
    defined('ABSPATH') or die();
?>
<footer class="main-footer">
    <div class="container">
        <p><?php
            if( is_category(icl_object_id(4, 'category')) ) {
                _e('Orangetime Konverentsid OÜ', 'orangetime');
            } elseif( is_single() ) {
                $terms = wp_get_post_terms($post->ID, 'category');
                if( ! is_wp_error($terms) && isset($terms[0]) && 4 == icl_object_id($terms[0]->term_id, 'category') ) {
                    _e('Orangetime Konverentsid OÜ', 'orangetime');
                }
            } else {
                _e('Orangetime Event OÜ', 'orangetime');
            }
        ?><span class="sep">/</span>Telliskivi 60, Tallinn 10412<span class="sep">/</span>www.orangetime.ee<span class="sep">/</span><a href="https://www.facebook.com/orangetimeevent" target="_blank" class="ico-fb">orangetimeevent</a></p>
    </div>
</footer>

</div>
<?php wp_footer(); ?>
</body>
</html>
