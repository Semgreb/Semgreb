<?php

function permision_vulnerabilities()
{
    $CI = &get_instance();
    $CI->load->view('vulnerabilities/permision');
}

function get_vulnerabilities_notification($contactid)
{
    $CI = &get_instance();
    return  $CI->db
        ->where('contactid', $contactid)
        ->get(db_prefix() . 'permision_vulnerabilities')
        ->row();
}

function ts_permision_vulnerabilities_data_create($id)
{
    $CI = &get_instance();
    $affectedRows = false;

    $vulnerabilities = $CI->input->post("vulnerabilities") != null ?
        1 : 0;


    $CI->db->insert(db_prefix() . 'permision_vulnerabilities', [
        'contactid'        => $id,
        'vulnerabilities' =>  $vulnerabilities,
    ]);

    if ($CI->db->affected_rows() > 0) {
        $affectedRows = true;
    }
}

function ts_permision_vulnerabilities_data_delete($id)
{
    $CI = &get_instance();
    $affectedRows = false;
    $CI->db->where('contactid', $id)
        ->delete(db_prefix() . 'permision_vulnerabilities');
    if ($CI->db->affected_rows() > 0) {
        $affectedRows = true;
    }
}

function ts_permision_vulnerabilities_data($data)
{
    $CI = &get_instance();
    $vulnerabilities = 0;

    $id = $CI->input->post("contactid");

    if (isset($data['vulnerabilities'])) {
        $vulnerabilities = 1;
        unset($data['vulnerabilities']);
    }

    $affectedRows = false;

    if ($id > 0) {

        if (
            $CI->db
            ->where('contactid', $id)
            ->get(db_prefix() . 'permision_vulnerabilities')
            ->num_rows() > 0
        ) {

            $CI->db->where('contactid', $id)->update(db_prefix() . 'permision_vulnerabilities', [
                'vulnerabilities' => $vulnerabilities
            ]);

            if ($CI->db->affected_rows() > 0) {
                $affectedRows = true;
            }
        } else {
            $CI->db->insert(db_prefix() . 'permision_vulnerabilities', [
                'contactid'        => $id,
                'vulnerabilities' =>  $vulnerabilities
            ]);
            if ($CI->db->affected_rows() > 0) {
                $affectedRows = true;
            }
        }
    }

    return $data;
}

function vulnerabilities_module_init_menu_items()
{
    $CI = &get_instance();


    $CI->app_menu->add_sidebar_menu_item('vulnerabilities', [
        'name' => _l('vulnerabilities'),
        'href' => admin_url('vulnerabilities'),
        'position' => 24,
        'icon' => 'fa fa-bug',
    ]);

    $CI->app_tabs->add_customer_profile_tab('vulnerabilities', [
        'name' => _l('vulnerability_header_tab'),
        'icon' => 'fa fa-bug',
        'view' => 'vulnerabilities/vulnerabilities_tab_client',
        'position' => 75,
        'badge' => [],

    ]);

    if (has_permission('reports', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'vulnerabilities-reports',
            'name'     => _l('Vulnerabilities'),
            'href'     => admin_url('vulnerabilities/reports/process_vulnerabilities'),
            'position' => 50,
            'badge'    => [],
        ]);
    }

    // $CI->app_menu->add_setup_menu_item('vulnerabilities', [
    //     'collapse' => true,
    //     'name'     => _l('vulnerabilities'),
    //     'position' => 15,
    //     'badge'    => [],
    // ]);

    // $CI->app_menu->add_setup_children_item('vulnerabilities', [
    //     'slug'     => 'vulnerabilities-config',
    //     'name'     => _l('vulnerabilities_confi_scan'),
    //     'href'     => admin_url('vulnerabilities/config_scan'),
    //     'position' => 25,
    //     'badge'    => [],
    // ]);


    if (has_permission('settings', '', 'view')) {
        $CI->app_menu->add_setup_menu_item('vulnerabilities', [
            'slug'     => 'vulnerabilities-config',
            'name'     => _l('vulnerabilities_confi_scan'),
            'href'     => admin_url('vulnerabilities/config_scan'),
            'position' => 25,
            'badge'    => [],
        ]);
    }
}

function add_vulnerabilities_email_template()
{
    $CI = &get_instance();

    $data['trust_seal'] = $CI->emails_model->get([
        'type'     => 'vulnerabilities',
        'language' => 'english'
    ]);

    $CI->load->view('vulnerabilities/emails/email_templates',  $data);
}


function vulnerabilities_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit' => _l('permission_edit'),
        'delete' => _l('permission_delete')
    ];

    register_staff_capabilities('vulnerabilities', $capabilities, _l('vulnerabilities'));
}


function vulnerabilities_module_activation_hook()
{
    require_once('modules/vulnerabilities/install.php');
}


function ts_contact_permissions_vulnerabilities($items)
{
    $options_disable = array(0, 1, 2, 3);

    foreach ($options_disable as $opt) {
        unset($items[$opt]);
    }

    $permissions = [
        [
            'id'         => 7,
            'name'       => _l('customer_permission_vulnerabilities'),
            'short_name' => 'vulnerabilities',
        ],
        // [
        //     'id'         => 8,
        //     'name'       => _l('customer_permission_complaints'),
        //     'short_name' => 'complaints',
        // ],
        [
            'id'         => 8,
            'name'       => _l('customer_permission_audits'),
            'short_name' => 'audits',
        ],
        // [
        //     'id'         => 10,
        //     'name'       => _l('customer_permission_exams'),
        //     'short_name' => 'exams',
        // ],
        [
            'id'         => 9,
            'name'       => _l('customer_permission_certifications'),
            'short_name' => 'certifications',
        ],
        // [
        //     'id'         => 12,
        //     'name'       => _l('customer_permission_seal'),
        //     'short_name' => 'trust_seal',
        // ]
    ];

    return array_merge($items,  $permissions);
}

function run_cron_time_vulnerabilities($times)
{
    $times = 60;
    return $times;
}
