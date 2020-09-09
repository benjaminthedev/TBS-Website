<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wf_Estimated_Delivery_Settings extends WC_Settings_Page {

	public function __construct() {	
		$this->id		= 'wf_estimated_delivery';
		$this->label	= __( 'Estimated Delivery', 'wf_estimated_delivery' );

		add_filter( 'woocommerce_settings_tabs_array',		array( $this, 'add_settings_page' ), 21 );
		add_action( 'woocommerce_sections_' . $this->id,	  array( $this, 'output_sections' ) );

		add_action( 'woocommerce_settings_' . $this->id,	  array( $this, 'wf_estimated_delivery_output' ) );
		add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'wf_estimated_delivery_save' ) );

		add_action( 'woocommerce_update_options_wf_estimated_delivery', array( $this, 'wf_estimated_delivery_update_settings') );

		add_action('woocommerce_admin_field_holiday',array( $this, 'generate_holiday_html')); 
		add_action('woocommerce_admin_field_shipping_class',array( $this, 'generate_shipping_class_html'));
		add_action('woocommerce_admin_field_shipping_zone',array( $this, 'generate_shipping_zone_html'));
		add_action('woocommerce_admin_field_shipping_methods',array( $this, 'generate_shipping_methods_html'));
		add_action('woocommerce_admin_field_day_limits',array( $this, 'generate_day_limits_html'));
		add_action( 'current_screen', array( $this,'wf_estimated_delivery_this_screen' ));
		add_action( 'wp_footer', array( $this, 'wf_estimated_delivery_scripts' ) );
		// To remove the shipping class rule if id not set for any particular rule, trigger on save shipping class
		add_filter( 'woocommerce_admin_settings_sanitize_option_wf_estimated_delivery_shipping_class', array( $this, 'ph_remove_shipping_class_rule_in_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wf_estimated_delivery_enqueue_admin_scripts' ) );
	}

	/**
	 * Add Script.
	 */
	public function wf_estimated_delivery_enqueue_admin_scripts() {
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'ph-est-delivery-common-script', plugins_url( '../assests/js/common.js', __FILE__ ), array( 'jquery' ) );
	}
	
	public function get_sections() {  
		$sections = array(
			''				=> __( 'General Settings', 'wf_estimated_delivery' ),
			'wf_holidays'			=> __( 'Holidays', 'wf_estimated_delivery' ),
			'wf_shipping_class'		=> __( 'Shipping Class', 'wf_estimated_delivery' ),
			'wf_shipping_zone'		=> __( 'Shipping Zones', 'wf_estimated_delivery' ),   
			'wf_shipping_methods'		=> __( 'Shipping methods', 'wf_estimated_delivery' ),   
		);			   
		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	public function wf_estimated_delivery_update_settings( $current_section ) {
		global $current_section;
		switch($current_section) {
			case '':
				$options = $this->wf_estimated_delivery_get_settings();
			case 'wf_holidays':
				$options = $this->wf_estimated_delivery_get_settings();
				break;
			
			case 'wf_shipping_class':
				$options = $this->wf_estimated_delivery_get_settings();
				break;
			
			case 'wf_shipping_zone':
				$options = $this->wf_estimated_delivery_get_settings();
				break;
			
			case 'wf_shipping_methods':
				$options = $this->wf_estimated_delivery_get_settings();
				break;
		}
	}

	public function wf_estimated_delivery_get_settings( $current_section = '' ) {
		global $current_section;
		switch($current_section){
			case 'wf_holidays':
				$settings = apply_filters( 'wf_estimated_delivery_section2_settings', array(

					'holidays_options_title'	=>	array(
						'name' 	=> __( 'Holidays', 'wf_estimated_delivery' ),
						'type' 	=> 'title',
						'desc' 	=> '',
						'id' 	=> 'wf_estimated_delivery_holidays_options_title',
						'value'	=> __( 'Holidays', 'wf_estimated_delivery' ),
					),
					'holiday' =>	array(
						'type'	=> 'holiday',
						'id'	=> 'wf_estimated_delivery_holiday',
						'value'	=> '',
					),					
					'holidays_options_sectionend' => array(
						'type'	=> 'sectionend',
						'id'	=> 'wf_estimated_delivery_holidays_options_sectionend',
						'value'	=> '',
					),			
				) );			
				break;	
					
			case '':
				$settings = apply_filters( 'wf_estimated_delivery_section1_settings', array(
					
					'date_options_title'	=>	array(
						'name' => __( 'General Settings', 'wf_estimated_delivery' ),
						'type' => 'title',
						'desc' => '',
						'id'   => 'wf_estimated_delivery_date_options_title',
					),
					'page_text_format' => array(
						'id'	  => 'wf_estimated_delivery_page_text_format',
						'type'	  => 'select',
						'css' 			=> 'padding: 0px;',
						'name'	=> __( 'Text Format', 'wf_estimated_delivery'),
						'default' => 'simple',
						'desc'	  => __( 'You can select a format for text display. There are three available choices 
							<br/>Simple – Allows you to set simple text display as defined in the Product Page Text field below the Text Format option.<br/>Simple Range – Allows you to set a simple date range within which the package is estimated to be delivered. The Product Page Text (Range) field is enabled instead of Product Page Text field(for simple format), where you can set custom text for date range. <br/>Date Range – Allows you to set a date range within which the package is estimated to be delivered.','wf_estimated_delivery'),
						'desc_tip'=> true,
						'options' =>array(
								'simple'		=>	__( 'Simple', 'wf_estimated_delivery' ),
								'simple_range'		=>	__( 'Simple Range', 'wf_estimated_delivery' ),
								'date_range'		=>	__( 'Date Range', 'wf_estimated_delivery' )
							)
					),
					'product_page_text_simple'	=>	array(
						'type'			=> 'textarea',
						'id'			=> 'wf_estimated_delivery_product_page_text_simple',
						'desc'			=> __( ' Enter the custom text with the date delimiter. This text gets visible on the product page just above the add to cart button.<br/>For Example: Estimated delivery by [date].<br/>Note: To Display the text the product must be linked with a shipping class. HTML tags accepted','wf_estimated_delivery'),
						'placeholder'	=> __( 'Estimated delivery by ','wf_estimated_delivery').'[date]',
						'default'		=> 'Estimated delivery by [date]',
						'css'			=> 'width:400px;',
						'desc_tip'		=> true,
						'name'			=> __( 'Product Page Text', 'wf_estimated_delivery' ),
					),
					'product_page_text_range'	=>	array(
						'type'			=> 'textarea',
						'id'			=> 'wf_estimated_delivery_product_page_text_range',
						'desc'			=> __( 'Enter your custom text here with the delimiter [date_1] & [date_2].<br/>Note: To Display the text the product must be linked with a shipping class. HTML tags accepted','wf_estimated_delivery'),
						'placeholder'	=> __( 'Estimated delivery by ', 'wf_estimated_delivery').'[date_1] - [date_2]',
						'default'		=> 'Estimated delivery by [date_1] - [date_2]',
						'css'			=> 'width:400px;',
						'desc_tip'		=> true,
						'name'			=> __( 'Product Page Text (Range)', 'wf_estimated_delivery' ),
					),
					'accept_html'	=> array(
						'title'		   	=> __( 'HTML Support', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'desc'	 		=> __( 'Enable to support HTML Tags in Product Page Text', 'wf_estimated_delivery'),
						'id'   			=> 'wf_estimated_delivery_accept_html',
					),
					'wf_estimated_delivery_lower_range'	=>	array(
						'type'			=> 'text',
						'id'			=> 'wf_estimated_delivery_lower_range',
						'desc'			=> __( 'Allows you to set a numerical value for lower range of the delivery date. For example, if the date lower range value is 2 and delivery date is 25/01/2019, the start range for delivery date will be 23/01/2019.','wf_estimated_delivery'),
						'default'		=> '1',
						'css'			=> 'width:400px;',
						'desc_tip'		=> true,
						'name'			=> __( 'Date Lower Range [date_1]', 'wf_estimated_delivery' ),
					),
					'wf_estimated_delivery_higher_range'	=>	array(
						'type'	 		=> 'text',
						'id'	   		=> 'wf_estimated_delivery_higher_range',
						'desc'	 		=> __( 'Allows you to set a numerical value for higher range of the delivery date. For example, if the date higher range value is 2 and delivery date is 27/01/2019, the end range for delivery date will be 29/01/2019.','wf_estimated_delivery'),
						'default'  		=> '1',
						'css'			=> 'width:400px;',
						'name'	 		=> __( 'Date Higher Range [date_2]', 'wf_estimated_delivery' ),
						'desc_tip'		=> true,
					),
					'cart_page_text'	=>	array(
						'type'	 		=> 'text',
						'id'	   		=> 'wf_estimated_delivery_cart_page_text',
						'desc'	 		=> __( 'Enter text which gets displayed on Cart, Checkout, Admin Order, Order Received Page, and Email beside which the estimated delivery date is shown.','wf_estimated_delivery'),
						'placeholder'		=> __( 'Estimated Delivery', 'wf_estimated_delivery'),
						'default'  		=> __( 'Estimated Delivery', 'wf_estimated_delivery'),
						'name'	 		=> __( 'Display Text', 'wf_estimated_delivery' ),
						'desc_tip'		=> true,
					),
					'use_time_zone' 	=> array(
						'id'	  		=> 'wf_estimated_delivery_date_time_zone',
						'type'			=> 'select',
						'css' 			=> 'padding: 0px;',
						'name'   		=> __( 'Time Zone', 'wf_estimated_delivery'),
						'desc'			=> __( 'The plugin provides two time zones for calculating delivery date. UTC – This time zone is the time standard commonly used across the world. WP Time Zone – This time zone is the selected GMT time zone in the General Settings section of WordPress. By default, the time zones are city names, which constitute to string values.', 'wf_estimated_delivery'),
						'desc_tip'		=> true,
						'options' 		=>array(
											'utc'	=>	'UTC',
											'gmt'	=>	'WP Time Zone'
										)
						 
					),
					'date_display_format' => array(
						'id'	  		=> 'wf_estimated_delivery_date_display_format',
						'type'			=> 'select',
						'css' 			=> 'padding: 0px;',
						'name'   		=> __( 'Date Display', 'wf_estimated_delivery'),
						'desc'			=> __( 'Select the required date format from the drop-down list.','wf_estimated_delivery'),
						'desc_tip'		=> true,
						'options' 		=>array(
											'd/m/Y'	=>	'DD/MM/YYYY',
											'Y/m/d'	=>	'YYYY/MM/DD',
											'm/d/Y'	=>	'MM/DD/YYYY',
											'd-m-Y'	=>	'DD-MM-YYYY',
											'Y-m-d'	=>	'YYYY-MM-DD',
											'm-d-Y' =>  'MM-DD-YYYY',
											'd.m.Y'	=>	'DD.MM.YYYY',
											'Y.m.d'	=>	'YYYY.MM.DD',
											'm.d.Y'	=>	'MM.DD.YYYY',
											'd M Y'	=>	'DD MON YYYY',
											'F j'	=>	'MON DD',
											'F j, Y'=>	'MON DD, YYYY'
										)
						 
					),
					'min_delivery_days'	=>	array(
						'type'	 		=> 'text',
						'id'	   		=> 'wf_estimated_delivery_min_delivery_days',
						'name'	 		=> __( 'Minimum Delivery Days', 'wf_estimated_delivery' ),
						'desc'	 		=> __( 'Enter the minimum number of days for the delivery of all the products.','wf_estimated_delivery'),
						'desc_tip'		=> true,
						'default'  		=> '0',
					),
					'additional_days_for_backorder_products'	=>	array(
						'type'	 		=> 'text',
						'id'	   		=> 'wf_estimated_delivery_additional_days_for_backorder_products',
						'name'	 		=> __( 'Adjustment for Back Ordered Products', 'wf_estimated_delivery' ),
						'desc'	 		=> __( 'The specified numer of days will be adjusted if product is backordered enabled.','wf_estimated_delivery'),
						'desc_tip'		=> true,
						'default'  		=> '0',
					),				
					'day_limits'		=>	array(
						'type'	 		=> 'day_limits',
						'id'	   		=> 'wf_estimated_delivery_day_limits',
						'desc_tip'		=> true,
						'value' 		=> 'abc',
						'default'  		=> 'Monday',
					),
					'operation_days' => array(
						'type'	  => 'multiselect',
						'css' 	  => 'padding: 0px;',
						'id'	  => 'wf_estimated_delivery_operation_days',
						'name'	  => __( 'Working Days', 'wf_estimated_delivery' ),
						'desc'	  => __( 'Select the working days of the week and according to these days the date of delivery can be estimated.','wf_estimated_delivery' ),
						'class'   => 'chosen_select',
						'desc_tip'		=> true,
						'default' => array('mon','tue','wed','thu','fri'), //If changed here chage abstract-class-calc-est-stratergy.php also.
						'options' => array(
							'mon'  => __('Monday','wf_estimated_delivery' ),
							'tue'  => __('Tuesday','wf_estimated_delivery' ),
							'wed'  => __('Wednesday','wf_estimated_delivery' ),
							'thu'  => __('Thursday','wf_estimated_delivery' ),
							'fri'  => __('Friday','wf_estimated_delivery' ),
							'sat'  => __('Saturday','wf_estimated_delivery' ),
							'sun'  => __('Sunday','wf_estimated_delivery' ),
						)
					),
					'operation_delay'		=> array(
						'id'   			=> 'wf_estimated_delivery_operation_delay',
						'title'		   	=> __( 'Start from Next Working Day', 'wf_estimated_delivery' ),
						'name'   	=> __( 'Calculation Based On', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'desc'	 		=> __( 'Enable', 'wf_estimated_delivery').'<small><br/>'.__('Calculated based on consecutive working days of the week. For example, if the order date is 6/1/2017 (Thursday), next day is not holiday and estimated day is 3, then the delivery date will be 6/5/2017 (Monday), based on time zone.','wf_estimated_delivery' ).'</small>',
					),
					'calculation_mode' => array(
						'id'	   	=> 'wf_estimated_delivery_calculation_mode',
						'type'		=> 'select',
						'css' 			=> 'padding: 0px;',
						'name'   	=> __( 'Calculation Mode', 'wf_estimated_delivery' ),
						'class'		=> 'chosen_select',
						'desc_tip'	=> true,
						'options' 	=> array(
							'holiday_for_shop' 	=> __('Consider holiday For Shipper Only','wf_estimated_delivery' ),
							'holiday_for_shop_dest' 	=> __('Consider holiday for Shipper and Recipient','wf_estimated_delivery' )
						)
					),
					'show_on_items'		=> array(
						'title'		   	=> __( 'Display for every Item in Cart/Checkout', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __('Enable <br/><small>Display the Estimated Delivery Date of each item in cart and checkout page.</small>', 'wf_estimated_delivery'),
						'id'   			=> 'wf_estimated_delivery_show_on_items',
					),
					'show_on_shop'		=> array(
						'title'		   	=> __( 'Display on Shop', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __('Enable <br/><small>Display the Estimated Delivery Date of each item in Shop page, Search page and on Suggested items.</small>', 'wf_estimated_delivery'),
						'id'   			=> 'wf_estimated_delivery_show_on_shop',
					),
					'show_on_product_page'	=> array(
						'title'		   	=> __( 'Display on Product Page', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'yes',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __('Enable <br/><small>Display the Estimated Delivery Date on Individual Product Page for In-Stock Products.</small>', 'wf_estimated_delivery'),
						'id'   			=> 'wf_estimated_delivery_show_on_product_page',
					),
					'plain_text_mode'	=> array(
						'title'		   	=> __( 'Plain Text Mode', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __( "Enable <br/><small>Enabling this option will remove all HTML Tags from Product Page Text Field.</small>", 'wf_estimated_delivery'),
						'id'   			=> 'ph_estimated_delivery_plain_text_mode',
					),
					'show_on_all_product_page'	=> array(
						'title'		   	=> __( 'Display on All Products', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __( "Enable <br/><small>Display the Estimated Delivery Date on Product page regardless of Product Stock Status.</small>", 'wf_estimated_delivery'),
						'id'   			=> 'wf_estimated_delivery_show_on_all_product_page',
					),
					'consider_non_wroking_days_in_transit'		=> array(
						'title'		   	=> __( 'Consider Non Working Days in Transit Time', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __('Enable', 'wf_estimated_delivery' ).'<br/><small>'.__('To Consider Non Working days, Holidays in Transit Time.', 'wf_estimated_delivery' ).'</small>',
						'custom_attributes'=> array(
							'autocomplete'=> 'off'),
						'id'   			=> 'wf_estimated_delivery_consider_non_wroking_days_in_transit',
					),
					'per_package_est_delivery'	=>	array(
						'title'			=>	__( 'Estimated Delivery Per Package', 'wf_estimated_delivery' ),
						'type'			=>	'checkbox',
						'desc'			=>	__( 'Enable <br><small><i>It will display Estimated Delivery Date for every package along with the shipping methods (The common Estimated Delivery Date will not be displayed).</i></small>','wf_estimated_delivery' ),
						'id'			=>	'wf_estimated_delivery_per_package_est_delivery',
					),
					'per_package_est_delivery_text'	=>	array(
						'title'			=>	__( 'Per Package Delivery Date Text', 'wf_estimated_delivery' ),
						'type'			=>	'textarea',
						'placeholder'	=>	'<br><small>'.__( 'Est. Delivery', 'wf_estimated_delivery' ).': [EST_DEL_DATE]</small>',
						'default'		=>	'<br><small>'.__( 'Est. Delivery', 'wf_estimated_delivery' ).': [EST_DEL_DATE]</small>',
						'desc'			=>	__( 'Enter the text to use in Estimated Delivery with Shipping Methods. Supported Tags - [EST_DEL_DATE].', 'wf_estimated_delivery'),
						'desc_tip'		=>	true,
						'id'			=>	'wf_estimated_delivery_per_package_est_delivery_text',
						'css'			=>	'height:31px'
					),
					'position_on_cart_and_checkout'	=>	array(
						'title'		=>	__( 'Position for Estimated Delivery on Cart & Checkout', 'wf_estimated_delivery' ),
						'type'		=>	'select',
						'options'	=>	array(
							'before_shipping'	=>	__( 'Above the Shipping Field', 'wf_estimated_delivery' ),
							'after_shipping'	=>	__( 'Below the Shipping Field', 'wf_estimated_delivery' )
						),
						'id'		=>	'wf_estimated_delivery_position_on_cart_and_checkout'
					),
					'record_log'		=> array(
						'title'		   	=> __( 'Record Log', 'wf_estimated_delivery' ),
						'type'			=> 'checkbox',
						'default'		=> 'no',
						'name'		 	=> __( 'Enable', 'wf_estimated_delivery' ),
						'desc'	 		=> __('Enable', 'wf_estimated_delivery' ).'<br/><small>'.__('To debug the problem, select the checkbox to record log which gets generated in folder wordpress\wp-content\uploads\wc-logs. Here, you can check the estimation input and result pair.', 'wf_estimated_delivery' ).'</small>',
						'custom_attributes'=> array(
							'autocomplete'=> 'off'),
						'id'   			=> 'wf_estimated_delivery_record_log',
					),
					
					'date_options_sectionend'	=>	array(
						'type' => 'sectionend',
						'id'   => 'wf_estimated_delivery_date_options_sectionend'
					),			
				) );
				break;		
			case 'wf_shipping_class':
				$settings = apply_filters( 'wf_estimated_delivery_section3_settings', array(
					'shipping_class_options_title'	=>	array(
						'name'	=> __( 'Shipping Class', 'wf_estimated_delivery' ),
						'type'	=> 'title',
						'desc'	=> '',
						'id'	=> 'wf_estimated_delivery_shipping_class_options_title',
						'value'	=> __( 'Shipping Class', 'wf_estimated_delivery' ),
					),	
					'shipping_class'	=>	array(
						'type'	=> 'shipping_class',
						'id'	=> 'wf_estimated_delivery_shipping_class',
						'value'	=> '',
					),			
					'shipping_class_options_sectionend'	=>	array(
						'type'	=> 'sectionend',
						'id'	=> 'wf_estimated_delivery_shipping_class_options_sectionend',
						'value'	=> '',
					),			
				) );
				
				break;
			case 'wf_shipping_zone':
				$settings = apply_filters( 'wf_estimated_delivery_section4_settings', array(
					'shipping_zone_options_title'	=>	array(
						'name'	=> __( 'Shipping Zone', 'wf_estimated_delivery' ),
						'type'	=> 'title',
						'desc'	=> '',
						'id'	=> 'wf_estimated_delivery_shipping_zone_options_title',
						'value'	=> __( 'Shipping Zone', 'wf_estimated_delivery' ),
					),	
					'shipping_zone'	=>	array(
						'type'	=> 'shipping_zone',
						'id'	=> 'wf_estimated_delivery_shipping_zone',
						'value'	=> '',
						),			
					'shipping_zone_options_sectionend'	=>	array(
						'type'	=> 'sectionend',
						'id'	=> 'wf_estimated_delivery_shipping_zone_options_sectionend',
						'value'	=> '',
					),			
				) );
				break;
			case 'wf_shipping_methods':
				$settings = apply_filters( 'wf_estimated_delivery_section5_settings', array(
					'shipping_methods_options_title'	=>	array(
						'name'	=> __( 'Shipping methods', 'wf_estimated_delivery' ),
						'type'	=> 'title',
						'desc'	=> '',
						'id'	=> 'wf_estimated_delivery_shipping_methods_options_title',
						'value'	=> __( 'Shipping methods', 'wf_estimated_delivery' ),
					),	
					'shipping_methods'	=>	array(
						'type'	=> 'shipping_methods',
						'id'	=> 'wf_estimated_delivery_shipping_methods',
						'value'	=> '',
						),			
					'shipping_methods_options_sectionend'	=>	array(
						'type'	=> 'sectionend',
						'id'	=> 'wf_estimated_delivery_shipping_methods_options_sectionend',
						'value'	=> '',
					),			
				) );
				break;
		}
		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );	
	}

	public function wf_estimated_delivery_output() {
		global $current_section;
		$settings = $this->wf_estimated_delivery_get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	public function wf_estimated_delivery_save() {   
		global $current_section;  
		$settings = $this->wf_estimated_delivery_get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );
	}

	//to add the necessary js scripts and css styles
	public function wf_estimated_delivery_admin_scripts() {
		
		wp_enqueue_script( 'wf-settingsAlign-script', plugins_url( '../assests/js/settings.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'wf-timepicker-script', plugins_url( '../assests/js/jquery.timepicker.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_style( 'wf-timepicker-style', plugins_url( '../assests/css/jquery.timepicker.css', __FILE__ ) );
	}	
	public function wf_estimated_delivery_scripts() {
		if(is_checkout()&&!is_order_received_page()){
			wp_enqueue_script( 'wf-checkout-script', plugins_url( '../assests/js/checkout.js', __FILE__ ), array( 'jquery' ) );
		}	
	}
	public function generate_holiday_html() {
		include( 'html-holiday.php' );
	}

	public function generate_day_limits_html() {
		$this->day_limits			= get_option( 'wf_estimated_delivery_day_limits' );

		?>
		<tr valign="top">

		<th scope="row" class="titledesc"><?php _e( 'Shipping Times', 'wf_estimated_delivery' ); ?> <span class="woocommerce-help-tip" data-tip="Set the time limit for the days of the week."></span></th>
		<td class="forminp" id="bacs_accounts">
		<table class="widefat wc_input_table sortable" cellspacing="0">
			<thead>
				<tr>
					<th><?php _e( 'Monday', 'wf_estimated_delivery' ); ?></th>
					<th><?php _e( 'Tuesday', 'wf_estimated_delivery' ); ?></th>
					<th><?php _e( 'Wednesday', 'wf_estimated_delivery' ); ?></th>
					<th><?php _e( 'Thursday', 'wf_estimated_delivery' ); ?></th>
					<th><?php _e( 'Friday', 'wf_estimated_delivery' ); ?></th>
					<th><?php _e( 'Saturday', 'wf_estimated_delivery' ); ?></th>
					<th><?php _e( 'Sunday', 'wf_estimated_delivery' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[0]) ? $this->day_limits[0] : '20:00' ?>"/></td>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[1]) ? $this->day_limits[1] : '20:00' ?>"/></td>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[2]) ? $this->day_limits[2] : '20:00' ?>"/></td>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[3]) ? $this->day_limits[3] : '20:00' ?>"/></td>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[4]) ? $this->day_limits[4] : '20:00' ?>"/></td>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[5]) ? $this->day_limits[5] : '20:00' ?>"/></td>
					<td><input  name="wf_estimated_delivery_day_limits[]" type="text" placeholder="20:00" value="<?php echo !empty($this->day_limits[6]) ? $this->day_limits[6] : '20:00' ?>"/></td>
				</tr>
			</tbody>
		</table>
		</td>
		</tr>
		<?php
	}

	public function generate_shipping_class_html() {
		include( 'html-shipping-class.php' );
	}
	public function generate_advanced_html() {
		include( 'html-advanced-settings.php' );
	}
	public function generate_shipping_zone_html() {
		include( 'html-shipping-zone.php' );
	}
	public function generate_shipping_methods_html() {
		include( 'html-shipping-methods.php' );
	}
	public function wf_estimated_delivery_this_screen() {
		$currentScreen = get_current_screen();
		if($currentScreen->id=='woocommerce_page_wc-settings'){
		
		add_action( 'admin_enqueue_scripts', array( $this, 'wf_estimated_delivery_admin_scripts' ) );
		}
	}

	/**
	 * Function to remove the shipping class rules while saving the shipping class rules, if rule doesn't contain shipping class i.e. contain only min_date.
	 * @param $shipping_class_rules_arr_to_save array Array of shipping class rules.
	 * @return array Array of shipping class rules.
	 */
	public function ph_remove_shipping_class_rule_in_settings( $shipping_class_rules_arr_to_save ) {
		foreach( $shipping_class_rules_arr_to_save as $key => $rule ) {
			if( ! isset($rule['id']) ) {
				unset($shipping_class_rules_arr_to_save[$key]);
			}
		}
		return $shipping_class_rules_arr_to_save;
	}
	
}