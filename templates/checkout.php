<?php

//Template Name: Checkout


$bag_class = is_cart() ? 'active' : 'done';

$details_class = !is_cart() ? 'active' : '';

get_header(); ?>

    <section id="static_content">

        <div class="container">

            <?php if (have_posts()) :

                while (have_posts()) : the_post(); ?>

                    <div class="row">

                        <div class="col-12">

                            <?php the_breadcrumb(); ?>

                        </div>

                    </div>

                    <?php if (!is_wc_endpoint_url( 'order-received' )) : ?>

                        <header class="checkout_header">

                            <ul class="clearfix">

                                <li class="<?php echo $bag_class; ?>">1. Your Bag</li>

                                <li class="<?php echo $details_class; ?> your_details">2. Checkout</li>

                            </ul>

                        </header>

                    <?php endif; ?>

                <?php endwhile;

            endif; ?>

        </div>

        <?php the_content(); ?>

    </section>

<?php get_footer(); ?>