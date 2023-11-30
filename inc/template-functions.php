<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package TestWork
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function testwork_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'testwork_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function testwork_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'testwork_pingback_header' );
/////////

add_action('woocommerce_product_options_general_product_data', 'add_custom_fields_to_products');
function add_custom_fields_to_products() {
    global $post;

    echo '<div class="options_group custom_fields_group">';

    $image_id = get_post_meta($post->ID, '_custom_product_image_id', true);
    woocommerce_wp_hidden_input(
        array(
            'id'    => '_custom_product_image_id',
            'value' => $image_id
        )
    );
    $image_url = wp_get_attachment_url($image_id);
    echo '<div id="custom_product_image_wrapper"' . ($image_url ? '' : ' style="display:none;"') . '>';
    echo '<img src="' . esc_url($image_url) . '" alt="" style="max-width:100%;">';
    echo '<button type="button" class="remove_custom_image_button button">' . __('Удалить изображение', 'woocommerce') . '</button>';
    echo '</div>';
    echo '<button type="button" class="upload_custom_image_button button">' . __('Добавить изображение', 'woocommerce') . '</button>';

    woocommerce_wp_text_input(
        array(
            'id'    => '_custom_product_date',
            'label' => __('Creation Date', 'woocommerce'),
            'type'  => 'date',
        )
    );

    woocommerce_wp_select(
        array(
            'id'      => '_custom_product_type',
            'label'   => __('Product Type', 'woocommerce'),
            'options' => array(
                'rare'     => __('Rare', 'woocommerce'),
                'frequent' => __('Frequent', 'woocommerce'),
                'unusual'  => __('Unusual', 'woocommerce')
            )
        )
    );

    echo '</div>';
}

add_action('woocommerce_process_product_meta', 'save_custom_fields');
function save_custom_fields($post_id) {
    if (isset($_POST['_custom_product_image_id'])) {
        update_post_meta($post_id, '_custom_product_image_id', sanitize_text_field($_POST['_custom_product_image_id']));
    }
    if (isset($_POST['_custom_product_date'])) {
        update_post_meta($post_id, '_custom_product_date', sanitize_text_field($_POST['_custom_product_date']));
    }
    if (isset($_POST['_custom_product_type'])) {
        update_post_meta($post_id, '_custom_product_type', sanitize_text_field($_POST['_custom_product_type']));
    }
}

/////



add_action('manage_product_posts_custom_column', 'custom_product_thumbnail_column_content', 10, 2);
function custom_product_thumbnail_column_content($column, $post_id) {
    if ('thumb' === $column) {
        $custom_image_id = get_post_meta($post_id, '_custom_product_image_id', true);
        if ($custom_image_id) {
            $image = wp_get_attachment_image($custom_image_id, 'thumbnail', false, array('style' => 'width:50px;height:auto;'));
            if ($image) {
                echo $image;
            }
        } else {
             echo '<img src="' . esc_url(wc_placeholder_img_src()) . '" alt="" style="width:50px;height:auto;">';
        }
    }
}