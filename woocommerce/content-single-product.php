<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<?php
/**
 * woocommerce_before_single_product hook.
 *
 * @hooked wc_print_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php post_class('product_single_wrapper'); ?>>


    <div class="row product_row_wrapper">


        <?php
        /**
         * woocommerce_before_single_product_summary hook.
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action('woocommerce_before_single_product_summary');
        ?>

        <div class="product_summary ">

       <div class="summary_wrapper">
            <h1><?php the_title(); ?></h1>
            <?php 
            $subtitle = get_field('product_subtitle');

            if( $subtitle ) : echo '<p>' . $subtitle . '</p>'; endif; 
            
           /**
            * woocommerce_single_product_summary hook.
            *
            * @hooked woocommerce_template_single_title - 5
            * @hooked woocommerce_template_single_rating - 10
            * @hooked woocommerce_template_single_price - 10
            * @hooked woocommerce_template_single_excerpt - 20
            * @hooked woocommerce_template_single_add_to_cart - 30
            * @hooked woocommerce_template_single_meta - 40
            * @hooked woocommerce_template_single_sharing - 50
            * @hooked WC_Structured_Data::generate_product_data() - 60
            */

           remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 20);
           remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
           remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
           add_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 6);
           do_action('woocommerce_single_product_summary');
           ?>
       </div>

        </div><!-- .summary -->

    </div>
    <?php
    $product = new WC_Product(get_the_ID());
    /**
     * woocommerce_after_single_product_summary hook.
     *
     * @hooked woocommerce_output_product_data_tabs - 10
     * @hooked woocommerce_upsell_display - 15
     * @hooked woocommerce_output_related_products - 20
     */

    if( count($product->get_upsells()) ) {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
    } else {
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    }


    do_action('woocommerce_after_single_product_summary');
    ?>

     <span class="clerk" data-template="@product-page-alternatives" data-products="[<?php echo $product->get_id(); ?>]"></span> 

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action('woocommerce_after_single_product'); ?>
