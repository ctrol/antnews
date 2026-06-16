<?php
/**
 * Footer template.
 *
 * @package AntNews
 */
?>
<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
    <div class="container hidden-xs j-partner">
        <div class="sec-panel footer-widgets">
            <?php dynamic_sidebar( 'footer-widgets' ); ?>
        </div>
    </div>
<?php endif; ?>

<footer class="footer">
    <div class="container">
        <?php
        wp_nav_menu( array(
            'theme_location' => 'footer',
            'menu_class'     => 'footer-nav',
            'container'      => false,
            'fallback_cb'    => false,
            'depth'          => 1,
        ) );
        ?>
        <div class="copyright">
            <?php echo wp_kses_post( antnews_get_option( 'antnews_footer_text', __( 'Copyright © 2026 AntNews. Powered by WordPress.', 'antnews' ) ) ); ?>
        </div>
    </div>
</footer>

<nav class="footer-bar" aria-label="<?php esc_attr_e( '移动端底部导航', 'antnews' ); ?>">
    <a class="fb-item" href="<?php echo esc_url( home_url( '/' ) ); ?>"><span class="fb-item-icon">⌂</span><span><?php esc_html_e( '首页', 'antnews' ); ?></span></a>
    <a class="fb-item" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>"><span class="fb-item-icon">☰</span><span><?php esc_html_e( '文章', 'antnews' ); ?></span></a>
    <a class="fb-item" href="<?php echo esc_url( wp_login_url() ); ?>"><span class="fb-item-icon">○</span><span><?php esc_html_e( '我的', 'antnews' ); ?></span></a>
</nav>

<?php wp_footer(); ?>
</body>
</html>
