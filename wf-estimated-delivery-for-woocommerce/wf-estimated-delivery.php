<?php
/**
 * @package Estimated Delivery Date Plugin For WooCommerce
 */
/*
Plugin Name: Estimated Delivery Date Plugin For WooCommerce
Plugin URI: https://www.pluginhive.com/product/estimated-delivery-date-plugin-woocommerce/
Description: Intuitive order delivery date plugin using which you can set delivery dates for your orders based on shipping class, zones and host of other features.
Version: 1.8.1
Author: PluginHive
Author URI: https://www.pluginhive.com/about/
License: GPLv2
WC requires at least: 2.6.0
WC tested up to: 4.1.1
Text Domain: wf_estimated_delivery
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	

// Define PH_ESTIMATED_DELIVERY_PLUGIN_VERSION
if ( ! defined( 'PH_ESTIMATED_DELIVERY_PLUGIN_VERSION' ) )
	define( 'PH_ESTIMATED_DELIVERY_PLUGIN_VERSION', '1.8.1' );

// Include API Manager
if ( !class_exists( 'PH_Estimated_Delivery_API_Manager' ) ) {

	include_once( 'ph-api-manager/ph_api_manager_estimated_delivery.php' );
}

if ( class_exists( 'PH_Estimated_Delivery_API_Manager' ) ) {

	$estimated_delivery_api_obj 	= new PH_Estimated_Delivery_API_Manager( __FILE__, '', PH_ESTIMATED_DELIVERY_PLUGIN_VERSION, 'plugin', 'https://www.pluginhive.com/', 'Estimated Delivery Date' );
}

// Check if WooCommerce exists
if ( !class_exists( 'woocommerce' ) ) {

	add_action( 'admin_init', 'ph_estimated_delivery_date_plugin_deactivate' );

	if ( ! function_exists( 'ph_estimated_delivery_date_plugin_deactivate' ) ) {
		function ph_estimated_delivery_date_plugin_deactivate() {

			if ( !class_exists( 'woocommerce' ) ) {
				
				deactivate_plugins( plugin_basename( __FILE__ ) );
				wp_safe_redirect( admin_url('plugins.php') );
			}
		}
	}
}

if( ! class_exists('Ph_Estimated_Delivery_Common') ) {
	require_once 'class-ph-estimated-delivery-common.php';
}

function xa_activation_check(){
	//check if basic version is there
	if ( is_plugin_active('estimated-delivery-woocommerce/wf-estimated-delivery.php') ){
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( __("Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete <b>Estimated Delivery Date for Woocommerce (Basic)</b> and then try again", "wf-easypost" ), "", array('back_link' => 1 ));
	}
}
register_activation_hook( __FILE__, 'xa_activation_check' );

if( Ph_Estimated_Delivery_Common::woocommerce_active_check() ) {

	//Class - To setup the plugin
	if( !class_exists('Wf_Estimated_Delivery_Setup') ){
		class Wf_Estimated_Delivery_Setup {
			//constructor
			public function __construct() {
				$this->wf_estimated_delivery_init();
				add_action( 'wp_enqueue_scripts', array( $this, 'ph_estimated_delivery_scripts' ) );
				// Without is_admin check it's being conflicted with Yoast Seo on page=wpseo_configurator
				if( is_admin() ) {
					add_filter( 'woocommerce_get_settings_pages',array($this, 'wf_estimated_delivery_initialize') );
					add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'wf_estimated_delivery_plugin_action_links' ) );
				}
			}

			public function ph_estimated_delivery_scripts(){
				wp_enqueue_style( 'ph_estimated_delivery_style', plugins_url( '/assests/css/ph_est_delivery_style.css', __FILE__ ));
			}
			public function wf_get_settings_url(){
				return version_compare(WC()->version, '1.0', '>=') ? "wc-settings" : "woocommerce_settings";
			}

			//to add settings url near plugin under installed plugin
			public function wf_estimated_delivery_plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=' . $this->wf_get_settings_url() . '&tab=wf_estimated_delivery' ) . '">' . __( 'Settings', 'wf_estimated_delivery' ) . '</a>',
					'<a href="https://www.pluginhive.com/knowledge-base/category/estimated-delivery-date-plugin-for-woocommerce/" target="_blank">' . __( 'Documentation', 'wf_estimated_delivery' ) . '</a>',
					'<a href="https://www.pluginhive.com/support/" target="_blank">' . __( 'Support', 'wf_estimated_delivery' ) . '</a>',

				);
				return array_merge( $plugin_links, $links );
			} 

			public function wf_estimated_delivery_initialize($settings = array()){
				if( ! class_exists('Wf_Estimated_Delivery_Settings') )
					include_once( 'includes/class-wf-estimated-delivery-settings.php' );
				$settings[] = new Wf_Estimated_Delivery_Settings();
				return $settings;
			}
			//to include the necessary files for plugin
			public function wf_estimated_delivery_init() {

				load_plugin_textdomain( 'wf_estimated_delivery', false, dirname( plugin_basename( __FILE__ ) ) .'/lang/');
				
				include_once( 'includes/class-wf-estimated-delivery.php' );
				include_once 'includes/class-xa-estimated-delivery-external-plugin-support.php';
				include_once( 'includes/log.php' );
			}		
		}

		new Wf_Estimated_Delivery_Setup();
	}
}