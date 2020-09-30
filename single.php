<?php

$featured_video = get_field('featured_video');

$the_post_thumbnail_url = get_the_post_thumbnail_url(null, 'full');

get_header();

if (have_posts()) :

    while (have_posts()) : the_post(); ?>

        <section class="vlog_section">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-6">

                        <div class="vlog_content">

                            <h1><?php the_title(); ?></h1>


                        </div>

                    </div>

                    <div class="col-md-6 video_wrap"

                        <?php echo has_post_thumbnail() ? "style='background-image: url($the_post_thumbnail_url)'" : '' ?> >


                        <?php if ($featured_video) : ?>

                            <div class="play_button"

                                 data-toggle="modal"

                                 data-target="#vlog_modal"

                                 data-play="vlog_modal_video">

                                <?php echo get_img('playbtn.png', 'play button'); ?>

                            </div>

                        <?php endif; ?>


                    </div>

                </div>

            </div>

        </section>

        <div class="section_container">

            <div class="container">

                <?php the_content(); ?>

            </div>

        </div>

        <?php if ($featured_video) : ?>

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

        <?php endif; ?>

    <?php endwhile;

endif;

get_footer(); ?>
