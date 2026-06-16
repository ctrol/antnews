<?php
/**
 * Single post content.
 *
 * @package AntNews
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'sec-panel single-entry' ); ?>>
    <header class="entry-header">
        <h1 class="entry-title">
            <span class="entry-cats"><?php the_category( ' ' ); ?></span><span class="entry-title-text"><?php the_title(); ?></span>
        </h1>
        <div class="entry-meta"><?php antnews_posted_meta(); ?></div>
    </header>
    <div class="entry-top-box">
        <figure class="entry-thumb">
            <img src="<?php echo esc_url( antnews_get_image_url( 'antnews-thumb' ) ); ?>" alt="<?php the_title_attribute(); ?>">
        </figure>
        <div class="entry-square-ad">
            <?php
            $square_ad_html = antnews_get_option( 'single_square_ad_html', '' );
            if ( $square_ad_html ) {
                antnews_render_ad_html( $square_ad_html );
            } else {
                $square_ad_image = antnews_get_option( 'single_square_ad_image', ANTNEWS_URI . '/assets/images/ad-square.svg' );
                $square_ad_link  = antnews_get_option( 'single_square_ad_link', '' );
                if ( $square_ad_link ) {
                    echo '<a href="' . esc_url( $square_ad_link ) . '" target="_blank" rel="noopener">';
                }
                echo '<img src="' . esc_url( $square_ad_image ) . '" alt="' . esc_attr__( '广告', 'antnews' ) . '">';
                if ( $square_ad_link ) {
                    echo '</a>';
                }
            }
            ?>
        </div>
        <aside class="entry-summary-box">
            <h2><?php esc_html_e( '文章摘要', 'antnews' ); ?></h2>
            <p><?php echo esc_html( antnews_excerpt( 80 ) ); ?></p>
        </aside>
    </div>
    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( '分页：', 'antnews' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>
    <footer class="entry-footer">
        <?php the_tags( '<div class="entry-tags">', '', '</div>' ); ?>
    </footer>
</article>
