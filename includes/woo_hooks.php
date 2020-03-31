<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 16/05/2017
 * Time: 10:04
 */

/**
 * Add Custom Meta
 */

/*

function wc_add_custom_to_product()
{
    global $post;
    woocommerce_wp_text_input(array(
        'id' => '_rrp_price',
        'data_type' => 'price',
        'value' => get_post_meta($post->ID, '_rrp_price', true),
        'label' => __('RRP Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')'
    ));
    woocommerce_wp_text_input(array(
        'id' => '_ean',
        'type' => 'text',
        'value' => get_post_meta($post->ID, '_ean', true),
        'label' => __('EAN', 'woocommerce')
    ));
}

function wc_add_custom_to_product_var()
{
    global $post;
    $product = wc_get_product($post->ID);
    if ($product->is_type('variable'))
        woocommerce_wp_text_input(array(
            'id' => '_rrp_price',
            'data_type' => 'text',
            'value' => get_post_meta($post->ID, '_rrp_price', true),
            'label' => __('RRP Price', 'woocommerce') . ' (' . get_woocommerce_currency_symbol() . ')'
        ));
}

function variation_settings_fields($loop, $variation_data, $variation)
{
    woocommerce_wp_text_input(array(
        'id' => '_ean',
        'type' => 'text',
        'value' => get_post_meta($variation->ID, '_ean', true),
        'label' => __('EAN', 'woocommerce')
    ));
}


add_action('woocommerce_product_options_pricing', 'wc_add_custom_to_product');
add_action('woocommerce_product_options_sku', 'wc_add_custom_to_product_var');
add_action('woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3);

function wc_custom_save_product($product_id)
{
    if (isset($_POST['_inline_edit']))
        if (wp_verify_nonce($_POST['_inline_edit'], 'inlineeditnonce'))
            return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (isset($_POST['_ean'])) {
        if (is_numeric($_POST['_ean']))
            update_post_meta($product_id, '_ean', $_POST['_ean']);
    } else delete_post_meta($product_id, '_ean');
    if (isset($_POST['_rrp_price'])) {
        if (is_numeric($_POST['_rrp_price']))
            update_post_meta($product_id, '_rrp_price', $_POST['_rrp_price']);
    } else delete_post_meta($product_id, '_rrp_price');
}

add_action('save_post', 'wc_custom_save_product');
add_action('woocommerce_save_product_variation', 'wc_custom_save_product', 10, 2);
*/
/**
 * Unset Taxonomy Columns In Product List
 * @param $column_headers
 * @return mixed
 */
function wc_unset_taxonomy_columns_in_product_list($column_headers)
{
    unset($column_headers['taxonomy-product_SPF']);
    unset($column_headers['taxonomy-product_fragrance_name']);
    unset($column_headers['taxonomy-product_age_group']);
    unset($column_headers['taxonomy-product_brand']);
    unset($column_headers['taxonomy-product_colour']);
    unset($column_headers['taxonomy-product_formulation']);
    unset($column_headers['taxonomy-product_gender']);
    unset($column_headers['taxonomy-product_hazardous_goods']);
    unset($column_headers['taxonomy-product_skin_type']);
    unset($column_headers['taxonomy-product_size']);
    unset($column_headers['wpseo-focuskw']);
    unset($column_headers['wpseo-metadesc']);
    unset($column_headers['wpseo-score']);
    unset($column_headers['wpseo-score-readability']);
    unset($column_headers['wpseo-title']);
    return $column_headers;
}

add_filter('manage_edit-product_columns', 'wc_unset_taxonomy_columns_in_product_list');


/**
 * Remove Woocomerce CSS
 */

add_filter('woocommerce_enqueue_styles', '__return_empty_array');


/**
 * Remove Woocomerce Actions
 */

// Rating from product loop

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
//remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);


/**
 * @param $text
 * @param $product
 * @return mixed
 */
function add_to_cart_wc_ajax_text_replace($text, $product)
{
    $text = $product->is_purchasable() && $product->is_in_stock() ? '<i class="fa fa-shopping-bag" aria-hidden="true"></i>' . __('Add to cart', 'woocommerce') : '<i class="fa fa-eye" aria-hidden="true"></i>' . __('Read more', 'woocommerce');
    $text = $product->is_type('simple') ? $text : '<i class="fa fa-eye" aria-hidden="true"></i>' . __('See Variations', 'woocommerce');
    return $text;
}

add_filter('woocommerce_product_add_to_cart_text', 'add_to_cart_wc_ajax_text_replace', 10, 2);


function change_breadcrumb_args($args)
{
    $args['delimiter'] = '';
    $args['wrap_before'] = '<div class="container"><nav class="woocommerce-breadcrumb">';
    $args['wrap_after'] = '</nav></div>';
    return $args;
}

add_filter('woocommerce_breadcrumb_defaults', 'change_breadcrumb_args');
