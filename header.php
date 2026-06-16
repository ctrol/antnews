<?php
/**
 * Header template.
 *
 * @package AntNews
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( '跳到内容', 'antnews' ); ?></a>

<?php if ( '0' !== (string) antnews_get_option( 'show_top_news', antnews_get_option( 'antnews_show_top_news', true ) ) ) : ?>
    <div class="top-news top-news-fluid" data-antnews-top>
        <div class="top-news-content container">
            <?php
            $top_items = antnews_parse_top_news_items( antnews_get_option( 'top_news_items', '' ) );
            if ( empty( $top_items ) ) {
                $top_items = array(
                    array(
                        'text' => antnews_get_option( 'antnews_top_news_text', __( '欢迎使用 AntNews，适合资讯站、博客站和内容社区。', 'antnews' ) ),
                        'link' => antnews_get_option( 'antnews_top_news_link', '' ),
                    ),
                );
            }
            ?>
            <div class="content-text top-news-list" data-antnews-top-rotate>
                <?php foreach ( $top_items as $i => $top_item ) : ?>
                    <span class="top-news-item<?php echo ( 0 === $i ) ? ' active' : ''; ?>">
                        <?php if ( ! empty( $top_item['link'] ) ) : ?>
                            <a href="<?php echo esc_url( $top_item['link'] ); ?>"><?php echo esc_html( $top_item['text'] ); ?></a>
                        <?php else : ?>
                            <?php echo esc_html( $top_item['text'] ); ?>
                        <?php endif; ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <button class="top-news-close" type="button" aria-label="<?php esc_attr_e( '关闭通知', 'antnews' ); ?>">×</button>
        </div>
    </div>
<?php endif; ?>

<header class="header header-fluid" id="masthead">
    <div class="container">
        <button class="nav-toggle" type="button" aria-controls="site-navigation" aria-expanded="false">
            <span class="screen-reader-text"><?php esc_html_e( '展开菜单', 'antnews' ); ?></span>
            <span></span>
            <span></span>
            <span></span>
        </button>
        <div class="brand-wrap">
            <?php $setting_logo = antnews_get_option( 'logo_url', '' ); ?>
            <?php if ( $setting_logo ) : ?>
                <h1 class="logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $setting_logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"></a></h1>
            <?php elseif ( has_custom_logo() ) : ?>
                <h1 class="logo"><?php the_custom_logo(); ?></h1>
            <?php else : ?>
                <h1 class="logo text-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
            <?php endif; ?>
        </div>

        <form class="mobile-header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <label class="screen-reader-text" for="mobile-header-search-field"><?php esc_html_e( '搜索', 'antnews' ); ?></label>
            <input id="mobile-header-search-field" type="search" name="s" placeholder="<?php esc_attr_e( '搜索', 'antnews' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
            <button type="submit"><?php esc_html_e( '搜索', 'antnews' ); ?></button>
        </form>

        <div class="navbar-collapse" id="site-navigation">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_class'     => 'nav navbar-nav',
                'container'      => false,
                'fallback_cb'    => 'antnews_fallback_menu',
                'depth'          => 0,
            ) );
            ?>
            <form class="header-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="screen-reader-text" for="header-search-field"><?php esc_html_e( '搜索', 'antnews' ); ?></label>
                <input id="header-search-field" type="search" name="s" placeholder="<?php esc_attr_e( '搜索', 'antnews' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
                <button type="submit"><?php esc_html_e( '搜索', 'antnews' ); ?></button>
            </form>
            <div class="navbar-action">
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( admin_url( 'profile.php' ) ); ?>"><?php esc_html_e( '我的', 'antnews' ); ?></a>
                <?php else : ?>
                    <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>"><?php esc_html_e( '登录', 'antnews' ); ?></a>
                    <a href="<?php echo esc_url( wp_registration_url() ); ?>"><?php esc_html_e( '注册', 'antnews' ); ?></a>
                <?php endif; ?>
                <a class="submit-post" href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php esc_html_e( '投稿', 'antnews' ); ?></a>
            </div>
        </div>
    </div>
</header>
