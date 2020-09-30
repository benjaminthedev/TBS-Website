<?php
if (have_rows('brands_slider', 'options')) : ?>

    <div id="brands_slider">

        <div class="container">

            <ul class="client_logos">

                <?php while (have_rows('brands_slider', 'options')) :the_row();

                    $brand_logo = get_sub_field('brand_logo');

                    $brand = get_sub_field('brand');

                    $brand = $brand ? get_term_link($brand, 'product_brand') : false; ?>

                    <li <?php echo 'data-update="item' . get_row_index() . '"' ?>>

                        <?php echo $brand ? "<a href='$brand'>" : '<div>' ?>

                        <?php echo $brand_logo ? get_img($brand_logo['url'], $brand_logo['alt'], false) : '' ?>

                        <?php echo $brand ? "</a>" : '</div>' ?>

                    </li>

                <?php endwhile; ?>

            </ul>

        </div>

    </div>

<?php endif; ?>
