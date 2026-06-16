<?php
/**
 * Archive template.
 *
 * @package AntNews
 */

get_header();
?>
<div id="wrap">
    <div class="wrap container">
        <main id="primary" class="main">
            <section class="sec-panel main-list">
                <div class="archive-header">
                    <h1><?php antnews_archive_title(); ?></h1>
                    <?php the_archive_description( '<div class="archive-description">', '</div>' ); ?>
                </div>
                <div class="filter-tabs">
                    <a class="active" href="#"><?php esc_html_e( '全部', 'antnews' ); ?></a>
                    <a href="#"><?php esc_html_e( '最新', 'antnews' ); ?></a>
                    <a href="#"><?php esc_html_e( '热门', 'antnews' ); ?></a>
                    <a href="#"><?php esc_html_e( '评论最多', 'antnews' ); ?></a>
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
