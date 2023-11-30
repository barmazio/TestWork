<?php
/*
Template Name: CREATE PRODUCT
*/

function handle_product_creation() {
    if( isset($_POST['submit_product']) && !empty($_FILES['product_image']['name']) ) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $product_title = sanitize_text_field($_POST['product_title']);
        $product_price = sanitize_text_field($_POST['product_price']);
        $product_date = sanitize_text_field($_POST['product_date']);
        $product_type = sanitize_text_field($_POST['product_type']);

        $product = new WC_Product_Simple();
        $product->set_name($product_title);
        $product->set_regular_price($product_price);
        $product->set_status('publish');

        $product_id = $product->save();

        update_post_meta($product_id, '_custom_product_date', $product_date);
        update_post_meta($product_id, '_custom_product_type', $product_type);

        $attachment_id = media_handle_upload('product_image', $product_id);
        if (is_wp_error($attachment_id)) {
            echo $attachment_id->get_error_message();
        } else {
            update_post_meta($product_id, '_custom_product_image_id', $attachment_id);
        }

        wp_redirect(home_url('/'));
        exit;
    }
}

handle_product_creation();

get_header();
?>

<div class="container">
    <h1>Create Product</h1>
    <form action="" method="post" class="create_product_form" enctype="multipart/form-data">
        <input type="text" name="product_title" placeholder="Название товара" required>
        <input type="text" name="product_price" placeholder="Цена товара" required>
        <input type="file" name="product_image" accept="image/*">
        <input type="date" name="product_date" placeholder="Дата">
        <select name="product_type">
            <option value="">Выберите тип</option>
            <option value="rare">Rare</option>
            <option value="frequent">Frequent</option>
            <option value="unusual">unusual</option>
        </select>
        <button type="button" onclick="clearForm()">Очистить все поля</button>
        <input type="submit" name="submit_product" value="Добавить товар">
    </form>
</div>

<script>
function clearForm() {
    var form = document.querySelector('.create_product_form');
    form.reset();
}
</script>

<?php
get_footer();
?>
