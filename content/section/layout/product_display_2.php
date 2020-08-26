<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 23/05/2017
 * Time: 10:25
 */
?>

<section class="section_container">
	
	    <div class="container-fluid">
        <h2 class="section_title">
         <span>Trending</span></h2>

		<span class="clerk"
  data-template="@home-page-trending"
></span>
          
            </div>

</section>

<?php

if (have_rows('top_offers', 'options')) : ?>

<section class="section_container" id="homepage_promo_2">
		
		<div class="container-fluid">
			
			<div class="row">           

                <div class="col">

                <?php while (have_rows('homepage_promo_2', 'options')) : the_row();

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
				</div>
			
			</div>
</section>
<?php endif; ?>

<section class="section_container">
	
	    <div class="container-fluid">

        <h2 class="section_title"><span>What's New</span></h2>

		<span class="clerk"
  data-template="@homepage"
></span>
          
            </div>

</section>