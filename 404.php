<?php get_header(); ?>

    <section id="static_content">

        <div class="container">

            <div class="row">

                <div class="col-12">

                    <?php the_breadcrumb(); ?>

                </div>

            </div>

            <h1 class="section_title text-left"><span>404</span></h1>

            <img src="https://www.thebeautystore.com/wp-content/uploads/2019/11/404.png" alt="404 sorry!" />

            Sorry Couldn't find what you were looking for. <a href="<?php echo get_site_url(); ?>">Go home.</a>

        </div>

    </section>

<?php get_footer(); ?>