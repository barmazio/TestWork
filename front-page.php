<?php
get_header(); ?>

<div class="container">
    <h1>Catalog</h1>
    <div class="products-row">
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1
        );
        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post();
            global $product;
            $image_id = get_post_meta(get_the_ID(), '_custom_product_image_id', true);
            $image_url = wp_get_attachment_url($image_id);
            $product_date = get_post_meta(get_the_ID(), '_custom_product_date', true);
            $product_type = get_post_meta(get_the_ID(), '_custom_product_type', true);
        ?>
            <div class="product">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>">
                <?php endif; ?>
                <h2><?php the_title(); ?></h2>
                <p class="price"><?php echo $product->get_price_html(); ?></p>
                <p class="date">Дата публикации: <?php echo esc_html($product_date); ?></p>
                <p class="type">Тип товара: <?php echo esc_html($product_type); ?></p>
            </div>
        <?php endwhile; wp_reset_query(); ?>
    </div>
</div>

<?php get_footer(); ?>
