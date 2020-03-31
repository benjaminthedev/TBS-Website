<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
    exit;
}

if ($related_products) : ?>

    <section class="section_container">

        <h2 class="section_title"><span><?php esc_html_e('Related products', 'woocommerce'); ?></span></h2>

        <div id="product_tabs">


            <div class="owls">

                <ul class="slides clearfix">

                    <li class="tab" id="featured">

                        <div class="owl-carousel product_owl products">

                            <?php foreach ($related_products as $related_product) : ?>

                                <?php
                                $post_object = get_post($related_product->get_id());

                                setup_postdata($GLOBALS['post'] =& $post_object);

                                wc_get_template_part('content', 'product'); ?>

                            <?php endforeach; ?>

                        </div>

                    </li>

                </ul>
            </div>

        </div>

    </section>

<?php endif;

wp_reset_postdata();


$viewed_products = !empty($_COOKIE['woocommerce_recently_viewed']) ? (array)explode('|', $_COOKIE['woocommerce_recently_viewed']) : array();

$viewed_products = array_reverse(array_filter(array_map('absint', $viewed_products)));

if (empty($viewed_products)) {
    return;
}


$query_args = [
    'posts_per_page' => 8,
    'no_found_rows' => 1,
    'post_status' => 'publish',
    'post_type' => 'product',
    'post__in' => $viewed_products,
    'orderby' => 'post__in',
];

if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
    $query_args['tax_query'] = [
        [
            'taxonomy' => 'product_visibility',
            'field' => 'name',
            'terms' => 'outofstock',
            'operator' => 'NOT IN',
        ],
    ];
}

$r = new WP_Query($query_args);


if ($r->have_posts()) : ?>

    <section class="section_container">

        <h2 class="section_title"><span><?php esc_html_e('Recently Viewed', 'woocommerce'); ?></span></h2>

        <div id="product_tabs">


            <div class="owls">

                <ul class="slides clearfix">

                    <li class="tab" id="featured">

                        <div class="owl-carousel product_owl products">

                            <?php while ($r->have_posts()) : $r->the_post(); ?>

                                <?php wc_get_template_part('content', 'product'); ?>

                            <?php endwhile; ?>

                        </div>

                    </li>

                </ul>
            </div>

        </div>

    </section>

<?php endif;