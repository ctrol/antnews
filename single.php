<?php
/**
 * Single post template.
 *
 * @package AntNews
 */

get_header();
?>
<div id="wrap">
    <div class="wrap container">
        <main id="primary" class="main">
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content', 'single' );

                /* Custom post navigation with thumbnails */
                $prev_post = get_previous_post();
                $next_post = get_next_post();
                if ( $prev_post || $next_post ) :
                    ?>
                    <nav class="post-navigation antnews-post-nav" aria-label="<?php esc_attr_e( '文章导航', 'antnews' ); ?>">
                        <div class="nav-links">
                            <?php if ( $prev_post ) : ?>
                                <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="nav-card nav-prev">
                                    <span class="nav-corner-label">&laquo; <?php esc_html_e( '上一篇', 'antnews' ); ?></span>
                                    <?php
                                    if ( has_post_thumbnail( $prev_post ) ) {
                                        echo get_the_post_thumbnail( $prev_post, 'antnews-small' );
                                    } else {
                                        echo '<img src="' . esc_url( antnews_get_image_url( 'antnews-small' ) ) . '" alt="">';
                                    }
                                    ?>
                                    <div class="nav-card-overlay">
                                        <span class="nav-card-title"><?php echo esc_html( get_the_title( $prev_post ) ); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                            <?php if ( $next_post ) : ?>
                                <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="nav-card nav-next">
                                    <span class="nav-corner-label"><?php esc_html_e( '下一篇', 'antnews' ); ?> &raquo;</span>
                                    <?php
                                    if ( has_post_thumbnail( $next_post ) ) {
                                        echo get_the_post_thumbnail( $next_post, 'antnews-small' );
                                    } else {
                                        echo '<img src="' . esc_url( antnews_get_image_url( 'antnews-small' ) ) . '" alt="">';
                                    }
                                    ?>
                                    <div class="nav-card-overlay">
                                        <span class="nav-card-title"><?php echo esc_html( get_the_title( $next_post ) ); ?></span>
                                    </div>
                                </a>
                            <?php endif; ?>
                        </div>
                    </nav>
                    <?php
                endif;
                $related = new WP_Query( array(
                    'posts_per_page'      => 3,
                    'post__not_in'        => array( get_the_ID() ),
                    'ignore_sticky_posts' => true,
                ) );
                if ( $related->have_posts() ) :
                    ?>
                    <section class="sec-panel related-posts">
                        <div class="archive-header" style="min-height:auto;padding:0 0 16px;border:0">
                            <h1 style="font-size:18px;line-height:26px"><?php esc_html_e( '相关文章', 'antnews' ); ?></h1>
                        </div>
                        <div class="related-grid">
                            <?php
                            while ( $related->have_posts() ) :
                                $related->the_post();
                                ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( 'antnews-small' );
                                    } else {
                                        echo '<img src="' . esc_url( antnews_get_image_url( 'antnews-small' ) ) . '" alt="">';
                                    }
                                    ?>
                                    <h3><?php the_title(); ?></h3>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </section>
                    <?php
                    wp_reset_postdata();
                endif;
                if ( comments_open() || get_comments_number() ) {
                    comments_template();
                }
            endwhile;
            ?>
        </main>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>
