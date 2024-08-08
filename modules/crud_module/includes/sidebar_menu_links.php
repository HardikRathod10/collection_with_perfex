<?php

/*
 * Inject sidebar menu and links for crud_module module
 */
hooks()->add_action('admin_init', 'crud_module_module_init_menu_items');
function crud_module_module_init_menu_items() {
    if (has_permission('crud_module', '', 'view')) {
        get_instance()->app_menu->add_sidebar_menu_item('crud_module', [
            'slug'     => 'crud_module',
            'name'     => _l('crud_module'),
            'icon'     => 'fa fa-cart-plus',
            'href'     => '#',
            'position' => 20,
        ]);
    }
    if (has_permission('crud_module', '', 'view')) {
        get_instance()->app_menu->add_sidebar_children_item('crud_module', [
            'slug'     => 'link2',
            'name'     => _l('link2'),
            'href'     => admin_url('crud_module/link2'),
            'position' => 2,
        ]);
    }
}

