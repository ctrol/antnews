<?php
/**
 * 404 template.
 *
 * @package AntNews
 */

get_header();
?>
<div id="wrap">
    <div class="wrap container">
        <main id="primary" class="main">
            <section class="sec-panel empty-page">
                <h1><?php esc_html_e( '页面不存在', 'antnews' ); ?></h1>
                <p><?php esc_html_e( '你访问的页面可能已被删除或移动。可以尝试搜索内容，或返回首页继续浏览。', 'antnews' ); ?></p>
                <?php get_search_form(); ?>
                <p style="margin-top:18px"><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( '返回首页', 'antnews' ); ?></a></p>
            </section>
        </main>
        <?php get_sidebar(); ?>
    </div>
</div>
<?php get_footer(); ?>
