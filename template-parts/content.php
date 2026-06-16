<?php
/**
 * Post loop item.
 *
 * @package AntNews
 */

$classes = array( 'item' );
if ( is_sticky() ) {
    $classes[] = 'item-sticky';
}
if ( ! has_post_thumbnail() && ! get_post_meta( get_the_ID(), '_antnews_demo_image', true ) ) {
    $classes[] = 'item-no-thumb';
}
?>
<li <?php post_class( $classes ); ?>>
    <div class="item-img">
        <a class="item-img-inner" href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url( antnews_get_image_url( 'antnews-thumb' ) ); ?>" alt="<?php the_title_attribute(); ?>">
            <?php antnews_category_badge(); ?>
        </a>
    </div>
    <div class="item-content">
        <h3 class="item-title">
            <a href="<?php the_permalink(); ?>">
                <?php if ( is_sticky() ) : ?><span class="sticky-post"><?php esc_html_e( '置顶', 'antnews' ); ?></span><?php endif; ?>
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="item-excerpt"><p><?php echo esc_html( antnews_excerpt( 48 ) ); ?></p></div>
        <div class="item-meta"><?php antnews_posted_meta(); ?></div>
    </div>
</li>
