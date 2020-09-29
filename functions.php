<?php

$GLOBALS['THEME_ABS_URL'] = __DIR__;
$GLOBALS['CONTENT_ABS_URL'] = $GLOBALS['THEME_ABS_URL'] . "/content";

/* =============== New Widgets Sections! ===============  */

register_sidebar(
    array(
        'name' => 'Shop Filters Sidebar',
        'before_widget' => '<div class="sub clearfix %2$s">',  
        'after_widget' => '</div>',  
        'before_title' => '<header><h4>',  
        'after_title' => '</h4></header>',  
    ));



/* =============== LinnWorks ===============  */
require_once 'includes/linnworks/Factory.php';
require_once 'includes/linnworks/Auth.php';
require_once 'includes/linnworks/Orders.php';
require_once 'includes/linnworks/Stock.php';

/* =============== Theme dependent plugins ===============  */
require_once 'includes/theme_plugins/plugins_init.php';

/* =============== Admin & header cleanup ===============  */
require_once 'includes/helper_fn.php';

/* =============== Composer Autoload ===============  */

require_once 'vendor/autoload.php';

__autoload (__DIR__ . "/includes/classes/global");

/* =============== Custom  post type ===============  */
require_once 'includes/post-type.php';

/* ===============  style and scripts=============== */
require_once 'includes/styles_scripts.php';

/* =============== Ajax ===============  */
require_once 'includes/ajax.php';

/* =============== Default Plugins ===============  */
require_once 'includes/default_plugins.php';

/* =============== Admin & header cleanup ===============  */
require_once 'includes/base.php';

/* =============== Hooks ===============  */
require_once 'includes/hooks.php';

/* =============== Woo Hooks ===============  */
require_once 'includes/woo_hooks.php';

/* =============== duplicate post ===============  */
require_once 'includes/duplicate_post.php';

/* =============== Add Users ===============  */
require_once 'includes/add_users.php';

add_action( 'pre_get_posts', 'custom_pre_get_posts_query' );

function custom_pre_get_posts_query( $q ) {

if ( ! $q->is_main_query() ) return;
if ( ! $q->is_post_type_archive() ) return;
if ( ! is_admin() ) {


$q->set( 'meta_query', array(array(
    'key'       => '_stock_status',
    'value'     => 'outofstock',
    'compare'   => 'NOT IN'
)));

}

remove_action( 'pre_get_posts', 'custom_pre_get_posts_query' );

}

   add_filter('wc_add_to_cart_message', 'handler_function_name', 10, 2);
   function handler_function_name($message, $product_id) {
       return get_the_title( $product_id ) . ' has been added to your bag.';
   }
// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
	 $fields['billing']['billing_email']['placeholder'] = 'Email Address*';
	 $fields['billing']['billing_first_name']['placeholder'] = 'First Name*';
	 $fields['billing']['billing_last_name']['placeholder'] = 'Last Name*';
	 $fields['billing']['billing_phone']['placeholder'] = 'Contact Number*';
	 $fields['billing']['billing_company']['placeholder'] = 'Company Name';
	 $fields['billing']['billing_address_1']['placeholder'] = 'Address Line 1*';
	 $fields['billing']['billing_address_2']['placeholder'] = 'Address Line 2';
	 $fields['billing']['billing_city']['placeholder'] = 'Town/City*';
	 $fields['billing']['billing_postcode']['placeholder'] = 'Postcode*';
	 $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name*';
	 $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name*';
	 $fields['shipping']['shipping_company']['placeholder'] = 'Company Name';
	 $fields['shipping']['shipping_address_1']['placeholder'] = 'Address Line 1*';
	 $fields['shipping']['shipping_address_2']['placeholder'] = 'Address Line 2';
	 $fields['shipping']['shipping_city']['placeholder'] = 'Town/City*';
	 $fields['shipping']['shipping_postcode']['placeholder'] = 'Postcode*';
     return $fields;
}
// WooCommerce Checkout Fields Hook
add_filter('woocommerce_checkout_fields','custom_wc_checkout_fields_no_label');

// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function custom_wc_checkout_fields_no_label($fields) {
    // loop by category
    foreach ($fields as $category => $value) {
        // loop by fields
        foreach ($fields[$category] as $field => $property) {
            // remove label property
            unset($fields[$category][$field]['label']);
        }
    }
     return $fields;
}
// hide coupon field on checkout page
function hide_coupon_field_on_checkout( $enabled ) {
	if ( is_checkout() ) {
		$enabled = false;
	}
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );
add_action( 'wp_footer', 'cart_update_qty_script' );
function cart_update_qty_script() {
    if (is_cart()) :
        ?>
        <script type="text/javascript">
            (function($){
                $(function(){
                    $('div.woocommerce').on( 'change', '.qty', function(){
                        $("[name='update_cart']").trigger('click');
                    });
                });
            })(jQuery);
        </script>
        <?php
    endif;
}
add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );
add_action('woocommerce_order_status_changed', 'send_custom_email_notifications', 10, 4 );
function send_custom_email_notifications( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled' || $new_status == 'failed' ){
        $wc_emails = WC()->mailer()->get_emails(); // Get all WC_emails objects instances
        $customer_email = $order->get_billing_email(); // The customer email
    }

    if ( $new_status == 'cancelled' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
    } 
    elseif ( $new_status == 'failed' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Failed_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Failed_Order']->trigger( $order_id );
    } 
}

/**
 * Add Additional tabs to WooCommerce products
 *
 */
 
	add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );
	
	function woo_new_product_tab( $tabs ) {
	
		global $post;
		$product_how_to_use = get_post_meta( $post->ID, 'how_to_use', true );
		$product_ingredients    = get_post_meta( $post->ID, 'ingredients', true );
		$product_about_the_brand    = get_post_meta( $post->ID, 'about_the_brand', true );
	 
		if ( ! empty( $product_how_to_use ) ) {
		
			$tabs['how_to_use_tab'] = array(
				'title'    => __( 'How to Use', 'woocommerce' ),
				'priority' => 15,
				'callback' => 'woo_how_to_use_tab_content'
			);
			
		}
		
		if ( ! empty( $product_ingredients ) ) {
		
			$tabs['ingredients_tab'] = array(
				'title'    => __( 'Ingredients', 'woocommerce' ),
				'priority' => 16,
				'callback' => 'woo_ingredients_tab_content'
			);
			
			}
		
		if ( ! empty( $product_about_the_brand ) ) {
		
			$tabs['about_the_brand_tab'] = array(
				'title'    => __( 'About the Brand', 'woocommerce' ),
				'priority' => 16,
				'callback' => 'woo_about_the_brand_tab_content'
			);
			
		}
		
		return $tabs;
	 
	}
	
	function woo_how_to_use_tab_content() {
	
		echo get_field('how_to_use', $post_id);
			
		}
	 
	
	function woo_ingredients_tab_content() {
	
		echo get_field('ingredients', $post_id);
			
		}
	 

     function woo_about_the_brand_tab_content() {
	
		echo get_field('about_the_brand', $post_id);
			
	 }

// Switch off Refund and Restock by Default

add_action('admin_footer', 'uncheck_restock_refund_checkbox');
function uncheck_restock_refund_checkbox() {
	echo '<script>jQuery("#restock_refunded_items").prop("checked", false);</script>';
}

//assign user in guest order
add_action( 'woocommerce_new_order', 'action_woocommerce_new_order', 10, 1 );
function action_woocommerce_new_order( $order_id ) {
	$order = new WC_Order($order_id);
	$user = $order->get_user();
	
	if( !$user ){
		//guest order
		$userdata = get_user_by( 'email', $order->get_billing_email() );
		if(isset( $userdata->ID )){
			//registered
			update_post_meta($order_id, '_customer_user', $userdata->ID );
		}else{
			//Guest
		}
	}
}

//add brand to product page
add_action( 'woocommerce_single_product_summary', 'brand_product_page', 5 );
  
function brand_product_page() {
   global $product;

    $term_ids = wp_get_post_terms( $product->get_id(), 'product_brand', array('fields' => 'ids') );

    echo get_the_term_list( $product->get_id(), 'product_brand', '<span class="brand_product_page">' . _n( 'by', 'product_brand:', count( $term_ids ), 'woocommerce' ) . ' ', ', ', '</span>' );
}

//hide the Payment Request button on the single Product page

add_filter( 'wc_stripe_hide_payment_request_on_product_page', '__return_true' );

// show the Payment Request button on the Checkout page

add_filter( 'wc_stripe_show_payment_request_on_checkout', '__return_true' );

//remove cart notices from checkout page

function sv_remove_cart_notice_on_checkout() {
	if ( function_exists( 'wc_cart_notices' ) ) {
		remove_action( 'woocommerce_before_checkout_form', array( wc_cart_notices(), 'add_cart_notice' ) );
	}
}
add_action( 'init', 'sv_remove_cart_notice_on_checkout' );

// Get wishlist for current user.
$wl = tinv_wishlist_get();

// Get share key for wishlist if exists.
$share_key = ( $wl && isset( $wl['share_key'] ) ) ? $wl['share_key'] : '';

// Get wishlist url with share key if exists.
$url = tinv_url_wishlist_by_key( $share_key );

// Strikethrough Price - Simple Product

add_filter( 'woocommerce_get_price_html', 'strike_though_simple_woocommerce_get_price_html', 9999, 2 );function strike_though_simple_woocommerce_get_price_html( $price_html, $product ) {   if ( $product->get_type() == 'variable' ) {      $prices = $product->get_variation_prices();      $regular_lowest_sale_price         = current( $prices['sale_price'] );      $regular_lowest_variation_price    = current( $prices['regular_price'] );      $discounted_lowest_variation_price = current( $prices['price'] );      $abs_lowest = min( $regular_lowest_sale_price, $regular_lowest_variation_price, $discounted_lowest_variation_price );      if ( $regular_lowest_variation_price != $abs_lowest ) {         $price_html = 'From ' . wc_format_sale_price( $regular_lowest_variation_price, $discounted_lowest_variation_price );      }   }   return $price_html;}

// Strikethrough Price - Variation Product

add_filter( 'woocommerce_get_price_html', 'strike_though_woocommerce_get_price_html', 10, 2 );

function strike_though_woocommerce_get_price_html( $price_html, $product ) {

   if ( $product->get_type() == 'variable' ) {
      $prices = $product->get_variation_prices();

      $regular_lowest_variation_price  = current( $prices['regular_price'] );
      $regular_highest_variation_price = end( $prices['regular_price'] );

      $discounted_lowest_variation_price  = current( $prices['price'] );
      $discounted_highest_variation_price = end( $prices['price'] );


      if ( $regular_lowest_variation_price != $discounted_lowest_variation_price ) {
         $price_html = '<del>' . wc_format_price_range( $regular_lowest_variation_price, $regular_highest_variation_price ) . '</del>';
         $price_html .= ' ' . wc_format_price_range( $discounted_lowest_variation_price, $discounted_highest_variation_price );
      }

   }


   return $price_html;

}

// Remove Contact Form 7 Scripts

function remove_contactform_css_js() {
    global $post;
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'contact-form-7') ) {
        wp_enqueue_script('contact-form-7');
         wp_enqueue_style('contact-form-7');

    }else{
        wp_dequeue_script( 'contact-form-7' );
        wp_dequeue_style( 'contact-form-7' );
    }
}
add_action( 'wp_enqueue_scripts', 'remove_contactform_css_js');

// Dequeue Styles
function dequeue_unnecessary_styles() {
    wp_dequeue_style( 'videoJS' );
        wp_deregister_style( 'videoJS' );
}
add_action( 'wp_print_styles', 'dequeue_unnecessary_styles' );

// Dequeue JavaScripts
function dequeue_unnecessary_scripts() {
    wp_dequeue_script( 'videoJS' );
        wp_deregister_script( 'videoJS' );
	 wp_dequeue_script( 'Youtube' );
        wp_deregister_script( 'Youtube' );
}
add_action( 'wp_print_scripts', 'dequeue_unnecessary_scripts' );

/**
 * Proper way to enqueue scripts and styles.
 */
function get_scripts() {
     wp_enqueue_style( 'customStyle', get_stylesheet_directory_uri() . '/assets/custom-css.css', array() );
    
    //Use as template if needed.
	//wp_enqueue_script( 'customJSProductPage', get_stylesheet_directory_uri() . '/js/single-product.js', array(), '1.0.0', true );
	
	//Conditionally Loading The JS for single product
	// if (is_product()) {
	// 	wp_enqueue_script( 'customJSProductPage', get_stylesheet_directory_uri() . '/js/single-product.js', array(), '1.0.0', true );
	// }

	// if (is_page(262)) {
	// 	wp_enqueue_script( 'homeJs', get_stylesheet_directory_uri() . '/js/home-page.js', array(), '1.0.0', true );
	// }
  
	
}
add_action( 'wp_enqueue_scripts', 'get_scripts' );
