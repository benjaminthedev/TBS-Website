<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 25/05/2017
 * Time: 13:44
 * Template Name: Content Center
 */

get_header(); ?>

<section id="static_content">

    <div class="container text-center">

        <?php if (have_posts()) :

            while (have_posts()) : the_post(); ?>

                <div class="row">

                    <div class="col-12">

                        <?php the_breadcrumb(); ?>

                    </div>

                </div>

                <h1 class="section_title"><span><?php the_title() ?></span></h1>

                <?php the_content(); ?>

            <?php endwhile;

        endif; ?>

    </div>

</section>

<?php get_footer(); ?>