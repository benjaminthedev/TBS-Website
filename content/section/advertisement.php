<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 21/06/2017
 * Time: 16:45
 */
?>
<div class="product_ads">

    <ul class="slides">
        <?php while (have_rows('advertisement', 'options')) : the_row();

            $link_type = get_sub_field('link_type');

            $link = get_sub_field($link_type . '_link');

            $link = $link_type === 'category' ? get_term_link($link, 'product_cat') : $link;

            $link = $link_type === 'brand' ? get_term_link($link, 'product_brand') : $link;

            $target = $link_type === 'external' ? '_blank' : '_self';

            $image = get_sub_field('image');

            echo '<li>';

            echo $link ? "<a href='$link' target='$target'>" : "";

            echo $image ? get_img($image['url'], $image['alt'], false) : 'Please add an image here';

            echo $link ? "</a>" : "";

            echo '</li>';

        endwhile; ?>
    </ul>

</div>
