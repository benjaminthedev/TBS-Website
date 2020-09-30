<header id="main_nav" class="hidden-md-down">
    <div class="container">
        <?php
        wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_class' => 'navigation_wrap',
                'menu_class' => 'navigation clearfix',
                'walker' => new main_menu_walker()
            )
        );
        ?>
    </div>

</header>
