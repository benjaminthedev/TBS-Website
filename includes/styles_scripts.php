<?php

function theme_styles()
{
    if (!is_admin()) {

        /* =============== Flex Slider ===============  */
        wp_register_style('flexslider', get_template_directory_uri() . '/assets/plugins/flexslider/flexslider.css', array(), '');
        wp_enqueue_style('flexslider');

        /* =============== Owl Carousel ===============  */
        wp_register_style('owlcarousel', get_template_directory_uri() . '/assets/plugins/owl/owl.carousel.css', array(), '');
        wp_enqueue_style('owlcarousel');
//
//        /* =============== Photoswipe ===============  */
//        wp_register_style('photoswipe-css', get_template_directory_uri() . '/assets/plugins/photoswipe/photoswipe.css', array(), '');
//        wp_enqueue_style('photoswipe-css');
//
//        wp_register_style('photoswipe-default', get_template_directory_uri() . '/assets/plugins/photoswipe/default-skin/default-skin.css', array(), '');
//        wp_enqueue_style('photoswipe-default');


        /* =============== videoJS ===============  */
        wp_enqueue_style('videoJS', get_template_directory_uri() . '/assets/plugins/videoJS/videoJS.css', array(), '');
        wp_enqueue_style('videoJS');


        /* =============== Website stylesheet ===============  */
        wp_register_style('style', get_css_dir_uri() . 'style.min.css', array(), '');
        wp_enqueue_style('style');


        /* =============== Temp stylesheet todo integrate these back. ===============  */
        wp_register_style('temp-style', get_css_dir_uri() . 'temp_styles.css', array(), '');
        wp_enqueue_style('temp-style');
    }
}

function theme_scripts(){
    if (!is_admin()) {
        /* =============== jquery ===============  */
        // wp_deregister_script("jquery");
        // wp_register_script('jquery', get_js_dir_uri() . 'frameworks/jquery-1.11.2.min.js', false, '1.11.2', true);
        // wp_enqueue_script('jquery');

        wp_deregister_script("jquery");
        wp_register_script('jquery', get_js_dir_uri() . 'frameworks/jquery-1.11.2.min.js', false, '1.11.2', true);
        wp_enqueue_script('jquery');
        

        /* =============== WOW JS ===============  */
        wp_register_script('validate', get_template_directory_uri() . '/assets/plugins/validate/jquery.form-validator.min.js', array("jquery"), false, false);
        wp_enqueue_script('validate');


        /* =============== CSS3/HTML5 Check ===============  */
        wp_register_script('modernizr', get_js_dir_uri() . 'frameworks/modernizr.min.js', false, false, true);
        wp_enqueue_script('modernizr');

//
        /* =============== flexslider===============  */
        wp_register_script('flexslider', get_template_directory_uri() . '/assets/plugins/flexslider/jquery.flexslider-min.js', false, false, true);
        wp_enqueue_script('flexslider');

        /* =============== Owl Carousel ===============  */
        wp_register_script('owlcarousel', get_template_directory_uri() . '/assets/plugins/owl/owl.carousel.min.js', false, false, true);
        wp_enqueue_script('owlcarousel');


        /* =============== Cool Carousel ===============  */
        wp_register_script('cool', get_template_directory_uri() . '/assets/plugins/cool/cool.js', false, false, true);
        wp_enqueue_script('cool');


        /* =============== tether ===============  */
        wp_register_script('tether', get_template_directory_uri() . '/assets/plugins/bootstrap/tether.min.js', array("jquery"), false, true);
        wp_enqueue_script('tether');

        /* =============== bootstrap ===============  */
        wp_register_script('bootstrap', get_template_directory_uri() . '/assets/plugins/bootstrap/bootstrap.min.js', array("jquery"), false, true);
        wp_enqueue_script('bootstrap');

        /* =============== videoJS ===============  */
        wp_register_script('videoJS', get_template_directory_uri() . '/assets/plugins/videoJS/videoJS.js', array("jquery"), false, true);
        wp_enqueue_script('videoJS');

        /* =============== Youtube ===============  */
        wp_register_script('Youtube', get_template_directory_uri() . '/assets/plugins/videoJS/Youtube.js', array("jquery"), false, true);
        wp_enqueue_script('Youtube');

        /* =============== Masonry ===============  */
        wp_register_script('masonry', get_template_directory_uri() . '/assets/plugins/masonry/masonry.js', array("jquery"), false, true);
        wp_enqueue_script('masonry');

        /* =============== readmore ===============  */
        wp_register_script('readmore', get_template_directory_uri() . '/assets/plugins/readmore/readmore.js', array("jquery"), false, true);
        wp_enqueue_script('readmore');


//
//
//        /* =============== headroom ===============  */
//        wp_register_script('headroom', get_template_directory_uri() . '/assets/plugins/headroom/headroom.min.js', array("jquery"), false, true);
//        wp_enqueue_script('headroom');
//
//
//        /* =============== lazyload ===============  */
//        wp_register_script('lazyload', get_js_dir_uri() . 'frameworks/lazyload.min.js', array("jquery"), false, true);
//        wp_enqueue_script('lazyload');
//

//
//
//        /* =============== Photoswipe ===============  */
//        wp_register_script('photoswipe', get_template_directory_uri() . '/assets/plugins/photoswipe/photoswipe.min.js', null, false, true);
//        wp_enqueue_script('photoswipe');
//
//        wp_register_script('photoswipe-ui', get_template_directory_uri() . '/assets/plugins/photoswipe/photoswipe-ui-default.min.js', null, false, true);
//        wp_enqueue_script('photoswipe-ui');
//
//        wp_register_script('photoswipe-init', get_template_directory_uri() . '/assets/plugins/photoswipe/init.js', null, false, true);
//        wp_enqueue_script('photoswipe-init');


        /* =============== Topclick Utility ===============  */
        wp_register_script('tc_utility', get_template_directory_uri() . '/assets/plugins/topclick/utility.js', array("jquery"), false, true);
        wp_enqueue_script('tc_utility');

        /* =============== Site wide Scripts ===============  */
        wp_register_script('site-script', get_js_dir_uri() . 'main.js', array("jquery"), false, true);
        wp_enqueue_script('site-script');
        wp_localize_script('site-script', 'ajax_url', admin_url('admin-ajax.php'));
        wp_localize_script('site-script', "img_url", get_image_dir_uri());


        if (have_rows('stores')) :


            $locations = [];

            while (have_rows('stores')): the_row();

                $i = get_row_index();

                $location = get_sub_field('location');


                $locations["location_$i"] = $location;

            endwhile;

            /* =============== Gmaps ===============  */

            wp_register_script('googleMapAPI', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD9qCJ0F_wE8SKBHNWAIh0gK-itDmKiiC8', false, false, true);

            wp_enqueue_script('googleMapAPI');

            /* =============== Store Locations ===============  */
            wp_register_script('store_locations', get_js_dir_uri() . 'store_locations.js', array("jquery"), false, true);
            wp_enqueue_script('store_locations');
            wp_localize_script('store_locations', 'store_locations', $locations);


        endif;

    }
}

add_action('wp_enqueue_scripts', 'theme_styles');
add_action('wp_enqueue_scripts', 'theme_scripts');


