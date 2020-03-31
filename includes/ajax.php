<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 08/03/2017
 * Time: 09:26
 */


function pop_product_load()
{
    $data = $_POST;
    wp_send_json_success($data);

    die();
}

add_action('wp_ajax_nopriv_pop_product_load', 'pop_product_load');
add_action('wp_ajax_pop_product_load', 'pop_product_load');

function normal_product_load()
{

    $data = $_POST['data'];

    if(!isset($data['per_page']))
        $data['per_page'] = 12;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => $data['per_page'],
        'paged' => $data['page'],
        'meta_query' => [
            [
                'key' => '_stock_status',
                'value' => 'outofstock',
                'compare' => 'NOT IN'
            ]
        ]
    );

    $args['tax_query']['relation'] = 'AND';


    $args['tax_query'][] = [
        'relation' => 'AND',
        ['taxonomy' => 'product_visibility', 'field' => 'name', 'terms' => 'exclude-from-catalog', 'operator' => 'NOT IN']
    ];

    $selection = '';

    $url = '?';
    $url .= $data['sort'] !== 'A-Z' ? 'sort=' . $data['sort'] . '&' : '';
    $url .= (int)$data['page'] !== 1 ? 'page=' . $data['page'] . '&' : '';
    $url .= (int)$data['per_page'] !== 12 ? 'per_page=' . $data['per_page'] . '&' : '';


    $cat_return = get_lower_categories(0);
    $exclude = [];
    $tax_of = [];

    $cat_selected = false;


    if ($data['search']) {
        $args['s'] = sanitize_text_field(stripslashes($data['search']));
        $selection .= "<li class='remove clearfix' data-tax='search' >Search: " . sanitize_text_field(stripslashes($data['search'])) . "<i class='fa fa-times'></i></li>";
        $url .= 'search=' . implode(',', $data['search']) . '&';
    }

    if ($data['cat']) {
        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_cat', 'field' => 'id', 'terms' => $data['cat']]
        ];
        $cat_return = "<div class='sidebar_box active' data-tax='product_cat'>";
        $cat_return .= "<header class='header'>Select Department</header>";
        $cat_return .= "<div class='feed' style='display: block'>";
        $cat_return .= " <ul id='product_cat' class='filter'>";
        $cat_return .= get_lower_categories($data['cat'], true);
        $cat_return .= '</ul>';
        $cat_return .= '</div>';
        $cat_return .= '</div>';


        if ((int)$data['cat'] !== (int)$data['cat_default']) {
            $cat = get_term_by('id', $data['cat'], 'product_cat');
            while ($cat->parent !== 0 && $cat->parent !== (int)$data['cat_default']) {
                $cat = get_term_by('id', $cat->parent, 'product_cat');
                $selection .= "<li>$cat->name</li>";
            }
            $cat_child = get_term_by('id', $data['cat'], 'product_cat');
            $selection .= "<li class='remove clearfix' data-tax='cat' data-value='$cat_child->term_id' data-parent='$cat_child->parent'>$cat_child->name <i class='fa fa-times'></i></li>";
        }
        $exclude['product_cat'] = $data['cat'];
        $tax_of[] = $data['cat'];
        $cat_selected = $data['cat'];
        if ($data['cat'] !== $data['cat_default'])
            $url .= 'category=' . $data['cat'] . '&';
    }


    if (count(array_filter($data['age_group']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_age_group', 'field' => 'id', 'terms' => $data['age_group']]
        ];
        $exclude['product_age_group'] = $data['age_group'];
        $tax_of = array_merge($tax_of, $data['age_group']);
        $url .= 'age_group=' . implode(',', $data['age_group']) . '&';

        foreach ($data['age_group'] as $age_group) {
            $tax_grab = get_term_by('id', $age_group, 'product_age_group');
            $selection .= "<li class='remove clearfix' data-tax='product_age_group' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }

    if (count(array_filter($data['brand']))) {
        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_brand', 'field' => 'id', 'terms' => $data['brand']]
        ];
        $exclude['product_brand'] = $data['brand'];
        $tax_of = array_merge($tax_of, $data['brand']);
        $url .= 'brand=' . implode(',', $data['brand']) . '&';
        foreach ($data['brand'] as $brand) {
            $tax_grab = get_term_by('id', $brand, 'product_brand');
            $selection .= "<li class='remove clearfix' data-tax='product_brand' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }

    if (count(array_filter($data['colour']))) {
        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_colour', 'field' => 'id', 'terms' => $data['colour']]
        ];
        $exclude['product_colour'] = $data['colour'];
        $tax_of = array_merge($tax_of, $data['colour']);
        $url .= 'colour=' . $data['colour'];
        foreach ($data['colour'] as $colour) {
            $tax_grab = get_term_by('id', $colour, 'product_colour');
            $selection .= "<li class='remove clearfix' data-tax='product_colour' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if ($data['fragrance_name']) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_fragrance_name', 'field' => 'id', 'terms' => $data['fragrance_name']]
        ];
        $exclude['product_fragrance_name'] = $data['fragrance_name'];
        $tax_of = array_merge($tax_of, $data['fragrance_name']);
        $url .= 'fragrance_name=' . implode(',', $data['fragrance_name']) . '&';
        foreach ($data['fragrance_name'] as $fragrance_name) {
            $tax_grab = get_term_by('id', $fragrance_name, 'product_fragrance_name');
            $selection .= "<li class='remove clearfix' data-tax='product_fragrance_name' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if (count(array_filter($data['formulation']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_formulation', 'field' => 'id', 'terms' => $data['formulation']]
        ];
        $exclude['product_formulation'] = $data['formulation'];
        $tax_of = array_merge($tax_of, $data['formulation']);
        $url .= 'formulation=' . implode(',', $data['formulation']) . '&';
        foreach ($data['formulation'] as $formulation) {
            $tax_grab = get_term_by('id', $formulation, 'product_formulation');
            $selection .= "<li class='remove clearfix' data-tax='product_formulation' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if (count(array_filter($data['gender']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_gender', 'field' => 'id', 'terms' => $data['gender']]
        ];
        $exclude['product_gender'] = $data['gender'];
        $tax_of = array_merge($tax_of, $data['gender']);
        $url .= 'gender=' . implode(',', $data['gender']) . '&';
        foreach ($data['gender'] as $gender) {
            $tax_grab = get_term_by('id', $gender, 'product_gender');
            $selection .= "<li class='remove clearfix' data-tax='product_gender' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if (count(array_filter($data['hazardous_goods']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_hazardous_goods', 'field' => 'id', 'terms' => $data['hazardous_goods']]
        ];
        $exclude['product_hazardous_goods'] = $data['hazardous_goods'];
        $tax_of = array_merge($tax_of, $data['hazardous_goods']);
        $url .= 'hazardous_goods=' . implode(',', $data['hazardous_goods']) . '&';
        foreach ($data['hazardous_goods'] as $hazardous_goods) {
            $tax_grab = get_term_by('id', $hazardous_goods, 'product_hazardous_goods');
            $selection .= "<li class='remove clearfix' data-tax='product_hazardous_goods' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if (count(array_filter($data['size']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_size', 'field' => 'id', 'terms' => $data['size']]
        ];
        $exclude['product_size'] = $data['size'];
        $tax_of = array_merge($tax_of, $data['size']);
        $url .= 'size=' . implode(',', $data['size']) . '&';
        foreach ($data['size'] as $size) {
            $tax_grab = get_term_by('id', $size, 'product_size');
            $selection .= "<li class='remove clearfix' data-tax='product_size' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if (count(array_filter($data['skin_type']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_skin_type', 'field' => 'id', 'terms' => $data['skin_type']]
        ];
        $exclude['product_skin_type'] = $data['skin_type'];
        $tax_of = array_merge($tax_of, $data['skin_type']);
        $url .= 'skin_type=' . implode(',', $data['skin_type']) . '&';
        foreach ($data['skin_type'] as $skin_type) {
            $tax_grab = get_term_by('id', $skin_type, 'product_skin_type');
            $selection .= "<li class='remove clearfix' data-tax='product_skin_type' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }
    if (count(array_filter($data['spf']))) {

        $args['tax_query'][] = [
            'relation' => 'OR',
            ['taxonomy' => 'product_spf', 'field' => 'id', 'terms' => $data['spf']]
        ];
        $exclude['product_spf'] = $data['spf'];
        $tax_of = array_merge($tax_of, $data['spf']);
        $url .= 'spf=' . implode(',', $data['spf']) . '&';
        foreach ($data['spf'] as $spf) {
            $tax_grab = get_term_by('id', $spf, 'product_spf');
            $selection .= "<li class='remove clearfix' data-tax='product_spf' data-value='$tax_grab->term_id'>$tax_grab->name <i class='fa fa-times'></i></li>";
        }
    }


    if ('yes' === get_option('woocommerce_hide_out_of_stock_items')) {
        $query_args['tax_query'][] = [
            'relation' => 'AND',
            [
                'taxonomy' => 'product_visibility',
                'field' => 'name',
                'terms' => 'outofstock',
                'operator' => 'NOT IN',
            ],
        ];
    }


    $side_bar = new get_all_tags(false, $cat_selected, false, true);
    $side_bar_posts = $side_bar->get_post_object($exclude, $data['search']);
    $side_tax_object = $side_bar->get_tax_object(implode(',', $side_bar_posts));
    $side_bar_tags = $side_bar->get_tags($side_tax_object, $cat_selected, $exclude);
    $all_tags = ['product_cat' => 'delete', 'product_age_group' => 'delete', 'product_brand' => 'delete', 'product_colour' => 'delete', 'product_formulation' => 'delete', 'product_fragrance_name' => 'delete', 'product_gender' => 'delete',
        'product_hazardous_goods' => 'delete', 'product_size' => 'delete', 'product_skin_type' => 'delete', 'product_spf' => 'delete'];

    $x = 0;
    foreach ($all_tags as $tag => $value) {
        $x++;

        if (key_exists($tag, $side_bar_tags)) {
            if ($side_bar_tags[$tag] === $tag)
                $all_tags[$tag] = 'keep';
            else {

                $taxonomy = get_taxonomy($tag);
                $content = $x < 2 ? "<div class='sidebar_box active' data-tax='$tag'>" : "<div class='sidebar_box' data-tax='$tag'>";
                $content .= $tag == 'product_cat' ? "<header class='header'>Select Department</header>" : "<header class='header'>$taxonomy->label</header>";
                $content .= $x < 2 ? "<div class='feed' style='display: block'>" : "<div class='feed'>";
                $content .= " <ul id='$tag' class='filter'>";
                foreach ($side_bar_tags[$tag] as $child)
                    $content .= "<li data-value='$child->term_id' data-tax='$tag'>$child->name</li>";
                $content .= '</ul>';
                $content .= '</div>';
                $content .= '</div>';
                $all_tags[$tag] = $content;
            }
        }


    }


    switch ($data['sort']) {
        case 'A-Z':
            $args['order'] = 'ASC';
            $args['orderby'] = 'title';
            break;
        case "Z-A":
            $args['order'] = 'DESC';
            $args['orderby'] = 'title';
            break;
        case "LowHigh":
            $args['order'] = 'ASC';
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
            break;
        case  "High-Low":
            $args['order'] = 'DESC';
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = '_price';
            break;
        case "skuA-Z":
            $args['order'] = 'ASC';
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = '_sku';
            break;
        case  "skuZ-A":
            $args['order'] = 'DESC';
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = '_sku';
            break;
        case  "recent" :
            $args['order'] = 'DESC';
            $args['orderby'] = 'date';
            break;
        case "old":
            $args['order'] = 'ASC';
            $args['orderby'] = 'date';
            break;
        case  "top":
            $args['orderby'] = ['meta_value_num' => 'DESC', 'title' => 'ASC'];
            $args['meta_key'] = 'total_sales';
            break;
    }

    ob_start();

    $query = new WP_Query($args);


    if ($query->have_posts())
        while ($query->have_posts()) : $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
    else
        do_action('woocommerce_no_products_found');


    $content = ob_get_contents();

    ob_clean();
    $url = trim($url, '&');
    $return = array(
        'content' => $content,
        'url' => $url,
        'cat_return' => $cat_return,
        'args' => $query,
        'side_bar' => $all_tags,
        'exclude' => $side_bar_posts,
        'pagination' => the_product_pagination($query),
        'per_page' => per_page($query),
        'selection' => $selection
    );

    wp_send_json_success($return);

    die();
}

add_action('wp_ajax_nopriv_normal_product_load', 'normal_product_load');
add_action('wp_ajax_normal_product_load', 'normal_product_load');


function get_lower_categories($term_id, $ajax = false)
{
    $children = get_current_tax_children($term_id, $ajax);
    $return = '';
    if ($children)
        foreach ($children as $child)
            $return .= (int)$child->term_id === (int)$term_id ?
                "<li data-value='$child->term_id' data-parent='$child->parent' data-tax='product_cat' class='active'>$child->name</li>" :
                "<li data-value='$child->term_id' data-parent='$child->parent' data-tax='product_cat'>$child->name</li>";

    return $return;
}

function array_not_unique($raw_array)
{
    $dupes = array();
    natcasesort($raw_array);
    reset($raw_array);

    $old_key = NULL;
    $old_value = NULL;
    foreach ($raw_array as $key => $value) {
        if ($value === NULL) {
            continue;
        }
        if (strcasecmp($old_value, $value) === 0) {
            $dupes[$old_key] = $old_value;
            $dupes[$key] = $value;
        }
        $old_value = $value;
        $old_key = $key;
    }
    return $dupes;
}


function wish_list_control()
{


    if (is_user_logged_in()) {

        $user_id = get_current_user_id();
        $wishlist = get_field('wishlist', 'user_' . $user_id);

        if ($wishlist)
            $wishlist = explode('|', $wishlist); else $wishlist = [];

        $wishlist_key = array_search($_POST['product'], $wishlist);
        if ($wishlist_key)
            unset($wishlist[$wishlist_key]);
        else
            $wishlist[] = $_POST['product'];

        update_field('wishlist', implode('|', $wishlist), 'user_' . $user_id);

        wp_send_json_success($wishlist);


    } else
        wp_send_json_error(get_permalink(get_option('woocommerce_myaccount_page_id')));
}

add_action('wp_ajax_nopriv_wish_list_control', 'wish_list_control');
add_action('wp_ajax_wish_list_control', 'wish_list_control');


function get_cart_totals()
{
    wp_send_json(WC()->cart->get_cart_subtotal());
}

add_action('wp_ajax_nopriv_get_cart_totals', 'get_cart_totals');
add_action('wp_ajax_get_cart_totals', 'get_cart_totals');


function additional_info()
{
    WC()->session->set('additional_info_session', $_POST['val']);
    wp_send_json($_POST['val'] . ' saved');
}

add_action('wp_ajax_nopriv_additional_info', 'additional_info');
add_action('wp_ajax_additional_info', 'additional_info');