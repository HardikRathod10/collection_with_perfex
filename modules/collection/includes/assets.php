<?php

/*
 * Inject Javascript file for collection module
 */
hooks()->add_action('app_admin_footer', 'collection_load_js');
function collection_load_js() {
    if (get_instance()->app_modules->is_active('collection')) {
        echo '<script src="'.module_dir_url('collection', 'assets/js/collection.js').'?v='.get_instance()->app_scripts->core_version().'"></script>';
    }
}

