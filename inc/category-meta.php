<?php
/**
 * Category cover image settings.
 *
 * @package AntNews
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function antnews_category_add_cover_field() {
    ?>
    <div class="form-field term-antnews-cover-wrap">
        <label for="antnews_cover"><?php esc_html_e( 'AntNews 专题封面 URL', 'antnews' ); ?></label>
        <input name="antnews_cover" id="antnews_cover" type="url" value="">
        <p><?php esc_html_e( '用于首页专题卡片，建议比例 480 × 300。', 'antnews' ); ?></p>
    </div>
    <?php
}
add_action( 'category_add_form_fields', 'antnews_category_add_cover_field' );

function antnews_category_edit_cover_field( $term ) {
    $cover = get_term_meta( $term->term_id, 'antnews_cover', true );
    ?>
    <tr class="form-field term-antnews-cover-wrap">
        <th scope="row"><label for="antnews_cover"><?php esc_html_e( 'AntNews 专题封面 URL', 'antnews' ); ?></label></th>
        <td>
            <input name="antnews_cover" id="antnews_cover" type="url" value="<?php echo esc_attr( $cover ); ?>">
            <p class="description"><?php esc_html_e( '用于首页专题卡片，建议比例 480 × 300。', 'antnews' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'antnews_category_edit_cover_field' );

function antnews_save_category_cover_field( $term_id ) {
    if ( isset( $_POST['antnews_cover'] ) ) {
        update_term_meta( $term_id, 'antnews_cover', esc_url_raw( wp_unslash( $_POST['antnews_cover'] ) ) );
    }
}
add_action( 'created_category', 'antnews_save_category_cover_field' );
add_action( 'edited_category', 'antnews_save_category_cover_field' );
