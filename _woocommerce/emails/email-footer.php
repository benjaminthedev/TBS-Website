<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
															</div>
														</td>
													</tr>
												</table>
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
							<tr>
								<td align="center" valign="top">
									<!-- Footer -->
									<table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">
										<tr>
											<td valign="top">
												<table border="0" cellpadding="10" cellspacing="0" width="100%">
													<tr>
														<td colspan="2" valign="middle" id="credit">
															<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<!-- End Footer -->
								</td>
							</tr>
						</table>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
							<tr>
								<td>
									<a href="https://twitter.com/thebeautystore" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/twitter.jpg" width="207" height="211" alt="twitter" style="display:block; border:none" border="0"></a>
								</td>
								<td>
									<a href="https://www.instagram.com/thebeautystoreuk/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/instagram.jpg" alt="Instagram" width="190" height="211" style="display:block; border:none" border="0"></a>
								</td>
								<td>
									<a href="https://www.facebook.com/TheBeautyStoreUK" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/facebook.jpg" width="203" height="211" alt="facebook" style="display:block; border:none" border="0"></a>
								</td>
							</tr>
						</table>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
							<tr>
								<td>
									<a href="https://www.trustpilot.com/review/www.thebeautystore.co.uk" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/trustpilot.jpg" width="300" height="84" alt="trust pilot" style="display:block; border:none" border="0"></a>
								</td>
								<td>
									<a href="https://www.google.com/shopping/seller?q=thebeautystore.co.uk&amp;hl=en_GB" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/google-certified.jpg" width="300" height="84" alt="google certified shop" style="display:block; border:none" border="0"></a>
								</td>
							</tr>
						</table>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1">
							<tr>
								<td>
									<a href="https://www.thebeautystore.co.uk/returns-refunds/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/returns.jpg" width="198" height="140" alt="free tracked delivery" style="display:block; border:none" border="0"></a>
								</td>
								<td>
									<a href="https://www.thebeautystore.co.uk/delivery-information/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/delivery.jpg" width="102" height="140" alt="free worldwide delivery over 25" style="display:block; border:none" border="0"></a>
								</td>
								<td>
									<a href="https://www.thebeautystore.co.uk/about-us/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/genuine-products.jpg" width="89" height="140" alt="100% genuine products" style="display:block; border:none" border="0"></a>
								</td>
								<td>
									<a href="https://www.thebeautystore.co.uk/discounts-offers/" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/woocommerce/emails/images/free-samples.jpg" width="211" height="140" alt="free samples with orders over 25" style="display:block; border:none" border="0"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
