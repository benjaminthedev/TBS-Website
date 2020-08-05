<?php
/**
 * Gift Card product add to cart
 *
 * @author  Yithemes
 * @package YITH WooCommerce Gift Cards
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

global $product, $post;

$categories = get_terms(YWGC_CATEGORY_TAXONOMY, array('hide_empty' => 1));

$item_categories = array();

foreach ($categories as $item) {

    $object_ids = get_objects_in_term($item->term_id, YWGC_CATEGORY_TAXONOMY);

    foreach ($object_ids as $object_id)

        $item_categories[$object_id] = isset($item_categories[$object_id]) ? $item_categories[$object_id] . ' ywgc-category-' . $item->term_id : 'ywgc-category-' . $item->term_id;

}

$default_image = (int)get_post_meta($post->ID, '_ywgc_product_image', true);

?>


<div class="gift_form">

    <div class="title"><span>1. SELECT YOUR GIFT VOUCHER</span></div>

    <div class="design_selector clearfix">

        <?php foreach ($item_categories as $item_id => $categories): ?>

            <div class="design_block image_design <?php echo $default_image === $item_id ? 'active' : ''; ?>"

                 data-design-id="<?php echo $item_id; ?>"

                 data-design-url="<?php echo yith_get_attachment_image_url(intval($item_id), 'full'); ?>">

                <?php echo wp_get_attachment_image(intval($item_id), 'full'); ?>

            </div>

        <?php endforeach; ?>

        <div class="design_block" id="design_preview_block">

            <div class="design_block ">

                <div class="top clearfix">

                    <div class="ywgc-card-amount">

                        <span class="woocommerce-Price-amount amount">£0.00</span>

                    </div>

                    <div class="logo">

                        <?php echo get_img('logo.png'); ?>

                    </div>

                </div>

                <div class="bottom">

                    <div class="to">To: <span></span></div>

                    <div class="message">"<span></span>"</div>

                    <div class="from">From: <span></span></div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="gift_form">

    <div class="title"><span>2. ENTER GIFT VOUCHER DETAILS</span></div>

    <div class="row">

        <div class="col-md-4">

            <div class="form-group">

                <table class="gift-cards-list" cellspacing="0">

                    <tbody>

                    <tr>

                        <td class="ywgc-amount-value">

                            <input id="ywgc-manual-amount" name="ywgc-manual-amount"

                                   class="form-control ywgc-manual-amount" type="text"

                                   placeholder="Voucher value *" value="10" >

                        </td>

                    </tr>

                    </tbody>

                </table>

            </div>

            <div class="form-group">

                <input type="text" name="ywgc-recipient-name" id="ywgc-recipient-name" placeholder="To *"

                       class="form-control" >

            </div>

            <div class="form-group">

                <input type="text" name="ywgc-sender-name" id="ywgc-sender-name" placeholder="From *"

                       class="form-control" >

            </div>

        </div>

        <div class="col-md-4">

              <div class="form-group">

                    <textarea id="ywgc-edit-message" name="ywgc-edit-message"

                              placeholder="Personal Message" class="form-control"></textarea>

              </div>

        </div>

        <div class="col-md-4">

            <div class="form-group">

                <input type="text"  name="ywgc-delivery-date" class="form-control datepicker"

                       placeholder="Delivery date*" >

            </div>

            <div class="form-group">

                <input type="email" name="ywgc-recipient-email[]"  class="form-control" id="send_email"

                       placeholder="Send to email*"/>

            </div>

            <div class="form-group">

                <input type="email" name="ywgc-recipient-email-confirm"  class="form-control" id="send_email_confirm"

                       placeholder="Confirm email*"/>

            </div>

            <input type="checkbox" id="ywgc-postdated" name="ywgc-postdated" checked>

        </div>

    </div>

</div>