<?php
/**
 * Gift Card product add to cart
 *
 * @author  Yithemes
 * @package YITH WooCommerce Gift Cards
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**  @var WC_Product_Gift_Card $product */
global $product;

do_action( 'yith_gift_cards_template_before_add_to_cart_form' );
do_action( 'woocommerce_before_add_to_cart_form' );
?>
<div class="margin_top_2">
    <?php wc_print_notices(); ?>
</div>
<form class="gift-cards_form cart" method="post" enctype='multipart/form-data'
      data-product_id="<?php echo absint( yit_get_prop($product, 'id' )); ?>">

    <?php do_action( 'yith_gift_cards_template_after_form_opening' ); ?>

    <?php if ( ! $product->is_purchasable() ) : ?>
        <p class="gift-card-not-valid">
            <?php _e( "This product cannot be purchased", 'yith-woocommerce-gift-cards' ); ?>
        </p>
    <?php else : ?>


        <?php do_action( 'yith_gift_cards_template_before_add_to_cart_button' ); ?>

        <div class="ywgc-product-wrap" style="display:none;">
            <?php
            /**
             * yith_gift_cards_template_before_gift_card Hook
             */
            do_action( 'yith_gift_cards_template_before_gift_card' );

            /**
             * yith_gift_cards_template_gift_card hook. Used to output the cart button and placeholder for variation data.
             *
             * @since  2.4.0
             * @hooked yith_gift_cards_template_gift_card - 10 Empty div for variation data.
             * @hooked yith_gift_cards_template_gift_card_add_to_cart_button - 20 Qty and cart button.
             */
            do_action( 'yith_gift_cards_template_gift_card' );

            /**
             * yith_gift_cards_template_after_gift_card Hook
             */
            do_action( 'yith_gift_cards_template_after_gift_card' );
            ?>
        </div>

        <?php do_action( 'yith_gift_cards_template_after_add_to_cart_button' ); ?>

    <?php endif; ?>

    <?php do_action( 'yith_gift_cards_template_after_gift_card_form' ); ?>
</form>

<?php do_action( 'yith_gift_cards_template_after_add_to_cart_form' ); ?>
