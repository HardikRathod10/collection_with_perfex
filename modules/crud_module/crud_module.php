<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
    Module Name: crud_module
    Description: This Custom Module is developed for Module to practice CRUD with perfex.
    Version: 1.0.0
    Requires at least: 3.0.*
    Author: <a href="https://codecanyon.net/user/corbitaltech" target="_blank">Corbital Technologies<a/>
*/

/*
 * Define module name
 * Module Name Must be in CAPITAL LETTERS
*/
define('CRUD_MODULE_MODULE', 'crud_module');

// require_once __DIR__.'/vendor/autoload.php';

/*
 * Register activation module hook
 */
register_activation_hook(CRUD_MODULE_MODULE, 'crud_module_module_activate_hook');
function crud_module_module_activate_hook() {
    require_once __DIR__.'/install.php';
}

/*
 * Register deactivation module hook
 */
register_deactivation_hook(CRUD_MODULE_MODULE, 'crud_module_module_deactivate_hook');
function crud_module_module_deactivate_hook() {
    update_option('crud_module_enabled', 0);
}
/*
 * Register language files, must be registered if the module is using languages
 */
register_language_files(CRUD_MODULE_MODULE, [CRUD_MODULE_MODULE]);


/*
 * Load module helper file
 */
get_instance()->load->helper(CRUD_MODULE_MODULE.'/crud_module');

require_once __DIR__ . '/includes/assets.php';
require_once __DIR__ . '/includes/sidebar_menu_links.php';

hooks()->add_action('admin_auth_init', 'crud_module_admin_auth_init');
function crud_module_admin_auth_init() {
    // add your logic here

}

hooks()->add_action('module_activated', 'crud_module_module_activated');
function crud_module_module_activated() {
    // add your logic here

}

hooks()->add_action('module_deactivated', 'crud_module_module_deactivated');
function crud_module_module_deactivated() {
    // add your logic here

}

