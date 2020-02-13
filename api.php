<?php

    /*
        Plugin Name: Get Api
        Author: Mohammad Ubaidillah
    */

    require_once('api_list.php');
    require_once('api_post.php');
    require_once('api_update.php');

    // Add action to the Admin Menu
    add_action('admin_menu','api_all_menu');

    // On time of activation of the plugin, add the "Users" option
    function api_all_menu() {

        add_menu_page('List', // page title
            'Api Post', // menu title
            'manage_options', // capabilities
            'api_post', // menu slug
            'api_getdata' // function
        );

        add_submenu_page('api_post', // parent slug
            'Add New Post', // page title
            'Add New Post', // menu title
            'manage_options', // capability
            'api_post_create', // menu slug
            'api_postmerge' // function
        );

        add_submenu_page('api_post', // parent slug
            'Update Post', // page title
            'Update Post',
            'manage_options', // capability
            'api_post_update', // menu slug
            'api_updatemerge' // function
        );
    }
?>