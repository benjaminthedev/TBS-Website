<?php

$the_post_thumbnail_url = get_the_post_thumbnail_url(null, 'full');

$wishlist = get_field('wishlist', 'user_' . get_current_user_id());

get_header(); ?>

<section id="static_content" class="no_margin">

    <div class="container">

        <div class="row">

            <div class="col-12">

                <?php the_breadcrumb(); ?>

            </div>

        </div>

    </div>

</section>

<?php if (have_posts()) :

    while (have_posts()) : the_post(); ?>

        <section class="vlog_section wish_list">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-12">

                        <div class="vlog_content">

                            <h1><?php the_title(); ?></h1>

                            <?php echo $wishlist ? '<a href="#" data-toggle="modal" data-target="#email_friend">Email to a friend.</a>' : ''; ?>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <div id="product_content_wrapper">

            <div class="container">

                <div class="product_feed_wrap clearfix">

                    <div class="sidebar">

                        <?php if (have_rows('advertisement', 'options')) get_section('advertisement'); ?>

                    </div>

                    <div class="products">

                            <?php

                            $args = [
                                'posts_per_page' => -1,
                                'post_status' => 'publish',
                                'post_type' => 'product',
                                'post__in' => explode('|', $wishlist),
                                'orderby' => 'post__in',
                            ];

                            $query = new WP_Query($args);

                            if ($query->have_posts()) : ?>

                                <div class="row justify-content-center" id="product_row">

                                    <?php while($query->have_posts()) : $query->the_post(); ?>

                                        <?php do_action('woocommerce_shop_loop');  ?>

                                        <?php wc_get_template_part('content', 'product'); ?>

                                    <?php endwhile; // end of the loop. ?>

                                </div>
                                <?php

                            else: do_action('woocommerce_no_products_found');

                            endif;

                            wp_reset_postdata();

                            ?>

                    </div>

                </div>

            </div>

        </div>

    <?php endwhile;

endif; ?>

<div class="modal fade" id="email_friend" tabindex="-1" role="dialog" aria-labelledby="email_friend_label" aria-hidden="true">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="email_friend_label">Send Wishlist</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                    <span aria-hidden="true">&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <?php echo do_shortcode('[contact-form-7 id="54189" title="Wishlist"]') ?>

            </div>

        </div>

    </div>

</div>

<?php get_footer(); ?>
