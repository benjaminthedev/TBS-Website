<?php
/**
 * The template to display the reviewers star rating in reviews
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $comment;
$rating = intval(get_comment_meta($comment->comment_ID, 'rating', true));


if ($title = get_comment_meta($comment->comment_ID, 'pmg_comment_title', true))
    echo '<h3>' . esc_attr($title) . '</h3>';


if ($rating && get_option('woocommerce_enable_review_rating') === 'yes') : ?>
    <ul class="star_rating">
        <?php 
        for($i = 0; $i < $rating; $i++) {
            echo '<li><i class="fa fa-star"></i></li>';
        }
        
        for($i = $rating; $i < 5; $i++) {
            echo '<li><i class="fa fa-star-o"></i></li>';
        } ?>
    </ul>
<?php endif; ?>