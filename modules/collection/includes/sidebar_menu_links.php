<?php

/*
 * Inject sidebar menu and links for collection module
 */
hooks()->add_action('admin_init', 'collection_module_init_menu_items');
function collection_module_init_menu_items() {
    if (has_permission('collection', '', 'view')) {
        get_instance()->app_menu->add_sidebar_menu_item('collection', [
            'slug'     => 'collection',
            'name'     => _l('collection'),
            'icon'     => 'fa fa-cart-plus',
            'href'     => '#',
            'position' => 20,
        ]);
    }
    if (has_permission('collection', '', 'view')) {
        get_instance()->app_menu->add_sidebar_children_item('collection', [
            'slug'     => 'link2',
            'name'     => _l('col_invoices'),
            'href'     => admin_url('collection/collection_view'),
            'position' => 2,
        ]);
    }
}

