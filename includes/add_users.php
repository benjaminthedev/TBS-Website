<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 12/05/2017
 * Time: 09:47
 */

/**
 * @param array $users
 * @param array $meta - $meta_key => $meta_value
 */
function add_default_users($users = [], $meta = [])
{

    $default_users = [
        'web_group@topclick.com',
        'seo@topclick.com',
        'ppc@topclick.com'
    ];

    $users =  array_merge($users, $default_users);


    foreach ($users as $user)
        if (null == username_exists($user)) {

            $password = wp_generate_password(12, false);
            $user_id = wp_create_user($user, $password, $user);

            wp_update_user(
                array(
                    'ID' => $user_id,
                    'nickname' => $user
                )
            );

            if ($meta)
                foreach ($meta as $meta_key => $meta_value)
                    add_user_meta($user_id, $meta_key, $meta_value);

            $user_ob = new WP_User($user_id);
            $user_ob->set_role('administrator');
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($user, 'Account Created For ' . get_bloginfo('name'),
                "<p>We have created you a Wordpress</p> <p><b>Username:</b> $user<br> <b>Your Password:</b> $password</p> <p>topclick development.</p>", $headers);

        }
        echo '<script type="application/javascript">alert("Default Accounts Have Been Added!")</script>';

}

/**
 * @param $wp_admin_bar
 */
function add_users_button($wp_admin_bar)
{
    $args = [
        'id' => 'add_users',
        'title' => 'Add Default Users',
        'href' => '?add_users=1',
    ];
    $wp_admin_bar->add_node($args);
}

add_action('admin_bar_menu', 'add_users_button', 50);


if(isset($_GET['add_users']))
    add_default_users();