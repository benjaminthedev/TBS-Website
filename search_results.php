<?php
/*
Template Name: Search Results
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

                    </div>

                    <h1 class="section_title <?php echo is_account_page() ? 'text-center' : 'text-left' ?>"><span><?php the_title() ?></span></h1>

                    <?php the_content(); ?>

                <?php endwhile;

            endif; ?>

        </div>

    </section>

<?php get_footer(); ?>