<table id="wf_estimated_delivery_holiday_table" class="wp-list-table widefat">
    <thead>
        <tr>
            <th class="check-column" style="padding: 0px; vertical-align: middle;"><input type="checkbox" /></th>
            <th><?php _e('Name', 'wf_estimated_delivery' ); ?></th>
            <th><?php _e('From', 'wf_estimated_delivery'); ?></th>
            <th><?php _e('To', 'wf_estimated_delivery'); ?></th>
        </tr>
    </thead>
    <tbody>

        <?php
        $this->holiday_table    = array();
        $this->holidays         = get_option( 'wf_estimated_delivery_holiday' );
        $this->holidays         = empty($this->holidays) ? array() : $this->holidays;

        for( $i=0; $i < count($this->holidays); $i++ ) {

            if( !empty($this->holidays[$i]['name']) && !empty($this->holidays[$i]['from'] ) && !empty($this->holidays[$i]['to'] ) ) {
                
                $this->holiday_table[$i]['name'] = $this->holidays[$i]['name'];
                $this->holiday_table[$i]['from'] = $this->holidays[$i]['from'];
                $this->holiday_table[$i]['to'] = $this->holidays[$i]['to'];
            }
        }
        if(count($this->holiday_table)>0)
        {
            foreach ( $this->holiday_table as $key => $value ) {
                ?>

                <tr>
                    <td class="check-column" style="padding: 9px; vertical-align: middle;">
                        <input type="checkbox" />
                    </td>
                    <td>
                        <input type="text" placeholder="Holiday Name"  class="validate" name="wf_estimated_delivery_holiday[<?php echo $key; ?>][name]" value="<?php echo isset( $this->holiday_table[ $key ]['name'] ) ? $this->holiday_table[ $key ]['name'] : ''; ?>"/>
                    </td>
                    <td>
                        <input type="text" placeholder="dd/mm/yyyy" class="wf_date validate" name="wf_estimated_delivery_holiday[<?php echo $key; ?>][from]" value="<?php echo isset( $this->holiday_table[ $key ]['from'] ) ? $this->holiday_table[ $key ]['from'] : ''; ?>" />
                    </td>
                    <td >
                        <input type="text" placeholder="dd/mm/yyyy" class="wf_date validate" name="wf_estimated_delivery_holiday[<?php echo $key; ?>][to]" value="<?php echo isset( $this->holiday_table[ $key ]['to'] ) ? $this->holiday_table[ $key ]['to'] : ''; ?>" />
                    </td>
                </tr>
                <?php $i++;
            }
        }else{   

            ?>
            <tr>
                <td class="check-column" style="padding: 9px; vertical-align: middle;">
                    <input type="checkbox" />
                </td>
                <td>
                    <input type="text"  class="validate" name="wf_estimated_delivery_holiday[0][name]" placeholder="Holiday Name" value=""/>
                </td>
                <td>
                    <input type="text"  class="wf_date validate" name="wf_estimated_delivery_holiday[0][from]" placeholder="dd/mm/yyyy" value="" />
                </td>
                <td >
                    <input type="text" class="wf_date validate" name="wf_estimated_delivery_holiday[0][to]" placeholder="dd/mm/yyyy" value="" />
                </td>
            </tr>
            <?php    
        }
        ?>
    </tbody>

    <tfoot>
        <tr>
            <th colspan="4">
                <a href="#" class="button plus insert"><?php _e( 'Add Holiday', 'wf_estimated_delivery' ); ?></a>
                <a href="#" class="button minus remove"><?php _e( 'Remove Holiday(s)', 'wf_estimated_delivery' ); ?></a>
            </th>
        </tr>
    </tfoot>

    <div>
        <p>
            <?php _e('You can mark holidays using the date range in this tab. For the particular date range, the plugin will not consider the days for estimation of delivery (For single day holiday, set same date for both from and to).', 'wf_estimated_delivery'); ?>

        </p>
    </div>
</table>

<style type="text/css">
    
    .empty_field
    {
        border: 2px solid red !important;
    }

</style>

<script type="text/javascript">

    jQuery(window).load(function(){

        jQuery('#wf_estimated_delivery_holiday_table .insert').click( function() {

            var tbody = jQuery('#wf_estimated_delivery_holiday_table').find('tbody');
            var size = tbody.find('tr').length;
            var code = '<tr>\
            <td class="check-column" style="padding: 9px; vertical-align: middle;">\
            <input type="checkbox" />\
            </td>\
            <td>\
            <input type="text" placeholder="<?php _e( 'Holiday Name', 'wf_estimated_delivery' ); ?>" class="validate" name="wf_estimated_delivery_holiday['+size+'][name]" value=""/>\
            </td>\
            <td>\
            <input type="text" placeholder="dd/mm/yyyy" class="wf_date validate" name="wf_estimated_delivery_holiday['+size+'][from]" value=""/>\
            </td>\
            <td >\
            <input type="text" placeholder="dd/mm/yyyy" class="wf_date validate" name="wf_estimated_delivery_holiday['+size+'][to]" value="" />\
            </td>\
            </tr>';

            tbody.append( code );

            jQuery('#wf_estimated_delivery_holiday_table .wf_date').datepicker({ dateFormat: 'dd/mm/yy' });

            return false;
        });

        jQuery('#wf_estimated_delivery_holiday_table .remove').click(function() {

            var tbody = jQuery('#wf_estimated_delivery_holiday_table').find('tbody');

            tbody.find('.check-column input:checked').each(function() {

                jQuery(this).closest('tr').hide().find('input').val('');

            });

            return false;

        });

        jQuery('#wf_estimated_delivery_holiday_table .time').datepicker({ dateFormat: 'dd/mm/yy' });

        jQuery(window).load(function() {

            var tbody   = jQuery('#wf_estimated_delivery_holiday_table').find('tbody');
            var size    = tbody.find('tr').length;

            if(size<1)
            {

            }

        });
        
        jQuery(function(){
            jQuery(".wf_date").datepicker({ dateFormat: 'dd/mm/yy' });
        });

        jQuery('.woocommerce-save-button').click(function(e) {

            jQuery('.validate').each(function(){

                var val         = jQuery(this).val();
                var isVisible   = jQuery(this).closest('tr').is(':visible');
            
                if( val =='' && isVisible )
                {
                    jQuery(this).addClass("empty_field");
                    e.preventDefault();
                }else{
                    jQuery(this).removeClass("empty_field");
                }

            });
            
        });

    });
</script>