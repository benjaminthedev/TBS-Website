<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product_cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header('shop');

$rating_count = $product->get_rating_count();
$review_count = $product->get_review_count();
$average      = round($product->get_average_rating());




$is_tax = is_tax('product_cat');
$search = isset($_GET['search']) ? $_GET['search'] : '';

$search = sanitize_text_field(stripslashes($search));

$new = isset($_GET['new']);
if (is_shop() && !$search)
    $title = get_the_title(wc_get_page_id('shop'));
elseif (is_shop() && $search)
    $title = 'Searched For: ' . htmlentities($search);
else
    $title = get_queried_object()->name;

global $wp_query;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'recent';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 12;

$cat_init = $is_tax ? get_queried_object()->term_id : [];
$cat = isset($_GET['category']) ? (int)$_GET['category'] : $cat_init;

$brand = is_tax('product_brand') ? [ get_queried_object()->term_id ] : [];
$brand = isset($_GET['brand']) ? array_map('intval', explode(',', $_GET['brand'])) : $brand;


$colour = isset($_GET['colour']) ? array_map('intval', explode(',', $_GET['colour'])) : [];
$colour = isset($_GET['colour']) ? array_map('intval', explode(',', $_GET['colour'])) : [];
$sizes = isset($_GET['size']) ? array_map('intval', explode(',', $_GET['size'])) : [];
$age_group = isset($_GET['age_group']) ? array_map('intval', explode(',', $_GET['age_group'])) : [];
$formulation = isset($_GET['formulation']) ? array_map('intval', explode(',', $_GET['formulation'])) : [];
$skin_type = isset($_GET['skin_type']) ? array_map('intval', explode(',', $_GET['formulation'])) : [];
$hazardous_goods = isset($_GET['hazardous_goods']) ? array_map('intval', explode(',', $_GET['hazardous_goods'])) : [];
$gender = isset($_GET['gender']) ? array_map('intval', explode(',', $_GET['gender'])) : [];
$spf = isset($_GET['gender']) ? array_map('intval', explode(',', $_GET['spf'])) : [];

global $product;


?>
<section id="static_content" class="no_margin">


    <?php
    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     * @hooked WC_Structured_Data::generate_website_data() - 30
     */
    do_action('woocommerce_before_main_content');

    echo ' <div class="loader"><div class="spinner"></div></div>';

    if ($is_tax || is_tax('product_brand')) {

        $thumb_id = get_term_meta(get_queried_object()->term_id, 'thumbnail_id', true);
        $term_img = wp_get_attachment_url($thumb_id);

        ?>


        <section class="vlog_section">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-8">

                        <div class="vlog_content">

                            <h1><?php echo $title; ?></h1>

                            <div <?php if( strpos( term_description(), '<!--more-->' ) ) : echo 'class="readmore"'; endif; ?>>

                                <?php 
                                if( strpos( term_description(), '<!--more-->' ) ) :
                                    $content_parts = get_extended( term_description() );

                                    echo '<div id="readmore-top">';
                                        echo $content_parts['main'];
                                    echo '</div>';

                                    echo $content_parts['extended'];
                                else :
                                    echo term_description(); 
                                endif; ?>

                            </div>

                        </div>

                    </div>

                    <?php if ($term_img) : ?>

                        <div class="col-md-4 video_wrap"

                            <?php echo "style='background-image: url($term_img)'" ?> >

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </section>

<script type="text/javascript">
      var clerk_response = false;
      var category_page_selector = "container"
      Clerk('on','response', function(data,content){
        if (content.result.length > 0) {
            clerk_response = true;
        }
      });
      setTimeout(
        function(){
          if (clerk_response == false) {
             $(category_page_selector).show();
          }
        }
      ,750)
</script>


<section>

<style>
.clerk-facet-group.clerk-facet-price .clerk-range-label-left:before {
    content: '£';
}

.clerk-facet-price .clerk-range-label-left:before,
.clerk-range-label-right:before {
    content: '£';
}

div#clerk-category-filters {
    width: 20%;
    float: left;
}


  @media only screen and (max-width:800px) {
            div#clerk-category-filters {
                display: none;
            }
                          }


 /*Addtional Styles*/

                            div#clerk-category-filters * {
                                color: black;
                            }

                            input.clerk-facet-search::placeholder {
                                color: black;
                            }
                            .clerk-facet-selected .clerk-facet-name:before {
                                background-color: #40E0D0;
                                border-color: #40E0D0;
                            }
                            .clerk-range-selected-range {
                                background-color: #40E0D0;
                            }
                           
</style>


<div class="container">
    <div class="row">
        <div class="col-3-sm">
            <div id="clerk-category-filters"></div>
            <ul id="clerk-category-results"></ul>
            <span
                id="clerk-category"
                class="clerk"
                data-template="@category-page-results"
                
                data-target="#clerk-category-results"
                data-category="<?php echo get_queried_object()->term_id;?>"
                data-facets-target="#clerk-category-filters"
                data-facets-attributes='["price","categories","product_brand","product_gender","product_fragrance_name","product_formulation","product_size","product_colour","product_skin_type","product_spf","product_age_group"]'
                data-facets-titles='{"price":"Price","categories":"Categories","product_brand":"Brands","product_gender":"Gender","product_fragrance_name":"Product Range","product_formulation":"Formulation","product_size":"Size","product_colour":"Colour","product_skin_type":"Skin Type","product_spf":"Skin Type","product_age_group":"Age Group"}'
                >
            </span>
        </div>



    </div><!-- end row -->
</div><!-- end container -->

</section>



    <?php } else echo "<div class='container no_border'><h1 class='section_title text-left shop_archive'><span>$title</span></h1></div>";
    ?>



    <div id="product_content_wrapper">

        <div class="container <?php echo $is_tax ? 'no_border' : 'no_padding_top' ?>">
            <header class="product_feed_header clearfix">

                <div class="filter_btn hidden-md-up">
                    <i class="fa fa-filter" aria-hidden="true"></i> Filter
                </div>
                <div class="sort">
                    <i class="fa fa-cog"></i>
                    <select name="sort_order" id="sort_order">
                        <?php
                        $sort_args = [
                            "A-Z" => "Title A-Z",
                            "Z-A" => "Title Z-A",
                            "LowHigh" => "Price Low-High",
                            "High-Low" => "Price High-Low",
                            "skuA-Z" => "Reference A-Z",
                            "skuZ-A" => "Reference Z-A",
                            "recent" => "Newest",
                            "old" => "Oldest",
                            "top" => "Best Sellers"
                        ];
                        foreach ($sort_args as $value => $title)
                            echo $sort === $value ? '<option value="' . $value . '" selected>' . $title . '</option>' : '<option value="' . $value . '">' . $title . '</option>';
                        ?>
                    </select>
                    <i class="fa fa-angle-down"></i>
                </div>
                <div class="per_page">
                    <?php
                    echo per_page($wp_query); ?>
                </div>
                <div class="pagination">
                    <ul>
                        <?php echo the_product_pagination($wp_query); ?>
                    </ul>
                </div>
            </header>
        </div>

        <div class="container">

            <div class="product_feed_wrap clearfix">
                <?php
                /**
                 * woocommerce_sidebar hook.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                do_action('woocommerce_sidebar');
                ?>
                <div class="products" id="product_feed_loader"

                     data-colour="<?php echo json_encode($colour); ?>"
                     data-sizes="<?php echo json_encode($sizes); ?>"
                     data-brand="<?php echo json_encode($brand); ?>"
                     data-age_group="<?php echo json_encode($age_group); ?>"
                     data-formulation="<?php echo json_encode($formulation); ?>"
                     data-skin_type="<?php echo json_encode($skin_type); ?>"
                     data-hazardous_goods="<?php echo json_encode($hazardous_goods); ?>"
                     data-gender="<?php echo json_encode($gender); ?>"
                     data-spf="<?php echo json_encode($spf); ?>"
                     data-sort="<?php echo $sort; ?>"
                     data-page="<?php echo $page; ?>"
                     data-per_page="<?php echo $per_page; ?>"
                     data-cat="<?php echo json_encode($cat); ?>"
                     data-cat_default="<?php echo json_encode($cat_init); ?>"
                     data-search="<?php echo htmlentities($search); ?>"
                >
                    <?php if (have_posts()) : ?>
                        <div class="row justify-content-center" id="product_row">
                            <?php while (have_posts()) : the_post(); ?>
                                <?php
                                /**
                                 * woocommerce_shop_loop hook.
                                 *
                                 * @hooked WC_Structured_Data::generate_product_data() - 10
                                 */
                                do_action('woocommerce_shop_loop');
                                ?>
                                <?php // wc_get_template_part('content', 'product'); ?>
                            <?php endwhile; // end of the loop. ?>
                        </div>
                        <?php
                    else:
                        do_action('woocommerce_no_products_found');
                    endif;
                    ?>


                </div>

            </div>

        </div>

<style>
.newnewBye{
	display:none!important;
}
div#product_content_wrapper {
    display: none;
}

</style>
        <div class="container margin_top_2 newnewBye">
            <footer class="product_feed_footer clearfix">
                <div class="per_page">
                    <?php echo per_page($wp_query); ?>
					
                </div>
                <div class="pagination">
                    <ul>
                        <?php echo the_product_pagination($wp_query); ?>
                    </ul>
                </div>
            </footer>
        </div>

    </div>

    <?php
    /**
     * woocommerce_after_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action('woocommerce_after_main_content');
    ?>


</section>
<?php get_footer('shop'); ?>