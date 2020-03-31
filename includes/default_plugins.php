<?php
/**
 * Created by PhpStorm.
 * User: connormulhall
 * Date: 08/03/2017
 * Time: 09:27
 */

add_action('tgmpa_register', 'default_plugins_register_required_plugins');

function default_plugins_register_required_plugins()
{

    $plugins = array(
        array(
            'name' => 'Resize-images-before-upload',
            'slug' => 'resize-images-before-upload',
            'source' => 'https://github.com/WPsites/Resize-images-before-upload/archive/master.zip',
            'required' => true,
        ),

        array(
            'name' => 'ACF PRO', // The plugin name.
            'slug' => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
            'source' => 'https://connect.advancedcustomfields.com/index.php?p=pro&a=download&k=b3JkZXJfaWQ9NzI3MTN8dHlwZT1kZXZlbG9wZXJ8ZGF0ZT0yMDE2LTAxLTE1IDA5OjQ5OjA4', // The plugin source.
            'required' => true, // If false, the plugin is only 'recommended' instead of required.
        ),
        array(
            'name' => 'Flamingo',
            'slug' => 'flamingo',
            'required' => true,
        ),
        array(
            'name' => 'Simple 301 Redirects',
            'slud' => 'simple-301-redirects',
            'required' => true,
        ),
        array(
            'name' => 'Yoast SEO',
            'slug' => 'wordpress-seo',
            'required' => true,
        ),
        array(
            'name' => 'Wordfence Security',
            'slug' => 'wordfence',
            'required' => true,
        ),
        array(
            'name' => 'Contact Form 7',
            'slug' => 'contact-form-7',
            'required' => true,
        ),
        array(
            'name' => 'W3 Total Cache',
            'slug' => 'w3-total-cache',
            'required' => true,
        ),
        array(
            'name' => 'Autoptimize',
            'slug' => 'autoptimize',
            'required' => true,
        ),
        array(
            'name' => 'All-in-One WP Migration',
            'slug' => 'all-in-one-wp-migration',
            'required' => true,
        ),
        array(
            'name' => 'Advanced Custom Fields: Font Awesome',
            'slug' => 'advanced-custom-fields-font-awesome',
            'required' => true,
        ),


    );

    /*
     * Array of configuration settings. Amend each line as needed.
     *
     * TGMPA will start providing localized text strings soon. If you already have translations of our standard
     * strings available, please help us make TGMPA even better by giving us access to these translations or by
     * sending in a pull-request with .po file(s) with the translations.
     *
     * Only uncomment the strings in the config array if you want to customize the strings.
     */
    $config = array(
        'id' => 'topclick',                 // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu' => 'tgmpa-install-plugins', // Menu slug.
        'parent_slug' => 'themes.php',            // Parent menu slug.
        'capability' => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true,                    // Show admin notices or not.
        'dismissable' => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message' => '',                      // Message to output right before the plugins table.
    );

    tgmpa($plugins, $config);
}