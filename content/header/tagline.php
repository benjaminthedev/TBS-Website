<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 22/05/2017
 * Time: 16:16
 */
$offer_tagline = get_field('offer_tagline', 'options');
echo $offer_tagline ? '<header id="offer"><div class="container">' . $offer_tagline . '<div class="close_btn">Close <b>X</b></div></div></header>' : ''; ?>