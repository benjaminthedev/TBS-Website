<?php

$the_post_thumbnail_url = get_the_post_thumbnail_url(null, 'full');

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

        <?php if (have_rows('stores')) : ?>

            <div class="section_container">

                <div class="container">

                    <?php while (have_rows('stores')): the_row();

                        $title = get_sub_field('title');

                        $address = get_sub_field('address');

                        $i = get_row_index();

                        $logo = get_sub_field('logo');

                        $location = get_sub_field('location');

                        ?>

                        <div class="store_row">

                            <div class="row">

                                <div class="col-md-6">

                                    <?php echo $logo ? get_img($logo['url'], $logo['alt'], false) : ''; ?>

                                    <?php echo $location ? "<div class='map' id='location_$i'></div>" : '' ?>

                                </div>

                                <div class="col-md-6">

                                    <?php echo $title ? "<div class='title'>The Beauty Store $title</div>" : '' ?>

                                    <?php echo $address ? "<address>$address</address>" : ''; ?>

                                    <?php if (have_rows('opening_hours')) : ?>

                                        <div class="title">Opening Hours</div>

                                        <table class="opening_hours">

                                            <?php while (have_rows('opening_hours')) : the_row();

                                                $day = get_sub_field('day');

                                                $open = get_sub_field('open');

                                                $close = get_sub_field('close');

                                                echo $day && $open && $close ? "<tr><td class='day'>$day</td><td class='hours'>$open - $close</td></tr>" : '';

                                            endwhile; ?>

                                        </table>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    <?php endwhile; ?>

                </div>

            </div>

        <?php endif; ?>

    <?php endwhile;

endif;

get_footer(); ?>
