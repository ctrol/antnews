<?php
/**
 * Comments template.
 *
 * @package AntNews
 */

if ( post_password_required() ) {
    return;
}
?>
<section id="comments" class="comments-area sec-panel">
    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            printf(
                esc_html( _nx( '%1$s 条评论', '%1$s 条评论', get_comments_number(), 'comments title', 'antnews' ) ),
                number_format_i18n( get_comments_number() )
            );
            ?>
        </h2>
        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true,
                'avatar_size'=> 48,
            ) );
            ?>
        </ol>
        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php
    if ( ! comments_open() && get_comments_number() ) :
        ?>
        <p class="no-comments"><?php esc_html_e( '评论已关闭。', 'antnews' ); ?></p>
    <?php endif; ?>

    <?php comment_form(); ?>
</section>
