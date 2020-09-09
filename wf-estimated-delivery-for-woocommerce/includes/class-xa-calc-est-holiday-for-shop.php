<?php 
include_once('abstract-class-calc-est-stratergy.php');
class XA_Calc_Est_Strategy_Holiday_for_Shop extends XA_Calc_Est_Strategy {
	
	/**
	 * Consider Non Working days in transit time.
	 */
	public static $consider_non_working_days_in_transit_time;

	/***
	 * Get Delivery Date.
	 */
	public function wf_get_delivery_date($date, $product = null, $order_id = "",$shipping_method='') {

		if( $date < 0 )
		{
			$date = 0;
		}
		
		$this->dafault_days = $date;	
		$this->xa_write_log($this->dafault_days);		

		$cutoff_time = $this->xa_get_cutoff_time( $this->xa_get_current_day() );

		$starting_date = $this->xa_get_staring_date( $cutoff_time );

		$result_date = $this->find_nearest_working_day( $starting_date );
		
		//Add Minimum Delivery Days.
		if( empty(self::$consider_non_working_days_in_transit_time) )
			self::$consider_non_working_days_in_transit_time = get_option('wf_estimated_delivery_consider_non_wroking_days_in_transit', 'no');
		if( self::$consider_non_working_days_in_transit_time == 'yes' ){
			$no_of_days = $this->dafault_days;
			while( ! empty($no_of_days) ) {
				$no_of_days--;
				$result_date = $result_date->modify("+1 day");
				if(! empty($no_of_days) )		// Leave Last Day
					$result_date = $this->find_nearest_working_day( $result_date );
			}
		}
		else {
			$result_date = $result_date->modify("+$this->dafault_days day");
		}
		// Get Estimated delivery based on Shipping class, Shipping Zone and Shipping method working days
		$result_date = $this->get_shipping_method_delivery_date( $result_date, $order_id, $product,$shipping_method );

		$this->xa_write_log( $result_date->format( $this->delivery_date_display_format ), 'est_date' );
		
		$result_date = apply_filters( 'xa_estimated_delivery_date', $result_date, $product );
		return $result_date;
	}
}