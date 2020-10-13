<?php

function add_async_forscript($url)
{
    if (strpos($url, '#asyncload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#asyncload', '', $url);
    else
        return str_replace('#asyncload', '', $url)."' async='async"; 
}
add_filter('clean_url', 'add_async_forscript', 11, 1);

function add_defer_forscript($url)
{
    if (strpos($url, '#deferload')===false)
        return $url;
    else if (is_admin())
        return str_replace('#deferload', '', $url);
    else
        return str_replace('#deferload', '', $url)."' defer='defer"; 
}
add_filter('clean_url', 'add_defer_forscript', 11, 1);

function theme_styles()
{
    if (!is_admin()) {

        /* =============== Flex Slider ===============  */
        wp_register_style('flexslider', get_template_directory_uri() . '/assets/plugins/flexslider/flexslider.css#asyncload', array(), '');
        wp_enqueue_style('flexslider');

        /* =============== Owl Carousel ===============  */
        wp_register_style('owlcarousel', get_template_directory_uri() . '/assets/plugins/owl/owl.carousel.css#asyncload', array(), '');
        wp_enqueue_style('owlcarousel');

        /* =============== Website stylesheet ===============  */
        wp_register_style('style', get_css_dir_uri() . 'style.min.css#asyncload#deferload', array(), '');
        wp_enqueue_style('style');

    }
}

function theme_scripts()
{
    if (!is_admin()) {
        /* =============== jquery ===============  */
        // wp_deregister_script("jquery");
        // wp_register_script('jquery', get_js_dir_uri() . 'frameworks/jquery-1.11.2.min.js', false, '1.11.2', true);
        // wp_enqueue_script('jquery');

        // wp_deregister_script("jquery");
        // wp_register_script('jquery', get_js_dir_uri() . 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js', null, null, true);
        // wp_enqueue_script('jquery');

        wp_enqueue_script( 'newjquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js' , false, '', true );
        wp_enqueue_script( 'newjquery' );


		 /* =============== WOW JS ===============  */
        wp_register_script('validate', get_template_directory_uri() . '/assets/plugins/validate/jquery.form-validator.min.js', array("jquery"), false, false);
        wp_enqueue_script('validate');
		
        /* =============== CSS3/HTML5 Check ===============  */
        wp_register_script('modernizr', get_js_dir_uri() . 'frameworks/modernizr.min.js', false, false, true);
        wp_enqueue_script('modernizr');

        /* =============== flexslider===============  */
        wp_register_script('flexslider', get_template_directory_uri() . '/assets/plugins/flexslider/jquery.flexslider-min.js', false, false, true);
        wp_enqueue_script('flexslider');

        /* =============== Owl Carousel ===============  */
        wp_register_script('owlcarousel', get_template_directory_uri() . '/assets/plugins/owl/owl.carousel.min.js', false, false, true);
        wp_enqueue_script('owlcarousel');

        /* =============== tether ===============  */
        wp_register_script('tether', get_template_directory_uri() . '/assets/plugins/bootstrap/tether.min.js', array("jquery"), false, true);
        wp_enqueue_script('tether');

        /* =============== bootstrap ===============  */
        wp_register_script('bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap/bootstrap.min.js', array("jquery"), false, true);
        wp_enqueue_script('bootstrap');

        /* =============== readmore ===============  */
        wp_register_script('readmore', get_template_directory_uri() . '/assets/plugins/readmore/readmore.js', array("jquery"), false, true);
        wp_enqueue_script('readmore');

        /* =============== Topclick Utility ===============  */
        wp_register_script('tc_utility', get_template_directory_uri() . '/assets/plugins/topclick/utility.js', array("jquery"), false, true);
        wp_enqueue_script('tc_utility');

        /* =============== Site wide Scripts ===============  */
        wp_register_script('site-script', get_js_dir_uri() . 'main.js', array("jquery"), false, true);
        wp_enqueue_script('site-script');
        wp_localize_script('site-script', 'ajax_url', admin_url('admin-ajax.php'));
        wp_localize_script('site-script', "img_url", get_image_dir_uri());

    }
}

add_action('wp_enqueue_scripts', 'theme_styles');
add_action('wp_enqueue_scripts', 'theme_scripts');