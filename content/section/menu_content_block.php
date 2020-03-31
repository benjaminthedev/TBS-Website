<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 19/05/2017
 * Time: 14:19
 */

$block_title = get_sub_field('block_title');
$block_subtitle = get_sub_field('block_subtitle');
$link_text = get_sub_field('link_text');
$link_type = get_sub_field('link_type');
$link = false;
$internal_link = get_sub_field('internal_link');
$external_link = get_sub_field('external_link');
$category_link = get_sub_field('category_link');
$category_link = get_term_link($category_link, 'product_cat');
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
}

$class = get_row_layout() === 'one_block' ? get_row_layout()  : 'two_block';
$image = get_sub_field('image');
$image_size = $class === 'one_block' ? 'menu_big' : 'menu_small';
$image = $image ? get_img($image['sizes'][$image_size], $image['alt'], false) : false;
if ($link) :
    echo "<a href='$link' class='menu_block $class'>";
    echo '<div class="overlay"></div>';
    echo '<div class="menu_block_content">';
    echo $block_title ? "<div class='block_title'>$block_title</div>" : '';
    echo $block_subtitle ? "<div class='block_subtitle'>$block_subtitle</div>" : '';
    echo $link_text ? "<div class='link_text'>$link_text</div>" : '';
    echo '</div>';
    echo $image ? $image : '';
    echo '</a>';
endif;
?>

