<?php get_header(); ?>

    <section id="static_content">

        <div class="container">

            <div class="row">

                <div class="col-12">

                    <?php the_breadcrumb(); ?>

                </div>

            </div>


            <h1 class="section_title text-left"><span>Beauty News</span></h1>
        </div>

        <div class="header_spacer white">
            <ul>
                <li><a href="<?php echo get_permalink(get_option('page_for_posts')) ?>">All</a></li>
                <?php wp_list_categories([
                    'title_li' => '',
                    'exclude' => [1]
                ]); ?>
            </ul>

        </div>
        <div class="container">
            <?php if (have_posts()) : ?>
                <div class="row blog_row blog_page">
                    <?php while (have_posts()) : the_post(); ?>

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

                <?php // Previous/next page navigation.
                the_posts_pagination(array(
                    'prev_text' => __('Previous page'),
                    'next_text' => __('Next page'),
                ));endif; ?>


        </div>

    </section>


<?php get_footer();