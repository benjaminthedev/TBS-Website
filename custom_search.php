<?php
/*
Template Name: Search Results Latest
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

                    <div class="row">
                      <div class="col-md-3">
                            <!-- Trying to get filters to work here: -->
                            <div id="clerk-search-filters"></div>
                            <!-- Filters End -->
                            <style>
                            div#clerk-search-filters {
                                display: contents;
                            }

                            /*Addtional Styles*/
								
							.clerk-facet-group.clerk-facet-price .clerk-range-label-left:before { content: '£';
								}
							.clerk-facet-price .clerk-range-label-left:before,
							.clerk-range-label-right:before { content: '£';
								}

                            div#clerk-search-filters * {
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
                            .clerk-facet-stock,
                            .clerk-facet-_yoast_wpseo_focuskw,
							.clerk-facet-on_sale,
							.clerk-facet-_wc_average_rating{
                                display: none !important;
                            }
								
							.clerk-facet-stock,
                            .clerk-facet-_msrp_price {
								display: none !important;
                            }

                            @media only screen and (max-width:1024px) {
                            div#clerk-search-filters * {
                              display:none;
                            }
                          }


                          </style>
                        </div>


                      <div class="col-md-9">
                        <?php the_content(); ?>
                      </div>

                    </div><!-- end row -->


                <?php endwhile;
            endif; ?>
</div>
    </section>
<?php get_footer(); ?>
