<?php
/**
 * Main index template.
 *
 * @package AntNews
 */

get_header();
?>
<div id="wrap">
    <div class="wrap container">
        <main id="primary" class="main">
            <section class="sec-panel main-list">
                <div class="sec-panel-head">
                    <h2><?php esc_html_e( '最新文章', 'antnews' ); ?></h2>
                </div>
                <?php if ( have_posts() ) : ?>
                    <ul class="post-loop post-loop-default">
                        <?php $antnews_loop_index = 0; ?>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php $antnews_loop_index++; ?>
                            <?php get_template_part( 'template-parts/content', get_post_format() ); ?>
                            <?php antnews_maybe_render_loop_banner_ad( $antnews_loop_index ); ?>
                        <?php endwhile; ?>
                    </ul>
                    <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
                <?php else : ?>
                    <?php get_template_part( 'template-parts/content', 'none' ); ?>
                <?php endif; ?>
            </section>
        </main>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>
