<?php
/**
 * Page content.
 *
 * @package AntNews
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'sec-panel single-entry page-entry' ); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>
    <div class="entry-content">
        <?php
        the_content();
        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( '分页：', 'antnews' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>
</article>
