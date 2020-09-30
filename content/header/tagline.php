<?php
$offer_tagline = get_field('offer_tagline', 'options');
echo $offer_tagline ? '<header id="offer" class="offer"><div class="container">' . $offer_tagline . '</div></header>' : ''; ?>