<?php
/**
 * Shipping Calculator
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/shipping-calculator.php.
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
 * @version     3.4.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if ('no' === get_option('woocommerce_enable_shipping_calc') || !WC()->cart->needs_shipping()) {
    return;
}

?>

<?php do_action('woocommerce_before_shipping_calculator'); ?>

<form class="woocommerce-shipping-calculator" id="shipping_method_form"
      action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">


    <div class="form_select_wrap">
        <div class="row">
            <div class="col-sm-6">
                <label for="calc_shipping_country">Ship To</label>
            </div>
            <div class="col-sm-6">
                <select name="calc_shipping_country" id="calc_shipping_country" class="country_to_state"
                        rel="calc_shipping_state">
                    <option value=""><?php _e('Select a country&hellip;', 'woocommerce'); ?></option>
                    <?php
                    foreach (WC()->countries->get_shipping_countries() as $key => $value) {
                        echo '<option value="' . esc_attr($key) . '"' . selected(WC()->customer->get_shipping_country(), esc_attr($key), false) . '>' . esc_html($value) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>


    <button id="calc_shipping" type="submit" name="calc_shipping" value="1"
            class="button hidden-xl-down hidden-xl-up"><?php _e('Update totals', 'woocommerce'); ?></button>

    <?php wp_nonce_field('woocommerce-cart'); ?>
</form>

<?php do_action('woocommerce_after_shipping_calculator'); ?>
