<?php

$weekdays = array( 
        'Sun'   =>  __( 'Sunday', 'wf_estimated_delivery' ), 
        'Mon'   =>  __( 'Monday', 'wf_estimated_delivery' ),
        'Tue'   =>  __( 'Tuesday', 'wf_estimated_delivery' ),
        'Wed'   =>  __( 'Wednesday', 'wf_estimated_delivery' ),
        'Thu'   =>  __( 'Thursday', 'wf_estimated_delivery' ),
        'Fri'   =>  __( 'Friday', 'wf_estimated_delivery' ),
        'Sat'   =>  __( 'Saturday', 'wf_estimated_delivery' )
    );
$tool_tip_icon = plugins_url("woocommerce/assets/images/help.png");
?>

<style>
    /*Style for tooltip*/
    .ph-tooltip { position: relative; top: 3px; }
    .ph-tooltip .ph-tooltiptext { visibility: hidden; width: 150px; background-color: #333; color: #fff; text-align: center; border-radius: 6px; 
        padding: .618em 1em; font-size: .8em;
        /* Position the tooltip */
        position: absolute; z-index: 1;}
    .ph-tooltip:hover .ph-tooltiptext {visibility: visible;}
    /*End of tooltip styling*/
</style>

<table class="wp-list-table widefat fixed posts">
    <thead>
        <tr>
            <th><?php _e( 'Id', 'wf_estimated_delivery' ); ?></th>
            <th><?php _e('Shipping Zone', 'wf_estimated_delivery'); ?></th>
            <th><?php _e('No. of Days', 'wf_estimated_delivery'); ?></th>
            <th>
                <?php
                    _e('No Delivery On', 'wf_estimated_delivery'); 
                    echo '<span class="ph-tooltip"><img src="'.$tool_tip_icon.'" height="16" width="16" /><span class="ph-tooltiptext">Delivery on the following Days will not be available for the specified Shipping Zones.</span></span>';
                ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        $zone_obj       = new WC_Shipping_Zones;
        $shipping_zones = $zone_obj->get_zones();
        $this->shipping_zone_table = array();
	    $this->shipping_zone_dates = get_option( 'wf_estimated_delivery_shipping_zone' );
        $i=0;
        if(empty($this->shipping_zone_dates))
        {
            foreach ( $shipping_zones as $id => $value ) {
                $this->shipping_zone_table[$i]['id'] = $shipping_zones[$id]['zone_name'];
                $this->shipping_zone_table[$i]['name'] = $shipping_zones[$id]['formatted_zone_location'];
                $this->shipping_zone_table[$i]['min_date'] = '';
                $this->shipping_zone_table[$i]['non_working_days'] = array();
                $i++;
            }
        } else {
            $i=0;
            foreach ( $this->shipping_zone_dates as $id => $value ) {
                if(is_array($shipping_zones)){
                    foreach($shipping_zones as $zone_id => $zone_value) {
                        // For new added zone there will be no data available
                        if($value['id'] == $zone_value['zone_name'] && ! empty($this->shipping_zone_dates[$id]) )
                        {
                            $this->shipping_zone_table[$i]['id'] = $zone_value['zone_name'];
                            $this->shipping_zone_table[$i]['name'] = $zone_value['formatted_zone_location'];
                            $this->shipping_zone_table[$i]['min_date'] = $this->shipping_zone_dates[$id]['min_date'];
                            $this->shipping_zone_table[$i]['non_working_days'] = ! empty($this->shipping_zone_dates[$id]['non_working_days']) ? $this->shipping_zone_dates[$id]['non_working_days'] : array();
                            unset($shipping_zones[$zone_id]);
                            $i++;
                        }  
                    }
                } 
            }
            // For New added zones
            if( ! empty($shipping_zones) )
            {
                $i=0;
                foreach ( $shipping_zones as $id => $value ) {
                    $this->shipping_zone_table[$id]['id'] = $shipping_zones[$id]['zone_name'];
                    $this->shipping_zone_table[$id]['name'] = $shipping_zones[$id]['formatted_zone_location'];
                    $this->shipping_zone_table[$id]['min_date'] = '';
                    $this->shipping_zone_table[$id]['non_working_days'] = array();
                    $i++;
                }
            }
        }
        $i=0;
        foreach ( $this->shipping_zone_table as $key => $value ){?>
            <tr>
                <td>
                    <input type="text" readonly name="wf_estimated_delivery_shipping_zone[<?php echo $key; ?>][id]" value="<?php echo isset( $this->shipping_zone_table[ $key ]['id'] ) ? $this->shipping_zone_table[ $key ]['id'] : ''; ?>"/>
                </td>
                <td>
                    <input type="text" readonly name="wf_estimated_delivery_shipping_zone[<?php echo $key; ?>][name]" value="<?php echo isset( $this->shipping_zone_table[ $key ]['name'] ) ? $this->shipping_zone_table[ $key ]['name'] : ''; ?>" size="35"/>
                </td>
                <td >
                    <input type="text" name="wf_estimated_delivery_shipping_zone[<?php echo $key; ?>][min_date]" value="<?php echo isset( $this->shipping_zone_table[ $key ]['min_date'] ) ? $this->shipping_zone_table[ $key ]['min_date'] : ''; ?>"/>
                </td>
                <td >
                <select class="wc-enhanced-select ph_est_shipping_zone_non_working_days" multiple="multiple" style="width: 70%;" name="wf_estimated_delivery_shipping_zone[<?php echo $key ?>][non_working_days][]" >
                    <?php
                    $non_working_days = $value['non_working_days'];
                    foreach( $weekdays as $weekday_key => $weekday_name ) {
                        if( in_array($weekday_key, $non_working_days) ) {
                            echo "<option value='".$weekday_key."' selected>".$weekday_name."</option>";
                        }
                        else{
                            echo "<option value='".$weekday_key."'>".$weekday_name."</option>";
                        }
                    }
                    ?>
                </select>
                </td>
            </tr>
            <?php $i++;
        }
        ?>
    </tbody>
     <div>
        <p><?php _e('Set delivery days for Woocommerce Shipping Zones.', 'wf_estimated_delivery'); ?>
        </p>
    <div>
</table>