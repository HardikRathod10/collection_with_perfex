<?php

/*
 * Inject Javascript file for crud_module module
 */
hooks()->add_action('app_admin_footer', 'crud_module_load_js');
function crud_module_load_js() {
    if (get_instance()->app_modules->is_active('crud_module')) {
        echo '<script src="'.module_dir_url('crud_module', 'assets/js/crud_module.js').'?v='.get_instance()->app_scripts->core_version().'"></script>';
    }
}

