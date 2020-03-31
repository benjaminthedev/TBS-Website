<?php
/**
 * Sidebar
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/sidebar.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     1.6.4
 */
global $wp_query;
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$is_cat = is_tax('product_cat');
$is_brand = is_tax('product_brand');
$key = $is_cat ? 'product_cat' : 'product_brand';
$query_object = get_queried_object();


if ($is_cat || $is_brand) {
    $tags = new get_all_tags(false, true, false, true);
    $posts = $tags->get_post_object([$key => $query_object->term_id], false);
    $posts = is_array($posts) ? implode(',', $posts) : $posts;
    $tag_object = $tags->get_tax_object($posts);
    $tags->tags = $tags->get_tags($tag_object, $query_object->term_id, false);
} else {
    $tags = new get_all_tags();
}
?>


    <div class="sidebar">

        <button type="button" class="close_sidebar hidden-md-up">
            <i class="fa fa-times"></i>
        </button>

        <div class="sidebar_box active">
            <header class="header">Your Selection</header>
            <div class="feed" style="display:block;">
                <ul id="selection">
                    <?php echo $is_cat || $is_brand ? '<li>' . $query_object->name . '</li>' : ''; ?>
                </ul>
            </div>
        </div>

        <div id="product_tags">


            <?php if ($tags) :
                $x = 0;
                foreach ($tags->tags as $tax_name => $tax_items) :
                    $x++;
                    $taxonomy = get_taxonomy($tax_name);
                    $active_taxes = [];
                    if ($tax_name === 'product_hazardous_goods') continue;
                    if (isset($_GET[str_replace('product_', '', $tax_name)]))
                        $active_taxes = array_map('intval', explode(',', $_GET[str_replace('product_', '', $tax_name)]));
                    ?>
                    <div class="sidebar_box <?php echo $x < 2 ? 'active' : ''; ?>" data-tax="<?php echo $tax_name; ?>">
                        <header class="header"><?php echo $tax_name === 'product_cat' ? 'Select Department' : $taxonomy->label; ?></header>
                        <div class="feed" <?php echo $x < 2 ? 'style="display:block;"' : ''; ?>>
                            <ul id="<?php echo $tax_name; ?>" class="filter">
                                <?php foreach ($tax_items as $child)
                                    echo in_array($child->term_id, $active_taxes) ?
                                        "<li data-value='$child->term_id' data-tax='$tax_name' class='active'>$child->name</li>" :
                                        "<li data-value='$child->term_id' data-tax='$tax_name'>$child->name</li>";
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                endforeach;
            endif; ?>

        </div>

        <?php
        if (have_rows('advertisement', 'options'))
            get_section('advertisement');
        ?>


    </div>


<?php
/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */


