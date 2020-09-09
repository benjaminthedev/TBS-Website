<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Wf_Estimated_Delivery {
	
	/**
	 * Hold the Stock status on which adjustment need to be done.
	 */
	private static $allowed_adjujustment_stock_status_arr = array( 'onbackorder' );
	
	public function __construct() {
		$shipping_class_dates		   = get_option('wf_estimated_delivery_shipping_class');
		$this->shipping_class_dates	 = !empty( $shipping_class_dates ) ? $shipping_class_dates : array();
		
		$shipping_zone				  = get_option('wf_estimated_delivery_shipping_zone');
		$this->shipping_zone			= !empty( $shipping_zone ) ? $shipping_zone : array();

		$estimated_delivery			 = get_option('wf_estimated_delivery_min_delivery_days');
		$this->estimated_delivery	   = !empty( $estimated_delivery ) ? $estimated_delivery : 0;
		
		$page_text_format			   = get_option('wf_estimated_delivery_page_text_format');
		$this->page_text_format		 = !empty( $page_text_format ) ? $page_text_format : '';
		
		$cart_page_text				 = get_option('wf_estimated_delivery_cart_page_text');
		$this->cart_page_text		   = !empty( $cart_page_text ) ? __($cart_page_text,'wf_estimated_delivery') : __('Estimated Delivery', 'wf_estimated_delivery' );
		
		$page_text_format_range		 = get_option('wf_estimated_delivery_page_text_format_range');
		$this->page_text_format_range   = !empty( $page_text_format_range ) ? $page_text_format_range : 0;
		
		$lower_range					= get_option('wf_estimated_delivery_lower_range');
		$this->lower_range			  = !empty( $lower_range ) ? $lower_range : 0;
		
		$higher_range				   = get_option('wf_estimated_delivery_higher_range');
		$this->higher_range			 = !empty( $higher_range ) ? $higher_range : 0;

		$show_on_items				   = get_option('wf_estimated_delivery_show_on_items');
		$this->show_on_items			 = !empty( $show_on_items ) && $show_on_items === 'yes' ? true : false;

		$show_on_shop					= get_option('wf_estimated_delivery_show_on_shop');
		$this->show_on_shop 			= ( $show_on_shop == 'yes' ) ? true : false;

		$show_on_product_page 			= get_option('wf_estimated_delivery_show_on_product_page');
		$this->show_on_product_page 	= ( $show_on_product_page == 'yes' || empty($show_on_product_page) ) ? true : false;	// Empty check for previous version compatibility can be removed after some time introduced in 1.5.15

		$plain_text_mode 				= get_option('ph_estimated_delivery_plain_text_mode');
		$this->plain_text_mode 			= ( isset($plain_text_mode) && !empty($plain_text_mode) && $plain_text_mode == 'yes' ) ? true : false;

		$accept_html 					= get_option('wf_estimated_delivery_accept_html');
		$this->accept_html 				= ( isset($accept_html) && !empty($accept_html) && $accept_html == 'yes' ) ? true : false;

		$show_on_all_product 			= get_option('wf_estimated_delivery_show_on_all_product_page');
		$this->show_on_all_product 		= ( $show_on_all_product == 'yes' ) ? true : false;

		$calculation_mode		   = get_option( 'wf_estimated_delivery_calculation_mode' );
		$this->calculation_mode	 = !empty( $calculation_mode  ) ? $calculation_mode : '';
		$this->adjustment_on_backordered_product = get_option('wf_estimated_delivery_additional_days_for_backorder_products');
		$this->per_package_est_delivery		= get_option( 'wf_estimated_delivery_per_package_est_delivery', 'no' );
		$this->shipping_methods_adjustments = get_option( 'wf_estimated_delivery_shipping_methods', array() );

		if ( ! class_exists( 'XA_Calc_Est_Strategy' ) )
			include_once 'abstract-class-calc-est-stratergy.php';

		$this->delivery_date_calculator_obj =  XA_Calc_Est_Strategy::get_calculation_mode( $this->calculation_mode );

		add_action( 'wp_ajax_wf_estimated_delivery',array($this ,'wf_estimated_delivery_checkout_page'));

		add_action( 'woocommerce_admin_order_data_after_order_details', array($this,'wf_estimated_delivery_admin_order_meta') );
		if( ! is_admin() && $this->show_on_product_page ) {
			add_filter( 'woocommerce_get_availability', array($this, 'wf_estimated_delivery_product_page'), 45, 2 );	// Third party stock management plugin used to modify the information, so give the priority more than default case
		}
		add_action('ph_display_estimated_delivery_date_in_any_custom_place_in_product_page',array($this,'ph_estimated_delivery_get_est_delivery_date_based_on_product')); // To print estimted delivery in anywhere in the product page
		// Estimated Delivery on Cart and Checkout Page depending On Position
		$this->position_on_cart_checkout = get_option( 'wf_estimated_delivery_position_on_cart_and_checkout', 'before_shipping' );
		if( $this->per_package_est_delivery != 'yes' ) {
			if( $this->position_on_cart_checkout == 'before_shipping' ) {
				add_action( 'woocommerce_cart_totals_before_shipping', array($this, 'wf_estimated_delivery_cart_page'));
				add_action( 'woocommerce_review_order_before_shipping', array($this,'wf_estimated_delivery_checkout_page'), 10, 0 );
			}
			else {
				add_action( 'woocommerce_cart_totals_after_shipping', array($this, 'wf_estimated_delivery_cart_page'));
				add_action( 'woocommerce_review_order_after_shipping', array($this,'wf_estimated_delivery_checkout_page'), 10, 0 );
			}
			add_filter( 'woocommerce_get_order_item_totals', array($this, 'wf_estimated_delivery_thankyou_page'),10,2);
			add_action( 'woocommerce_checkout_update_order_meta' , array($this, 'wf_add_est_delivery_to_order_meta' ) , 2 );
		}
		else{
			// Package Based Estimated Delivery
			$this->per_package_est_delivery_text = get_option( 'wf_estimated_delivery_per_package_est_delivery_text', '<br><small>'.__( 'Est. Delivery', 'wf_estimated_delivery').': [EST_DEL_DATE]</small>' );
			add_filter( 'woocommerce_package_rates', array( $this, 'add_est_delivery_to_shipping_rates' ), 10, 2 );
			add_filter( 'woocommerce_cart_shipping_method_full_label', array($this, 'ph_add_delivery_time_shipping_method'), 10, 2 );
			add_filter( 'woocommerce_get_order_item_totals', array($this, 'ph_add_est_delivery_on_thankyou_page_from_rates'), 10, 2 );
		}
		
		// To generate estimated delivery manually on order page
		if( is_admin() ) {
			add_action( 'woocommerce_after_register_post_type', array( $this, 'ph_generate_est_delivery_manually') );
			add_action( 'woocommerce_update_order', array( $this, 'ph_update_estimated_delivery_on_order_page_manually') );
		}

		// To display estimated delivery on shop page, on suggested item in product page, on search page, show on shop page settings must be enabled to make it work.
		if( $this->show_on_shop ) {
			add_action( 'woocommerce_after_shop_loop_item', array( $this, 'xa_estimated_delivery_on_every_product' ) );
		}

		if( ! is_admin() && $this->show_on_items){

			//For disply Est-delivery with each cart item
			add_filter('woocommerce_cart_item_name', array($this, 'xa_display_est_delivery_cart_item'),10,2 );

			// Add Cart Item Delivery Date to Order Meta
			add_action('woocommerce_checkout_create_order_line_item', array($this, 'ph_add_cart_item_est_delivery_to_order_meta'),10, 4 );

			//For disply Est-delivery with each item in Thankyou page
			add_action('woocommerce_order_item_meta_end', array($this, 'xa_print_est_delivery_cart_item'),10,3 );
		}
	}

	/**
	 * For manual estimated delivery generation on order page.
	 */
	public function ph_generate_est_delivery_manually(){
		if( ! empty($_GET['calculate_est_delivery']) && ! empty($_GET['post']) ) {
			$order_id = $_GET['post'];
			$this->wf_add_est_delivery_to_order_meta( $order_id );
			wp_redirect(  admin_url( '/post.php?post='.$_GET['post'].'&action=edit') );
		}
	}

	/**
	 * Attach the Estimated Delivery to the Woocommerce Shipping Method.
	 * @param array $shipping_rates Shipping Rates
	 * @param array $package Cart Package
	 * @return array
	 */
	public function add_est_delivery_to_shipping_rates( $shipping_rates, $package ) {

		$est_date_adjustment = (int) $this->estimated_delivery;
		// Add the adjustment based on Product in package add the backorder adjustment also if applicable
		$est_date_adjustment += $this->get_adjustment_based_on_package_content( $package['contents'] );
		// Adjustment based on zone
		$zone = WC_Shipping_Zones::get_zone_matching_package($package);
		$est_date_adjustment += $this->get_adjustment_based_on_shipping_zone($zone);
		// Adjustment based on Shipping Method
		foreach( $shipping_rates as &$shipping_rate ) {
			$shipping_method = $shipping_rate->get_id();
			$shipping_method_adjustment = $this->get_adjustment_based_on_shipping_method($shipping_method);
			$estimated_delivery_date = $this->get_estimated_delivery( $est_date_adjustment + $shipping_method_adjustment ,$shipping_method );
			$shipping_rate->add_meta_data( 'ph_est_delivery_date', $estimated_delivery_date );
		}
		return $shipping_rates;
	}

	/**
	 * Get the adjustment based on shipping zone.
	 * @param object WC_Shipping_Zone Woocommerce Shipping Zone
	 * @return int Number of days adjustment.
	 */
	public function get_adjustment_based_on_shipping_zone( $zone ) {
		$adjustment = 0;
		if(is_array($this->shipping_zone) && !empty($this->shipping_zone)) {
			foreach ( $this->shipping_zone as $id => $value ) {
				if($value['id'] == $zone->get_zone_name())	{
					$adjustment = (int) $value['min_date'];
				}
			}
		}
		return $adjustment;
	}

	/**
	 * Get Adjustment based on Package Contents (Products).
	 * @param array $package_contents
	 * @return int
	 */
	public function get_adjustment_based_on_package_content( $package_contents = array() ) {

		$add_adjustment_of_out_stock_or_backordered_product = false;
		foreach ($package_contents as $cart_item) {
			// Check for the adjustment based on Shipping Class
			$shipping_class = $cart_item['data']->get_shipping_class();

			$any_shipping_class = false;

			if(is_array($this->shipping_class_dates) && !empty($this->shipping_class_dates)){
				foreach ( $this->shipping_class_dates as $id => $value ) {
					if( is_array($value['id']) && in_array('ph_any_shipping_class',$value['id']) )
					{
						$any_shipping_class = true;
					}
				}
			}

			$product_shipping_class_min_date = 0;

			if( ( ! empty($shipping_class) || $any_shipping_class ) && ! empty($this->shipping_class_dates) ) {
				foreach( $this->shipping_class_dates as $value ) {
					if( ! empty($value['min_date']) && ! empty( $value['id']) && ( in_array( $shipping_class, $value['id']) || in_array('ph_any_shipping_class',$value['id']) ) ) {
						$product_shipping_class_min_date += !empty( $value['min_date'] ) ? $value['min_date'] : 0;
					}
				}
			}

			$est[] = $product_shipping_class_min_date;

			// Check whether adjustment required for Backorder Product or not
			// Removed the second condition for Backorder Addon - && ! empty($this->adjustment_on_backordered_product)
			if( ! $add_adjustment_of_out_stock_or_backordered_product ) {
				$cart_item_stock_status = $cart_item['data']->get_stock_status();
				$cart_item_manage_stock = $cart_item['data']->get_manage_stock();
				$cart_item_product_quantity = $cart_item['quantity'];
				$cart_item_product_backorder_status 	= $cart_item['data']->is_on_backorder($cart_item_product_quantity);

				if( ( in_array( $cart_item_stock_status, self::$allowed_adjujustment_stock_status_arr ) && !$cart_item_manage_stock ) || ( $cart_item_product_backorder_status && $cart_item_manage_stock ) ) {
					$add_adjustment_of_out_stock_or_backordered_product = true;
				}
			}
		}

		$adjustment = empty($est) ? null : max($est) ;			// Get the maximum number of adjustment from all the shipping class
		// Add the adjustment based on Backorder product
		if( $add_adjustment_of_out_stock_or_backordered_product ) {
			// $adjustment += (int) $this->adjustment_on_backordered_product;
			$adjustment +=apply_filters('ph_add_extra_days_for_backordered_products_package', (int) $this->adjustment_on_backordered_product, $package_contents);
		}
		return $adjustment;
	}

	/**
	 * Get Adjustment based on Shipping Method. If same shipping method has been configured multiple times then select first.
	 * @param string $shipping_method Shipping method Id. eg. - flat_rate:3
	 * @return int
	 */
	public function get_adjustment_based_on_shipping_method( $shipping_method = '' ){
		$adjustment = 0;
		
		if( ! empty($this->shipping_methods_adjustments) && ! empty($shipping_method) ) {
			foreach( $this->shipping_methods_adjustments as $shipping_method_adjustment_details ) {
				$shipping_methods_arr = array_map( 'trim', explode( ',', $shipping_method_adjustment_details['methods_names'] ) );
				if( in_array( $shipping_method, $shipping_methods_arr ) || in_array( '*', $shipping_methods_arr ) ){
					$adjustment = (int) $shipping_method_adjustment_details['min_date'];
					break;
				}
			}
		}
		return $adjustment;
	}

	/**
	 * Get Estimated Delivery.
	 * @param $adjustment No. of days to adjust.
	 * @return string
	 */
	public function get_estimated_delivery( $adjustment = 0,$shipping_method='' ){

		if($this->page_text_format === 'simple'){
			$cart_page_date = $this->xa_date_format($this->delivery_date_calculator_obj->wf_get_delivery_date($adjustment,'','',$shipping_method) );
		}elseif($this->page_text_format === 'simple_range'){
			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $adjustment - $this->lower_range ,'','',$shipping_method);
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $adjustment + $this->higher_range ,'','',$shipping_method);
			if( $start_date_object->format('d M Y') == $end_date_object->format('d M Y') && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
			}
			$current_time = current_time('Y m d H:i:s');
			$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
			$today_wp_date_seconds = $today_wp_date->format('U');
			$date_diff_between_current_and_start 	=  ceil( ($start_date_object->format('U') - $today_wp_date_seconds ) / 86400 );
			$date_diff_between_current_and_end 		=  ceil( ($end_date_object->format('U') - $today_wp_date_seconds ) /86400 );

			$cart_page_date = ( $date_diff_between_current_and_start < 0  ? 0 : $date_diff_between_current_and_start ) .' - '.( $date_diff_between_current_and_end ).' ' .__( 'days','wf_estimated_delivery' );
		}else{
			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $adjustment - $this->lower_range ,'','',$shipping_method);
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $adjustment + $this->higher_range ,'','',$shipping_method);

			$start_date = $this->xa_date_format($start_date_object);	// Formatted Start date
			$end_date 	= $this->xa_date_format($end_date_object);		// Formatted end date

			// If start Date and End Date are equal and lower range and heigher range not equal to zero.
			if( $start_date == $end_date && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$new_end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
				$end_date = $this->xa_date_format($new_end_date_object);
			}
			$cart_page_date = $start_date  .' - '. $end_date;
		}
		return $cart_page_date;
	}

	/**
	 * Add the Estimated Delivery to the Shipping Method label to display.
	 * @param string $label Shipping Label to display
	 * @param object $method WC_Shipping_Rate object.
	 * @return string
	 */
	public function ph_add_delivery_time_shipping_method( $label, $method ){
		$method_meta_data_arr = $method->get_meta_data();
		if( isset($method_meta_data_arr['ph_est_delivery_date']) && strpos( $label, $method_meta_data_arr['ph_est_delivery_date'] ) === false ) {
			$label .= str_replace( '[EST_DEL_DATE]', $method_meta_data_arr['ph_est_delivery_date'], $this->per_package_est_delivery_text );
		}
		return $label;
	}

	/**
	 * Add Estimated Delivery to Thank You Page.
	 * @param array $array Fields to Show.
	 * @param object $order WC_Order
	 * @return array
	 */
	public function ph_add_est_delivery_on_thankyou_page_from_rates( $array, $order ) {
		$shipping_methods = $order->get_shipping_methods();
		$est_delivery_date = '';
		foreach( $shipping_methods as $shipping_method ) {
			$package_est_delivery = $shipping_method->get_meta('ph_est_delivery_date');
			if( ! empty($package_est_delivery) )
				$est_delivery_date .= $package_est_delivery.' <small>'.__( 'via', 'wf_estimated_delivery').' '.$shipping_method->get_name().'<br></small>';
		}
		if( ! empty($est_delivery_date) ) {
			$array['ph_est_delivery'] = array(
				'label'		=>	esc_attr( $this->cart_page_text ),
				'value'		=>	$est_delivery_date
			);
		}
		return $array;
	}


	/**
	* Show Estimated Delivery on Shop Page for every Product .
	*/
	public function xa_estimated_delivery_on_every_product(){
		global $product;
		if( $product instanceof WC_Product ) {
			$delivery_text = $this->xa_get_estimated_delivery_of_item( $product );
			echo apply_filters( 'xa_estimated_delivery_shop_page',$delivery_text);

			if( empty($delivery_text) ) {
				Estimated_Delivery_Log::log_update( "Estimated deivery not found. Product Id - ".$product->get_id(), 'No estimated delivery' );
			}
		}
		else {
			Estimated_Delivery_Log::log_update( "Global Variable Product is not an object of WC_Product.", 'WC_Product Object' );
		}
	}

	public function xa_print_est_delivery_cart_item($item_id, $product,$order=''){
		
		if (empty($product)) {
			return;
		}
		
		$var_id 		= $product->get_variation_id();
		$product_id 	= !empty($var_id) ? $var_id : $product->get_product_id();

		$product_meta 	= $product->get_meta('ph_item_est_delivery');

		if( !empty($product_meta) && is_array($product_meta) && isset($product_meta[0]) && !empty($product_meta[0]) ) {

			$delivery_text 	= $product_meta[0];
		}else{

			$delivery_text 	= $this->xa_get_estimated_delivery_of_item( $product_id,$order );
		}

		$delivery_text = apply_filters('ph_adjust_estimated_delivery_based_on_each_item_on_thankyou_page',$delivery_text,$product);

		if( !empty($delivery_text) ){
			echo'<br/><small>'.$delivery_text.'</small>';
		}
	}

	public function ph_add_cart_item_est_delivery_to_order_meta( $item, $cart_item_key, $values, $order ) {
		
		$item_id 		= $item->get_product_id();
		$variation_id 	= $item->get_variation_id();

		$product_id 	= !empty($variation_id) ? $variation_id : $item_id;

		$delivery_text 	= $this->xa_get_estimated_delivery_of_item($product_id);

		$item->add_meta_data( 'ph_item_est_delivery', array($delivery_text) );

	}

	public function xa_display_est_delivery_cart_item( $product_name, $cart_item ){
		$product_id = !empty($cart_item['variation_id']) ? $cart_item['variation_id'] : $cart_item['product_id'];

		$delivery_text = $this->xa_get_estimated_delivery_of_item($product_id);
		if( !empty($delivery_text) ){
			return $product_name.'<br/><small>'.$delivery_text.'</small>';
		}
		return $product_name;
	}

	/**
	* This function return the estimated delivery of given product.
	* There might be a shipping class associated and shipping class should configured with an estimated delivery
	*
	* @since 1.4.0
	* @param product id or variation id
	* @return Estimated delivery as sting.
	*/
	private function xa_get_estimated_delivery_of_item( $product,$order='' ){
		if(!$product){
			return false;
		}

		$skip_calculation = apply_filters('ph_skip_estimated_delivery_of_given_product',false, $product);

		if( $skip_calculation){
			return false;
		}

		try{
			$product = wc_get_product($product);
		}catch(Exception $e){
			// echo "<br> Invalid product";
			return false;
		}
		
		$product_page_sample_text	= get_option('wf_estimated_delivery_product_page_text_simple');
		$product_page_sample_text	= !empty( $product_page_sample_text ) ? __($product_page_sample_text,'wf_estimated_delivery') : '<p style="color:green;font-size:small">'.__( 'Estimated delivery by', 'wf_estimated_delivery' ).' [date]</p>';
		
		$product_page_text_range	 = get_option('wf_estimated_delivery_product_page_text_range');
		$product_page_text_range	 = !empty( $product_page_text_range ) ? __($product_page_text_range,'wf_estimated_delivery') : '<p style="color:green;font-size:small">'.__( 'Estimated delivery by','wf_estimated_delivery' ).' [date_1] - [date_2]</p>';
		
		$product_page_sample_text	= apply_filters( 'wpml_translate_single_string', $product_page_sample_text, 'wf_estimated_delivery', 'product_page_simple_text');
		$product_page_text_range	= apply_filters( 'wpml_translate_single_string', $product_page_text_range, 'wf_estimated_delivery', 'product_page_range_text');

		$test = new WC_Shipping;
		$shipping_classes = $test->get_shipping_classes();

		$class_id = apply_filters( 'wpml_object_id', $product->get_shipping_class_id(), 'product_shipping_class', TRUE  );	// WPML Compatible

		$product_shipping_class = '';

		for ( $i=0; $i < count($shipping_classes); $i++ ) { 
			if($shipping_classes[$i]->term_id === $class_id){
				$product_shipping_class = $shipping_classes[$i]->slug;
				break;
			}
		}

		$any_shipping_class = false;

		if(is_array($this->shipping_class_dates) && !empty($this->shipping_class_dates)){
			foreach ( $this->shipping_class_dates as $id => $value ) {
				if( is_array($value['id']) && in_array('ph_any_shipping_class',$value['id']) )
				{
					$any_shipping_class = true;
				}
			}
		}

		if( empty($product_shipping_class) && $any_shipping_class == false ){
			return false;
		}

		$product_shipping_class_min_date = 0;

		if(is_array($this->shipping_class_dates) && !empty($this->shipping_class_dates)){
			foreach ( $this->shipping_class_dates as $id => $value ) {

				// $value['id'] == $product_shipping_class is For Backward Compatibility, before 1.5.7.8
				if( ( is_array($value['id']) && ( in_array($product_shipping_class, $value['id']) || in_array('ph_any_shipping_class',$value['id']) ) ) || $value['id'] == $product_shipping_class ){
					$product_shipping_class_min_date += !empty( $value['min_date'] ) ? $value['min_date'] : 0;
				}
			}
		}

		$estimated_delivery = $this->estimated_delivery;
		$estimated_delivery += (int)$product_shipping_class_min_date;

		$geo=new WC_Geolocation();
		$ip=$geo->get_ip_address();
		$destination=$geo->geolocate_ip($ip);
		$zone1 = WC_Shipping_Zones::get_zone_matching_package( array(
				'destination' => array(
					'country'  => $destination['country'],
					'state'	=> $destination['state'],
					'postcode' => '',
				)
			) 
		);
		$ship_zone_date=0;
		if(is_array($this->shipping_zone) && !empty($this->shipping_zone))		{
			foreach ( $this->shipping_zone as $id => $value ) { 
				if($value['id'] == $zone1->get_zone_name())
				{
					$ship_zone_date=$value['min_date'];
				}
			}
		}
		if($ship_zone_date)
			$estimated_delivery += $ship_zone_date;

		// Adjustment based on stock status
		$product_stock_status 	= $product->get_stock_status();
		$product_manage_stock   = $product->get_manage_stock();
		$product_name 			= $product->get_name();
		$cart 					= array();
		
		if( WC() != null && WC()->cart != null )
		{
			$cart 	= WC()->cart->cart_contents;
		}
		
		$product_quantity 		= 1;

		if( is_array($cart) && !empty($cart) ){
			foreach( $cart as $cart_item ) {
				if( $product_name === $cart_item['data']->get_name() ){	
					$product_quantity 		= $cart_item['quantity'];
				}
			}
		}

		$product_backorder_status 	= $product->is_on_backorder($product_quantity);

		if( ( in_array( $product_stock_status, self::$allowed_adjujustment_stock_status_arr) && !$product_manage_stock ) ||
			( $product_backorder_status && $product_manage_stock ) )
		{
			//$estimated_delivery += (int) $this->adjustment_on_backordered_product );
			$estimated_delivery += apply_filters('ph_add_extra_days_for_backordered_products_product_page', (int) $this->adjustment_on_backordered_product, $product);
		}
		
		$order_id = is_object( $order ) ? $order->get_id() : "";

		if( $this->page_text_format === 'simple'){
			if( is_account_page() && is_object($order) )
			{	
				$order_date = $order->get_date_created();

				$current_time = current_time('Y m d H:i:s');
				$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
				$today_wp_date_seconds = $today_wp_date->format('U');

				$order_date = $order_date->format('U');
				$estimated_delivery = ceil( ($order_date - $today_wp_date_seconds ) / 86400 ) + $estimated_delivery;
			}
			$text = str_replace( '[date]', $this->xa_date_format($this->delivery_date_calculator_obj->wf_get_delivery_date($estimated_delivery, $product, $order_id ) ) , $product_page_sample_text );
		}elseif( $this->page_text_format === 'simple_range' ){

			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $estimated_delivery - $this->lower_range, $product, $order_id );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $estimated_delivery + $this->higher_range, $product, $order_id );
			if( $start_date_object->format('d M Y') == $end_date_object->format('d M Y') && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
			}
			$current_time = current_time('Y m d H:i:s');
			$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
			$today_wp_date_seconds = $today_wp_date->format('U');
			$date_diff_between_current_and_start 	=  ceil( ($start_date_object->format('U') - $today_wp_date_seconds ) / 86400 );
			$date_diff_between_current_and_end 		=  ceil( ($end_date_object->format('U') - $today_wp_date_seconds ) /86400 );

			$text = str_replace('[date_1]', ( ( $date_diff_between_current_and_start > 0 ) ? ( $date_diff_between_current_and_start ) : 0 ) , $product_page_text_range );

			Estimated_Delivery_Log::log_update( ($date_diff_between_current_and_start), 'input' );
			Estimated_Delivery_Log::log_update( ( ( $date_diff_between_current_and_start > 0 ) ? ( $date_diff_between_current_and_start ).' days'  : 0 .' days'  ), 'est_days' );
			Estimated_Delivery_Log::log_update( ( $date_diff_between_current_and_end), 'input' );
			Estimated_Delivery_Log::log_update( ( ( $date_diff_between_current_and_end > 0 ) ? ( $date_diff_between_current_and_end ).' days' : 0 .' days'  ), 'est_days' );
			$text = str_replace('[date_2]', ( $date_diff_between_current_and_end ) , $text);
		}
		else{

			if(  is_account_page() && is_object($order) )
			{
				$order_date = $order->get_date_created();

				$current_time = current_time('Y m d H:i:s');
				$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
				$today_wp_date_seconds = $today_wp_date->format('U');

				$order_date = $order_date->format('U');
				$estimated_delivery = ceil( ($order_date - $today_wp_date_seconds ) / 86400 ) + $estimated_delivery;

			}
			
			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $estimated_delivery - $this->lower_range, $product, $order_id );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $estimated_delivery + $this->higher_range, $product, $order_id );

			$start_date = $this->xa_date_format($start_date_object);	// Formatted Start date
			$end_date 	= $this->xa_date_format($end_date_object);		// Formatted end date

			// If start Date and End Date are equal and lower range and heigher range not equal to zero.
			if( $start_date == $end_date && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$new_end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
				$end_date = $this->xa_date_format($new_end_date_object);
			}

			$text = str_replace('[date_1]', $start_date , $product_page_text_range );
			$text = str_replace('[date_2]', $end_date , $text);			   
		}
		return $text;
	}

	public function wf_estimated_delivery_product_page($stock_arr, $item=''){
		// Return if not single product page
		// if( ! is_product() ){
		// 	return $stock_arr;
		// }
		// Commented above if check for Ticket #17631 issue
		
		if( empty($item) ){
			global $product;
			if( ! is_object($product) ) {
				global $post;
				$product = wc_get_product($post->ID);
			}
			$item = $product;
		}

		$delivery_text = $this->xa_get_estimated_delivery_of_item( $item->get_id() );
		
		if( isset($stock_arr['availability']) && !empty($delivery_text) ){
			// Some stock management plugin used to add their data to $stock_arr['class']
			$in_stock = $item->is_in_stock();
			
			if( $this->plain_text_mode )
			{
				if( empty($stock_arr['availability']) )
				{
					$delivery_text_style = apply_filters('ph_est_delivery_product_page_html',"%s\n");
				}else{
					$delivery_text_style = apply_filters('ph_est_delivery_product_page_html'," - %s\n");
				}

				$delivery_text = strip_tags($delivery_text);

			}elseif( $this->accept_html ) {

				$delivery_text_style = apply_filters('ph_est_delivery_product_page_html',"%s");

			} else {

				$delivery_text_style 	= apply_filters('ph_est_delivery_product_page_html',"<p class='ph_est_delivery_product_page'>%s</p>");
				$delivery_text 			= strip_tags($delivery_text);
			}

			if( $this->show_on_all_product || $in_stock ){
				$stock_arr['availability'] = $stock_arr['availability'].sprintf( $delivery_text_style, $delivery_text );
			}
		}
		return $stock_arr;
	}
	public function ph_estimated_delivery_get_est_delivery_date_based_on_product()
	{
		global $product;
		if( ! is_object($product) ) {
			global $post;
			$product = wc_get_product($post->ID);
		}
		$delivery_text = $this->xa_get_estimated_delivery_of_item( $product->get_id() );
		echo apply_filters('ph_estimated_delivery_anywhere_on_product_page',$delivery_text, $product);
	}
	private function get_estimate_days( $destination, $order = null ){

		global $woocommerce;
		
		if( is_object($order) ) {
			$order_items = $order->get_items();
			foreach( $order_items as $order_item ) {
				$cart_items[] = array(
					'product_id' 	=> $order_item->get_product_id(),
					'variation_id' 	=> $order_item->get_variation_id(),
					'quantity' 	=> $order_item->get_quantity(),
					'data'	=>	$order_item->get_product(),
				);
			}
		}
		else {
			$cart_items = $woocommerce->cart->get_cart();
		}

		$backorder_shipping_class=array();
		foreach ($cart_items as $cart_item) {
			$add_adjustment_of_out_stock_or_backordered_product = false;
			$ship_class = $cart_item['data']->get_shipping_class();
			$product_shipping_class [] = $ship_class;
			$cart_item_stock_status = $cart_item['data']->get_stock_status();
			$cart_item_manage_stock = $cart_item['data']->get_manage_stock();
			$cart_item_product_quantity = $cart_item['quantity'];
			$cart_item_product_backorder_status 	= $cart_item['data']->is_on_backorder($cart_item_product_quantity);

			if( (in_array($cart_item_stock_status, self::$allowed_adjujustment_stock_status_arr) && !$cart_item_manage_stock )
				|| ( $cart_item_product_backorder_status && $cart_item_manage_stock ) )
			{
				$add_adjustment_of_out_stock_or_backordered_product = true;
			}
			if( (isset($backorder_shipping_class[$ship_class]) && $backorder_shipping_class[$ship_class] == false) || !isset($backorder_shipping_class[$ship_class]) ){
				$backorder_shipping_class[$ship_class] = $add_adjustment_of_out_stock_or_backordered_product;
			}
		}

		for ($i=0; $i < count($product_shipping_class) ; $i++) { 

			$product_shipping_class_min_date = 0;

			if(is_array($this->shipping_class_dates) && !empty($this->shipping_class_dates)){
				foreach ( $this->shipping_class_dates as $id => $value ) {
					// $value['id'] == $product_shipping_class[$i] is For Backward Compatibility, before 1.5.7.8
					if( ( is_array($value['id']) && ( in_array($product_shipping_class[$i], $value['id']) || in_array('ph_any_shipping_class',$value['id']) ) ) || $value['id'] == $product_shipping_class[$i] ) {
						$product_shipping_class_min_date += !empty( $value['min_date'] ) ? $value['min_date'] : 0;
					}
				}
			}
			$estimated_delivery = $this->estimated_delivery + (int)$product_shipping_class_min_date;

			$key = $product_shipping_class[$i];

			if( $backorder_shipping_class[$key] == 1 )
			{
			//$estimated_delivery += (int) $this->adjustment_on_backordered_product );
				$estimated_delivery += apply_filters('ph_add_extra_days_for_backordered_products_cart_page', (int) $this->adjustment_on_backordered_product, $cart_items);
			}
			$est[] = $estimated_delivery;
		}

		$test = empty($est) ? null : max($est) ;

		// if( $add_adjustment_of_out_stock_or_backordered_product ) {
		// 	$test += (int) $this->adjustment_on_backordered_product;
		// }

		$skip_calculation = apply_filters('ph_skip_estimated_delivery_extra_days_calculation',false, $cart_items);

		if( $skip_calculation){
			$test = 0;
		}

		$ship = new WC_Shipping_Zones;
		$shipping_zones = $ship->get_zones();

		$zone1 = WC_Shipping_Zones::get_zone_matching_package( 
			array(
				'destination' => array(
					'country'  => $destination['country'],
					'state'	=> $destination['state'],
					'postcode' => $destination['postcode'],
				)
			) 
		);

		$ship_zone_date=0;
		if(is_array($this->shipping_zone) && !empty($this->shipping_zone))		{
			foreach ( $this->shipping_zone as $id => $value ) { 
				if($value['id'] == $zone1->get_zone_name())
				{
					$ship_zone_date=$value['min_date'];
				}
			}
		}

		if($ship_zone_date)
			$test += $ship_zone_date;

		$test += (int) Wf_Estimated_Delivery::calculate_estimate_shipping_method($order);

		return $test;
	}

	public function wf_estimated_delivery_cart_page(){
		global $woocommerce;
		$destination = array(
			'country'  => $woocommerce->customer->get_shipping_country(),
			'state'   => $woocommerce->customer->get_shipping_state(),
			'postcode' => $woocommerce->customer->get_shipping_postcode(),
		);
		$test = $this->get_estimate_days( $destination );

		$this->cart_page_text = apply_filters( 'xa_estimated_delivery_cart_page_text', $this->cart_page_text );
		$this->cart_page_text = apply_filters( 'wpml_translate_single_string', $this->cart_page_text, 'wf_estimated_delivery', 'cart_page_text' ); //For WPML translation

		if($this->page_text_format === 'simple'){
			$cart_page_date = '<tr class="shipping" ><th> ' . $this->cart_page_text . '</th><td data-title="'.$this->cart_page_text.'" > ' . $this->xa_date_format($this->delivery_date_calculator_obj->wf_get_delivery_date($test) )  . '</td></tr>';  
		}elseif($this->page_text_format === 'simple_range'){
			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test - $this->lower_range );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test + $this->higher_range );
			if( $start_date_object->format('d M Y') == $end_date_object->format('d M Y') && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
			}
			$current_time = current_time('Y m d H:i:s');
			$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
			$today_wp_date_seconds = $today_wp_date->format('U');
			$date_diff_between_current_and_start 	=  ceil( ($start_date_object->format('U') - $today_wp_date_seconds ) / 86400 );
			$date_diff_between_current_and_end 		=  ceil( ($end_date_object->format('U') - $today_wp_date_seconds ) /86400 );

			// ceil function will return -0 if value is like -0.23
			if( $date_diff_between_current_and_start == '-0' )
			{
				$date_diff_between_current_and_start =0;
			}

			$cart_page_date = '<tr class="shipping"><th> ' . $this->cart_page_text . '</th><td data-title="'.$this->cart_page_text.'"> ' . ( $date_diff_between_current_and_start < 0  ? 0 : $date_diff_between_current_and_start ) .' - '.( $date_diff_between_current_and_end ).' ' .__( 'days','wf_estimated_delivery' ) .'</td></tr>';

			Estimated_Delivery_Log::log_update( ($date_diff_between_current_and_start), 'input' );
			Estimated_Delivery_Log::log_update( ( ( $date_diff_between_current_and_start > 0 ) ? ( $date_diff_between_current_and_start ).' days'  : 0 .' days'  ), 'est_days' );
			Estimated_Delivery_Log::log_update( ( $date_diff_between_current_and_start), 'input' );
			Estimated_Delivery_Log::log_update( ( ( $date_diff_between_current_and_start > 0 ) ? ( $date_diff_between_current_and_start ).' days' : 0 .' days'  ), 'est_days' );
		}else{
			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test - $this->lower_range );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test + $this->higher_range );

			$start_date = $this->xa_date_format($start_date_object);	// Formatted Start date
			$end_date 	= $this->xa_date_format($end_date_object);		// Formatted end date

			// If start Date and End Date are equal and lower range and heigher range not equal to zero.
			if( $start_date == $end_date && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$new_end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
				$end_date = $this->xa_date_format($new_end_date_object);
			}
			$cart_page_date = '<tr class="shipping"><th> ' . $this->cart_page_text . '</th><td data-title="'.$this->cart_page_text.'"> ' . $start_date  .' - '. $end_date. '</td></tr> ';
		}

		echo apply_filters( 'xa_estimated_delivery_cart_checkout_page_html_formatted_date', $cart_page_date, $test, $this ) ;
	}

	/**
	* Get Formatted date from date object.
	* @param $date object date_object
	* @return Formatted date
	*/
	private function xa_date_format( $date ) {
		// Object if Calculation mode is selected as consider holiday for both recipient and shipper
		return is_object($date) ? apply_filters( 'xa_change_estimated_delivery_date_format', date_i18n( $this->delivery_date_calculator_obj->delivery_date_display_format, strtotime($date->format( "F j, Y H:i:s" )) ), $date->format($this->delivery_date_calculator_obj->delivery_date_display_format) ) :
			apply_filters( 'xa_change_estimated_delivery_date_format', $date );
	}

	/**
	* Get new end date if start and end date both are same.
	* @param $start_date_object object date_object
	* @param $end_date_object object date_object
	* @return object DateTime object
	*/
	private function xa_calculate_end_date( $start_date_object, $end_date_object ) {
		$days_count_to_add	 = $this->lower_range + $this->higher_range;
		$new_end_date_object = $end_date_object->modify("+$days_count_to_add day");
		return $this->delivery_date_calculator_obj->find_nearest_working_day($new_end_date_object);
	}


	public function calculate_estimate_shipping_method( $order = null ){
		global $woocommerce;
		$selected_shipping_method = null;
		$shipping_methods_dates	= get_option('wf_estimated_delivery_shipping_methods');
		$shipping_methods_dates	= !empty( $shipping_methods_dates ) ? $shipping_methods_dates : array();

		// If order has been created from backend
		// if( is_object($order) ) {
		// 	$shipping_methods   			= $order->get_shipping_methods();
		// 	$selected_shipping_method_obj	= !empty($shipping_methods) ? array_shift($shipping_methods) : null;
		// 	if( ! empty($selected_shipping_method_obj) ) {
		// 		$shipping_method_id = $selected_shipping_method_obj->get_method_id();	// Before 3.4 e.g. flat_rate:1, after 3.4 e.g. flat_rate
				
		// 		if( version_compare( WC()->version, '3.4.0', '>' ) ) {
		// 			$shipping_method_instance_id 	= $selected_shipping_method_obj->get_instance_id();
		// 			$selected_shipping_method 		= $shipping_method_id.':'.$shipping_method_instance_id;
		// 		}
		// 		else {
		// 			$selected_shipping_method = $shipping_method_id;
		// 		}
		// 	}
		// 	// Filter to support Shipping plugin that doesn't have settings in woocommerce zones
		// 	$selected_shipping_method = apply_filters( 'ph_estimated_delivery_selected_shipping_method_on_order' , $selected_shipping_method, $order );
		// }
		// else {
		// 	$shipping_methods			= WC()->session->get( 'chosen_shipping_methods' );
		// 	$selected_shipping_method	= !empty( $shipping_methods ) ? array_shift( $shipping_methods ) : '';
		// }

		if( !is_admin() ){
			
			$shipping_methods = '';

			if ( is_object( WC()->session ) ) {
				$shipping_methods			= WC()->session->get( 'chosen_shipping_methods' );
			}

			if( !empty($shipping_methods))
			{		
				$selected_shipping_method	= !empty( $shipping_methods ) ? array_shift( $shipping_methods ) : '';
			}
		}
		
		$estimaded_delivary_array = array();
		foreach($shipping_methods_dates as $key => $value) {
			if( !empty($value['methods_names']) ){
				$role_shipping_methods = explode( ',', $value['methods_names'] );
				// * to apply on all shipping methods
				if( in_array( $selected_shipping_method, $role_shipping_methods ) || in_array( '*', $role_shipping_methods ) ){
					array_push( $estimaded_delivary_array, $value['min_date'] );
				}
			}
		}

		if( !empty($estimaded_delivary_array) ){
			return max($estimaded_delivary_array);
		}

		return false;
	}

	private function get_estimated_delivery_text($test){
		$this->cart_page_text = apply_filters( 'xa_estimated_delivery_cart_page_text', $this->cart_page_text );
		$this->cart_page_text = apply_filters( 'wpml_translate_single_string', $this->cart_page_text, 'wf_estimated_delivery', 'cart_page_text' ); //For WPML translation
		$text = '';
		if( $this->page_text_format === 'simple'){
			$text .= '<tr class="shipping"><th> ' . $this->cart_page_text . '</th><td data-title="'.$this->cart_page_text.'"> ' . $this->xa_date_format($this->delivery_date_calculator_obj->wf_get_delivery_date($test) ) . '</td></tr>';  
		}else if( $this->page_text_format === 'simple_range'){

			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test - $this->lower_range );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test + $this->higher_range );
			if( $start_date_object->format('d M Y') == $end_date_object->format('d M Y') && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
			}
			$current_time = current_time('Y m d H:i:s');
			$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
			$today_wp_date_seconds = $today_wp_date->format('U');
			$date_diff_between_current_and_start 	=  ceil( ($start_date_object->format('U') - $today_wp_date_seconds ) / 86400 ) ;
			$date_diff_between_current_and_end 		=  ceil( ($end_date_object->format('U') - $today_wp_date_seconds ) /86400 ) ;

			// ceil function will return -0 if value is like -0.23
			if( $date_diff_between_current_and_start == '-0' )
			{
				$date_diff_between_current_and_start =0;
			}

			$text .=  '<tr class="shipping"><th> ' . $this->cart_page_text . '</th><td data-title="'.$this->cart_page_text.'"> ' . ( $date_diff_between_current_and_start )  .' - '.( $date_diff_between_current_and_end ).' ' .__( 'days','wf_estimated_delivery' ).'</td></tr>';

		}else {

			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test - $this->lower_range );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test + $this->higher_range );

			$start_date = $this->xa_date_format($start_date_object);	// Formatted Start date
			$end_date 	= $this->xa_date_format($end_date_object);		// Formatted end date

			// If start Date and End Date are equal and lower range and heigher range not equal to zero.
			if( $start_date == $end_date && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$new_end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
				$end_date = $this->xa_date_format($new_end_date_object);
			}

			$text .=  '<tr class="shipping"><th> ' . $this->cart_page_text . '</th><td data-title="'.$this->cart_page_text.'"> ' . $start_date  .' - '. $end_date . '</td></tr>';
		}
		return apply_filters( 'xa_estimated_delivery_cart_checkout_page_html_formatted_date', $text, $test, $this );
	}

	public function wf_estimated_delivery_checkout_page(){
		if(isset($_POST["s_country"])){
			echo $this->get_estimated_delivery_text( $this->get_estimate_days( array(
				'country'  => $_POST["s_country"],
				'state'	=> $_POST["s_state"],
				'postcode' => $_POST["s_postcode"],
			)
		)
		);
		}
	}

	public function wf_estimated_delivery_thankyou_page($ids,$order){
		$this->cart_page_text = apply_filters( 'xa_estimated_delivery_cart_page_text', $this->cart_page_text );
		$this->cart_page_text = apply_filters( 'wpml_translate_single_string', $this->cart_page_text, 'wf_estimated_delivery', 'cart_page_text' ); //For WPML translation
		
		$post_id = (WC()->version < '2.7.0') ? $order->post->ID : $order->get_id();
		$est_delivery = get_post_meta($post_id, '_est_date', true );
		if( ! empty($est_delivery) ) {
			$ids['ph_est_delivery'] = array(
				'label'		=>	esc_attr( $this->cart_page_text ),
				'value'		=>	$est_delivery
			);
		}
		return $ids;
	}
	public function wf_add_est_delivery_to_order_meta($order_id){
		$order = null;
		if( $order_id ) {
			$order = wc_get_order($order_id);
			$order_items = $order->get_items();
			$shippable_product_exist = false;
			foreach( $order_items as $order_item ) {
				$order_item_product = $order_item->get_product();
				if( $order_item_product instanceof WC_Product && $order_item_product->needs_shipping() ) {
					$shippable_product_exist = true;
					break;
				}
			}
			// Skip estimated delivery calculation if none of the product is shippable
			if( $shippable_product_exist === false ) {
				return;
			}
			$destination = array(
				'country'   => $order->get_shipping_country(),
				'state'     => $order->get_shipping_state(),
				'postcode' => $order->get_shipping_postcode(),
			);
		}
		else {
			$destination = array(
				'country'  => WC()->customer->get_shipping_country(),
				'state'	=> WC()->customer->get_shipping_state(),
				'postcode' => WC()->customer->get_shipping_postcode(),
			);
		}

		$test = $this->get_estimate_days($destination, $order);

		if( $this->page_text_format === 'simple'){
			$text = $this->xa_date_format($this->delivery_date_calculator_obj->wf_get_delivery_date($test));
		}else if( $this->page_text_format === 'simple_range'){

			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test - $this->lower_range );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test + $this->higher_range );
			if( $start_date_object->format('d M Y') == $end_date_object->format('d M Y') && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
			}
			$current_time = current_time('Y m d H:i:s');
			$today_wp_date = date_create_from_format( 'Y m d H:i:s', $current_time);
			$today_wp_date_seconds = $today_wp_date->format('U');
			$date_diff_between_current_and_start 	=  ceil( ($start_date_object->format('U') - $today_wp_date_seconds ) / 86400 );
			$date_diff_between_current_and_end 		=  ceil( ($end_date_object->format('U') - $today_wp_date_seconds ) /86400 );

			// ceil function will return -0 if value is like -0.23
			if( $date_diff_between_current_and_start < 0 || $date_diff_between_current_and_start == '-0') { 
				$date_diff_between_current_and_start = 0;
			}
			
			$text = ( $date_diff_between_current_and_start )." - ".( $date_diff_between_current_and_end ).' '.__( 'days','wf_estimated_delivery' );
		}else {

			$start_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test - $this->lower_range );
			$end_date_object = $this->delivery_date_calculator_obj->wf_get_delivery_date( $test + $this->higher_range );

			$start_date = $this->xa_date_format($start_date_object);	// Formatted Start date
			$end_date 	= $this->xa_date_format($end_date_object);		// Formatted end date

			// If start Date and End Date are equal and lower range and heigher range not equal to zero.
			if( $start_date == $end_date && ( $this->lower_range != $this->higher_range || ! empty($this->lower_range) ) ) {
				$new_end_date_object = $this->xa_calculate_end_date( $start_date_object, $end_date_object );
				$end_date = $this->xa_date_format($new_end_date_object);
			}
			$text = $start_date.' - '. $end_date;
		}
		$text = apply_filters( 'xa_estimated_delivery_thank_you_page_html_formatted_date', $text, $test, $this );
		update_post_meta( $order_id, '_est_date', $text ); 
	}	

	public function wf_estimated_delivery_admin_order_meta($order){
		echo '<p class="form-field form-field-wide">';
		
		$id = (WC()->version < '2.7.0') ? $order->id : $order->get_id();
		$est_date = get_post_meta($id,'_est_date',true);
		if( empty($est_date) ) {
			$calculate_est_delivery 	= admin_url( '/?post='.( $id ).'&calculate_est_delivery=yes' );
			?>
			<br /><a class="button button-primary ph_est_delivery_calculate tips" href="<?php echo $calculate_est_delivery; ?>" data-tip="<?php _e( 'Calculate Estimated Delivery Date. Estimated Delivery Date calculation will start from Order Creation date.', 'wf_estimated_delivery' ); ?>"><?php _e('Calculate Estimated Delivery', 'wf_estimated_delivery'); ?></a>
			<?php
		}
		else{
			echo '<label for="ph_est_delivery_date"> '.$this->cart_page_text.' </label>';
			echo ' <input type="text" value="'.$est_date.'" id="ph_est_delivery_date" name="ph_est_delivery_date" >';
			echo '</p>';
		}
	}

	/**
	 * Manually Update Estimated Delivery on Order Page.
	 * @param int $order_id Order Id.
	 */
	public function ph_update_estimated_delivery_on_order_page_manually($order_id) {
		if( isset($_POST['ph_est_delivery_date']) ) {
			update_post_meta( $order_id, '_est_date', $_POST['ph_est_delivery_date']);
		}
	}

}
new Wf_Estimated_Delivery;
