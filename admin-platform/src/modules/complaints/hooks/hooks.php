<?php

function complaints_activation_hook()
{
    require_once('modules/complaints/install.php');
}
/**
 * Init menu setup module menu items in setup in admin_init hook
 * @return null
 */
function complaints_init_menu_items()
{
    /**
     * If the logged in user is administrator, add custom menu in Setup
     */
    if (is_admin()) {
        $CI = &get_instance();

        // $CI->app_menu->add_setup_menu_item('complaints', [
        //     'collapse' => true,
        //     'name'     => _l('complaints_menu'),
        //     'position' => 15,
        //     'badge'    => [],
        // ]);

        $CI->app_menu->add_sidebar_menu_item('Complaints', [
            'name'     => _l('complaints_menu'),
            'href'     => admin_url('complaints'),
            'position' => 40,
            'icon'     => 'fa fa-file-lines',
            'badge'    => [],
        ]);

        // $CI->app_menu->add_setup_children_item('complaints', [
        //     'slug'     => 'complaints-services',
        //     'name'     => _l('acs_complaint_services_submenu'),
        //     'href'     => admin_url('complaints/services'),
        //     'position' => 25,
        //     'badge'    => [],
        // ]);

        $CI->app_menu->add_setup_menu_item('complaints', [
            'slug'     => 'complaints-services',
            'name'     => _l('acs_complaint_services_submenu'),
            'href'     => admin_url('complaints/services'),
            'position' => 25,
            'badge'    => [],
        ]);

        $CI->app_tabs->add_customer_profile_tab('complaints', [
            'name'     => _l('complaints_menu'),
            'icon'     => 'fa fa-file-lines',
            'view'     => 'complaints/clients/groups/complaints',
            'visible'  => ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()),
            'position' => 75,
            'badge'    => [],
        ]);
    }
}

function add_complaints_email_template()
{
    $CI = &get_instance();

    $data['complaints'] = $CI->emails_model->get([
        'type'     => 'complaint',
        'language' => 'english'
    ]);

    $CI->load->view('complaints/emails/email_templates',  $data);
}

function complaints_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('complaints', $capabilities, _l('complaints_menu'));
}
