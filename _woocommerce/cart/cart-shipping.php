<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
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
 * @version     3.2.0
 */
if (!defined('ABSPATH')) {
    exit;
}

$current_method = false;

?>
<div class="shipping_calculator">

    <?php if (!empty($show_shipping_calculator)) : ?>
        <?php woocommerce_shipping_calculator(); ?>
    <?php endif; ?>

    <?php if (count($available_methods)) : ?>
        <div class="form_select_wrap">
            <div class="row">
                <div class="col-sm-6">
                    <label for="calc_shipping_country">Shipping Method</label>
                </div>
                <div class="col-sm-6">
                    <select name="shipping_method[<?php echo $index; ?>]" data-index="<?php echo $index; ?>"
                            id="shipping_method_<?php echo $index; ?>" class="shipping_method">
                        <option value=""><?php _e("Select Shipping Method", 'woo-shipping-display-mode'); ?></option>
                        <?php foreach ($available_methods as $method) :
                            if ($chosen_method === $method->id)
                                $current_method = $method;

                            ?>
                            <option value="<?php echo esc_attr($method->id); ?>" <?php selected($method->id, $chosen_method); ?>><?php echo $method->label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    <?php else : ?>
        <?php echo apply_filters(is_cart() ? 'woocommerce_cart_no_shipping_available_html' : 'woocommerce_no_shipping_available_html', wpautop(__('There are no shipping methods available. Please double check your address, or contact us if you need any help.', 'woocommerce'))); ?>
    <?php endif; ?>

    <?php $additional_info_session = WC()->session->get('additional_info_session'); ?>

    <?php if (!is_checkout()) : ?>
        <textarea name="additional_info" id="additional_info" class="text-input"
                  placeholder="If you have any specific delivery requirements, please state them here"><?php echo $additional_info_session ? $additional_info_session : ''; ?></textarea>
    <?php endif; ?>

    <div class="row additional_shipping_info">
        <div class="col-xl-6">
            <?php if ($current_method) :
                $method_info = wc_cart_totals_shipping_method_label($current_method);
                echo "<div class='current_method'>$method_info</div>";
            endif; ?>

        </div>
        <div class="col-xl-6">
            <table cellspacing="0" class="shop_table shop_table_responsive">
                <?php

                $discount = WC()->cart->get_cart_discount_total();

                ?>
                <tr class="cart-subtotal">
                    <th><?php _e('Subtotal', 'woocommerce'); ?></th>
                    <td data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>"><?php wc_cart_totals_subtotal_html(); ?></td>
                </tr>

                <?php if ($discount) : ?>
                    <tr class="cart-subtotal">
                        <th><?php _e('Discount', 'woocommerce'); ?></th>
                        <td data-title="<?php esc_attr_e('Discount', 'woocommerce'); ?>"><?php echo $discount; ?></td>
                    </tr>
                <?php endif; ?>


                <tr class="cart-delivery">
                    <th>Delivery</th>
                    <td data-title="delivery"><?php echo WC()->cart->get_cart_shipping_total(); ?></td>
                </tr>

                <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                    <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                        <td data-title="<?php echo esc_attr(wc_cart_totals_coupon_label($coupon, false)); ?>"><?php wc_cart_totals_coupon_html($coupon); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                    <tr class="fee">
                        <th><?php echo esc_html($fee->name); ?></th>
                        <td data-title="<?php echo esc_attr($fee->name); ?>"><?php wc_cart_totals_fee_html($fee); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart) :
                    $taxable_address = WC()->customer->get_taxable_address();
                    $estimated_text = WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()
                        ? sprintf(' <small>' . __('(estimated for %s)', 'woocommerce') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]])
                        : '';

                    if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                        <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                            <tr class="tax-rate tax-rate-<?php echo sanitize_title($code); ?>">
                                <th><?php echo esc_html($tax->label) . $estimated_text; ?></th>
                                <td data-title="<?php echo esc_attr($tax->label); ?>"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr class="tax-total">
                            <th><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?></th>
                            <td data-title="<?php echo esc_attr(WC()->countries->tax_or_vat()); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endif; ?>

                <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

                <tr class="order-total">
                    <th><?php _e('Total', 'woocommerce'); ?></th>
                    <td data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>"><?php wc_cart_totals_order_total_html(); ?></td>
                </tr>

                <?php do_action('woocommerce_cart_totals_after_order_total'); ?>

            </table>

        </div>
    </div>


</div>
