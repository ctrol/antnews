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
                the_post_navigation();
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
