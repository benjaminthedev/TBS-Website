<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 22/05/2017
 * Time: 16:14
 */
?>

<header id="main_header">
    <div class="middle_header">
        <div class="container-fluid">
<div class="row">


<div class="newHeaderWrap">

<div class="hamburgerMenu">
  <a href="#" class="hidden-md-up hidden-lg-up hidden-xl-up telephone_number float-left menu_expand_toggle"><i class="fa fa-bars fa-lg"></i></a>
</div>


<div id="mob-logo-container" class="container hidden-md-up text-center">
  <?php echo '<a href="' . get_site_url() . '" id="logo_mob">' . get_img('logo.png') . '</a>'; ?>
</div>

<div class=" logoSection">
  <?php echo '<a href="' . get_site_url() . '" id="logo" class="float-md-left hidden-sm-down">' . get_img('logo.png') . '</a>'; ?>
</div><!-- end logoSection -->




<div class="NewSearch">
  <?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }


    $options = get_option('clerk_options');
    ?>
     <form role="search" method="get" class="search-form float-md"
  action="<?php echo esc_url( get_page_link( $options['search_page'] ) ); ?>">
  <label>
    <span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
    <input type="search" id="clerk-searchfield" class="search-field"
           placeholder="I am looking for..."
           value="<?php echo get_search_query() ?>" name="searchterm"/>
  </label>
  <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>"/>
  </form>


   <!-- <form action="<?php echo esc_url( get_page_link( $options['search_page'] ) ); ?>" class="search_form" class="float-md-right">
        <div id="search_form_inner">
            <div class="input_wrap">
                <i class="fa fa-search"></i>
                <input type="text" name="searchterm" placeholder="I am looking for..." id="clerk-live-search">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
        </div>
    </form> -->






  <span class="clerk" data-template="@live-search" data-instant-search="#clerk-searchfield" data-instant-search-suggestions="6" data-instant-search-categories="6" data-instant-search-pages="6" data-instant-search-positioning="below"></span>


</div><!-- end NewSearch -->










<div class="myaccSection">
  <div class="itemsWrap">
    <nav class="account_navigation">
        <a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"><i class="fa fa-user fa-lg"></i></a>
    </nav>


    <div class="hearts">
      <a href="<?php the_field('wishlist_page', 'options'); ?>"><i class="fa fa-heart fa-lg"></i> </a>
    </div>


    <div class=" myCartSection">
    		<?php echo do_shortcode("[xoo_wsc_cart]"); ?>
    </div>
  </div><!-- end itemsWrap -->
</div>






</div><!-- end newHeaderWrap -->


      </div><!-- end row -->
    </div><!-- end container-fluid -->
  </div><!-- end middle_header -->
</header>

<style>

.newHeaderWrap{
  display: flex;
  width: 100%;
  align-items: center;
}


.logoSection {
    margin-right: 40px;
    margin-left: 20px;
}

.myaccSection {
    display: flex;
    align-items: flex-end;
    flex-grow: 1;
}


.itemsWrap {
    justify-content: flex-end;
    display: flex;
    flex-grow: 1;
}

nav.account_navigation {
    width: 40px;
}

.hearts {
    width: 50px;
}

.myCartSection {
    width: 50px;
}


/* @media (max-width: 769px) { */
@media (max-width: 767px) {

  .newHeaderWrap{
      flex-direction: column;
      align-items: baseline;
  }

  /* logo */

  div#mob-logo-container {
    top: 10%;
  }

  .NewSearch {
    margin-top: 30px;
    width: 95%;
    margin-left: 10px;
  }
  input.search-submit {
    display: none;
  }

  .myaccSection {
      margin-top: -103px;
      margin-bottom: 110px;
      width: 100%;
  }


  .mobileWrap {
    display: flex;
  }
   nav.account_navigation {
    width: 40px;
}
  .hearts{
    display: none;
  }
	.myCartSection {
    width: 40px;
}

  .logoSection{
    margin: 0;
  }

  .hamburgerMenu {
    z-index: 1;
    margin-left: 25px;

}

}


@media (max-width: 810px) {
  input.search-submit {
    display: none;
  }
  input#clerk-searchfield{
        border-right: 1px solid #d7d7d7;;
  }
}





</style>
