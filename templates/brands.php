<?php

$the_post_thumbnail_url = get_the_post_thumbnail_url(null, 'full');

$brands = [];

$product_brand = get_terms(['taxonomy' => 'product_brand', 'hide_empty' => true]);

foreach ($product_brand as $brand)
    $brands[strtoupper($brand->name[0])][] = [
        'title' => $brand->name,
        'link' => get_term_link($brand, '$brand')
    ];

$brands = array_chunk($brands, 9, true);


get_header(); ?>

<section id="static_content" class="no_margin">

    <div class="container">

        <div class="row">

            <div class="col-12">

                <?php the_breadcrumb(); ?>

            </div>

        </div>

    </div>

</section>

<?php if (have_posts()) :

    while (have_posts()) : the_post(); ?>

        <section class="vlog_section">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-6">

                        <div class="vlog_content">

                            <h1><?php the_title(); ?></h1>

                            <?php the_content(); ?>

                        </div>

                    </div>

                    <div class="col-md-6 video_wrap"

                        <?php echo has_post_thumbnail() ? "style='background-image: url($the_post_thumbnail_url)'" : '' ?> >

                    </div>

                </div>

            </div>

        </section>

        <section class="section_container">

            <div class="container">

                <div class="row">

                    <?php foreach ($brands as $col) : ?>

                        <div class="col-md-4">

                            <?php foreach ($col as $letter => $brands) : ?>


                                <div class="brand_block">


                                    <?php echo "<div class='title'>$letter</div>" ?>

                                    <ul class="brands">

                                        <?php foreach ($brands as $brand)

                                            echo '<li><a href="' . $brand['link'] . '">' . $brand['title'] . '</a></li>' ?>

                                    </ul>

                                </div>

                            <?php endforeach; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>


    <?php endwhile; ?>

<?php endif; ?>

<?php get_footer(); ?>
