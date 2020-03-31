<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 23/05/2017
 * Time: 09:24
 */

if (have_rows('slider')) : ?>

    <div class="slider">

        <ul class="slides clearfix">

            <?php while (have_rows('slider')) : the_row();

                $link_type = get_sub_field('link_type');

                $link = get_sub_field($link_type . '_link');

                $link = $link_type === 'category' ? get_term_link($link, 'product_cat') : $link;

                $target = $link_type === 'external' ? '_blank' : '_self';

                $image = get_sub_field('image');

                ?>

                <li>

                    <?php echo $link ? "<a href='$link' target='$target'>" : ''; ?>

                    <?php echo $image ? get_img($image['url'], $image['alt'], false) : ''; ?>

                    <?php echo $link ? "</a>" : ''; ?>

                </li>

            <?php endwhile; ?>

        </ul>

    </div>

<?php endif; ?>