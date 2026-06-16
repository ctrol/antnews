<?php
/**
 * Sidebar template.
 *
 * @package AntNews
 */
?>
<aside class="sidebar" aria-label="<?php esc_attr_e( '侧栏', 'antnews' ); ?>">
    <?php
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        dynamic_sidebar( 'sidebar-1' );
    } else {
        antnews_default_widgets();
    }
    ?>
</aside>
