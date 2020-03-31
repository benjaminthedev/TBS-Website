<?php
/* =============== Custom Login style  =============== */

function custom_login_css()
{
    echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/admin/login/login-styles.css" />';
}

add_action('login_head', 'custom_login_css');

/* remove private prefix from 404 page. Allows user to customize 404 page */

function remove_private_prefix($title)
{
    if (!is_admin()) {
        global $post;

        if ($post) {


            if ($post->post_name == "404-page") {
                $title = str_replace(
                    'Private:', '', $title);
            }
        }
    }
    return $title;
}

add_filter('the_title', 'remove_private_prefix');


/* ===============  Init WP_Head cleanUp ===============  */

function pd_startup()
{
    add_action('init', 'pd_head_cleanup');
    add_action('after_setup_theme', 'pd_theme_support'); /* end pd theme support */
}

/* ===============  Clearn wp_head ===============  */

function pd_head_cleanup()
{

    /* ===============
      Remove RSS from header
      =============== */
    remove_action('wp_head', 'rsd_link'); #remove page feed
    remove_action('wp_head', 'feed_links_extra', 3); // Remove category feeds
    remove_action('wp_head', 'feed_links', 2); // Remove Post and Comment Feeds


    /* ===============
      remove windindows live writer link
      =============== */
    remove_action('wp_head', 'wlwmanifest_link');


    /* ===============
      links for adjacent posts
      =============== */
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);


    /* ===============
      WP version
      =============== */
    remove_action('wp_head', 'wp_generator');


    /* ===============
      remove WP version from css
      =============== */
    add_filter('style_loader_src', 'pd_remove_wp_ver_css_js', 9999);


    /* ===============
      remove Wp version from scripts
      =============== */
    add_filter('script_loader_src', 'pd_remove_wp_ver_css_js', 9999);
}

add_action('after_setup_theme', 'pd_startup');


/* ===============  Theme support feature ===============  */

function pd_theme_support()
{

    /* =============== 	Add language supports ===============  */
    load_theme_textdomain('pd', get_template_directory() . '/lang');


    /* =============== Rss feed support ===============  */
    add_theme_support('automatic-feed-links');


    /* =============== 	 Add post formarts supports ===============  */
    add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));
}

add_theme_support('post-thumbnails');


/* ===============  Better standard pagination ===============  */

function pd_pagination()
{

    global $wp_query;
    $total_pages = $wp_query->max_num_pages;
    if ($total_pages > 1) {

        $current_page = max(1, get_query_var('paged'));

        echo '<div class="page_nav">';
        echo paginate_links(array(
            'base' => get_pagenum_link(1) . '%_%',
            'format' => '/page/%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text' => 'Prev',
            'next_text' => 'Next'
        ));
        echo '</div>';
    }
}

/* =============== Add navigation ===============  */

function register_menus()
{
    register_nav_menus(array(
        'primary' => __('Primary Navigation', 'pd'),
        'footer' => __('Footer Navigation', 'pd'),
    ));
}

add_action('after_setup_theme', 'register_menus');


/* =============== remove WP version from scripts ===============  */

function pd_remove_wp_ver_css_js($src)
{
    if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}

/* =============== Wrap img in figure tags ===============  */

function pd_img_unautop($imgWrap)
{
    $imgWrap = preg_replace('/<p>\\s*?(<a .*?><img.*?><\\/a>|<img.*?>)?\\s*<\\/p>/s', '<figure>$1</figure>', $imgWrap);
    return $imgWrap;
}

/* =============== topclick Credits =============== */

function my_admin_footer_text($default_text)
{
    return '<span id="footer-thankyou">Website built by <a href="http://www.topclick"><span style="color: #a3223b">topclick</span></a><span> | Powered by <a href="http://www.wordpress.org">WordPress</a>';
}

add_filter('admin_footer_text', 'my_admin_footer_text');


/* =============== Display on the admin bar what template is used  =============== */

function toolbar_link_to_mypage($wp_admin_bar)
{
    global $current_user;

    if (!is_admin() && $current_user->roles[0] == "administrator") {
        global $template;

        $url = get_template_directory();
        $template = str_replace($url . "/", "", $template);

        $args = array(
            'html' => true,
            'id' => 'my_page',
            'title' => "Template Name :   <strong>" . $template . "</strong>",
            'meta' => array('class' => 'template-name')
        );
        $wp_admin_bar->add_node($args);
    }
}

add_action('admin_bar_menu', 'toolbar_link_to_mypage', 999);

// Add Custom Post Type to WP-ADMIN Right Now Widget
function vm_right_now_content_table_end()
{
    $args = array(
        'public' => true,
        '_builtin' => false
    );
    $output = 'object';
    $operator = 'and';
    $post_types = get_post_types($args, $output, $operator);
    foreach ($post_types as $post_type) {
        $num_posts = wp_count_posts($post_type->name);
        $num = number_format_i18n($num_posts->publish);
        $text = _n($post_type->labels->name, $post_type->labels->name, intval($num_posts->publish));
        if (current_user_can('edit_posts')) {
            $cpt_name = $post_type->name;
        }
        echo '<li class="post-count"><tr><a href="edit.php?post_type=' . $cpt_name . '"><td class="first b b-' . $post_type->name . '"></td>' . $num . '&nbsp;<td class="t ' . $post_type->name . '">' . $text . '</td></a></tr></li>';
    }
}

add_action('dashboard_glance_items', 'vm_right_now_content_table_end');

// Remove WordPress logo from top-left corner of admin bar

function remove_admin_bar_links()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');          // Remove the WordPress logo
}

add_action('wp_before_admin_bar_render', 'remove_admin_bar_links');


/* =============== Get page ID by slug  =============== */

function get_ID_by_slug($page_slug)
{
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

/* =============== Thumbnail  =============== */

//add_action('after_setup_theme', 'gallery_thumb_size');
function menu_big_size()
{
    add_image_size('menu_big', 309, 441, true); // Hard crop to exact dimensions (crops sides or top and bottom)
}

add_action('after_setup_theme', 'menu_big_size');

function menu_small_size()
{
    add_image_size('menu_small', 309, 211, true); // Hard crop to exact dimensions (crops sides or top and bottom)
}

add_action('after_setup_theme', 'menu_small_size');

function resident_thumb_size()
{
    add_image_size('resident_thumb_size-thumb', 300, 200, true); // Hard crop to exact dimensions (crops sides or top and bottom)
}

add_action('after_setup_theme', 'resident_thumb_size');

function gallery_thumb_size()
{
    add_image_size('gallery-thumb', 210, 210, true); // Hard crop to exact dimensions (crops sides or top and bottom)
    add_image_size('blog-thumb', 635, 320, true); // Hard crop to exact dimensions (crops sides or top and bottom)
}

add_action('after_setup_theme', 'gallery_thumb_size');


/* ======= Upload Mimes =============== */

add_filter('upload_mimes', 'my_upload_mimes');

function my_upload_mimes($existing_mimes = array())
{
    $existing_mimes['csv'] = 'text/csv';
    $existing_mimes['svg'] = 'image/svg+xml';
    return $existing_mimes;
}

/* ======= CSV to Array =============== */

function csv_to_array($csvFile = '', $delimiter = ',')
{
    $aryData = array();
    $header = NULL;
    $handle = fopen($csvFile, "r");
    if ($handle) {
        while (!feof($handle)) {
            $aryCsvData = fgetcsv($handle);
            if (!is_array($aryCsvData)) {
                continue;
            }
            if (is_null($header)) {
                $header = $aryCsvData;
            } elseif (is_array($header) && count($header) == count($aryCsvData)) {
                $aryData[] = array_combine($header, $aryCsvData);
            }
        }
        fclose($handle);
    }
    return $aryData;
}

/* ======= Limit words  =============== */

function limit_words($string, $word_limit)
{
    $words = explode(" ", $string);
    return implode(" ", array_splice($words, 0, $word_limit));
}


/* =============== Pagination  =============== */

function pagination($pages = '', $range = 4)
{
    $showitems = ($range * 2) + 1;

    global $paged;
    if (empty($paged))
        $paged = 1;

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages) {
            $pages = 1;
        }
    }

    if (1 != $pages) {
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link(1) . "'>&laquo;</a>";
        if ($paged > 1 && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo;</a>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a>";
            }
        }
        if ($paged < $pages && $showitems < $pages)
            echo "<a href=\"" . get_pagenum_link($paged + 1) . "\">&rsaquo;</a>";
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages)
            echo "<a href='" . get_pagenum_link($pages) . "'>&raquo;</a>";
    }
}

/* =============== Add Excerpt  =============== */

add_action('init', 'my_add_excerpts_to_pages');

function my_add_excerpts_to_pages()
{
    add_post_type_support('page', 'excerpt');
}


/* =============== Get Attachment ID From SRC  =============== */

function upload_image_as_attachment($image_url, $post_id, $title)
{
    $img_name = basename($image_url);
    $local_url = wp_get_upload_dir()['url'] . '/' . $img_name;
    if (attachment_url_to_postid($local_url))
        $id = attachment_url_to_postid($local_url);
    else {

        $url = str_replace(WP_CONTENT_DIR, get_site_url() . '/wp-content/', $image_url);

        $attachment_src = media_sideload_image($url, $post_id, $title, 'src');
        $id = attachment_url_to_postid($attachment_src);


    }
    return $id;
}


/* =============== Importer Page  =============== */

function add_admin_menu_pages()
{
    add_menu_page('Import Products', 'Import Products', 'manage_options', 'import-products', 'hook_import_template', 'dashicons-tickets', 70);
}

function hook_import_template()
{
    get_admin_page_layout('importer');
}

// add_action('admin_menu', 'add_admin_menu_pages');

/* =============== Get Excerpt  =============== */

function get_excerpt($str, $maxLength = 100, $startPos = 0)
{
    if (strlen($str) > $maxLength) {
        $excerpt = substr($str, $startPos, $maxLength - 3);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt = substr($excerpt, 0, $lastSpace);
        $excerpt .= '...';
    } else {
        $excerpt = $str;
    }

    return $excerpt;
}

/* =============== Breadcrumbs  =============== */

function the_breadcrumb()
{
    echo '<ul id="crumbs" class="text-left">';
    if (!is_home()) {
        echo '<li><a href="';
        echo get_option('home');
        echo '">';
        echo 'Home';
        echo "</a></li>";
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' </li><li> ');
            if (is_single()) {
                echo "</li><li>";
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            echo '<li>';
            echo the_title();
            echo '</li>';
        } elseif (is_post_type_archive()) {
            echo '<li>';
            echo post_type_archive_title();
            echo '</li>';
        } elseif (is_tax()) {
            echo '<li>';
            echo single_cat_title('', false);
            echo '</li>';
        }
    } elseif (is_tag()) {
        single_tag_title();
    } elseif (is_day()) {
        echo "<li>Archive for ";
        the_time('F jS, Y');
        echo '</li>';
    } elseif (is_month()) {
        echo "<li>Archive for ";
        the_time('F, Y');
        echo '</li>';
    } elseif (is_year()) {
        echo "<li>Archive for ";
        the_time('Y');
        echo '</li>';
    } elseif (is_author()) {
        echo "<li>Author Archive";
        echo '</li>';
    } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
        echo "<li>Blog Archives";
        echo '</li>';
    } elseif (is_search()) {
        echo "<li>Search Results";
        echo '</li>';
    }
    echo '</ul>';
}

/* =============== ACF Maps  =============== */

function my_acf_google_map_api($api)
{

    $api['key'] = 'AIzaSyD9qCJ0F_wE8SKBHNWAIh0gK-itDmKiiC8';

    return $api;

}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

/* =============== Product Pagination  =============== */

function the_product_pagination($query)
{

    $max_page = (int)$query->max_num_pages;
    $current_page = key_exists('paged', $query->query) ? (int)$query->query['paged'] : 1;
    $return = '';
    if ($max_page > 1) {
        $return .= $current_page !== 1 ?
            '<li><a href="#" data-page="' . ($current_page - 1) . '"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>' :
            '<li><span><i class="fa fa-angle-left" aria-hidden="true"></i></span></li>';

        if (!($current_page - 4 <= 1)) {
            $return .= '<li><a href="#" data-page="1">1</a></li>';
            $return .= '<li><span>...</span></li>';

            for ($x = $current_page; $x >= ($current_page - 2); $x--) {
                $return .= $current_page !== $x ?
                    '<li><a href="#" data-page="' . $x . '">' . $x . '</a></li>' :
                    '';
            }


        } elseif ($current_page - 4 <= 1) {
            for ($x = 1; $x < $current_page; $x++)
                $return .= '<li><a href="#" data-page="' . $x . '">' . $x . '</a></li>';
        }


        for ($x = $current_page; $x <= ($current_page + 4); $x++) {
            if ($x <= $max_page)
                $return .= $current_page !== $x ?
                    '<li><a href="#" data-page="' . $x . '">' . $x . '</a></li>' :
                    '<li><span class="active">' . $x . '</span></li>';
        }
        if (!($current_page + 4 >= (int)$max_page)) {
            $return .= '<li><span>...</span></li>';
            $return .= '<li><a href="#" data-page="' . $max_page . '">' . $max_page . '</a></li>';
        }
        $return .= (int)$current_page !== (int)$max_page ?
            '<li><a href="#" data-page="' . ($current_page + 1) . '"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>' :
            '<li><span><i class="fa fa-angle-right" aria-hidden="true"></i></span></li>';


    }
    return $return;
}

/* =============== Products Per Page  =============== */

function per_page($query)
{
    $found_posts = (int)$query->found_posts;
    $per_page = key_exists('posts_per_page', $query->query) ? $query->query['posts_per_page'] : 12;
    $per_page = (int)$per_page;
    $return = '';
    if ($found_posts > 12) {
        $return .= 'View: ';
        $return .= $per_page === 12 ? '<a href="#" class="active" data-per_page="12">12</a>' : '<a href="#" data-per_page="12">12</a>';
        if ($found_posts >= 24)
            $return .= $per_page === 24 ? '<a href="#" class="active" data-per_page="24">24</a>' : '<a href="#" data-per_page="24">24</a>';
        if ($found_posts >= 44)
            $return .= $per_page === 44 ? '<a href="#" class="active" data-per_page="44">44</a>' : '<a href="#" data-per_page="44">44</a>';
        if ($found_posts >= 66)
            $return .= $per_page === 66 ? '<a href="#" class="active" data-per_page="66">66</a>' : '<a href="#" data-per_page="66">66</a>';
        $return .= '<span>Products Per Page</span>';
    }
    return $return;
}

/* =============== Woocommerce   =============== */

add_action('after_setup_theme', 'woocommerce_support');
function woocommerce_support()
{
    add_theme_support('woocommerce');
}


/* =============== Get Current Tax Children =============== */

function get_current_tax_children($term_id, $ajax = false, $multiple = false)
{
    $term = get_term_by('id', $term_id, 'product_cat');
    $children = get_terms(
        'product_cat',
        array(
            'parent' => $term_id,
        )
    );
    if (!$children && $ajax || $multiple)
        $children = $children = get_terms(
            'product_cat',
            array(
                'parent' => $term->parent,
            )
        );
    return $children;
}

function get_taxonomy_archive_link($taxonomy)
{
    $tax = get_taxonomy($taxonomy);
    return get_bloginfo('url') . '/' . $tax->rewrite['slug'];
}

/* =============== Add a confirm password field to the checkout registration form. =============== */

function o_woocommerce_confirm_password_checkout($checkout)
{
    if (get_option('woocommerce_registration_generate_password') == 'no') {

        $fields = $checkout->get_checkout_fields();
        $terms_and_conditions = get_field('terms_and_conditions');
        $fields['account']['account_confirm_password'] = array(
            'type' => 'password',
            'label' => __('Confirm password', 'woocommerce'),
            'required' => true,
            'placeholder' => _x('Confirm Password', 'placeholder', 'woocommerce')
        );


        $fields['account']['account_accept_terms'] = array(
            'type' => 'checkbox',
            'label' => "I have read and accepted the <a href='$terms_and_conditions' target='_blank'>terms and conditions</a>",
            'required' => true,
        );

        $checkout->__set('checkout_fields', $fields);
    }
}

add_action('woocommerce_checkout_init', 'o_woocommerce_confirm_password_checkout', 10, 1);

/**
 * Validate that the two password fields match.
 */
function o_woocommerce_confirm_password_validation($posted)
{
    $checkout = WC()->checkout;
    if (!is_user_logged_in() && ($checkout->must_create_account || !empty($posted['createaccount']))) {
        if (strcmp($posted['account_password'], $posted['account_confirm_password']) !== 0) {
            wc_add_notice(__('Passwords do not match.', 'woocommerce'), 'error');
        }
        if (!$posted['account_accept_terms']) {
            wc_add_notice(__('Please read and accept the terms and conditions.', 'woocommerce'), 'error');
        }
    }
}

add_action('woocommerce_after_checkout_validation', 'o_woocommerce_confirm_password_validation', 10, 2);


add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10, 3);
function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email)
{
    global $woocommerce;

    extract($_POST);
    if (strcmp($password, $password2) !== 0) {
        return new WP_Error('registration-error', __('Passwords do not match.', 'woocommerce'));
    }
    if (!$terms_conditions)
        return new WP_Error('registration-error', __('Please read and accept the terms and conditions.', 'woocommerce'));
    return $reg_errors;
}


function render_star_raiting($rating, $maxRating = 5, $commentCount)
{
    $fullStar = "<li><i class='fa fa-star'></i></li>";
    $halfStar = "<li><i class='fa fa-star-half-full'></i></li>";
    $emptyStar = "<li><i class='fa fa-star-o'></i></li>";
    $rating = $rating <= $maxRating ? $rating : $maxRating;

    $fullStarCount = (int)$rating;
    $halfStarCount = ceil($rating) - $fullStarCount;
    $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

    $html = str_repeat($fullStar, $fullStarCount);
    $html .= str_repeat($halfStar, $halfStarCount);
    $html .= str_repeat($emptyStar, $emptyStarCount);
    
    if($commentCount)
        $html .= '<li>(' . $commentCount . ')</li>';

    $html = '<ul class="star_rating">' . $html . '</ul>';
    return $html;
}


/**
 * Add the title to comments field
 */


add_action('comment_form_logged_in_after', 'comment_title_field');
add_action('comment_form_after_fields', 'comment_title_field');
function comment_title_field()
{
    $content = ' <label for="comment_title">Title</label>';
    $content .= '<input type="text" name="comment_title" id="comment_title" />';
    $content = "<p class='comment-form-title'>$content</p>";

    echo $content;
}


add_action('add_meta_boxes_comment', 'pmg_comment_tut_add_meta_box');
function pmg_comment_tut_add_meta_box()
{
    add_meta_box('pmg-comment-title', __('Comment Title'), 'pmg_comment_tut_meta_box_cb', 'comment', 'normal', 'high');
}

function pmg_comment_tut_meta_box_cb($comment)
{
    $title = get_comment_meta($comment->comment_ID, 'pmg_comment_title', true);
    wp_nonce_field('pmg_comment_update', 'pmg_comment_update', false);
    ?>
    <p>
        <label for="pmg_comment_title"><?php _e('Comment Title'); ?></label>
        <input type="text" name="pmg_comment_title" value="<?php echo esc_attr($title); ?>" class="widefat"/>
    </p>
    <?php
}

add_action('edit_comment', 'pmg_comment_tut_edit_comment');
function pmg_comment_tut_edit_comment($comment_id)
{
    if (!isset($_POST['pmg_comment_update']) || !wp_verify_nonce($_POST['pmg_comment_update'], 'pmg_comment_update')) return;
    if (isset($_POST['pmg_comment_title']))
        update_comment_meta($comment_id, 'pmg_comment_title', esc_attr($_POST['pmg_comment_title']));
}

add_action('comment_post', 'pmg_comment_tut_insert_comment', 10, 1);
function pmg_comment_tut_insert_comment($comment_id)
{
    if (isset($_POST['comment_title']))
        update_comment_meta($comment_id, 'pmg_comment_title', esc_attr($_POST['comment_title']));
}


add_action('load-edit-comments.php', 'wpse64973_load');
function wpse64973_load()
{
    $screen = get_current_screen();
    add_filter("manage_{$screen->id}_columns", 'wpse64973_add_columns');
}

function wpse64973_add_columns($cols)
{
    $cols['title'] = __('Comment Title', 'wpse64973');
    return $cols;
}

add_action('manage_comments_custom_column', 'wpse64973_column_cb', 10, 2);
function wpse64973_column_cb($col, $comment_id)
{
    // you could expand the switch to take care of other custom columns
    switch ($col) {
        case 'title':
            if ($t = get_comment_meta($comment_id, 'pmg_comment_title', true)) {
                echo esc_html($t);
            } else {
                esc_html_e('No Title', 'wpse64973');
            }
            break;
    }
}

/**
 * Track viewed products
 */

function custom_track_product_view()
{
    if (!is_singular('product')) {
        return;
    }

    global $post;

    if (empty($_COOKIE['woocommerce_recently_viewed']))
        $viewed_products = array();
    else
        $viewed_products = (array)explode('|', $_COOKIE['woocommerce_recently_viewed']);

    if (!in_array($post->ID, $viewed_products)) {
        $viewed_products[] = $post->ID;
    }

    if (sizeof($viewed_products) > 15) {
        array_shift($viewed_products);
    }

    array_unique($viewed_products);

    // Store for session only
    wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
}

add_action('template_redirect', 'custom_track_product_view', 20);


/**
 * Wishlist send [wish_list_send]
 */

function wish_list_send($atts)
{


    $user_id = $atts['user_id'];
    $wishlist = get_field('wishlist', 'user_' . $user_id);
    $output = '';

    if ($wishlist) {
        $wishlist = explode('|', $wishlist);
        foreach ($wishlist as $item)
            $output .= "<a href='" . get_the_permalink($item) . "'>" . get_the_title($item) . "</a><br>";
    }


    return $output;


}


add_shortcode('wish_list_send', 'wish_list_send');


// Allow custom shortcodes in CF7 HTML form
add_filter('wpcf7_form_elements', 'dacrosby_do_shortcodes_wpcf7_form');
function dacrosby_do_shortcodes_wpcf7_form($form)
{
    $form = do_shortcode($form);
    return $form;
}

// Allow custom shortcodes in CF7 mailed message body
add_filter('wpcf7_mail_components', 'dacrosby_do_shortcodes_wpcf7_mail_body', 10, 2);
function dacrosby_do_shortcodes_wpcf7_mail_body($components, $number)
{
    $components['body'] = do_shortcode($components['body']);
    return $components;
}


function get_currency_info($currency_code)
{


    switch ($currency_code) {
        case 'GBP':
            $name = "<span class='hidden-xl-down'>Pound sterling</span> ($currency_code)";
            $symbol = "£";
            break;
        case 'USD':
            $name = "<span class='hidden-xl-down'>United States dollar</span> ($currency_code)";
            $symbol = "$";
            break;
        case 'EUR':
            $name = "<span class='hidden-xl-down'>Euro</span> ($currency_code)";
            $symbol = "€";
            break;
        default:
            return false;
    }

    return [
        'name' => $name,
        'symbol' => $symbol
    ];

}


function add_brand_image_field()
{
    get_section('admin/add_brand_image');
}

add_action('product_brand_add_form_fields', 'add_brand_image_field');


function edit_brand_image_field($term)
{

    $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);

    if ($thumbnail_id) {
        $image = wp_get_attachment_thumb_url($thumbnail_id);
    } else {
        $image = wc_placeholder_img_src();
    }

    get_section('admin/edit_brand_image', [
        'thumbnail_id' => $thumbnail_id,
        'image' => $image
    ]);
}

add_action('product_brand_edit_form_fields', 'edit_brand_image_field');


function save_brand_image_field($term_id, $tt_id = '', $taxonomy = '')
{
    if (isset($_POST['product_cat_thumbnail_id'])) {
//        wp_die($term_id);
        update_term_meta($term_id, 'thumbnail_id', absint($_POST['product_cat_thumbnail_id']));
    }
}

add_action('created_term', 'save_brand_image_field');
add_action('edit_term', 'save_brand_image_field');

function get_price_in_currency($price, $to_currency = null, $from_currency = null) {

    if(empty($from_currency))

        $from_currency = get_option('woocommerce_currency');

    if(empty($to_currency))

        $to_currency = get_woocommerce_currency();

    return apply_filters('wc_aelia_cs_convert', $price, $from_currency, $to_currency);


}

function wc_cart_discount_totals_html()
{


    $cart = WC()->cart;

    $discount = 0;

    foreach ($cart->cart_contents as $cart_item) {


        if ((int)$cart_item['line_total'] === 0)

            $discount = $discount + (float)$cart_item['discounts']['display_price'];

    }

    return wc_price($discount);


}
