<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
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

global $product;
?>
<div class="product_meta">

<div class="countdown">
        <div class="title">
            <?php /* ORDER BY 2PM FOR SAME DAY DISPATCH */ ?>
            FREE 48 HR DELIVERY on orders over &#163;35
        </div>
        <div id="count_down_text"></div>
    </div>
    <div class="social_links">
        <ul>
            <li>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>"
                   target="_blank">
                    <i class="fa fa-facebook-official"></i>
                </a>
            </li>
            <li>
                <a href="https://twitter.com/home?status=<?php echo urlencode(get_the_title() . ' | The Beauty Store ' . get_the_permalink()); ?>"
                   target="_blank">
                    <i class="fa fa-twitter"></i>
                </a>
            </li>
            <?php if (has_post_thumbnail()) :
                global $post;
                $post_thumbnail_id = get_post_thumbnail_id($post->ID);
                $large_images = wp_get_attachment_image_src($post_thumbnail_id, 'full');
                ?>
                <li>
                    <a href="https://pinterest.com/pin/create/button/?url=<?php echo urlencode(get_the_permalink()); ?>&media=<?php echo urlencode($large_images) ?>&description=<?php echo urlencode(get_the_title()) ?>"
                       target="_blank">
                        <i class="fa fa-pinterest"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

    </div>


</div>

<div class="modal fade" id="ask_question" tabindex="-1" role="dialog" aria-labelledby="ask_question_label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ask_question_label">Ask A Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo do_shortcode('[contact-form-7 id="54181" title="Product Question"]') ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="friend_form" tabindex="-1" role="dialog" aria-labelledby="friend_form_label"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="friend_form_label">Tell A Friend</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo do_shortcode('[contact-form-7 id="54182" title="Tell A Friend"]') ?>
            </div>
        </div>
    </div>
</div>