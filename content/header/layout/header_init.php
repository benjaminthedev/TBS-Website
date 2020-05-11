<?php
get_header_part('tagline');
get_header_part('main_header');
get_header_part('main_nav');
get_header_part('mobile_nav');
?>



<?php /* <div class="header_spacer hidden-sm-down"></div> */ ?>



<?php if (have_rows('header_store_info', 'options')) : ?>

    <div class="header_store_info">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <?php while (have_rows('header_store_info', 'options')) : the_row();
                        $image = get_sub_field('image');
                        $title = get_sub_field('title');
                        $content = get_sub_field('content');
                        $link = get_sub_field('link');
                        echo "<div class='col-3'><a><span class='image'><a href=$link><img src=$image /></span><span class='title'>$title</span><span class='content'>$content</span></a></div>";
                    endwhile; ?>
                </div>
            </div>
        </div>
    </div>


<?php endif; ?>











