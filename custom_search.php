<?php
/*
Template Name: Search Results Latest
*/
?>
<?php get_header(); ?>
    <section id="static_content" >
        <div class="container">
            <?php if (have_posts()) :
                while (have_posts()) : the_post(); ?>
                    <div class="row">

                        <div class="col-12">
                            <?php the_breadcrumb(); ?>
                        </div>

                      <h1 class="section_title <?php echo is_account_page() ? 'text-center' : 'text-left' ?>">
                        <span><?php the_title() ?></span>
                      </h1>

                    </div>

                  <div class="clearfix"></div>


                    <div class="row">
                      <div class="col-3 is-responsive">
                            <!-- Trying to get filters to work here: -->
                            <div id="clerk-search-filters"></div>
                            <!-- Filters End -->
                            <style>
                            div#clerk-search-filters {
                                display: contents;
                            }



                            /*Addtional Styles*/

                            div#clerk-search-filters * {
                                color: black;
                            }

                            .clerk-facet-group-title {
                                font-family: "bromello", sans-serif;
                                font-size: 18px;
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
                            .clerk-facet-group {
                                /* border: 1px solid #ff0cb5; */
                                -webkit-box-shadow: 3px 3px 10px 0px rgba(50, 50, 50, 0.45);
                            -moz-box-shadow:    3px 3px 10px 0px rgba(50, 50, 50, 0.45);
                            box-shadow:         3px 3px 10px 0px rgba(50, 50, 50, 0.45);
                            }


                            .clerk-facet-stock,
                            .clerk-facet-_yoast_wpseo_focuskw {
                                display: none !important;
                            }
								
							.clerk-facet-stock,
                            .clerk-facet-_msrp_price {
								display: none !important;
                            }

                            @media only screen and (max-width:800px) {
                            .is-responsive {
                              display:none;
                            }
                          }


                          </style>
                        </div>


                      <div class="col-9">
                        <?php the_content(); ?>
                      </div>

                    </div><!-- end row -->


                <?php endwhile;
            endif; ?>

    </section>
<?php get_footer(); ?>
