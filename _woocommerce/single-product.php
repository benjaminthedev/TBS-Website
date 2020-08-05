<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
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
 * @version     1.6.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header('shop'); ?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');

while (have_posts()) : the_post();

    global $product;

    if ('gift-card' === $product->get_type()) : ?>

        <section class="vlog_section">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-12">

                        <div class="vlog_content full">

                            <h1><?php the_title(); ?></h1>

                            <?php if ('gift-card' === $product->get_type()) : ?>

                                <?php the_field('product_subtitle'); ?>

                            <?php else: ?>

                                <?php the_content(); ?>

                            <?php endif; ?>

                        </div>

                    </div>

                </div>

            </div>

        </section>


    <?php endif; ?>

    <div class="container">

        <?php

        if ('gift-card' === $product->get_type()) wc_get_template_part('content', 'gift-card');

        else wc_get_template_part('content', 'single-product');

        ?>

    </div>


    <?php if ('gift-card' === $product->get_type()) : ?>

        <div class="lower_content">

            <div class="container">

                <?php the_content(); ?>

            </div>

        </div>

        <?php

    endif;

endwhile; // end of the loop.
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
?>

<?php get_footer('shop');

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
