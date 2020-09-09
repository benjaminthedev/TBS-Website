

<?php
    $test                                   = new WC_Shipping;
    // All shipping Classes
    $available_shipping_classes             = $test->get_shipping_classes();
    $available_shipping_classes_list        = array('ph_any_shipping_class'=>'Any Shipping Class');

    foreach($available_shipping_classes as $shipping_class)
    {
        $available_shipping_classes_list[$shipping_class->slug] = $shipping_class->name;
    }
    
    // Shipping Class Slug Name Pair
    $all_shipping_classes_slug_name_pair    = array();
    // Saved data
    $shipping_class_info                    = get_option('wf_estimated_delivery_shipping_class');

    if( ! is_array($shipping_class_info ) ) {
        $shipping_class_info = array();
    }

     $non_working_days = array();

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
    <tr>
       <th style="width: 50%;"><?php _e('Shipping Class', 'wf_estimated_delivery'); ?></th>
        <th ><?php _e('No. of Days', 'wf_estimated_delivery'); ?></th>
         <th>
            <?php
            _e('No Delivery On', 'wf_estimated_delivery'); 
            echo '<span class="ph-tooltip"><img src="'.$tool_tip_icon.'" height="16" width="16" /><span class="ph-tooltiptext">Delivery on the following Days will not be available for the specified Shipping Class.</span></span>';
            ?>
        </th>
    </tr>
    <?php

    $rule_count = 0;
    // To print the saved data
    foreach( $shipping_class_info as $saved_shipping_class ) {
        // if rule is saved without shipping class then don't display it, is_string($saved_shipping_class['id']) && empty($saved_shipping_class['min_date']) for backward compatibility 1.5.7.8
        if( empty($saved_shipping_class['id']) || ( is_string($saved_shipping_class['id']) && empty($saved_shipping_class['min_date']) ) ) {
            continue;
        }

        // For Backward Compatibility before 1.5.7.8
        if( is_string($saved_shipping_class['id']) ) {
            $saved_shipping_class['id'] = array($saved_shipping_class['id']);
        }
        // End of Backward Compatibilty
        ?>
        <tr>
             <td style="width: 50%; text-align: left;">
                <select class="wc-enhanced-select xa_est_shipping_class" multiple="multiple" style="width: 100%;" name="wf_estimated_delivery_shipping_class[<?php echo $rule_count ?>][id][]" >
                    <?php
                        foreach( $available_shipping_classes_list as $slug => $shipping_class ) {
                            if( in_array($slug, $saved_shipping_class['id']) ) {
                                echo "<option value='".$slug."' selected>".$shipping_class."</option>";
                            }
                            else {
                                echo "<option value='".$slug."'>".$shipping_class."</option>";
                            }
                        }
                    ?>
                </select>
            </td>
            <td>
                <input type="text" name="wf_estimated_delivery_shipping_class[<?php echo $rule_count ?>][min_date]" value="<?php echo $saved_shipping_class['min_date']; ?>">
            </td>
             <td >
                <select class="wc-enhanced-select ph_est_shipping_class_non_working_days" multiple="multiple" style="width: 100%;" name="wf_estimated_delivery_shipping_class[<?php echo $rule_count ?>][non_working_days][]" >
                    <?php
                    $non_working_days = isset( $saved_shipping_class['non_working_days'] ) ? $saved_shipping_class['non_working_days'] : '';
                    if( empty($non_working_days))
                    {
                      $non_working_days = array();
                    }
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
        <?php
        $rule_count++;
    }
    // End of printing saved data
    ?>

    <tr>
        <td style="width: 50%; text-align: left;">
            <select class="wc-enhanced-select xa_est_shipping_class" multiple="multiple" style="width: 100%;" name="wf_estimated_delivery_shipping_class[<?php echo $rule_count ?>][id][]" >
                <?php
                    foreach( $available_shipping_classes_list as $slug => $shipping_class ) {
                    echo "<option value='".$slug."'>".$shipping_class."</option>";
                }
                ?>
            </select>
        </td>
        <td>
            <input type="text" name="wf_estimated_delivery_shipping_class[<?php echo $rule_count ?>][min_date]" value="">
        </td>
        <td >
            <select class="wc-enhanced-select ph_est_shipping_class_non_working_days" multiple="multiple" style="width: 100%;" name="wf_estimated_delivery_shipping_class[<?php echo $rule_count ?>][non_working_days][]" >
                <?php
                foreach( $weekdays as $weekday_key => $weekday_name ) {
                    echo "<option value='".$weekday_key."'>".$weekday_name."</option>"; 
                }
                ?>
            </select>
        </td>
    </tr>
</table>