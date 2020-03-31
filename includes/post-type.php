<?php

function product_colour()
{

    $labels = [
        'name' => _x('Colours', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Colour', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Colours', 'text_domain'),
        'all_items' => __('All Colours', 'text_domain'),
        'parent_item' => __('Parent Colour', 'text_domain'),
        'parent_item_colon' => __('Parent Colour:', 'text_domain'),
        'new_item_name' => __('New Colour Name', 'text_domain'),
        'add_new_item' => __('Add New Colour', 'text_domain'),
        'edit_item' => __('Edit Colour', 'text_domain'),
        'update_item' => __('Update Colour', 'text_domain'),
        'view_item' => __('View Colour', 'text_domain'),
        'separate_items_with_commas' => __('Separate Colours with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Colours', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Colours', 'text_domain'),
        'search_items' => __('Search Colours', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Colours', 'text_domain'),
        'items_list' => __('Colours list', 'text_domain'),
        'items_list_navigation' => __('Colours list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_colour', ['product'], $args);

}

add_action('init', 'product_colour', 0);

function product_size()
{

    $labels = [
        'name' => _x('Sizes', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Size', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Sizes', 'text_domain'),
        'all_items' => __('All Sizes', 'text_domain'),
        'parent_item' => __('Parent Size', 'text_domain'),
        'parent_item_colon' => __('Parent Size:', 'text_domain'),
        'new_item_name' => __('New Size Name', 'text_domain'),
        'add_new_item' => __('Add New Size', 'text_domain'),
        'edit_item' => __('Edit Size', 'text_domain'),
        'update_item' => __('Update Size', 'text_domain'),
        'view_item' => __('View Size', 'text_domain'),
        'separate_items_with_commas' => __('Separate Sizes with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Sizes', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Sizes', 'text_domain'),
        'search_items' => __('Search Sizes', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Sizes', 'text_domain'),
        'items_list' => __('Sizes list', 'text_domain'),
        'items_list_navigation' => __('Sizes list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_size', ['product'], $args);

}

add_action('init', 'product_size', 0);

function product_brand()
{

    $labels = [
        'name' => _x('Brands', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Brand', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Brands', 'text_domain'),
        'all_items' => __('All Brands', 'text_domain'),
        'parent_item' => __('Parent Brand', 'text_domain'),
        'parent_item_colon' => __('Parent Brand:', 'text_domain'),
        'new_item_name' => __('New Brand Name', 'text_domain'),
        'add_new_item' => __('Add New Brand', 'text_domain'),
        'edit_item' => __('Edit Brand', 'text_domain'),
        'update_item' => __('Update Brand', 'text_domain'),
        'view_item' => __('View Brand', 'text_domain'),
        'separate_items_with_commas' => __('Separate Brands with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Brands', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Brands', 'text_domain'),
        'search_items' => __('Search Brands', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Brands', 'text_domain'),
        'items_list' => __('Brands list', 'text_domain'),
        'items_list_navigation' => __('Brands list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'rewrite' => [
            'slug' => 'brands',
            'with_front' => false
        ],
    ];
    register_taxonomy('product_brand', ['product'], $args);

}

add_action('init', 'product_brand', 0);

function product_age_group()
{

    $labels = [
        'name' => _x('Age Groups', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Age Group', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Age Groups', 'text_domain'),
        'all_items' => __('All Age Groups', 'text_domain'),
        'parent_item' => __('Parent Age Group', 'text_domain'),
        'parent_item_colon' => __('Parent Age Group:', 'text_domain'),
        'new_item_name' => __('New Age Group Name', 'text_domain'),
        'add_new_item' => __('Add New Age Group', 'text_domain'),
        'edit_item' => __('Edit Age Group', 'text_domain'),
        'update_item' => __('Update Age Group', 'text_domain'),
        'view_item' => __('View Age Group', 'text_domain'),
        'separate_items_with_commas' => __('Separate Age Groups with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Age Groups', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Age Groups', 'text_domain'),
        'search_items' => __('Search Age Groups', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Age Groups', 'text_domain'),
        'items_list' => __('Age Groups list', 'text_domain'),
        'items_list_navigation' => __('Age Groups list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_age_group', ['product'], $args);

}

add_action('init', 'product_age_group', 0);

function product_formulation()
{

    $labels = [
        'name' => _x('Formulations', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Formulation', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Formulations', 'text_domain'),
        'all_items' => __('All Formulations', 'text_domain'),
        'parent_item' => __('Parent Formulation', 'text_domain'),
        'parent_item_colon' => __('Parent Formulation:', 'text_domain'),
        'new_item_name' => __('New Formulation Name', 'text_domain'),
        'add_new_item' => __('Add New Formulation', 'text_domain'),
        'edit_item' => __('Edit Formulation', 'text_domain'),
        'update_item' => __('Update Formulation', 'text_domain'),
        'view_item' => __('View Formulation', 'text_domain'),
        'separate_items_with_commas' => __('Separate Formulations with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Formulations', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Formulations', 'text_domain'),
        'search_items' => __('Search Formulations', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Formulations', 'text_domain'),
        'items_list' => __('Formulations list', 'text_domain'),
        'items_list_navigation' => __('Formulations list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_formulation', ['product'], $args);

}

add_action('init', 'product_formulation', 0);

function product_skin_type()
{

    $labels = [
        'name' => _x('Skin Types', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Skin Type', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Skin Types', 'text_domain'),
        'all_items' => __('All Skin Types', 'text_domain'),
        'parent_item' => __('Parent Skin Type', 'text_domain'),
        'parent_item_colon' => __('Parent Skin Type:', 'text_domain'),
        'new_item_name' => __('New Skin Type Name', 'text_domain'),
        'add_new_item' => __('Add New Skin Type', 'text_domain'),
        'edit_item' => __('Edit Skin Type', 'text_domain'),
        'update_item' => __('Update Skin Type', 'text_domain'),
        'view_item' => __('View Skin Type', 'text_domain'),
        'separate_items_with_commas' => __('Separate Skin Types with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Skin Types', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Skin Types', 'text_domain'),
        'search_items' => __('Search Skin Types', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Skin Types', 'text_domain'),
        'items_list' => __('Skin Types list', 'text_domain'),
        'items_list_navigation' => __('Skin Types list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_skin_type', ['product'], $args);

}

add_action('init', 'product_skin_type', 0);

function product_hazardous_goods()
{

    $labels = [
        'name' => _x('Hazardous Goods', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Hazardous Good', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Hazardous Goods', 'text_domain'),
        'all_items' => __('All Hazardous Goods', 'text_domain'),
        'parent_item' => __('Parent Hazardous Good', 'text_domain'),
        'parent_item_colon' => __('Parent Hazardous Good:', 'text_domain'),
        'new_item_name' => __('New Hazardous Good Name', 'text_domain'),
        'add_new_item' => __('Add New Hazardous Good', 'text_domain'),
        'edit_item' => __('Edit Hazardous Good', 'text_domain'),
        'update_item' => __('Update Hazardous Good', 'text_domain'),
        'view_item' => __('View Hazardous Good', 'text_domain'),
        'separate_items_with_commas' => __('Separate Hazardous Goods with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Hazardous Goods', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Hazardous Goods', 'text_domain'),
        'search_items' => __('Search Hazardous Goods', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Hazardous Goods', 'text_domain'),
        'items_list' => __('Hazardous Goods list', 'text_domain'),
        'items_list_navigation' => __('Hazardous Goods list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_hazardous_goods', ['product'], $args);

}

add_action('init', 'product_hazardous_goods', 0);

function product_gender()
{

    $labels = [
        'name' => _x('Genders', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Gender', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Genders', 'text_domain'),
        'all_items' => __('All Genders', 'text_domain'),
        'parent_item' => __('Parent Gender', 'text_domain'),
        'parent_item_colon' => __('Parent Gender:', 'text_domain'),
        'new_item_name' => __('New Gender Name', 'text_domain'),
        'add_new_item' => __('Add New Gender', 'text_domain'),
        'edit_item' => __('Edit Gender', 'text_domain'),
        'update_item' => __('Update Gender', 'text_domain'),
        'view_item' => __('View Gender', 'text_domain'),
        'separate_items_with_commas' => __('Separate Genders with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Genders', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Genders', 'text_domain'),
        'search_items' => __('Search Genders', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Genders', 'text_domain'),
        'items_list' => __('Genders list', 'text_domain'),
        'items_list_navigation' => __('Genders list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_gender', ['product'], $args);

}

add_action('init', 'product_gender', 0);

function product_SPF()
{

    $labels = [
        'name' => _x('SPFs', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('SPF', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('SPFs', 'text_domain'),
        'all_items' => __('All SPFs', 'text_domain'),
        'parent_item' => __('Parent SPF', 'text_domain'),
        'parent_item_colon' => __('Parent SPF:', 'text_domain'),
        'new_item_name' => __('New SPF Name', 'text_domain'),
        'add_new_item' => __('Add New SPF', 'text_domain'),
        'edit_item' => __('Edit SPF', 'text_domain'),
        'update_item' => __('Update SPF', 'text_domain'),
        'view_item' => __('View SPF', 'text_domain'),
        'separate_items_with_commas' => __('Separate SPFs with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove SPFs', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular SPFs', 'text_domain'),
        'search_items' => __('Search SPFs', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No SPFs', 'text_domain'),
        'items_list' => __('SPFs list', 'text_domain'),
        'items_list_navigation' => __('SPFs list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_spf', array('product'), $args);

}

add_action('init', 'product_SPF', 0);


function product_fragrance_name()
{

    $labels = [
        'name' => _x('Product Ranges', 'Taxonomy General Name', 'text_domain'),
        'singular_name' => _x('Product Range', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name' => __('Product Ranges', 'text_domain'),
        'all_items' => __('All Product Ranges', 'text_domain'),
        'parent_item' => __('Parent Product Range', 'text_domain'),
        'parent_item_colon' => __('Parent Product Range:', 'text_domain'),
        'new_item_name' => __('New Product Range Name', 'text_domain'),
        'add_new_item' => __('Add New Product Range', 'text_domain'),
        'edit_item' => __('Edit Product Range', 'text_domain'),
        'update_item' => __('Update Product Range', 'text_domain'),
        'view_item' => __('View Product Range', 'text_domain'),
        'separate_items_with_commas' => __('Separate Product Ranges with commas', 'text_domain'),
        'add_or_remove_items' => __('Add or remove Product Ranges', 'text_domain'),
        'choose_from_most_used' => __('Choose from the most used', 'text_domain'),
        'popular_items' => __('Popular Product Ranges', 'text_domain'),
        'search_items' => __('Search Product Ranges', 'text_domain'),
        'not_found' => __('Not Found', 'text_domain'),
        'no_terms' => __('No Product Ranges', 'text_domain'),
        'items_list' => __('Product Ranges list', 'text_domain'),
        'items_list_navigation' => __('Product Ranges list navigation', 'text_domain'),
    ];
    $args = [
        'labels' => $labels,
        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
    ];
    register_taxonomy('product_fragrance_name', array('product'), $args);

}

add_action('init', 'product_fragrance_name', 0);
