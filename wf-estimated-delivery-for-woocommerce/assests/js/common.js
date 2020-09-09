jQuery(document).ready(function(){
// Toggle Estimated delivery related data
	ph_estimated_delivery_toggle_based_on_checkbox_status( '#wf_estimated_delivery_show_on_product_page', '#wf_estimated_delivery_show_on_all_product_page' );
	jQuery('#wf_estimated_delivery_show_on_product_page').click(function(){
		ph_estimated_delivery_toggle_based_on_checkbox_status( '#wf_estimated_delivery_show_on_product_page', '#wf_estimated_delivery_show_on_all_product_page' );
	});

	ph_estimated_delivery_toggle_based_on_checkbox_status( '#wf_estimated_delivery_show_on_product_page', '#ph_estimated_delivery_plain_text_mode' );
	jQuery('#wf_estimated_delivery_show_on_product_page').click(function(){
		ph_estimated_delivery_toggle_based_on_checkbox_status( '#wf_estimated_delivery_show_on_product_page', '#ph_estimated_delivery_plain_text_mode' );
	});
	
	// Toggle the Per Package Estimated Delivery Settings
	ph_estimated_delivery_toggle_based_on_checkbox_status( '#wf_estimated_delivery_per_package_est_delivery', '#wf_estimated_delivery_per_package_est_delivery_text' );
	ph_estimated_delivery_show_if_unchecked( '#wf_estimated_delivery_per_package_est_delivery', '#wf_estimated_delivery_position_on_cart_and_checkout' );
	jQuery('#wf_estimated_delivery_per_package_est_delivery').click(function(){
		ph_estimated_delivery_toggle_based_on_checkbox_status( '#wf_estimated_delivery_per_package_est_delivery', '#wf_estimated_delivery_per_package_est_delivery_text' );
		ph_estimated_delivery_show_if_unchecked( '#wf_estimated_delivery_per_package_est_delivery', '#wf_estimated_delivery_position_on_cart_and_checkout' );
	});

})

// Toggle based on checkbox status
function ph_estimated_delivery_toggle_based_on_checkbox_status( tocheck, to_toggle ){
	if( ! jQuery(tocheck).is(':checked') ) {
		jQuery(to_toggle).closest('tr').hide();
	}
	else{
		jQuery(to_toggle).closest('tr').show();
 	}
}

function ph_estimated_delivery_show_if_unchecked( tocheck, to_toggle ){
	if( ! jQuery(tocheck).is(':checked') ) {
		jQuery(to_toggle).closest('tr').show();
	}
	else{
		jQuery(to_toggle).closest('tr').hide();
 	}
}