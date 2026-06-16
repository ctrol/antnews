<?php
/**
 * Front page template.
 *
 * @package AntNews
 */

get_header();

$slider_cat = absint( antnews_get_option( 'slider_category', antnews_get_option( 'antnews_slider_category', 0 ) ) );
$slider_ids = array_filter( array_map( 'absint', explode( ',', (string) antnews_get_option( 'slider_post_ids', '' ) ) ) );
$slider_args = array(
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => true,
);
if ( ! empty( $slider_ids ) ) {
    $slider_args['post__in'] = $slider_ids;
    $slider_args['orderby'] = 'post__in';
    $slider_args['posts_per_page'] = count( $slider_ids );
} elseif ( $slider_cat ) {
    $slider_args['cat'] = $slider_cat;
}
$slider = new WP_Query( $slider_args );
$slider_posts = $slider->posts;
?>

<div id="wrap">
    <div class="wrap container">
        <main id="primary" class="main">
            <?php if ( ! empty( $slider_posts ) ) : ?>
                <section class="slider-wrap">
                    <div class="main-slider wpcom-slider swiper-container">
                        <?php
                        $main_post = $slider_posts[0];
                        setup_postdata( $main_post );
                        ?>
                        <a href="<?php the_permalink(); ?>">
                            <?php
                            if ( has_post_thumbnail() ) {
                                the_post_thumbnail( 'antnews-hero' );
                            } else {
                                echo '<img src="' . esc_url( antnews_get_image_url( 'antnews-hero' ) ) . '" alt="">';
                            }
                            ?>
                            <span class="slider-mask"></span>
                            <h2><?php the_title(); ?></h2>
                            <div class="slider-dots"><span></span><span class="active"></span><span></span></div>
                        </a>
                    </div>

                    <div class="slider-right">
                        <?php
                        foreach ( array_slice( $slider_posts, 1, 2 ) as $side_post ) :
                            setup_postdata( $side_post );
                            ?>
                            <article class="side-card">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( 'antnews-small' );
                                    } else {
                                        echo '<img src="' . esc_url( antnews_get_image_url( 'antnews-small' ) ) . '" alt="">';
                                    }
                                    ?>
                                    <span class="slider-mask"></span>
                                    <h3><?php the_title(); ?></h3>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>

            <?php
            $topic_ids = array_filter( array_map( 'absint', explode( ',', (string) antnews_get_option( 'topic_category_ids', '' ) ) ) );
            if ( $topic_ids ) {
                $topic_categories = get_categories( array(
                    'include'    => $topic_ids,
                    'hide_empty' => false,
                    'orderby'    => 'include',
                ) );
            } else {
                $topic_categories = get_categories( array(
                    'number'     => 4,
                    'hide_empty' => true,
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                ) );
            }
            if ( $topic_categories ) :
                ?>
                <section class="sec-panel topic-recommend">
                    <div class="sec-panel-head">
                        <h2><?php esc_html_e( '专题介绍', 'antnews' ); ?> <small><?php esc_html_e( '精选分类与热门内容方向', 'antnews' ); ?></small></h2>
                        <a class="more" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '全部专题', 'antnews' ); ?></a>
                    </div>
                    <ul class="list topic-list topic-list-2 topic-col-4">
                        <?php
                        $topic_images = array(
                            ANTNEWS_URI . '/assets/images/topic-1.svg',
                            ANTNEWS_URI . '/assets/images/topic-2.svg',
                            ANTNEWS_URI . '/assets/images/topic-3.svg',
                            ANTNEWS_URI . '/assets/images/topic-4.svg',
                        );
                        foreach ( array_values( $topic_categories ) as $index => $cat ) :
                            $fallback = isset( $topic_images[ $index ] ) ? $topic_images[ $index ] : $topic_images[0];
                            $image = antnews_get_term_cover( $cat->term_id, $fallback );
                            ?>
                            <li>
                                <a class="topic-wrap" href="<?php echo esc_url( get_category_link( $cat ) ); ?>">
                                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>">
                                    <span class="slider-mask"></span>
                                    <span class="topic-title"><?php echo esc_html( $cat->name ); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>

            <section class="sec-panel main-list">
                <div class="sec-panel-head tabs-head">
                    <h2><?php echo esc_html( antnews_get_option( 'antnews_home_title', __( '最新文章', 'antnews' ) ) ); ?></h2>
                    <ul class="list tabs j-newslist antnews-tab-nav" data-tabs="home-news">
                        <li><button class="active" type="button" data-tab-target="home-news-latest"><?php esc_html_e( '最新文章', 'antnews' ); ?></button></li>
                        <?php foreach ( array_slice( $topic_categories, 0, 4 ) as $cat ) : ?>
                            <li><button type="button" data-tab-target="home-news-cat-<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $cat->name ); ?></button></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php
                $home_posts_per_page = max( 1, min( 50, absint( antnews_get_option( 'home_posts_per_page', 10 ) ) ) );
                $home_paged = max( 1, absint( get_query_var( 'paged' ) ), absint( get_query_var( 'page' ) ) );
                $home_posts = new WP_Query( array(
                    'posts_per_page'      => $home_posts_per_page,
                    'paged'               => $home_paged,
                    'ignore_sticky_posts' => false,
                ) );
                echo '<div class="antnews-tab-panels" data-tabs-panels="home-news">';
                if ( $home_posts->have_posts() ) :
                    echo '<div class="antnews-tab-panel active" data-tab-panel="home-news-latest"><ul class="post-loop post-loop-default">';
                    $antnews_loop_index = 0;
                    while ( $home_posts->have_posts() ) :
                        $home_posts->the_post();
                        $antnews_loop_index++;
                        get_template_part( 'template-parts/content', get_post_format() );
                        antnews_maybe_render_loop_banner_ad( $antnews_loop_index );
                    endwhile;
                    echo '</ul>';
                    if ( $home_posts->max_num_pages > 1 ) {
                        echo '<nav class="navigation pagination antnews-home-pagination" aria-label="' . esc_attr__( '首页文章分页', 'antnews' ) . '">';
                        echo wp_kses_post( paginate_links( array(
                            'current'   => $home_paged,
                            'total'     => $home_posts->max_num_pages,
                            'mid_size'  => 2,
                            'prev_text' => __( '上一页', 'antnews' ),
                            'next_text' => __( '下一页', 'antnews' ),
                        ) ) );
                        echo '</nav>';
                    }
                    echo '</div>';
                    wp_reset_postdata();
                else :
                    get_template_part( 'template-parts/content', 'none' );
                endif;
                foreach ( array_slice( $topic_categories, 0, 4 ) as $cat ) :
                    $cat_posts = new WP_Query( array(
                        'posts_per_page'      => 8,
                        'cat'                 => $cat->term_id,
                        'ignore_sticky_posts' => true,
                    ) );
                    echo '<div class="antnews-tab-panel" data-tab-panel="home-news-cat-' . esc_attr( $cat->term_id ) . '"><ul class="post-loop post-loop-default">';
                    $antnews_loop_index = 0;
                    while ( $cat_posts->have_posts() ) :
                        $cat_posts->the_post();
                        $antnews_loop_index++;
                        get_template_part( 'template-parts/content', get_post_format() );
                        antnews_maybe_render_loop_banner_ad( $antnews_loop_index );
                    endwhile;
                    echo '</ul></div>';
                    wp_reset_postdata();
                endforeach;
                echo '</div>';
                ?>
            </section>
        </main>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>
