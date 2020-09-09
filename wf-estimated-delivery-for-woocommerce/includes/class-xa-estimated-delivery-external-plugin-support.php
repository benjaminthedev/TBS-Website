<?php

/**
 * File to Support the external plugin compatibility, like Woocommerce Subscription Plugin.
 */

if( ! class_exists('Xa_Estimated_Delivery_External_Plugin_Support') ) {
	class Xa_Estimated_Delivery_External_Plugin_Support {

		/**
		 * Constructor of Xa_Estimated_Delivery_External_Plugin_Support class
		 */
		public function __construct() {

			// To Support Woocommerce Subscription Plugin
			add_filter( 'wcs_renewal_order_created', array($this,'xa_woocommerce_subscription_renewal_order') );
		}

		/**
		 * Function to update the Estimated delivery in renewal order created by Woocommerce Subscription plugin automatically while
		 * created on the next payment.
		 * @param $renewal_order object WC_Order Object.
		 * @return object WC_Order Object.
		 */
		public function xa_woocommerce_subscription_renewal_order( $renewal_order ) {

			if( $renewal_order instanceof WC_Order ) {
				$order_items = $renewal_order->get_items();
				// Trigger only if there are some items in the renewal order
				if( ! empty($order_items) ) {
					$wf_estimated_delivery = new Wf_Estimated_Delivery;				// class Wf_Estimated_Delivery
					$renewal_order->delete_meta_data('_est_date');					// Delete estimated delivery which has been copied from parent order.
					$wf_estimated_delivery->wf_add_est_delivery_to_order_meta( $renewal_order );	// It will add new estimated delivery to the renewal order.
				}
			}
			return $renewal_order;
		}

	}	// End of class Xa_Estimated_Delivery_External_Plugin_Support

	new Xa_Estimated_Delivery_External_Plugin_Support;

}