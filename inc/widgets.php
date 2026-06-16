<?php
/**
 * Custom widgets for AntNews.
 *
 * @package AntNews
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AntNews_Posts_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'antnews_posts_widget', __( 'AntNews 文章列表', 'antnews' ), array(
            'description' => __( '显示最新、热门或评论最多的文章。', 'antnews' ),
        ) );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '热门文章', 'antnews' );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $order = ! empty( $instance['order'] ) ? sanitize_key( $instance['order'] ) : 'latest';

        $query_args = array(
            'posts_per_page'      => $count,
            'ignore_sticky_posts' => true,
        );
        if ( 'views' === $order ) {
            $query_args['meta_key'] = antnews_post_views_key();
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'DESC';
        } elseif ( 'comments' === $order ) {
            $query_args['orderby'] = 'comment_count';
            $query_args['order'] = 'DESC';
        }

        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $query = new WP_Query( $query_args );
        if ( $query->have_posts() ) :
            echo '<ul class="compact-posts compact-posts-thumb">';
            while ( $query->have_posts() ) :
                $query->the_post();
                ?>
                <li>
                    <a class="compact-thumb" href="<?php the_permalink(); ?>">
                        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'antnews-small' ); } ?>
                    </a>
                    <div class="compact-body">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <span><?php echo esc_html( get_the_date() ); ?></span>
                    </div>
                </li>
                <?php
            endwhile;
            echo '</ul>';
            wp_reset_postdata();
        endif;
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( '热门文章', 'antnews' );
        $count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        $order = isset( $instance['order'] ) ? $instance['order'] : 'latest';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( '标题', 'antnews' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( '数量', 'antnews' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $count ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( '排序', 'antnews' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
                <option value="latest" <?php selected( $order, 'latest' ); ?>><?php esc_html_e( '最新', 'antnews' ); ?></option>
                <option value="views" <?php selected( $order, 'views' ); ?>><?php esc_html_e( '阅读量', 'antnews' ); ?></option>
                <option value="comments" <?php selected( $order, 'comments' ); ?>><?php esc_html_e( '评论数', 'antnews' ); ?></option>
            </select>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return array(
            'title' => sanitize_text_field( $new_instance['title'] ),
            'count' => absint( $new_instance['count'] ),
            'order' => sanitize_key( $new_instance['order'] ),
        );
    }
}

class AntNews_Ad_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'antnews_ad_widget', __( 'AntNews 广告图', 'antnews' ), array(
            'description' => __( '显示一张带链接的广告图片。', 'antnews' ),
        ) );
    }

    public function widget( $args, $instance ) {
        $html  = empty( $instance['image'] ) && empty( $instance['link'] ) ? antnews_get_option( 'sidebar_ad_html', '' ) : '';
        $image = ! empty( $instance['image'] ) ? esc_url( $instance['image'] ) : antnews_get_option( 'ad_image', antnews_get_option( 'antnews_ad_image', '' ) );
        $link  = ! empty( $instance['link'] ) ? esc_url( $instance['link'] ) : antnews_get_option( 'ad_link', antnews_get_option( 'antnews_ad_link', '' ) );
        if ( empty( $html ) && empty( $image ) ) {
            return;
        }
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<div class="ad-card antnews-html-ad">';
        if ( $html ) {
            antnews_render_ad_html( $html );
        } else {
            if ( $link ) {
                echo '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener">';
            }
            echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( '广告', 'antnews' ) . '">';
            if ( $link ) {
                echo '</a>';
            }
        }
        echo '</div>';
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    public function form( $instance ) {
        $image = isset( $instance['image'] ) ? $instance['image'] : '';
        $link = isset( $instance['link'] ) ? $instance['link'] : '';
        ?>
        <p><label><?php esc_html_e( '图片 URL', 'antnews' ); ?></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php echo esc_attr( $image ); ?>"></p>
        <p><label><?php esc_html_e( '链接 URL', 'antnews' ); ?></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_attr( $link ); ?>"></p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return array(
            'image' => esc_url_raw( $new_instance['image'] ),
            'link'  => esc_url_raw( $new_instance['link'] ),
        );
    }
}

function antnews_widget_query( $order = 'latest', $count = 5 ) {
    $args = array(
        'posts_per_page'      => absint( $count ),
        'ignore_sticky_posts' => true,
    );
    if ( 'views' === $order ) {
        $args['meta_key'] = antnews_post_views_key();
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ( 'comments' === $order ) {
        $args['orderby'] = 'comment_count';
        $args['order'] = 'DESC';
    } elseif ( 'rand' === $order ) {
        $args['orderby'] = 'rand';
    }
    return new WP_Query( $args );
}

function antnews_render_kuaixun_list( $count = 7 ) {
    $query = antnews_widget_query( 'latest', $count );
    echo '<ul class="kuaixun-list">';
    while ( $query->have_posts() ) :
        $query->the_post();
        ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span><?php echo esc_html( get_the_date() ); ?></span></li>
        <?php
    endwhile;
    wp_reset_postdata();
    echo '</ul>';
}

function antnews_render_hot_list( $order = 'views', $count = 5 ) {
    $query = antnews_widget_query( $order, $count );
    echo '<ul class="hot-list">';
    while ( $query->have_posts() ) :
        $query->the_post();
        ?>
        <li>
            <img src="<?php echo esc_url( antnews_get_image_url( 'antnews-small' ) ); ?>" alt="<?php the_title_attribute(); ?>">
            <div><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><span><?php echo esc_html( get_the_date() ); ?></span></div>
        </li>
        <?php
    endwhile;
    wp_reset_postdata();
    echo '</ul>';
}

class AntNews_Kuaixun_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'antnews_kuaixun_widget', __( 'AntNews 快讯列表', 'antnews' ), array(
            'description' => __( '以时间线样式显示最新文章，适合侧栏快讯。', 'antnews' ),
        ) );
    }
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '快讯', 'antnews' );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 7;
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo '<h3 class="widget-title">' . esc_html( $title ) . ' <a class="more" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( '更多', 'antnews' ) . '</a></h3>';
        antnews_render_kuaixun_list( $count );
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( '快讯', 'antnews' );
        $count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 7;
        ?>
        <p><label><?php esc_html_e( '标题', 'antnews' ); ?></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>"></p>
        <p><label><?php esc_html_e( '数量', 'antnews' ); ?></label><input class="tiny-text" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $count ); ?>"></p>
        <?php
    }
    public function update( $new_instance, $old_instance ) {
        return array( 'title' => sanitize_text_field( $new_instance['title'] ), 'count' => absint( $new_instance['count'] ) );
    }
}

class AntNews_Tabs_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'antnews_tabs_widget', __( 'AntNews 热门 Tab', 'antnews' ), array(
            'description' => __( '显示热门、热评、最新、随机四组文章。', 'antnews' ),
        ) );
    }
    public function widget( $args, $instance ) {
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $uid = 'widget-tabs-' . esc_attr( $this->number );
        echo '<div class="widget-tabs antnews-tab-nav" data-tabs="' . esc_attr( $uid ) . '">';
        echo '<button class="active" type="button" data-tab-target="' . esc_attr( $uid ) . '-views">' . esc_html__( '热门', 'antnews' ) . '</button>';
        echo '<button type="button" data-tab-target="' . esc_attr( $uid ) . '-comments">' . esc_html__( '热评', 'antnews' ) . '</button>';
        echo '<button type="button" data-tab-target="' . esc_attr( $uid ) . '-latest">' . esc_html__( '最新', 'antnews' ) . '</button>';
        echo '<button type="button" data-tab-target="' . esc_attr( $uid ) . '-rand">' . esc_html__( '随机', 'antnews' ) . '</button>';
        echo '</div><div class="antnews-tab-panels" data-tabs-panels="' . esc_attr( $uid ) . '">';
        echo '<div class="antnews-tab-panel active" data-tab-panel="' . esc_attr( $uid ) . '-views">';
        antnews_render_hot_list( 'views', $count );
        echo '</div><div class="antnews-tab-panel" data-tab-panel="' . esc_attr( $uid ) . '-comments">';
        antnews_render_hot_list( 'comments', $count );
        echo '</div><div class="antnews-tab-panel" data-tab-panel="' . esc_attr( $uid ) . '-latest">';
        antnews_render_hot_list( 'latest', $count );
        echo '</div><div class="antnews-tab-panel" data-tab-panel="' . esc_attr( $uid ) . '-rand">';
        antnews_render_hot_list( 'rand', $count );
        echo '</div></div>';
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    public function form( $instance ) {
        $count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 5;
        ?>
        <p><label><?php esc_html_e( '数量', 'antnews' ); ?></label><input class="tiny-text" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $count ); ?>"></p>
        <?php
    }
    public function update( $new_instance, $old_instance ) {
        return array( 'count' => absint( $new_instance['count'] ) );
    }
}

class AntNews_Product_Grid_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct( 'antnews_product_grid_widget', __( 'AntNews 产品双列', 'antnews' ), array(
            'description' => __( '以双列图片网格显示文章，适合产品设计侧栏。', 'antnews' ),
        ) );
    }
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( '产品设计', 'antnews' );
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 8;
        echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo $args['before_title'] . esc_html( $title ) . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $query = antnews_widget_query( 'latest', $count );
        echo '<div class="product-grid">';
        while ( $query->have_posts() ) :
            $query->the_post();
            ?>
            <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( antnews_get_image_url( 'antnews-small' ) ); ?>" alt="<?php the_title_attribute(); ?>"><h4><?php the_title(); ?></h4></a>
            <?php
        endwhile;
        wp_reset_postdata();
        echo '</div>';
        echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( '产品设计', 'antnews' );
        $count = isset( $instance['count'] ) ? absint( $instance['count'] ) : 8;
        ?>
        <p><label><?php esc_html_e( '标题', 'antnews' ); ?></label><input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>"></p>
        <p><label><?php esc_html_e( '数量', 'antnews' ); ?></label><input class="tiny-text" type="number" min="1" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $count ); ?>"></p>
        <?php
    }
    public function update( $new_instance, $old_instance ) {
        return array( 'title' => sanitize_text_field( $new_instance['title'] ), 'count' => absint( $new_instance['count'] ) );
    }
}

function antnews_register_custom_widgets() {
    register_widget( 'AntNews_Posts_Widget' );
    register_widget( 'AntNews_Ad_Widget' );
    register_widget( 'AntNews_Kuaixun_Widget' );
    register_widget( 'AntNews_Tabs_Widget' );
    register_widget( 'AntNews_Product_Grid_Widget' );
}
add_action( 'widgets_init', 'antnews_register_custom_widgets' );
