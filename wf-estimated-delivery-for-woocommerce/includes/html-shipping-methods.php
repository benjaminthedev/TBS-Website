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
        
<table id="estimate_delivery_shipping_method_table" class="wp-list-table widefat fixed posts">
    <thead>
        <tr>
            <th class="check-column" style="padding: 0px; vertical-align: middle;"><input type="checkbox" /></th>
            <th>
                <?php
                    _e( 'Shipping Methods', 'wf_estimated_delivery' );
                    echo '<span class="ph-tooltip"><img src="'.$tool_tip_icon.'" height="16" width="16" /><span class="ph-tooltiptext">Use * to apply on all Shipping Methods.</span></span>'
                ?>
            </th>
            <th><?php _e('No. of Days', 'wf_estimated_delivery'); ?></th>
            <th>
                <?php
                    _e('No Delivery On', 'wf_estimated_delivery'); 
                    echo '<span class="ph-tooltip"><img src="'.$tool_tip_icon.'" height="16" width="16" /><span class="ph-tooltiptext">Delivery on the following Days will not be available for the specified Shipping Methods.</span></span>'
                ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        $this->shipping_methods_table   = array();
	    $this->shipping_methods	        = get_option( 'wf_estimated_delivery_shipping_methods' );

        if( empty($this->shipping_methods) ) {

                $this->shipping_methods_table[0]['methods_names'] = '';
                $this->shipping_methods_table[0]['min_date'] = '';
                $this->shipping_methods_table[0]['non_working_days'] = array();
        }else{
            foreach ( $this->shipping_methods as $id => $value ) {
                if( !empty($value['methods_names']) && ( !empty($value['min_date']) || !empty($value['non_working_days']) ) ) {

                    $this->shipping_methods_table[$id]['methods_names']     = $value['methods_names'];
                    $this->shipping_methods_table[$id]['min_date']          = $value['min_date'];
                    $this->shipping_methods_table[$id]['non_working_days']  = ( isset($value['non_working_days']) && !empty($value['non_working_days']) ) ? $value['non_working_days'] : array();
                }
            }

            update_option( 'wf_estimated_delivery_shipping_methods', $this->shipping_methods_table );
        }

        foreach ( $this->shipping_methods_table as $key => $value ){
            ?>
            <tr>
                <td class="check-column" style="padding: 9px; vertical-align: middle;">
                    <input type="checkbox" />
                </td>
                <td>
                    <input type="text" size="50" placeholder="Eg: flat_rate:1,wf_fedex_woocommerce_shipping:FEDEX_GROUND" name="wf_estimated_delivery_shipping_methods[<?php echo $key; ?>][methods_names]" value="<?php echo isset( $this->shipping_methods_table[ $key ]['methods_names'] ) ? $this->shipping_methods_table[ $key ]['methods_names'] : ''; ?>"/>
                </td>
                <td >
                    <input type="text" name="wf_estimated_delivery_shipping_methods[<?php echo $key; ?>][min_date]" value="<?php echo isset( $this->shipping_methods_table[ $key ]['min_date'] ) ? $this->shipping_methods_table[ $key ]['min_date'] : ''; ?>"/>
                </td>
                <td >
                <select class="wc-enhanced-select ph_est_shipping_method_non_working_days" multiple="multiple" style="width: 70%;" name="wf_estimated_delivery_shipping_methods[<?php echo $key ?>][non_working_days][]" >
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
            </tr><?php
        }?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">
                <a href="#" class="button plus insert"><?php _e( 'Add Method', 'wf_estimated_delivery' ); ?></a>
                <a href="#" class="button minus remove"><?php _e( 'Remove Method(s)', 'wf_estimated_delivery' ); ?></a>
            </th>
        </tr>
    </tfoot>
     <div>
        <p><?php _e('Set delivery days for Woocommerce Shipping methods.','wf_estimated_delivery'); ?>
        </p>
    <div>
</table>


<script type="text/javascript">
    jQuery(window).load(function(){
        jQuery('#estimate_delivery_shipping_method_table .insert').click( function() {
            var tbody = jQuery('#estimate_delivery_shipping_method_table').find('tbody');
            var size = tbody.find('tr').length;
            var code = '<tr>\
                            <td class="check-column" style="padding: 9px; vertical-align: middle;">\
                                <input type="checkbox" />\
                            </td>\
                           <td>\
                               <input type="text" placeholder="Eg: flat_rate:1,wf_fedex_woocommerce_shipping:FEDEX_GROUND" size="50" name="wf_estimated_delivery_shipping_methods[' + size + '][methods_names]" value=""/>\
                           </td>\
                            <td>\
                                <input type="text" name="wf_estimated_delivery_shipping_methods['+ size +'][min_date]" value=""/>\
                            </td>\
                            <td>\
                            <select class="wc-enhanced-select multiselect ph_est_shipping_method_non_working_days" multiple="multiple" style="width: 70%;" name="wf_estimated_delivery_shipping_methods['+ size +'][non_working_days][]" >\
                                    <?php
                                    foreach( $weekdays as $weekday_key => $weekday_name ) {
                                        echo "<option value=".$weekday_key.">\ $weekday_name</option>\ ";
                                    }
                                    ?>
                                </select>\
                            </td>\
                        </tr>';
            tbody.append( code );

            jQuery('.multiselect').select2();

            return false;
        } );

        jQuery('#estimate_delivery_shipping_method_table .remove').click(function() {
                var tbody = jQuery('#estimate_delivery_shipping_method_table').find('tbody');
                tbody.find('.check-column input:checked').each(function() {
                        jQuery(this).closest('tr').hide().find('input').val('');
                });
                return false;
        });
        jQuery(window).load(function(){
            var tbody = jQuery('#estimate_delivery_shipping_method_table').find('tbody');
            var size = tbody.find('tr').length;
            if(size<1)
            {

            }
        });
        jQuery(function(){
            jQuery(".wf_date").datepicker();
        });
    });
</script>