<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
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
 * @version 3.4.0
 */

if (!defined('ABSPATH')) {
    exit;
}

wc_print_notices();


?>


<?php do_action('woocommerce_before_cart_table'); ?>
<div class="container">
    <div class="row">
        <div class="col-xl-6 left_side_checkout">
            <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <h1>Your bag contents:</h1>
                <table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="product-thumbnail">&nbsp;</th>
                        <th class="product-name"><?php _e('Product', 'woocommerce'); ?></th>
                        <th class="product-quantity"><?php _e('Quantity', 'woocommerce'); ?></th>
                        <th class="product-subtotal"><?php _e('Price', 'woocommerce'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php do_action('woocommerce_before_cart_contents'); ?>

                    <?php
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            ?>
                            <tr class="woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">


                                <td class="product-thumbnail">
                                    <?php
                                    $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                    if (!$product_permalink) {
                                        echo $thumbnail;
                                    } else {
                                        printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
                                    }
                                    ?>
                                </td>

                                <td class="product-name" data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                    <div class="inner">
                                        <?php
                                        if (!$product_permalink) {
                                            echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;';
                                        } else {
                                            echo apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key);
                                        }

                                        // Meta data
                                        echo WC()->cart->get_item_data($cart_item);

                                        // Backorder notification
                                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                            echo '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
                                        }
                                        ?>
                                    </div>
                                </td>


                                <td class="product-quantity"
                                    data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key);
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(array(
                                            'input_name' => "cart[{$cart_item_key}][qty]",
                                            'input_value' => $cart_item['quantity'],
                                            'max_value' => $_product->get_max_purchase_quantity(),
                                            'min_value' => '0',
                                        ), $_product, false);
                                    }

                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                    ?>
                                </td>

                                <td class="product-subtotal" data-title="<?php esc_attr_e('Total', 'woocommerce'); ?>">
                                    <?php
                                        echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
                                    ?>
                                </td>
                            </tr>
                            <tr class="spacer">
                                <td></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                    <?php do_action('woocommerce_cart_contents'); ?>

                    <tr>
                        <td colspan="6" class="actions">

                            <?php do_action('woocommerce_cart_actions'); ?>

                            <?php wp_nonce_field('woocommerce-cart'); ?>
                        </td>
                    </tr>

                    <?php do_action('woocommerce_after_cart_contents'); ?>
                    </tbody>
                </table>
                <?php do_action('woocommerce_after_cart_table'); ?>
            </form>
            <div class="row bellow_form">
                <div class="col-sm-6">
                    <?php echo get_img('smalllogo.jpg', 'Small Logo') ?>
                </div>
                <div class="col-sm-6">
                    <small>Customer Service</small>
                    <?php
                    $telephone_number = get_field('telephone_number', 'options');
                    $telephone_number_attributes = get_field('telephone_number_attributes', 'options');
                    $telephone_number_converted = preg_replace("/[^0-9+]/", "", $telephone_number);
                    echo $telephone_number ? '<a href="tel:' . $telephone_number_converted . '" class="telephone_number" ' . $telephone_number_attributes . '>' . $telephone_number . '</a>' : '';
                    ?>
                </div>
            </div>
            <?php do_action('woocommerce_before_cart'); ?>
            <?php wc_get_template('checkout/form-coupon.php', array('checkout' => WC()->checkout()));; ?>
        </div>
        <div class="col-xl-6 right_side_checkout">
            <div class="right_side_inner">
                <?php
                /**
                 * woocommerce_cart_collaterals hook.
                 *
                 * @hooked woocommerce_cross_sell_display
                 * @hooked woocommerce_cart_totals - 10
                 */
                remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
                do_action('woocommerce_cart_collaterals');
                ?>
            </div>
        </div>
    </div>
</div>
<?php do_action('woocommerce_after_cart'); ?>
