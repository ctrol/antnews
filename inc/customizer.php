<?php
/**
 * AntNews Customizer settings.
 *
 * @package AntNews
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function antnews_customize_register( $wp_customize ) {
    $wp_customize->add_panel( 'antnews_panel', array(
        'title'       => __( 'AntNews 主题设置', 'antnews' ),
        'description' => __( '管理首页、视觉、顶部通知、广告位和页脚内容。', 'antnews' ),
        'priority'    => 30,
    ) );

    $wp_customize->add_section( 'antnews_design', array(
        'title' => __( '视觉设置', 'antnews' ),
        'panel' => 'antnews_panel',
    ) );

    $wp_customize->add_setting( 'antnews_accent_color', array(
        'default'           => '#206be7',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'antnews_accent_color', array(
        'label'   => __( '主题主色', 'antnews' ),
        'section' => 'antnews_design',
    ) ) );

    $wp_customize->add_section( 'antnews_top_news', array(
        'title' => __( '顶部通知', 'antnews' ),
        'panel' => 'antnews_panel',
    ) );

    $wp_customize->add_setting( 'antnews_show_top_news', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'antnews_show_top_news', array(
        'label'   => __( '显示顶部通知', 'antnews' ),
        'section' => 'antnews_top_news',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'antnews_top_news_text', array(
        'default'           => __( '欢迎使用 AntNews，适合资讯站、博客站和内容社区。', 'antnews' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'antnews_top_news_text', array(
        'label'   => __( '通知文字', 'antnews' ),
        'section' => 'antnews_top_news',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'antnews_top_news_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'antnews_top_news_link', array(
        'label'   => __( '通知链接', 'antnews' ),
        'section' => 'antnews_top_news',
        'type'    => 'url',
    ) );

    $wp_customize->add_section( 'antnews_home', array(
        'title' => __( '首页设置', 'antnews' ),
        'panel' => 'antnews_panel',
    ) );

    $wp_customize->add_setting( 'antnews_slider_category', array(
        'default'           => 0,
        'sanitize_callback' => 'absint',
    ) );
    $wp_customize->add_control( 'antnews_slider_category', array(
        'label'       => __( '轮播文章分类', 'antnews' ),
        'description' => __( '不选择时默认读取最新文章。', 'antnews' ),
        'section'     => 'antnews_home',
        'type'        => 'dropdown-categories',
    ) );

    $wp_customize->add_setting( 'antnews_home_title', array(
        'default'           => __( '最新文章', 'antnews' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'antnews_home_title', array(
        'label'   => __( '首页文章区标题', 'antnews' ),
        'section' => 'antnews_home',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'antnews_ad_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'antnews_ad_image', array(
        'label'       => __( '侧栏广告图片 URL', 'antnews' ),
        'description' => __( '建议尺寸 300 × 320。', 'antnews' ),
        'section'     => 'antnews_home',
        'type'        => 'url',
    ) );

    $wp_customize->add_setting( 'antnews_ad_link', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'antnews_ad_link', array(
        'label'   => __( '侧栏广告链接', 'antnews' ),
        'section' => 'antnews_home',
        'type'    => 'url',
    ) );

    $wp_customize->add_section( 'antnews_footer', array(
        'title' => __( '页脚设置', 'antnews' ),
        'panel' => 'antnews_panel',
    ) );

    $wp_customize->add_setting( 'antnews_footer_text', array(
        'default'           => __( 'Copyright © 2026 AntNews. Powered by WordPress.', 'antnews' ),
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'antnews_footer_text', array(
        'label'   => __( '页脚版权文字', 'antnews' ),
        'section' => 'antnews_footer',
        'type'    => 'textarea',
    ) );
}
add_action( 'customize_register', 'antnews_customize_register' );
