<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 23/05/2017
 * Time: 10:25
 */
?>

<section class="section_container">

    <div class="container">

        <h1 class="section_title"><span>We Recommend</span></h1>

        <div id="product_tabs">

            <ul class="controls">

                <li><a href="#" data-tab="0" class="active"></a></li>

            </ul>

            <div class="owls">

                <ul class="slides clearfix">

                    <?php get_section('tabs/featured'); ?>

                </ul>
            </div>

        </div>

    </div>

</section>
