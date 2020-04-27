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
            <?php /* ORDER BY 2PM FOR NEXT DAY DELIVERY */ ?>
            NEXT DAY DELIVERY is just £2.95 on orders over &#163;25
        </div>
        <div id="count_down_text"></div>
    </div>


    <div class="content_tabs">
        <?php if ($delivery_and_returns = get_field('delivery_and_returns', 'options')) : ?>
            <div class="content_tab">
                <header>DELIVERY & RETURNS <i class="fa fa-plus"></i></header>
                <div class="content">
                    <div class="inner">
                        <?php echo $delivery_and_returns; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($payment_and_security = get_field('payment_and_security', 'options')) : ?>
            <div class="content_tab">
                <header>PAYMENT & SECURITY <i class="fa fa-plus"></i></header>
                <div class="content">
                    <div class="inner">
                        <?php echo $payment_and_security; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="product_controls">
        <div class="row">
            <div class="col-sm-4">
                <a href="#" data-toggle="modal" data-target="#ask_question">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>
                    Ask A Question
                </a>
            </div>
            <div class="col-sm-4">
                <a href="#" data-toggle="modal" data-target="#friend_form">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    Tell A Friend
                </a>
            </div>
            <?php

            $wishlist = get_field('wishlist', 'user_' . get_current_user_id());
            if ($wishlist)
                $wishlist = in_array(get_the_ID(), explode('|', $wishlist));

            ?>
            <div class="col-sm-4">
                <a href="#" class="wishlist <?php echo $wishlist ? 'active' : ''; ?>" data-product="<?php echo get_the_ID(); ?>">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span class="in_active_text"> Add To Wishlist</span>
                    <span class="active_text"> Remove From Wishlist</span>
                </a>
            </div>
        </div>
    </div>


    <div class="social_links">
        <ul>
            <li>
                <small>Share:</small>
            </li>
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
            <li>
                <a href="https://plus.google.com/share?url=<?php echo urlencode(get_the_permalink()); ?>"
                   target="_blank">
                    <i class="fa fa-google-plus"></i>
                </a>
            </li>
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