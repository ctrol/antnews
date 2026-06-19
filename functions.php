<?php
/**
 * AntNews functions and definitions.
 *
 * @package AntNews
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ANTNEWS_VERSION', '6.11.7' );
define( 'ANTNEWS_DIR', get_template_directory() );
define( 'ANTNEWS_URI', get_template_directory_uri() );

require ANTNEWS_DIR . '/inc/customizer.php';
require ANTNEWS_DIR . '/inc/widgets.php';
require ANTNEWS_DIR . '/inc/category-meta.php';
require ANTNEWS_DIR . '/inc/theme-settings.php';

if ( ! function_exists( 'antnews_setup' ) ) {
    function antnews_setup() {
        load_theme_textdomain( 'antnews', ANTNEWS_DIR . '/languages' );

        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'responsive-embeds' );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
        add_theme_support( 'custom-logo', array(
            'height'      => 36,
            'width'       => 148,
            'flex-height' => true,
            'flex-width'  => true,
        ) );

        add_image_size( 'antnews-thumb', 480, 300, true );
        add_image_size( 'antnews-hero', 960, 496, true );
        add_image_size( 'antnews-small', 360, 225, true );

        register_nav_menus( array(
            'primary' => __( '主导航', 'antnews' ),
            'footer'  => __( '页脚导航', 'antnews' ),
            'mobile'  => __( '移动端底部导航', 'antnews' ),
        ) );
    }
}
add_action( 'after_setup_theme', 'antnews_setup' );

function antnews_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'antnews_content_width', 860 );
}
add_action( 'after_setup_theme', 'antnews_content_width', 0 );

function antnews_widgets_init() {
    register_sidebar( array(
        'name'          => __( '主侧栏', 'antnews' ),
        'id'            => 'sidebar-1',
        'description'   => __( '显示在首页、列表页和文章页右侧。', 'antnews' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => __( '页脚模块', 'antnews' ),
        'id'            => 'footer-widgets',
        'description'   => __( '显示在页脚上方。', 'antnews' ),
        'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="footer-widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'antnews_widgets_init' );

function antnews_scripts() {
    wp_enqueue_style( 'antnews-style', get_stylesheet_uri(), array(), ANTNEWS_VERSION );
    wp_enqueue_style( 'antnews-theme', ANTNEWS_URI . '/assets/css/theme.css', array( 'antnews-style' ), ANTNEWS_VERSION );
    wp_enqueue_script( 'antnews-theme', ANTNEWS_URI . '/assets/js/theme.js', array(), ANTNEWS_VERSION, true );

    $accent = antnews_get_option( 'accent_color', antnews_get_option( 'antnews_accent_color', '#206be7' ) );
    $custom_css = ':root{--theme-color:' . esc_attr( $accent ) . ';--theme-hover:' . esc_attr( antnews_adjust_brightness( $accent, -20 ) ) . ';}';
    wp_add_inline_style( 'antnews-theme', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'antnews_scripts' );

function antnews_fallback_menu() {
    echo '<ul class="nav navbar-nav">';
    wp_list_pages( array(
        'title_li' => '',
        'depth'    => 1,
    ) );
    echo '</ul>';
}

function antnews_adjust_brightness( $hex, $steps ) {
    $hex = ltrim( $hex, '#' );
    if ( 3 === strlen( $hex ) ) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $steps = max( -255, min( 255, (int) $steps ) );
    $color_parts = str_split( $hex, 2 );
    $return = '#';
    foreach ( $color_parts as $part ) {
        $part = hexdec( $part );
        $part = max( 0, min( 255, $part + $steps ) );
        $return .= str_pad( dechex( $part ), 2, '0', STR_PAD_LEFT );
    }
    return $return;
}

function antnews_get_option( $key, $default = '' ) {
    $settings = get_option( 'antnews_settings', array() );
    if ( is_array( $settings ) && array_key_exists( $key, $settings ) && '' !== $settings[ $key ] ) {
        return $settings[ $key ];
    }
    return get_theme_mod( $key, $default );
}

function antnews_allowed_ad_html() {
    $allowed = wp_kses_allowed_html( 'post' );
    $allowed['iframe'] = array(
        'src'             => true,
        'width'           => true,
        'height'          => true,
        'frameborder'     => true,
        'scrolling'       => true,
        'allow'           => true,
        'allowfullscreen' => true,
        'loading'         => true,
        'referrerpolicy'  => true,
        'style'           => true,
        'class'           => true,
        'id'              => true,
    );
    $allowed['script'] = array(
        'src'           => true,
        'async'         => true,
        'defer'         => true,
        'type'          => true,
        'charset'       => true,
        'crossorigin'   => true,
        'referrerpolicy'=> true,
    );
    $allowed['ins'] = array(
        'class'          => true,
        'style'          => true,
        'data-ad-client' => true,
        'data-ad-slot'   => true,
        'data-ad-format' => true,
        'data-full-width-responsive' => true,
    );
    return $allowed;
}

function antnews_render_ad_html( $html ) {
    echo wp_kses( $html, antnews_allowed_ad_html() );
}

function antnews_excerpt( $length = 88 ) {
    $excerpt = get_the_excerpt();
    if ( empty( $excerpt ) ) {
        $excerpt = wp_strip_all_tags( get_the_content() );
    }
    return wp_trim_words( $excerpt, $length, '...' );
}

function antnews_post_views_key() {
    return '_antnews_post_views';
}

function antnews_get_post_views( $post_id = null ) {
    $post_id = $post_id ? $post_id : get_the_ID();
    $views = (int) get_post_meta( $post_id, antnews_post_views_key(), true );
    return $views;
}

function antnews_set_post_views() {
    if ( ! is_single() || ! get_the_ID() ) {
        return;
    }
    $post_id = get_the_ID();
    $views = antnews_get_post_views( $post_id );
    update_post_meta( $post_id, antnews_post_views_key(), $views + 1 );
}
add_action( 'wp_head', 'antnews_set_post_views' );

function antnews_posted_meta() {
    $author = sprintf(
        '<span class="item-meta-li author">%s</span>',
        esc_html( get_the_author() )
    );
    $date = sprintf(
        '<span class="item-meta-li date"><time datetime="%s">%s</time></span>',
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() )
    );
    $views = sprintf(
        '<span class="item-meta-li views">%s</span>',
        esc_html( sprintf( _n( '%s 次阅读', '%s 次阅读', antnews_get_post_views(), 'antnews' ), number_format_i18n( antnews_get_post_views() ) ) )
    );
    echo wp_kses_post( $author . $date . $views );
}

function antnews_category_badge() {
    $categories = get_the_category();
    if ( empty( $categories ) ) {
        return;
    }
    $cat = $categories[0];
    echo '<a class="item-category" href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
}

function antnews_get_image_url( $size = 'antnews-thumb' ) {
    if ( has_post_thumbnail() ) {
        return get_the_post_thumbnail_url( get_the_ID(), $size );
    }
    $demo_image = get_post_meta( get_the_ID(), '_antnews_demo_image', true );
    if ( $demo_image ) {
        return esc_url_raw( $demo_image );
    }
    return ANTNEWS_URI . '/assets/images/placeholder.svg';
}

function antnews_get_term_cover( $term_id, $fallback = '' ) {
    $cover = get_term_meta( $term_id, 'antnews_cover', true );
    if ( $cover ) {
        return esc_url_raw( $cover );
    }
    return $fallback ? $fallback : ANTNEWS_URI . '/assets/images/topic-1.svg';
}

function antnews_render_loop_banner_ad() {
    $html = antnews_get_option( 'loop_banner_ad_html', '' );
    if ( $html ) {
        echo '<li class="item item-myimg antnews-loop-ad antnews-html-ad">';
        antnews_render_ad_html( $html );
        echo '</li>';
        return;
    }

    $image = antnews_get_option( 'loop_banner_ad_image', '' );
    $link  = antnews_get_option( 'loop_banner_ad_link', '' );

    if ( ! $image ) {
        $image = ANTNEWS_URI . '/assets/images/ad-banner.svg';
    }

    echo '<li class="item item-myimg antnews-loop-ad">';
    if ( $link ) {
        echo '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener">';
    }
    echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr__( '广告', 'antnews' ) . '">';
    if ( $link ) {
        echo '</a>';
    }
    echo '</li>';
}

function antnews_maybe_render_loop_banner_ad( $index ) {
    if ( 5 === (int) $index ) {
        antnews_render_loop_banner_ad();
    }
}

function antnews_archive_title() {
    if ( is_category() ) {
        single_cat_title();
    } elseif ( is_tag() ) {
        single_tag_title();
    } elseif ( is_author() ) {
        the_post();
        echo esc_html( get_the_author() );
        rewind_posts();
    } elseif ( is_day() ) {
        echo esc_html( get_the_date() );
    } elseif ( is_month() ) {
        echo esc_html( get_the_date( 'F Y' ) );
    } elseif ( is_year() ) {
        echo esc_html( get_the_date( 'Y' ) );
    } else {
        esc_html_e( '文章归档', 'antnews' );
    }
}

function antnews_default_widgets() {
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        return;
    }
    $sidebar_ad_html = antnews_get_option( 'sidebar_ad_html', '' );
    $ad_image = antnews_get_option( 'ad_image', antnews_get_option( 'antnews_ad_image', 'https://demo.wpcom.cn/justnews/wp-content/uploads/sites/8/2021/03/2021032509164512.jpg?imageMogr2/format/webp' ) );
    if ( $sidebar_ad_html || $ad_image ) :
        ?>
        <section class="widget widget_image_myimg">
            <div class="ad-card antnews-html-ad">
                <?php
                if ( $sidebar_ad_html ) {
                    antnews_render_ad_html( $sidebar_ad_html );
                } else {
                    echo '<img src="' . esc_url( $ad_image ) . '" alt="' . esc_attr__( '广告', 'antnews' ) . '">';
                }
                ?>
            </div>
        </section>
        <?php
    endif;
    ?>
    <section class="widget widget_kuaixun">
        <h3 class="widget-title"><?php esc_html_e( '快讯', 'antnews' ); ?> <a class="more" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '更多', 'antnews' ); ?></a></h3>
        <?php antnews_render_kuaixun_list( 7 ); ?>
    </section>
    <section class="widget">
        <div class="widget-tabs antnews-tab-nav" data-tabs="default-sidebar-tabs">
            <button class="active" type="button" data-tab-target="default-sidebar-views"><?php esc_html_e( '热门', 'antnews' ); ?></button>
            <button type="button" data-tab-target="default-sidebar-comments"><?php esc_html_e( '热评', 'antnews' ); ?></button>
            <button type="button" data-tab-target="default-sidebar-latest"><?php esc_html_e( '最新', 'antnews' ); ?></button>
            <button type="button" data-tab-target="default-sidebar-rand"><?php esc_html_e( '随机', 'antnews' ); ?></button>
        </div>
        <div class="antnews-tab-panels" data-tabs-panels="default-sidebar-tabs">
            <div class="antnews-tab-panel active" data-tab-panel="default-sidebar-views"><?php antnews_render_hot_list( 'views', 5 ); ?></div>
            <div class="antnews-tab-panel" data-tab-panel="default-sidebar-comments"><?php antnews_render_hot_list( 'comments', 5 ); ?></div>
            <div class="antnews-tab-panel" data-tab-panel="default-sidebar-latest"><?php antnews_render_hot_list( 'latest', 5 ); ?></div>
            <div class="antnews-tab-panel" data-tab-panel="default-sidebar-rand"><?php antnews_render_hot_list( 'rand', 5 ); ?></div>
        </div>
    </section>
    <section class="widget product-widget">
        <h3 class="widget-title"><?php esc_html_e( '产品设计', 'antnews' ); ?></h3>
        <div class="product-grid">
            <?php
            $query = antnews_widget_query( 'latest', 8 );
            while ( $query->have_posts() ) :
                $query->the_post();
                ?>
                <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( antnews_get_image_url( 'antnews-small' ) ); ?>" alt="<?php the_title_attribute(); ?>"><h4><?php the_title(); ?></h4></a>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </section>
    <?php
}
