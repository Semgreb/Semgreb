<?php


function consumers_activation_hook()
{
    require_once('modules/consumers/install.php');
}

/**
 * Init menu setup module menu items in setup in admin_init hook
 * @return null
 */
function consumers_init_menu_items()
{
    /**
     * If the logged in user is administrator, add custom menu in Setup
     */
    if (is_admin()) {
        $CI = &get_instance();

        $CI->app_menu->add_sidebar_menu_item('Consumers', [
            'name'     => _l('consumers_menu'),
            'href'     => admin_url('consumers'),
            'position' => 5,
            'icon'     => 'fa fa-user',
            'badge'    => [],
        ]);
    }
}


function consumers_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        // 'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('consumers', $capabilities, _l('consumers_menu'));
}
