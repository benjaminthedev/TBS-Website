<?php
/*
Template Name: Custom Search
* User: jackm
* Date: 15/09/2020
* Time: 15:21
*/
?>
<?php get_header(); ?>
<section id="static_content">
<div class="container-fluid">
<?php if (have_posts()) :
while (have_posts()) : the_post(); ?>
<div class="row">
<div class="col-md-12">
<?php the_breadcrumb(); ?>
</div>
</div>
<section>
<hr class="wp-block-separator is-style-wide" />			
<style>
.clerk-facet-group.clerk-facet-price .clerk-range-label-left:before {content: '£';}
.clerk-facet-price .clerk-range-label-left:before,
.clerk-range-label-right:before {content: '£';}
div#clerk-search-filters {width: 20%;float: left;}
  @media only screen and (max-width:1024px) {div#clerk-search-filters {display: none;}}
 /*Addtional Styles*/
div#clerk-search-filters * {color: black;}
input.clerk-facet-search::placeholder {color: black;}
.clerk-facet-selected .clerk-facet-name:before {background-color: #40E0D0;border-color: #40E0D0;}
.clerk-range-selected-range {background-color: #40E0D0;}                           
</style>
<div class="row">
<div class="col">
<div id="clerk-search-filters"></div>
<span
class="clerk"
data-template="@search-page" 
data-query="<?php echo esc_attr(get_query_var('searchterm')); ?>"
data-facets-in-url="true"
data-facets-target="#clerk-search-filters"
data-facets-attributes='["categories","product_brand","product_fragrance_name","product_gender","product_formulation","product_colour","product_skin_type","product_spf","brands","price","product_size"]'
data-facets-titles='{"categories":"Categories","product_brand":"Brands","product_fragrance_name":"Product Range","product_gender":"Gender","product_formulation":"Formulation","product_colour":"Colour","product_skin_type":"Skin Type","product_spf":"Skin Type","brands":"Brands","price":"Price","product_size":"Size"}'>
</span>
</div>
</div><!-- end row -->
</section>
<?php endwhile;
endif; ?>
</div>
</section>
<?php get_footer(); ?>