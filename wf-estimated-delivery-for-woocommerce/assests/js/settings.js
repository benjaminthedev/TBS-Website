jQuery(function(){
    jQuery( '#wf_estimated_delivery_day_select' ).on( 'change', function() 
    { 
        jQuery('[id^="wf_estimated_delivery_limit"]').closest('tr').hide();
        jQuery('#wf_estimated_delivery_limit_'+jQuery( this ).val()).closest('tr').show();
    }).change();
});


jQuery(function(){
    jQuery( '#wf_estimated_delivery_page_text_format' ).on( 'change', function() 
    { 
        if(jQuery('#wf_estimated_delivery_page_text_format').val()==="simple") {
            jQuery('#wf_estimated_delivery_product_page_text_range').closest('tr').hide();
            jQuery('#wf_estimated_delivery_lower_range').closest('tr').hide();
            jQuery('#wf_estimated_delivery_higher_range').closest('tr').hide();
            jQuery('#wf_estimated_delivery_product_page_text_simple').closest('tr').show();
        }
        else{
            jQuery('#wf_estimated_delivery_product_page_text_range').closest('tr').show();
            jQuery('#wf_estimated_delivery_lower_range').closest('tr').show();
            jQuery('#wf_estimated_delivery_higher_range').closest('tr').show();
            jQuery('#wf_estimated_delivery_product_page_text_simple').closest('tr').hide();
        }
    }).change();
});