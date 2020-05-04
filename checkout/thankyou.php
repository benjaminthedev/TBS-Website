<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="vlog_section">

    <div class="container-fluid">

        <div class="row">

            <div class="col-md-6">

                <div class="vlog_content">

                    <?php if ($order) : print_object($order, true); ?>
                        <?php if ($order->has_status('failed')) : ?>
                            <h1>Order Failed</h1>
                            <p><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce'); ?></p>
                        <?php else : ?>
                            <h1>Thank You</h1>
                            <h3 class="order">Your Order Number Is <?php echo $order->get_order_number(); ?></h3>
                            <p>You will shortly receive an email confirming your order, please keep your order number
                                handy
                                for reference. If you have any problems with your order or have any questions about
                                delivery
                                email our <a href="<?php the_field('contact_page', 'options') ?>">customer service
                                    team</a>
                            </p>
                        <?php endif; ?>
                    <?php else : ?>
                        <h1>Order Received</h1>
                    <?php endif; ?>

                </div>

            </div>

            <div class="col-md-6 video_wrap"
                 style="background-image: url(<?php echo get_image_dir_uri() . '/thankyou.jpg'; ?>)">

            </div>

        </div>

    </div>

</section>


<div class="woocommerce-order container">

    <?php if ($order) : ?>

        <?php if (!$order->has_status('failed')) : ?>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

                <li class="woocommerce-order-overview__order order">
                    <?php _e('Order number:', 'woocommerce'); ?>
                    <strong><?php echo $order->get_order_number(); ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date">
                    <?php _e('Date:', 'woocommerce'); ?>
                    <strong><?php echo wc_format_datetime($order->get_date_created()); ?></strong>
                </li>

                <li class="woocommerce-order-overview__total total">
                    <?php _e('Total:', 'woocommerce'); ?>
                    <strong><?php echo $order->get_formatted_order_total(); ?></strong>
                </li>

                <?php if ($order->get_payment_method_title()) : ?>

                    <li class="woocommerce-order-overview__payment-method method">
                        <?php _e('Payment method:', 'woocommerce'); ?>
                        <strong><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                    </li>

                <?php endif; ?>

            </ul>

        <?php endif; ?>

        <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

    <?php endif; ?>

</div>

<?php if( $order && !$order->has_status('failed') ) : ?>

<script language="javascript" src="https://scripts.affiliatefuture.com/AFFunctions.js"></script>
<script language="javascript">
 
                var merchantID = 6927;
                var orderValue = '<?php echo $order->get_total(); ?>';
    
                var orderRef = '<?php echo $order->get_order_number(); ?>';
                var payoutCodes = '';
                var offlineCode = '';
                var voucher = '';
                var products = '';
                var curr = '';              
 
AFProcessSaleV5(merchantID,orderValue,orderRef,payoutCodes,offlineCode,voucher,products,curr);
</script>
<?php
endif;