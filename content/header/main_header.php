<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 22/05/2017
 * Time: 16:14
 */
?>

<header id="main_header">
    <div class="top_header">
        <div class="container clearfix">
            <div class="float-left clearfix">
                <nav class="account_navigation float-left hidden-sm-down">
                    <a href="#"><i class="fa fa-user"></i>
                        <span class="hidden-sm-down">My Account <?php echo is_user_logged_in() ? '(' . wp_get_current_user()->display_name . ')' : '(sign in)'; ?></span>
                        <i class="fa fa-chevron-circle-down hidden-sm-down" aria-hidden="true"></i></a>
                    <ul class="sub_menu">
                        <?php if (!is_user_logged_in()) : ?>
                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Login</a>
                            </li>
                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">Register</a>
                            </li>
                            <?php if( get_field('wishlist_page', 'options') ) : ?>
                            <li><a href="<?php the_field('wishlist_page', 'options'); ?>">Wishlist</a></li>
                            <?php endif; ?>
                        <?php else : ?>
                            <li><a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>">My Account</a></li>
                            <?php if( get_field('wishlist_page', 'options') ) : ?>
                            <li><a href="<?php the_field('wishlist_page', 'options'); ?>">Wishlist</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo wp_logout_url(home_url()); ?>">Log out</a></li>
                        <?php endif; ?>

                    </ul>
                </nav>
                <?php
                $telephone_number = get_field('telephone_number', 'options');
                $telephone_number_attributes = get_field('telephone_number_attributes', 'options');
                $telephone_number_converted = preg_replace("/[^0-9+]/", "", $telephone_number);
                echo $telephone_number ? '<a href="tel:' . $telephone_number_converted . '" class="telephone_number hidden-sm-down float-left" ' . $telephone_number_attributes . '><i class="fa fa-phone"></i><span class="hidden-md-down">' . $telephone_number . '</span></a>' : '';
                ?>
                <a href="#" class="hidden-md-up telephone_number float-left menu_expand_toggle"><i class="fa fa-bars"></i></a>
                <?php /* <a href="#" class="hidden-md-up telephone_number float-left search_expand_toggle"><i class="fa fa-search"></i></a> */ ?>

            </div>



            <div class="float-right clearfix">

                <?php echo do_shortcode('[aelia_currency_selector_widget widget_type="buttons"]') ?>

                <a href="<?php the_field('stores_page', 'options') ?>" class="account_link float-left hidden-sm-down"><i
                            class="fa fa-map-marker"></i> <span
                            class="hidden-sm-down">Our Stores</span></a>
                <a href="<?php echo wc_get_cart_url(); ?>" class="account_link float-left"><i
                            class="fa fa-shopping-bag"></i> <span
                            class="hidden-sm-down">Bag</span></a>

                <div class="cart_info float-left clearfix hidden-sm-down">
                    <div class="count float-left">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </div>
                    <div class="price float-left">
                        <?php woocommerce_mini_cart(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div id="mob-logo-container" class="container hidden-md-up text-center">
            <?php echo '<a href="' . get_site_url() . '" id="logo_mob">' . get_img('logo.png') . '</a>'; ?>
        </div>
    </div>
    <div class="middle_header">
        <div class="container clearfix ">
            <?php echo '<a href="' . get_site_url() . '" id="logo" class="float-md-left hidden-sm-down">' . get_img('logo.png') . '</a>'; ?>


            <form action="<?php echo get_permalink(wc_get_page_id('shop')) ?>" id="search_form" class="float-md-right">
                <div id="search_form_inner">
                    <div class="input_wrap">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" placeholder="I am looking for...">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Search</button>
                </div>
            </form>

        </div>
    </div>


</header>
