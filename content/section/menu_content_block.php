<?php
/**
 * Created by PhpStorm.
 * User: jackm
 * Date: 15/09/2020
 * Time: 15:09
 */

$link_type = get_sub_field('link_type');
$link = false;
$internal_link = get_sub_field('internal_link');
$external_link = get_sub_field('external_link');
$category_link = get_sub_field('category_link');
$category_link = get_term_link($category_link, 'product_cat');
$brand_link = get_sub_field('brand_link');
$brand_link = get_term_link($brand_link, 'product_brand');
switch ($link_type) {
    case 'internal':
        $link = $internal_link;
        break;
    case 'category':
        $link = $category_link;
        break;
    case 'external' :
        $link = $external_link;
        break;
	case 'brand':
        $link = $brand_link;
        break;
}
$class = get_row_layout() === 'one_block';
$image = get_sub_field('image');
$image = $image ? get_img($image, $image['alt'], false) : false;
$title = get_sub_field('title');
if ($link) :
echo '<div class="col-sm">';    
echo "<a href='$link' class='menu_block $class'>";
echo $image ? $image : '';
echo $title;
echo '</a>';
echo '</div>';
endif;
?>