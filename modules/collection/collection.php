<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
    Module Name: collection
    Description: This Custom Module is developed for Your Module Description
    Version: 1.0.0
    Requires at least: 3.0.*
    Author: <a href="https://codecanyon.net/user/corbitaltech" target="_blank">Corbital Technologies<a/>
*/

/*
 * Define module name
 * Module Name Must be in CAPITAL LETTERS
 */
define('COLLECTION_MODULE', 'collection');

// require_once __DIR__.'/vendor/autoload.php';

/*
 * Register activation module hook
 */
register_activation_hook(COLLECTION_MODULE, 'collection_module_activate_hook');
function collection_module_activate_hook()
{
    require_once __DIR__ . '/install.php';
}

/*
 * Register deactivation module hook
 */
register_deactivation_hook(COLLECTION_MODULE, 'collection_module_deactivate_hook');
function collection_module_deactivate_hook()
{
    update_option('collection_enabled', 0);
}
/*
 * Register language files, must be registered if the module is using languages
 */
register_language_files(COLLECTION_MODULE, [COLLECTION_MODULE]);


/*
 * Load module helper file
 */
get_instance()->load->helper(COLLECTION_MODULE . '/collection');

require_once __DIR__ . '/includes/assets.php';
require_once __DIR__ . '/includes/sidebar_menu_links.php';

hooks()->add_action('admin_auth_init', 'collection_admin_auth_init');
function collection_admin_auth_init()
{
    // add your logic here

}

hooks()->add_action('module_activated', 'collection_module_activated');
function collection_module_activated()
{
    // add your logic here

}

hooks()->add_action('module_deactivated', 'collection_module_deactivated');
function collection_module_deactivated()
{
    // add your logic here
    echo "<script>
            confirm('Do you really want to deactivate module?');
        </script>";
}

