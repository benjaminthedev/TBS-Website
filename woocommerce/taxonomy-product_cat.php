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

// $rating_count = $product->get_rating_count();
// $review_count = $product->get_review_count();
// $average      = round($product->get_average_rating());




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
<section id="static_content">


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
	
  @media only screen and (max-width:1024px) {
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


<div class="container-fluid">
    <div class="row">
        <div class="col">
            <div id="clerk-category-filters"></div>
            <ul id="clerk-category-results"></ul>
            <span
                id="clerk-category"
                class="clerk"
                data-template="@category-page-results"
                data-facets-in-url="true"
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



<script type="text/javascript">

/*issue happening here:

https://www.thebeautystore.com/hair-care/hair-treatments/dandruff-products/

/hair-care/
*/

                            console.log('hair-treatments/dandruff-products/');
                             console.log('HELLO WORLD :-');



  // handle rendered events but only for popular products


    //   var clerk_response = false;
    //   var category_page_selector = "clerk-category";




  setTimeout(function(){ 
      
        const gotEle = document.querySelector = 'clerk-category-results';
        console.log(gotEle);

        const newError = document.getElementById('error-page');
        console.log(`the length is £{newError.length}`);

        if(gotEle.length > 1){
            console.log('Products are loaded')
        } else {
            console.log('NO Products are loaded')
        }




      
    }, 3000);





    //   Clerk('on','response', function(data,content){
    //     if (content.result.length > 0) {
    //         clerk_response = true;
    //         console.log('logging true')
    //     }
    //   });
    //   setTimeout(
    //     function(){
    //       if (clerk_response == false) {
    //          $(category_page_selector).show();
    //          console.log('logging false')
    //       }
    //     }
    //   ,2750)
</script>



    <?php } ?>
    
    
    
    
    

<style>
.newnewBye{
	display:none!important;
}
div#product_content_wrapper {
    display: none;
}

</style>


  

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