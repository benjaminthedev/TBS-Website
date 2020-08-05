<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
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
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product;

$price_html = $product->get_price_html();

$rrp = get_post_meta(get_the_ID(), '_msrp_price', true);

if($rrp == "") {
    $rrp = get_post_meta(get_the_ID(), '_rrp_price', true);
}

$rrp = $rrp ? get_price_in_currency($rrp) : false;

$rrp = $rrp ? apply_filters('woocommerce_variable_price_html', wc_price($rrp) . $product->get_price_suffix(), $product) : false;

$rrp_html = $rrp ? apply_filters('woocommerce_get_price_html', $rrp, $product) : false;

$rrp_html = $rrp_html !== $price_html ? $rrp_html : false;

if ($price_html) : ?>

    <div class="price">

        <?php // echo $rrp_html ? "<span class='rrp'>RRP $rrp_html</span>" : '' ?>

        <span class="our_price">Our Price <?php echo $price_html; ?></span>

    </div>

<?php endif; ?>
