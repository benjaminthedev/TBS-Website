<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 23/05/2017
 * Time: 10:54
 */

$latest_offers = get_field('latest_offers', 'options');



$args = [
    'post_type' => 'product',
    'posts_per_page' => 8,
    'post__in' => $latest_offers,
    /*
    'tax_query' => array(
        array(
            'taxonomy' => 'product_visibility',
            'field' => 'term_taxonomy_id',
            'terms' => array( get_term_by( 'name', 'outofstock', 'product_visibility' )->term_taxonomy_id ),
            'operator' => 'NOT IN'
        )
    )
    */
];


$query = new WP_Query($args);

if ($query->have_posts() && $latest_offers) :?>

    <li class="tab" id="latest_offer">

        <div class="owl-carousel product_owl products">

            <?php while ($query->have_posts()) : $query->the_post(); ?>

                <?php wc_get_template_part('content', 'product'); ?>

            <?php endwhile; ?>

        </div>

    </li>

    <?php

else : ?>
    <li class="tab">
    </li>
<?php endif;

wp_reset_query();

?>