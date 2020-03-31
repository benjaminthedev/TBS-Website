<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
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
 * @version     2.3.6
 */

if (!defined('ABSPATH')) {
    exit;
}
$terms_and_conditions = get_field('terms_and_conditions', 'options');
$security = get_field('security', 'options');
?>

<div class="cart_totals <?php echo (WC()->customer->has_calculated_shipping()) ? 'calculated_shipping' : ''; ?>">

    <?php do_action('woocommerce_before_cart_totals'); ?>

    <h2><?php _e('Checkout options', 'woocommerce'); ?></h2>


    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

        <?php do_action('woocommerce_cart_totals_before_shipping'); ?>

        <?php wc_cart_totals_shipping_html(); ?>

        <?php do_action('woocommerce_cart_totals_after_shipping'); ?>

    <?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>

        <?php woocommerce_shipping_calculator(); ?>

    <?php endif; ?>

    <?php /*
    <p class="form-row terms wc-terms-and-conditions" style="display: none;">
		<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
			<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" id="terms"> <span>I’ve read and accept the <a href="/terms-conditions/" target="_blank" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a></span> <span class="required">*</span>
		</label>
		<input type="hidden" name="terms-field" value="1">
    </p>
    */ ?>

    <div class="wc-proceed-to-checkout clearfix">

        <?php if( $terms_and_conditions || $security ) : ?>
        <div class="terms">
            By clicking the button to proceed to payment, you confirm that you have read, understood and accept our
            <?php if( $terms_and_conditions ) : ?>
            <a href="<?php echo $terms_and_conditions; ?>" target="_blank"><strong>Terms &amp; Conditions</strong></a>
                <?php if( $security ) : echo ' and our'; endif; ?>
            <?php endif; ?>
            <?php if( $security ) : ?>
            <a href="<?php echo $security; ?>" target="_blank"><strong>Security &amp; Privacy</strong></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php do_action('woocommerce_proceed_to_checkout'); ?>
    </div>

    <?php do_action('woocommerce_after_cart_totals'); ?>

</div>
