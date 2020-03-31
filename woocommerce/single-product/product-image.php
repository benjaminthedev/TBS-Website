<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;


if ($product->is_type('variable')) :
    $available_variations = $product->get_available_variations();
    $y = 0;
    ?>

    <div class="variation_product_slider">
            <?php $x = 0;
            $large_images = [];
            $small_images = [];
            if (has_post_thumbnail()) {
                $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                $large_images[] = wp_get_attachment_image($post_thumbnail_id, 'full');
                $small_images[] = wp_get_attachment_image($post_thumbnail_id, 'thumbnail');
                $attachment_ids = $product->get_gallery_image_ids();
                if ($attachment_ids)
                    foreach ($attachment_ids as $attachment_id) {
                        $large_images[] = wp_get_attachment_image($attachment_id, 'full');
                        $small_images[] = wp_get_attachment_image($attachment_id, 'thumbnail');
                    }
            } else
                $large_images[] = wc_placeholder_img();
            ?>


                <div class="product_images active" style="display: block">
                    <div class="image_wrapper <?php echo (count($small_images) > 1) ? 'has_gallery' : ''; ?>">
                        <div class="product_image_slider" id="single_image_slider">
                            <ul class="slides clearfix">
                                <?php foreach ($large_images as $large_image) echo "<li>$large_image</li>"; ?>
                            </ul>
                        </div>
                        <?php if (count($small_images) > 1) : ?>
                            <div class="gallery_owl owl-carousel">
                                <?php foreach ($small_images as $small_image) {
                                    echo "<div class='item' data-image='$x' data-slider='single_image_slider'>$small_image</div>";
                                    $x++;
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php foreach ($available_variations as $key=>$available_variation)  :
                $y++;
                $x = 0;
                $large_images = [];
                $small_images = [];
                $variation_names = [];
                print_object($available_variation, true);
                $image_id = $available_variation['image_id'];
                if ($image_id) {
                    $large_images[] = wp_get_attachment_image($image_id, 'full');
                    $small_images[] = wp_get_attachment_image($image_id, 'thumbnail');
                    $variation_names[$key] = $available_variation['attributes']['attribute_pa_colour'];
                    $additional_variation_images = get_post_meta($available_variation['variation_id'], '_wc_additional_variation_images', true);
                    $additional_variation_images = explode(',', $additional_variation_images);
                    $additional_variation_images = array_filter($additional_variation_images);
                    if ($additional_variation_images) {
                        foreach ($additional_variation_images as $attachment_id) {
                            $large_images[] = wp_get_attachment_image($attachment_id, 'full');
                            $small_images[] = wp_get_attachment_image($attachment_id, 'thumbnail');
                        }
                    }
                } else
                    $large_images[] = wc_placeholder_img();
                ?>

                    <div class="product_images" data-variation-name="<?php echo $variation_names[$key]; ?>">
                        <div class="image_wrapper <?php echo (count($small_images) > 1) ? 'has_gallery' : ''; ?>">
                            <div class="product_image_slider" id="<?php echo "image_slider_$y" ?>">
                                <ul class="slides clearfix">
                                    <?php foreach ($large_images as $large_image) echo "<li>$large_image</li>"; ?>
                                </ul>
                            </div>
                            <?php if (count($small_images) > 1) : ?>
                                <div class="gallery_owl owl-carousel">
                                    <?php foreach ($small_images as $small_image) {
                                        echo "<div class='item' data-image='$x' data-slider='image_slider_$y'>$small_image</div>";
                                        $x++;
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

            <?php endforeach; ?>
    </div>


<?php else:


    $x = 0;
    $large_images = [];
    $small_images = [];
    if (has_post_thumbnail()) {
        $post_thumbnail_id = get_post_thumbnail_id($post->ID);
        $large_images[] = wp_get_attachment_image($post_thumbnail_id, 'full');
        $small_images[] = wp_get_attachment_image($post_thumbnail_id, 'thumbnail');
        $attachment_ids = $product->get_gallery_image_ids();
        if ($attachment_ids)
            foreach ($attachment_ids as $attachment_id) {
                $large_images[] = wp_get_attachment_image($attachment_id, 'full');
                $small_images[] = wp_get_attachment_image($attachment_id, 'thumbnail');
            }
    } else
        $large_images[] = wc_placeholder_img();

    ?>

    <div class="product_images">
        <div class="image_wrapper <?php echo (count($small_images) > 1) ? 'has_gallery' : ''; ?>">
            <div class="product_image_slider" id="single_image_slider">
                <ul class="slides clearfix">
                    <?php foreach ($large_images as $large_image) echo "<li>$large_image</li>"; ?>
                </ul>
            </div>
            <?php if (count($small_images) > 1) : ?>
                <div class="gallery_owl owl-carousel">
                    <?php foreach ($small_images as $small_image) {
                        echo "<div class='item' data-image='$x' data-slider='single_image_slider'>$small_image</div>";
                        $x++;
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php


endif;

?>