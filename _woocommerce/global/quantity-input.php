<?php
/**
 * Product quantity inputs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/quantity-input.php.
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
 * @version     2.5.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;


if ($max_value && $min_value === $max_value) {
    ?>
    <div class="quantity hidden">
        <input type="hidden" class="qty" name="<?php echo esc_attr($input_name); ?>"
               value="<?php echo esc_attr($min_value); ?>"/>
    </div>
    <?php
} else {
    ?>
    <div class="quantity clearfix">
        <div class="qty float-left">
            <?php echo is_product() ? 'QTY:' : ''; ?>
            <input type="number" step="<?php echo esc_attr($step); ?>" min="<?php echo esc_attr($min_value); ?>"
                   max="<?php echo esc_attr(0 < $max_value ? $max_value : ''); ?>"
                   name="<?php echo esc_attr($input_name); ?>" value="<?php echo esc_attr($input_value); ?>"
                   title="<?php echo esc_attr_x('Qty', 'Product quantity input tooltip', 'woocommerce') ?>"
                   class=" qty_input" size="4" pattern="<?php echo esc_attr($pattern); ?>"
                   inputmode="<?php echo esc_attr($inputmode); ?>"

            />
            <?php echo !is_product() ? ' <input type="submit" class="button" name="update_cart"
                                       value="&#xf079;"/>' : ''; ?>
        </div>
        <?php if (is_product()) : ?>
            <div class="buttons float-left">
                <input class="plus" type="button" value="+">
                <input class="minus" type="button" value="-">
            </div>
        <?php endif; ?>
    </div>
    <?php
}
