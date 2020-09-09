<?php 
abstract class XA_Calc_Est_Strategy {
	public $record_log 	= '';
	public $wf_holiday 	= '';
	public $wf_workdays = '';
	public $dafault_days = '';
	public $calculation_mode = '';
	public $delivery_date_display_format = '';
	public $calculate_option = '';
	public $wp_timezone_string = '';
	public $time_calculate = '';
	public $cutOff = '';
	/**
	 * Days of Week ( Sun to Sat).
	 */
	public static $weekdays = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );

	/**
	 * Shipping zone settings.
	 */
	public static $shipping_zone_settings = array();
	public static $shipping_class_settings = array();

	/**
	 * Shipping method Rules.
	 */
	public static $shipping_method_rules = array();

	
	function __construct(){
		// $args = func_get_args();
		// $this->dafault_days = $args[0];
		$this->xa_set_options();
	}

	private function xa_set_options(){
		$default_working_days 	= array('mon','tue','wed','thu','fri');

		$record_log				= get_option( 'wf_estimated_delivery_record_log' );
		$this->record_log 		= !empty( $record_log ) && $record_log ==='yes' ? true : false;

		$wf_holiday				= get_option( 'wf_estimated_delivery_holiday' );
		$this->wf_holiday 		= !empty( $wf_holiday ) ? $wf_holiday : array();

		$wf_workdays			= get_option( 'wf_estimated_delivery_operation_days' );
		$this->wf_workdays		= !empty( $wf_workdays  ) ? $wf_workdays : $default_working_days ;

		$calculation_mode			= get_option( 'wf_estimated_delivery_calculation_mode' );
		$this->calculation_mode		= !empty( $calculation_mode  ) ? $calculation_mode : '';

		$delivery_date_display_format = get_option('wf_estimated_delivery_date_display_format');
		$this->delivery_date_display_format 	= !empty( $delivery_date_display_format ) ? $delivery_date_display_format : '';

		$calculate_option = get_option('wf_estimated_delivery_operation_delay');
		$this->nextworkingday_as_start 	= !empty( $calculate_option ) && ( $calculate_option =='yes' || $calculate_option == 'extra' ) ? true : false; //Checking 'extra' for backward compatibility.

		// Timezone set to UTC+5:30 then timezone_string won't be set, it will set if Timezone is like Asia/Kolkata, didn't made as default since it might get affected depending on daylight saving
		$wp_timezone_string 		= get_option('timezone_string');
		if( empty($wp_timezone_string) ) {
			$this->time_offset 	= get_option('gmt_offset');
			// Considered daylight saving off, may be we need to give daylight saving option in settings
			// UTC−05:00 , Timezone CDT with daylight saving, Timezone EST without daylight saving
			$wp_timezone_string = timezone_name_from_abbr( "", $this->time_offset*60*60, 0 );
		}
		$this->wp_timezone_string 	= !empty( $wp_timezone_string ) ? $wp_timezone_string : '';

		$time_calculate 			= get_option('wf_estimated_delivery_date_time_zone');
		$this->time_calculate 		= !empty( $time_calculate ) ? $time_calculate : '';

		$this->closingTimes 	= get_option('wf_estimated_delivery_day_limits'); 

		$this->pocessed_holidays = $this->xa_get_holidays();

	}

	public static function get_calculation_mode( $calculation_mode ){
		switch ( $calculation_mode ) {
			case 'holiday_for_shop':
			include_once('class-xa-calc-est-holiday-for-shop.php');
			$delivery_date_calculator_obj = new XA_Calc_Est_Strategy_Holiday_for_Shop();
			break;

		    default: //'holiday_for_shop_dest':
		    include_once('class-xa-calc-est-holiday-for-shop-and-recipient.php');
		    $delivery_date_calculator_obj = new XA_Calc_Est_Strategy_Holiday_for_Shop_Recipient();
		    break;
		}
		return $delivery_date_calculator_obj;
	}

	public function xa_write_log($msg, $title='input'){
		if($this->record_log == 'yes'){
			Estimated_Delivery_Log::log_update($msg, $title);
		}
	}

	private function xa_get_holidays(){
		$wf_holidays = array();
		if( $this->wf_holiday != '' ){
			foreach( $this->wf_holiday as $wf_hday ){
				if( isset($wf_hday['to']) ){
					$timeF = date( 'd-m-Y', strtotime( str_replace('/','-',$wf_hday['from']) ) );
					$timeT = date( 'd-m-Y', strtotime( str_replace('/','-',$wf_hday['to']) ) );

					$wf_holidays[] 	= $timeF;
					$from_date 		= strtotime($timeF);
					$to_date 		= strtotime($timeT);

					while( $from_date < $to_date ){

						$timeF 			= date( 'd-m-Y',strtotime( "+1 day", strtotime($timeF) ) );
						$from_date 		= strtotime($timeF);
						$wf_holidays[] 	= $timeF;
					} 
				}
			}
		}
		return $wf_holidays;
	}

	protected function xa_get_current_day(){
		$cur_day = new DateTime();

		if( $this->time_calculate === 'gmt' && !empty($this->wp_timezone_string) ){
			$tz = new DateTimeZone( $this->wp_timezone_string );
			$cur_day->setTimezone($tz);
		}
		
		$cur_day 	= date_format($cur_day,'D');
		return strtolower($cur_day);
	}

	protected function xa_get_cutoff_time( $cur_day ){
		$day_order = array( 'mon'=>0, 'tue'=>1, 'wed'=>2, 'thu'=>3, 'fri'=>4, 'sat'=>5, 'sun'=>6 );
		
		$cutOff = !empty($this->closingTimes[ $day_order[$cur_day] ]) ? $this->closingTimes[ $day_order[$cur_day] ] : '';
		$this->cutOff 	= !empty( $cutOff ) ? $cutOff : '20:00';

		$this->cutOff = str_replace('.', ':', $this->cutOff);
		return apply_filters('ph_estimated_delivery_get_cutoff_time',( strstr($this->cutOff, ':') ) ? explode( ':', $this->cutOff ) : array($this->cutOff, ''),$cur_day);
	}

	protected function xa_get_staring_date( $cutoff_time ){
		list( $cut_hrs, $cut_min ) = $cutoff_time;

		$cut_hrs = intval($cut_hrs);
		$cut_min = intval($cut_min);

		$wf_date = new DateTime;

		// Take the order created date as start date in on order page not today date, if Estimated delivery has not been generated already.
		// is_ajax check to avoid the call admin-ajax.php in front end.
		if( is_admin() && ! is_ajax() ) {
			global $post;
			// To generate estimated delivery manually
			if( empty($post) && ! empty($_GET['calculate_est_delivery']) && ! empty($_GET['post']) ) {
				$post = get_post($_GET['post']);
			}

			$order = wc_get_order($post);

			if( is_object($order) && method_exists($order, "date_created")  )
			{
				$wf_date = $order->get_date_created();
			}
		}

		if( $this->time_calculate === 'gmt' && !empty( $this->wp_timezone_string ) ){
			$tz = new DateTimeZone( $this->wp_timezone_string );
			$wf_date->setTimezone($tz);
		}

		$wf_time = clone $wf_date;
		$wf_time->setTime($cut_hrs,$cut_min);

		$today_date = clone $wf_date;
		if ($wf_date >= $wf_time){
			$today_date->modify('+1 day');
		}

		if( $this->nextworkingday_as_start ){
			$today_date = $today_date->modify('+1 day');
		}
		return $today_date;
	}


	public function find_nearest_working_day( $curr_date ){
		$loop_limit = 30; //for preventing loop going endless in case of some misconfiguration of settings page.
		while( ( !$this->is_working_day( $curr_date )  || $this->is_holiday_day( $curr_date ) ) && $loop_limit > 0 ){
			$curr_date =  $curr_date->modify('+1 day') ;
			$loop_limit --;
		}
		return $curr_date;
	}

	private function is_working_day( $check_date ){
		return in_array( strtolower(date_format($check_date,'D')), $this->wf_workdays );
	}

	/**
	 * Get Estimated Delivery Date based on Shipping Methods working days.
	 * @param $est_date object DateTime Object.
	 * @param $order_id int Order Id.
	 * @return object DateTime Object.
	 */
	public function get_shipping_method_delivery_date( $est_date, $order_id= null, $product = null,$shipping_method) {
		global $woocommerce;

		$zone_non_working_days = array();
		$non_working_days = array();

		//Get Shipping Zone Settings from plugin
		if( empty(self::$shipping_zone_settings) ) {
			self::$shipping_zone_settings	= get_option('wf_estimated_delivery_shipping_zone', array() );
		}
		$zone_settings_ids = array_column( self::$shipping_zone_settings, 'id');

		//Get Shipping Class Settings from plugin
		if( empty(self::$shipping_class_settings) ) {
			self::$shipping_class_settings	= get_option('wf_estimated_delivery_shipping_class', array() );
		}
		self::$shipping_class_settings=array_values(self::$shipping_class_settings);
		$class_settings_ids = array_column( self::$shipping_class_settings, 'id');

		// Handle Cart and Checkout Page
		if( ! is_admin() ) {
			
			$shipping_methods = '';
			$zone = null;
			if ( is_object( WC()->session ) ) {
				$shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
			}
			$selected_shipping_method	= !empty( $shipping_methods ) ? array_shift( $shipping_methods ) : '';
			// Get the zone from cart
			$cart = WC()->cart;
			if( $cart != null ){
				$cart_packages = $cart->get_shipping_packages();
				if( ! empty($cart_packages) ) {
					$zone = WC_Shipping_Zones::get_zone_matching_package( current($cart_packages) );
				}
			}
			elseif( !empty( $order_id ) ) {
				$order = new WC_Order( $order_id );
				$order_address = $order->get_address('shipping');
				// Get the zones for order
				$zone = WC_Shipping_Zones::get_zone_matching_package( array(
					'destination'	=>	array(
						'country'  	=> $order_address['country'],
						'state'		=> $order_address['state'],
						'postcode' 	=> $order_address['postcode'],
					)
				) );
			}
		}
		// Handle Order Page
		else{
			global $post;
			if( !empty($post->post_type) && $post->post_type == 'shop_order' ) {
				$order = wc_get_order($post->ID);
				$shipping_methods   			= $order->get_shipping_methods();
				$selected_shipping_method_obj	= !empty($shipping_methods) ? array_shift($shipping_methods) : null;

				$selected_shipping_method = '';
				
				if( ! empty($selected_shipping_method_obj) ) {
					$shipping_method_id = $selected_shipping_method_obj->get_method_id();	// Before 3.4 e.g. flat_rate:1, after 3.4 e.g. flat_rate
					
					if( version_compare( WC()->version, '3.4.0', '>' ) ) {
						$shipping_method_instance_id 	= $selected_shipping_method_obj->get_instance_id();
						$selected_shipping_method 		= $shipping_method_id.':'.$shipping_method_instance_id;
					}
					else {
						$selected_shipping_method = $shipping_method_id;
					}
				}
				$order_address = $order->get_address('shipping');
				// Get the zones for order
				$zone = WC_Shipping_Zones::get_zone_matching_package( array(
					'destination'	=>	array(
						'country'  	=> $order_address['country'],
						'state'		=> $order_address['state'],
						'postcode' 	=> $order_address['postcode'],
					)
				) );
				// Filter to support Shipping plugin that doesn't have settings in woocommerce zones
				$selected_shipping_method = apply_filters( 'ph_estimated_delivery_selected_shipping_method_on_order' , $selected_shipping_method, $order );
			}
		}

		$product = wc_get_product($product);
		$shipping_class = array();

		if( !empty($product) )
		{
			$shipping_class[] = $product->get_shipping_class();	//Get shipping class of each product
		}else{
			if ( !is_admin() ){
				
				if( isset($woocommerce) && isset($woocommerce->cart) ){

					$cart_items = $woocommerce->cart->get_cart();
					foreach ($cart_items as $cart_item) {
						$shipping_class[] = $cart_item['data']->get_shipping_class();	//Get shipping class for cart section
					}
				}
				
			}elseif( is_admin() ){

				$order_id 	= isset( $_GET['post'] ) ? $_GET['post'] : '';

				if ( !empty($order_id) ) {
					$order 		= wc_get_order($order_id);

					if( is_a( $order, 'WC_Order') )
					{
						$order_items = $order->get_items();

						foreach( $order_items as $order_item ) {
							$order_item_product = $order_item->get_product();
							$shipping_class[] = $order_item_product->get_shipping_class();
						}
					}
				}				
			}	
		}

		//Non Working days for the shipping class
		for ($i=0; $i < count($shipping_class) ; $i++) {	

			if(is_array(self::$shipping_class_settings) && !empty(self::$shipping_class_settings)){

				foreach ( self::$shipping_class_settings as $id => $value ) {

					$class_setting_key = false;

					if( is_array($class_settings_ids[$id]) )
					{
						$class_setting_key = array_search( $shipping_class[$i], $class_settings_ids[$id] );

						if( in_array('ph_any_shipping_class',$class_settings_ids[$id]) )
						{
							$class_setting_key = true;
						}
						
						if( $class_setting_key !== false ) {

							$class_non_working_days = ! empty(self::$shipping_class_settings[$id]['non_working_days']) ? self::$shipping_class_settings[$id]['non_working_days'] : array();
							$non_working_days = array_merge($non_working_days, $class_non_working_days);
							array_unique($non_working_days);
						}
					}
				}
			}
		}

		// Non Working days for the zone
		if( ! empty($zone) ) {
			$zone_name = $zone->get_zone_name();
			$zone_setting_key = array_search( $zone_name, $zone_settings_ids );		// Key for zone in our plugin settings
			if( $zone_setting_key !== false ) {
				$zone_non_working_days = ! empty(self::$shipping_zone_settings[$zone_setting_key]['non_working_days']) ? self::$shipping_zone_settings[$zone_setting_key]['non_working_days'] : array();
				// All the weekdays can't be non working days
				if( ! empty($non_working_days) && ! empty($zone_non_working_days) ) {

					$non_working_days = array_merge($non_working_days, $zone_non_working_days);
					array_unique($non_working_days);
				}
				elseif( ! empty($zone_non_working_days) ) {

					$non_working_days = $zone_non_working_days;
				}
			}
		}

		if(!empty($shipping_method))
		{
			$selected_shipping_method=$shipping_method;
		}
		if( ! empty($selected_shipping_method) ) {
			if( empty(self::$shipping_method_rules) ) {
				self::$shipping_method_rules	= get_option('wf_estimated_delivery_shipping_methods', array() );
			}
			// Consider first matched rule for any shipping method
			foreach( self::$shipping_method_rules as $method_rule ) {

				$rule_shipping_methods_arr = array_map( 'trim', explode( ',', $method_rule['methods_names'] ) );

				if( in_array( $selected_shipping_method, $rule_shipping_methods_arr) || in_array( '*', $rule_shipping_methods_arr) ) {

					// All the weekdays can't be non working days
					if( ! empty($non_working_days) && ! empty($method_rule['non_working_days']) ) {
						$non_working_days = array_merge($non_working_days, $method_rule['non_working_days']);
						array_unique($non_working_days);
					}
					elseif( ! empty($method_rule['non_working_days']) ) {
						$non_working_days = $method_rule['non_working_days'];
					}
					break;
				}
			}
		}
		
		// Get the working days depending upon shipping method and shipping zone
		if( !empty($non_working_days) ){
			$working_days = array_diff(self::$weekdays, $non_working_days) ;
		}

		if( ! empty($working_days) && count($working_days) < 7 ) {
			while( ! in_array( $est_date->format('D'), $working_days ) ) {
				$est_date->modify('+1 day');
			}
		}
		return $est_date;
	}

	private function is_holiday_day( $check_date ){
		return in_array($check_date->format('d-m-Y'), $this->pocessed_holidays); // the date format here is hardcoded in html-holiday.php
	}
}
