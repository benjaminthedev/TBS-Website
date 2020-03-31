<?php

$class= get_field('offer_tagline', 'options') ? 'offer_open' : '';

?>
<!doctype html>
<!--[if IE 7]><html class="ie ie7 " <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="ie no-js" <?php language_attributes(); ?>> <!--<![endif]-->

    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <title><?php wp_title(); ?></title>

        <!-- Mobile viewport optimize -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Favicon and feed -->
        <link rel="SHORTCUT ICON" href="<?php echo get_template_directory_uri() ?>/assets/images/favicon.ico" />

        <?php wp_head(); ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->


        <?php echo get_field('header_scripts','option'); ?>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,700" rel="stylesheet">
        <!-- TrustBox script -->
        <script src="//widget.trustpilot.com/bootstrap/v5/tp.widget.bootstrap.min.js" async></script>
        <!-- End Trustbox script -->
    </head>

    <body <?php body_class($class); ?>>

    <?php echo get_field('body_scripts','option'); ?>

        <div class="wrapper">

            <?php get_header_layout('header_init') ?>