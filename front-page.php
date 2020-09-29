<?php

get_header();

echo do_shortcode('[smartslider3 slider="2"]');

get_section_layout('product_display');

get_section('top_offers');

get_section_layout('product_display_2');

get_section('blog_posts');

get_section('brands_slider');

get_footer();

?>