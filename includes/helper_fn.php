<?php

/* =============== gincludes php files by folder path =============== */

function __autoload($folder_path)
{
    foreach (glob($folder_path . '/*.php') as $filename) {
        require_once $filename;
    }
}

/* =============== get asset helper =============== */

function get_image_dir_uri()
{
    return get_template_directory_uri() . "/assets/images/";
}

function get_css_dir_uri()
{
    return get_template_directory_uri() . "/assets/css/";
}

function get_js_dir_uri()
{
    return get_template_directory_uri() . "/assets/js/";
}

function get_font_dir_uri()
{
    return get_template_directory_uri() . "/assets/fonts/";
}

function get_img($name, $alt = '', $internal = true)
{
    $url = $internal ? get_image_dir_uri() . $name : $name;
    return '<img src="' . $url . '" alt="' . $alt . '" />';
}

/* =============== ///get asset helper =============== */

/* =============== get_post_thumbnail =============== */

function get_post_thumbnail($postID, $imgClass = 'thumbnail')
{
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($postID), $imgClass);

    if ($thumb) {
        return $thumb['0'];
    } else {
        return false;
    }
}

/* =============== print a object and style with pre =============== */

function print_object($o, $js = false)
{
    if (!$js) {
        echo '<pre>' . print_r($o, true) . '</pre>';
    } else {
        echo '<script type="text/javascript">console.log(' . json_encode($o) . ');</script>';
    }
}

/* =============== returns the month by num =============== */

function get_month_by_num($num)
{
    $mons = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

    return $mons[$num];
}

/* =============== favicons ===============  */

function favicon()
{
    $path = get_template_directory_uri() . "/assets/images/icons/favicon/";

    echo '
	<link rel="shortcut icon" href="' . $path . '/favicon.ico" type="image/x-icon" />
	<link rel="apple-touch-icon" href="' . $path . '/apple-touch-icon.png" />
	<link rel="apple-touch-icon" sizes="57x57" href="' . $path . '/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="' . $path . '/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon" sizes="76x76" href="' . $path . '/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="' . $path . '/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon" sizes="120x120" href="' . $path . '/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon" sizes="144x144" href="' . $path . '/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon" sizes="152x152" href="' . $path . '/apple-touch-icon-152x152.png" />
	';
}

/* =============== exclude_archives ===============  */

function exclude_archives($query)
{
    if (!is_admin() && $query->is_archive() && !$query->is_date()) {
        $catID = get_cat_ID("Archived");

        if (!$catID)
            return;
        $query->set('category__not_in', $catID);
    }
}

/* add_action('pre_get_posts','exclude_archives'); */


/* =============== Sidebar, Footer Widgets =============== */

/*
  $sidebars = array('Sidebar1', 'Sidebar2', 'Sidebar3');
  foreach ($sidebars as $sidebar) {
  register_sidebar(array('name'=> $sidebar,
  'before_widget' => '<article id="%1$s" class="row widget %2$s"><div class="classname">',
  'after_widget' => '</div></article>',
  'before_title' => '<h6><strong>',
  'after_title' => '</strong></h6>'
  ));
  }
 */


/* =============== Return post meta information   ===============  */

function pd_entry_meta()
{
    echo '<time class="updated" datetime="' . get_the_time('c') . '" pubdate>' . sprintf(__('Posted on %s at %s.', 'pd'), get_the_time('l, F jS, Y'), get_the_time()) . '</time>';
    echo '<p class="byline author">' . __('Written by', 'pd') . ' <a href="' . get_author_posts_url(get_the_author_meta('ID')) . '" rel="author" class="fn">' . get_the_author() . '</a></p>';
}



/* =============custom excerpt length ============== */

function custom_excerpt_length($length)
{
    return (is_front_page()) ? 15 : 20;
}

//add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/* ============= Replaces the excerpt "more" text by a link ============== */

function new_excerpt_more($more)
{
    global $post;
    $perma = get_permalink($post->ID);
    return '<a class="moretag" href="' . $perma . '"> Read more ' . ((is_front_page()) ? '' : '>>') . ' </a>';
}

//add_filter('excerpt_more', 'new_excerpt_more');


/* =============  creating functions post_remove for removing menu item */
function post_remove()
{
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}

//add_action('admin_menu', 'post_remove');
//add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

function remove_wp_logo($wp_admin_bar)
{
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_menu('new-post');
    $wp_admin_bar->remove_menu('new-posts');
}

/* ============ shortcode =================== */
?>