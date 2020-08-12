<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 24/05/2017
 * Time: 14:16
 */

if (have_rows('top_offers', 'options')) : ?>

    <section class="section_container">
		
		<div class="container-fluid">
			
			 <h2 class="section_title"><span>Top Offers</span></h2>
			
			<div class="row">           

                <div class="col-md-3">

                <?php while (have_rows('top_offers_left', 'options')) : the_row();

                    $link_type = get_sub_field('link_type');

                    $link = get_sub_field($link_type . '_link');

                    $link = $link_type === 'category' && $link ? get_term_link($link, 'product_cat') : $link;

                    $link = $link_type === 'brand' ? get_term_link($link, 'product_brand') : $link;

                    $target = $link_type === 'external' ? '_blank' : '_self';

                    $image = get_sub_field('image');

                    $block_size = get_sub_field('block_size');

                    echo $link ? "<a href='$link' target='$target'>" : "";

                    echo $image ? get_img($image['url'], $image['alt'], false) : 'Please add an image here';

                    echo $link ? "</a>" : "</div>";

                endwhile; ?>
					
					</div>
		
		 <div class="col-md-3">
				
				 <?php while (have_rows('top_offers_middle_left', 'options')) : the_row();

                     $link_type = get_sub_field('link_type');

                    $link = get_sub_field($link_type . '_link');

                    $link = $link_type === 'category' && $link ? get_term_link($link, 'product_cat') : $link;

                    $link = $link_type === 'brand' ? get_term_link($link, 'product_brand') : $link;

                    $target = $link_type === 'external' ? '_blank' : '_self';

                    $image = get_sub_field('image');

                    $block_size = get_sub_field('block_size');

                    echo $link ? "<a href='$link' target='$target'>" : "";

                    echo $image ? get_img($image['url'], $image['alt'], false) : 'Please add an image here';

                    echo $link ? "</a>" : "</div>";

                endwhile; ?>
			 
			 </div>
				
				<div class="col-md-3">
				
				 <?php while (have_rows('top_offers_middle_right', 'options')) : the_row();

                     $link_type = get_sub_field('link_type');

                    $link = get_sub_field($link_type . '_link');

                    $link = $link_type === 'category' && $link ? get_term_link($link, 'product_cat') : $link;

                    $link = $link_type === 'brand' ? get_term_link($link, 'product_brand') : $link;

                    $target = $link_type === 'external' ? '_blank' : '_self';

                    $image = get_sub_field('image');

                    $block_size = get_sub_field('block_size');

                    echo $link ? "<a href='$link' target='$target'>" : "";

                    echo $image ? get_img($image['url'], $image['alt'], false) : 'Please add an image here';

                    echo $link ? "</a>" : "</div>";

                endwhile; ?>
			 
			 </div>
		
			 <div class="col-md-3">
				
				 <?php while (have_rows('top_offers_right', 'options')) : the_row();

                    $link_type = get_sub_field('link_type');

                    $link = get_sub_field($link_type . '_link');

                    $link = $link_type === 'category' && $link ? get_term_link($link, 'product_cat') : $link;

                    $link = $link_type === 'brand' ? get_term_link($link, 'product_brand') : $link;

                    $target = $link_type === 'external' ? '_blank' : '_self';

                    $image = get_sub_field('image');

                    echo $link ? "<a href='$link' target='$target'>" : "";

                    echo $image ? get_img($image['url'], $image['alt'], false) : 'Please add an image here';

                    echo $link ? "</a>" : "</div>";

                endwhile; ?>
				 
            </div>
			
			</div>
			
			</div>

    </section>

<?php endif; ?>