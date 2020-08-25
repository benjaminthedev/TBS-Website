<footer id="social_footer">

    <div class="container-fluid">

        <div class="row">

            <?php if (have_rows('social_links', 'options')) : ?>

                <div class="col">

                    <div class="social_links">

                        <ul>

                            <?php while (have_rows('social_links', 'options')) : the_row();

                                $icon = get_sub_field('icon');

                                $link = get_sub_field('link');

                                $extra_attributes = get_sub_field('extra_attributes');

                                echo $link && $icon ? "<li><a href='$link' rel='nofollow noopener noreferrer' target='_blank' $extra_attributes>$icon</a></li>" : '';

                            endwhile; ?>

                        </ul>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</footer>

<footer id="email_sign_up">
	
	<div class="container-fluid">

        <div class="row">
	
            <div class="col">

             <!-- Begin Mailchimp Signup Form -->
<div id="mc_embed_signup">
<form action="https://thebeautystore.us8.list-manage.com/subscribe/post?u=a256b3433ee594374bef00ca6&amp;id=2d603aff8f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" rel="nofollow noopener noreferrer" target="_blank" novalidate>
    <div id="mc_embed_signup_scroll">
	
	<input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="SIGN UP FOR THE LATEST Offers" required>
    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_a256b3433ee594374bef00ca6_2d603aff8f" tabindex="-1" value=""></div>
    <div class="clear"><input type="submit" value="SIGN UP" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
    </div>
</form>
</div>

<!--End mc_embed_signup-->

            </div>
	 </div>

    </div>
	</footer>

<footer id="footer_navigation">

    <div class="container">

        <div class="row">

            <?php if (have_rows('footer_navigation', 'options')) :

                while (have_rows('footer_navigation', 'options')) : the_row(); ?>

                    <div class="col-lg-3 col-sm-6 footer_block">

                        <?php $title = get_sub_field('title');

                        echo $title ? "<h4>$title</h4>" : '';

                        if (have_rows('navigation')) : ?>

                            <ul>

                                <?php while (have_rows('navigation')) : the_row();

                                    $link_type = get_sub_field('link_type');

                                    $link = get_sub_field($link_type . '_link');

                                    $title = $link_type === 'internal' ? get_the_title($link->ID) : $link->name;

                                    $link = $link_type === 'category' ? get_term_link($link, 'product_cat') : $link;

                                    $link = $link_type === 'brand' ? get_term_link($link, 'product_brand') : $link;

                                    $link = $link_type === 'internal' ? get_permalink($link->ID) : $link;

                                    echo $link ? "<li><a href='$link'>$title</a></li>" : '';

                                endwhile; ?>

                            </ul>

                        <?php endif; ?>

                    </div>

                <?php endwhile;

            endif; ?>

            <div class="col-lg-3 col-sm-6 footer_block">

                <g:ratingbadge merchant_id=102295467></g:ratingbadge>​

                <div class="trustpilot-widget"   data-locale="en-GB" data-template-id="53aa8807dec7e10d38f59f32"

                     data-businessunit-id="52fb37910000640005783555" data-style-height="100px" data-style-width="165px"

                     data-theme="dark">

                    <a href="https://www.trustpilot.com/review/www.thebeautystore.co.uk" rel="nofollow noopener noreferrer" target="_blank">Trustpilot</a>

                </div>

            </div>

			
        </div>

    </div>

</footer>

<?php $footer_credits = get_field('footer_credits', 'options'); ?>

<footer id="footer_credits">

    <div class="container">

        <div class="row">

            <?php echo $footer_credits ? "<div class='col-md-6 credits'>$footer_credits</div>" : '' ?>

            <div class="col-md-6 cards text-lg-right">

                <?php echo get_img('cards.jpg', 'Accepted Cards') ?>

            </div>

        </div>

    </div>

</footer>


<?php wp_footer(); ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.26/jquery.form-validator.min.js"></script>
<?php echo get_field('body_scripts', 'option'); ?>

<script src="https://apis.google.com/js/platform.js" async defer></script>​
