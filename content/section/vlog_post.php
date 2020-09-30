<?php

$featured_vlog = get_field('featured_vlog');

$featured_video = get_field('featured_video', $featured_vlog);

$args = [
    'posts_per_page' => 1,
    'category_name' => 'vlog',
    'post__in' => array($featured_vlog)
];

$the_query = new WP_Query($args);

if ($the_query->have_posts() && $featured_vlog && $featured_video) :

    while ($the_query->have_posts()) : $the_query->the_post();

        $the_post_thumbnail_url = get_the_post_thumbnail_url(null, 'full');

        ?>

        <section class="vlog_section">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-6">

                        <article class="vlog_content">

                            <div class="sub_title">

                                Tried & Tested

                            </div>

                            <h2><?php the_title(); ?></h2>

                            <p><?php echo get_excerpt(get_the_content(), 355); ?></p>

                            <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="btn btn-warning">WATCH VLOG</a>

                        </article>

                    </div>

                    <div class="col-md-6 video_wrap"

                        <?php echo has_post_thumbnail() ? "style='background-image: url($the_post_thumbnail_url)'" : '' ?> >

                        <div class="play_button"

                             data-toggle="modal"

                             data-target="#vlog_modal"

                             data-play="vlog_modal_video">

                            <?php echo get_img('playbtn.png', 'play button'); ?>

                        </div>

                    </div>

                </div>

            </div>

        </section>

        <div class="modal fade video_modal" id="vlog_modal">

            <div class="modal-dialog video" role="document">

                <div class="modal-content">

                    <div class="modal-body">

                        <div class="fa fa-times" data-dismiss="modal" aria-label="Close"></div>

                        <div class="embed-responsive embed-responsive-16by9">

                            <video

                                    controls

                                    id="vlog_modal_video"

                                    class="video-js embed-responsive-item"

                                    poster="<?php echo has_post_thumbnail() ? $the_post_thumbnail_url : ''; ?>"

                                    data-setup='{ "techOrder": ["youtube"], "sources": [{ "type": "video/youtube", "src": "<?php echo $featured_video; ?>"}] }'>

                            </video>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endwhile;

endif;

wp_reset_postdata(); ?>