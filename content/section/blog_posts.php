<?php
$args = [
    'posts_per_page' => 2,
//    'category__not_in' => array( 2268 ),
];

$the_query = new WP_Query( $args );

if ($the_query->have_posts()) : ?>

    <section class="section_container">

        <div class="container-fluid">

            <h2 class="section_title"><span>Beauty News</span></h2>

            <div class="row blog_row">

                <?php while ($the_query->have_posts()) : $the_query->the_post();

                     ?>

                    <article class="col-md-6">

                        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="blog_post">

                            <?php if (has_post_thumbnail()) : ?>

                                <div class="image"><?php the_post_thumbnail('blog-thumb') ?></div>

                            <?php endif; ?>

                            <div class="title"><?php the_title(); ?></div>

                            <div class="read_more">Read More</div>

                        </a>

                    </article>

                <?php endwhile; ?>

            </div>

        </div>

    </section>

<?php endif; ?>

<?php wp_reset_postdata(); ?>