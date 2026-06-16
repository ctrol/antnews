<?php
/**
 * AntNews admin settings pages.
 *
 * @package AntNews
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function antnews_settings_defaults() {
    return array(
        'logo_id'            => '',
        'logo_url'           => '',
        'favicon_id'         => '',
        'favicon_url'        => '',
        'accent_color'       => '#206be7',
        'top_news_text'      => '自6.2开始主题新增页头通知功能，购买用户可免费升级到最新版体验',
        'top_news_link'      => '',
        'top_news_items'     => "自6.2开始主题新增页头通知功能，购买用户可免费升级到最新版体验|",
        'show_top_news'      => '1',
        'slider_category'    => '',
        'slider_post_ids'    => '',
        'topic_category_ids' => '',
        'home_posts_per_page' => 10,
        'ad_image'           => '',
        'ad_link'            => '',
        'sidebar_ad_html'    => '',
        'loop_banner_ad_image' => '',
        'loop_banner_ad_link'  => '',
        'loop_banner_ad_html'  => '',
        'single_square_ad_image' => '',
        'single_square_ad_link'  => '',
        'single_square_ad_html'  => '',
    );
}

function antnews_get_settings() {
    $stored = get_option( 'antnews_settings', array() );
    if ( is_array( $stored ) && empty( $stored['top_news_items'] ) && ! empty( $stored['top_news_text'] ) ) {
        $stored['top_news_items'] = trim( $stored['top_news_text'] . '|' . ( isset( $stored['top_news_link'] ) ? $stored['top_news_link'] : '' ), '|' );
    }
    return wp_parse_args( $stored, antnews_settings_defaults() );
}

function antnews_register_settings_menu() {
    add_menu_page(
        __( '主题设置', 'antnews' ),
        __( '主题设置', 'antnews' ),
        'manage_options',
        'antnews-settings',
        'antnews_general_settings_page',
        'dashicons-admin-customizer',
        61
    );
    add_submenu_page(
        'antnews-settings',
        __( '通用设置', 'antnews' ),
        __( '通用设置', 'antnews' ),
        'manage_options',
        'antnews-settings',
        'antnews_general_settings_page'
    );
    add_submenu_page(
        'antnews-settings',
        __( '广告设置', 'antnews' ),
        __( '广告设置', 'antnews' ),
        'manage_options',
        'antnews-ad-settings',
        'antnews_ad_settings_page'
    );
}
add_action( 'admin_menu', 'antnews_register_settings_menu' );

function antnews_admin_enqueue_settings_assets( $hook ) {
    if ( false === strpos( (string) $hook, 'antnews' ) ) {
        return;
    }
    wp_enqueue_media();
    $script = <<<'JS'
(function($){
    $(document).on('click', '.antnews-upload-image', function(e){
        e.preventDefault();
        var button  = $(this);
        var target  = $(button.data('target'));
        var preview = $(button.data('preview'));
        var frame = wp.media({
            title: '选择图片',
            button: { text: '使用这张图片' },
            multiple: false
        });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            target.val(attachment.url);
            if (preview.length) {
                preview.html('<img src="' + attachment.url + '" style="max-width:180px;max-height:80px;border:1px solid #ccd0d4;background:#fff;padding:4px;" alt="">');
            }
        });
        frame.open();
    });
    $(document).on('click', '.antnews-remove-image', function(e){
        e.preventDefault();
        var button  = $(this);
        var target  = $(button.data('target'));
        var preview = $(button.data('preview'));
        target.val('');
        preview.empty();
    });
})(jQuery);
JS;
    wp_add_inline_script( 'jquery', $script );
}
add_action( 'admin_enqueue_scripts', 'antnews_admin_enqueue_settings_assets' );

function antnews_save_settings( $group ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $settings = antnews_get_settings();
    if ( 'general' === $group ) {
        $settings['logo_id'] = '';
        $settings['logo_url'] = isset( $_POST['logo_url'] ) ? esc_url_raw( wp_unslash( $_POST['logo_url'] ) ) : '';
        $settings['favicon_id'] = '';
        $settings['favicon_url'] = isset( $_POST['favicon_url'] ) ? esc_url_raw( wp_unslash( $_POST['favicon_url'] ) ) : '';
        $settings['accent_color'] = isset( $_POST['accent_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['accent_color'] ) ) : '#206be7';
        $settings['top_news_items'] = isset( $_POST['top_news_items'] ) ? sanitize_textarea_field( wp_unslash( $_POST['top_news_items'] ) ) : '';
        $first_notice = antnews_parse_top_news_items( $settings['top_news_items'] );
        $settings['top_news_text'] = isset( $first_notice[0]['text'] ) ? $first_notice[0]['text'] : '';
        $settings['top_news_link'] = isset( $first_notice[0]['link'] ) ? $first_notice[0]['link'] : '';
        $settings['show_top_news'] = isset( $_POST['show_top_news'] ) ? '1' : '0';
        $settings['slider_category'] = isset( $_POST['slider_category'] ) ? absint( $_POST['slider_category'] ) : '';
        $settings['slider_post_ids'] = isset( $_POST['slider_post_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['slider_post_ids'] ) ) : '';
        $settings['topic_category_ids'] = isset( $_POST['topic_category_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['topic_category_ids'] ) ) : '';
        $settings['home_posts_per_page'] = isset( $_POST['home_posts_per_page'] ) ? max( 1, min( 50, absint( $_POST['home_posts_per_page'] ) ) ) : 10;
    }
    if ( 'ad' === $group ) {
        $settings['sidebar_ad_html'] = isset( $_POST['sidebar_ad_html'] ) ? wp_unslash( $_POST['sidebar_ad_html'] ) : '';
        $settings['loop_banner_ad_html'] = isset( $_POST['loop_banner_ad_html'] ) ? wp_unslash( $_POST['loop_banner_ad_html'] ) : '';
        $settings['single_square_ad_html'] = isset( $_POST['single_square_ad_html'] ) ? wp_unslash( $_POST['single_square_ad_html'] ) : '';
    }
    update_option( 'antnews_settings', $settings );
}

function antnews_parse_top_news_items( $raw = '' ) {
    $items = array();
    $raw   = trim( (string) $raw );
    if ( '' === $raw ) {
        $text = antnews_get_option( 'top_news_text', '' );
        $link = antnews_get_option( 'top_news_link', '' );
        if ( $text ) {
            $items[] = array(
                'text' => $text,
                'link' => $link,
            );
        }
        return $items;
    }

    foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
        $line = trim( $line );
        if ( '' === $line ) {
            continue;
        }
        $parts = array_map( 'trim', explode( '|', $line, 2 ) );
        $text  = sanitize_text_field( $parts[0] );
        $link  = isset( $parts[1] ) ? esc_url_raw( $parts[1] ) : '';
        if ( $text ) {
            $items[] = array(
                'text' => $text,
                'link' => $link,
            );
        }
    }
    return $items;
}

function antnews_media_upload_field( $field_id, $value, $description = '' ) {
    $preview_id = $field_id . '_preview';
    ?>
    <input class="regular-text" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( $field_id ); ?>" type="url" value="<?php echo esc_attr( $value ); ?>">
    <button class="button antnews-upload-image" data-target="#<?php echo esc_attr( $field_id ); ?>" data-preview="#<?php echo esc_attr( $preview_id ); ?>"><?php esc_html_e( '上传/选择图片', 'antnews' ); ?></button>
    <button class="button antnews-remove-image" data-target="#<?php echo esc_attr( $field_id ); ?>" data-preview="#<?php echo esc_attr( $preview_id ); ?>"><?php esc_html_e( '移除', 'antnews' ); ?></button>
    <div id="<?php echo esc_attr( $preview_id ); ?>" style="margin-top:10px;">
        <?php if ( $value ) : ?>
            <img src="<?php echo esc_url( $value ); ?>" style="max-width:180px;max-height:80px;border:1px solid #ccd0d4;background:#fff;padding:4px;" alt="">
        <?php endif; ?>
    </div>
    <?php if ( $description ) : ?>
        <p class="description"><?php echo esc_html( $description ); ?></p>
    <?php endif; ?>
    <?php
}

function antnews_general_settings_page() {
    if ( isset( $_POST['antnews_save_general'] ) && check_admin_referer( 'antnews_save_general_action' ) ) {
        antnews_save_settings( 'general' );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( '通用设置已保存。', 'antnews' ) . '</p></div>';
    }
    $settings = antnews_get_settings();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( '主题设置 - 通用设置', 'antnews' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'antnews_save_general_action' ); ?>
            <table class="form-table" role="presentation">
                <tr><th><label for="logo_url"><?php esc_html_e( 'Logo 图片', 'antnews' ); ?></label></th><td><?php antnews_media_upload_field( 'logo_url', $settings['logo_url'], __( '建议 148 × 36 横向 SVG/PNG。留空则使用 WordPress 自定义 Logo。', 'antnews' ) ); ?></td></tr>
                <tr><th><label for="favicon_url"><?php esc_html_e( 'Favicon 图片', 'antnews' ); ?></label></th><td><?php antnews_media_upload_field( 'favicon_url', $settings['favicon_url'], __( '建议上传正方形 PNG/ICO 图片。', 'antnews' ) ); ?></td></tr>
                <tr><th><label for="accent_color"><?php esc_html_e( '主题主色', 'antnews' ); ?></label></th><td><input id="accent_color" name="accent_color" type="text" value="<?php echo esc_attr( $settings['accent_color'] ); ?>" class="regular-text" placeholder="#206be7"></td></tr>
                <tr><th><?php esc_html_e( '顶部通知', 'antnews' ); ?></th><td><label><input name="show_top_news" type="checkbox" value="1" <?php checked( $settings['show_top_news'], '1' ); ?>> <?php esc_html_e( '显示顶部通知', 'antnews' ); ?></label></td></tr>
                <tr><th><label for="top_news_items"><?php esc_html_e( '多条通告', 'antnews' ); ?></label></th><td><textarea class="large-text" id="top_news_items" name="top_news_items" rows="6"><?php echo esc_textarea( $settings['top_news_items'] ? $settings['top_news_items'] : trim( $settings['top_news_text'] . '|' . $settings['top_news_link'], '|' ) ); ?></textarea><p class="description"><?php esc_html_e( '每行一条通告，格式：通告文字|链接。链接可留空，例如：网站维护通知|https://example.com。', 'antnews' ); ?></p></td></tr>
                <tr><th><label for="slider_category"><?php esc_html_e( '首页焦点幻灯片分类', 'antnews' ); ?></label></th><td><?php wp_dropdown_categories( array( 'show_option_none' => __( '读取最新文章', 'antnews' ), 'hide_empty' => false, 'name' => 'slider_category', 'id' => 'slider_category', 'selected' => absint( $settings['slider_category'] ) ) ); ?></td></tr>
                <tr><th><label for="slider_post_ids"><?php esc_html_e( '首页焦点文章 ID', 'antnews' ); ?></label></th><td><input class="regular-text" id="slider_post_ids" name="slider_post_ids" type="text" value="<?php echo esc_attr( $settings['slider_post_ids'] ); ?>"><p class="description"><?php esc_html_e( '可选，英文逗号分隔，如 12,15,20。填写后优先于分类。', 'antnews' ); ?></p></td></tr>
                <tr><th><label for="topic_category_ids"><?php esc_html_e( '专题分类 ID', 'antnews' ); ?></label></th><td><input class="regular-text" id="topic_category_ids" name="topic_category_ids" type="text" value="<?php echo esc_attr( $settings['topic_category_ids'] ); ?>"><p class="description"><?php esc_html_e( '可选，英文逗号分隔，最多显示 4 个。留空则自动读取文章最多的 4 个分类。', 'antnews' ); ?></p></td></tr>
                <tr><th><label for="home_posts_per_page"><?php esc_html_e( '首页文章列表每页数量', 'antnews' ); ?></label></th><td><input class="small-text" id="home_posts_per_page" name="home_posts_per_page" type="number" min="1" max="50" step="1" value="<?php echo esc_attr( absint( $settings['home_posts_per_page'] ) ); ?>"><p class="description"><?php esc_html_e( '控制首页“最新文章”默认展示数量，超出后自动显示分页。建议 6-20。', 'antnews' ); ?></p></td></tr>
            </table>
            <p><button class="button button-primary" name="antnews_save_general" value="1"><?php esc_html_e( '保存设置', 'antnews' ); ?></button></p>
        </form>
    </div>
    <?php
}

function antnews_ad_settings_page() {
    if ( isset( $_POST['antnews_save_ad'] ) && check_admin_referer( 'antnews_save_ad_action' ) ) {
        antnews_save_settings( 'ad' );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( '广告设置已保存。', 'antnews' ) . '</p></div>';
    }
    $settings = antnews_get_settings();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( '主题设置 - 广告设置', 'antnews' ); ?></h1>
        <form method="post">
            <?php wp_nonce_field( 'antnews_save_ad_action' ); ?>
            <table class="form-table" role="presentation">
                <tr><th><label for="sidebar_ad_html"><?php esc_html_e( '侧栏广告 HTML', 'antnews' ); ?></label></th><td><textarea class="large-text code" id="sidebar_ad_html" name="sidebar_ad_html" rows="7"><?php echo esc_textarea( $settings['sidebar_ad_html'] ); ?></textarea><p class="description"><?php esc_html_e( '支持填写 img、a、iframe、script 等广告代码。留空时使用主题默认广告图。', 'antnews' ); ?></p></td></tr>
                <tr><th><label for="loop_banner_ad_html"><?php esc_html_e( '列表横幅广告 HTML', 'antnews' ); ?></label></th><td><textarea class="large-text code" id="loop_banner_ad_html" name="loop_banner_ad_html" rows="7"><?php echo esc_textarea( $settings['loop_banner_ad_html'] ); ?></textarea><p class="description"><?php esc_html_e( '显示在主体文章列表第 5 篇文章下方，建议尺寸 820 × 120。', 'antnews' ); ?></p></td></tr>
                <tr><th><label for="single_square_ad_html"><?php esc_html_e( '正文方形广告 HTML', 'antnews' ); ?></label></th><td><textarea class="large-text code" id="single_square_ad_html" name="single_square_ad_html" rows="7"><?php echo esc_textarea( $settings['single_square_ad_html'] ); ?></textarea><p class="description"><?php esc_html_e( '显示在文章正文顶部，建议尺寸 180 × 180 或 240 × 240。', 'antnews' ); ?></p></td></tr>
            </table>
            <p><button class="button button-primary" name="antnews_save_ad" value="1"><?php esc_html_e( '保存设置', 'antnews' ); ?></button></p>
        </form>
    </div>
    <?php
}

function antnews_output_favicon() {
    $favicon = antnews_get_option( 'favicon_url', '' );
    if ( $favicon ) {
        echo '<link rel="icon" href="' . esc_url( $favicon ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'antnews_output_favicon', 2 );
