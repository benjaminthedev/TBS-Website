<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 22/05/2017
 * Time: 16:15
 */?>

<header id="mobile_nav" class="hidden-lg-up">
   <div class="mobile_menu_wrap">
       <header>
           Menu <a href="#" class="menu_expand_toggle"><i class="fa fa-times"></i></a>
       </header>
       <?php
       wp_nav_menu(array(
               'theme_location' => 'primary',
               'container' => 'nav',
               'container_class' => 'navigation_wrap',
               'menu_class' => 'navigation clearfix',
           )
       );
       ?>
   </div>
</header>
