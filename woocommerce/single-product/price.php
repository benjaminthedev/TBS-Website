<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
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
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
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


        <div class="our_price">Our Price <?php echo $price_html; ?></div>
        <?php if($rrp) : ?>
        <?php /* <div class="rrp">RRP <?php echo $rrp; ?></div> */ ?>
        <?php else : ?>
        <?php /* <?php echo $rrp_html ? "<div class='rrp'>RRP $rrp_html</div>" : '' ?> */ ?>
        <?php endif; ?>
    </div>

<?php endif; ?>