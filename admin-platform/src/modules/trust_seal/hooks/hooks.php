<?php

function ts_activation_hook()
{
    require_once('modules/trust_seal/install.php');
}

function ts_csrf_exclude_uris($current)
{
    return array_merge($current, ['trust_seal/api/clients\/.+', 'trust_seal/api/clients/[0-9a-z]+']);
}


function ts_help_menu_item_link($url)
{
    return "https://docs.malla.agency/";
}
// function ts_contact_permissions($items)
// {
//     $options_disable = array(0, 1, 2, 3);

//     foreach ($options_disable as $opt) {
//         unset($items[$opt]);
//     }

//     return $items;
// }

function ts_project_tabs($items)
{
    // $options_disable = array("sales");

    // // $options_disable = array("sales", "project_contracts", "project_timesheets");

    // foreach ($options_disable as $opt) {
    //     unset($items[$opt]);
    // }

    // $items = array_merge($items, ['project_expenses']);
    return $items;
}

function ts_app_admin_head()
{
    require 'modules/trust_seal/assets/css/header.php';

    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri, '/admin/clients/client/') === false)) {
        require 'modules/trust_seal/assets/css/client_profile.php';
    }

    if (!(strpos($viewuri, '/admin/projects/view/') === false)) {
        require 'modules/trust_seal/assets/css/project.php';
    }

    $CI = &get_instance();
    if (
        $CI->uri->segment(1) == "admin" &&
        $CI->uri->segment(2) == "settings"
    ) {

        require_once 'modules/trust_seal/assets/js/rename_fields_only_rnc_admin.php';
    }
}

function ts_app_admin_footer()
{
    $viewuri = $_SERVER['REQUEST_URI'];

    // if (!(strpos($viewuri, '/admin/projects/view/') === false)) {
    require 'modules/trust_seal/assets/js/project.php';
    // }
}


function trust_seal_init_menu_items()
{
    $CI = &get_instance();

    if (is_admin()) {
        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        $CI->app_menu->add_sidebar_menu_item('trust_seal', [
            'href'     => admin_url('trust_seal'),
            'name'     => _l('trust_seal'),
            'icon'     => 'fa-regular fa-handshake',
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('trust_seal', [
            'slug'     => 'certifications',
            'href'     => admin_url('trust_seal/certifications/manage'),
            'name'     => _l('certification'),
            // 'name'     => _l('certification'),
            'position' => 4,
        ]);

        $CI->app_menu->add_sidebar_children_item('trust_seal', [
            'slug'     => 'exams',
            'href'     => admin_url('trust_seal/exams/manage_exams'),
            'name'     => _l('exams'),
            // 'name'     => _l('exams'),
            'position' => 1,
        ]);

        $CI->app_menu->add_sidebar_children_item('trust_seal', [
            'slug'     => 'seals',
            'href'     => admin_url('trust_seal/seals/manage_seals'),
            'name'     => _l('seals'),
            // 'name'     => _l('exams'),
            'position' => 2,
        ]);

        $CI->app_menu->add_sidebar_children_item('trust_seal', [
            'slug'     => 'audits',
            'href'     => admin_url('trust_seal/audits/manage'),
            'name'     => _l('audits'),
            // 'name'     => _l('exams'),
            'position' => 3,
        ]);


        $CI->app_tabs->add_customer_profile_tab('audits', [
            'name'     => _l('audits'),
            'icon'     => 'fa fa-user-nurse',
            'view'     => 'trust_seal/audits/clients/groups/trust_seal_audits',
            //'visible'  => ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()),
            'position' => 75,
            'badge'    => [],
        ]);

        $CI->app_tabs->add_customer_profile_tab('certifications', [
            'name'     => _l('certifications'),
            'icon'     => 'fa fa-award',
            'view'     => 'trust_seal/certifications/clients/groups/trust_seal_certifications',
            //'visible'  => ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()),
            'position' => 75,
            'badge'    => [],
        ]);

        $CI->app_tabs->add_customer_profile_tab('reminder_certification', [
            'name'     => _l('reminder_certification'),
            'icon'     => 'fa fa-clock',
            'view'     => 'trust_seal/reminders_certifications',
            //'visible'  => ((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member()),
            'position' => 75,
            'badge'    => [],
        ]);

        if (has_permission('settings', '', 'view')) {
            $CI->app_menu->add_setup_menu_item('trust_seal', [
                'slug'     => 'trust_seal-config',
                'name'     => _l('trust_seal'),
                'href'     => admin_url('trust_seal/seals/config_seal'),
                'position' => 35,
                'badge'    => [],
            ]);
        }
    }

    if (has_permission('reports', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('reports', [
            'slug'     => 'certifications-reports',
            'name'     => _l('reports_certification'),
            'href'     => admin_url('trust_seal/reports/process_certifications'),
            'position' => 40,
            'badge'    => [],
        ]);
    }
}


function add_trust_seal_email_template()
{
    $CI = &get_instance();

    $data['trust_seal'] = $CI->emails_model->get([
        'type'     => 'trust_seal',
        'language' => 'english'
    ]);

    $CI->load->view('trust_seal/emails/email_templates',  $data);
}

function trust_seal_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('certifications', $capabilities, _l('certifications'));
    register_staff_capabilities('audits', $capabilities, _l('audits'));
    register_staff_capabilities('exams', $capabilities, _l('exams'));
    register_staff_capabilities('seals', $capabilities, _l('seals'));
}

function run_reminder_client_certifications($manually)
{

    if ($manually == true) {
        if (!extension_loaded('suhosin')) {
            @ini_set('memory_limit', '-1');
        }
    }

    $CI = &get_instance();

    $CI->db->select('' . db_prefix() . 'reminders.*');
    //$CI->db->join(db_prefix() . 'contacts', '' . db_prefix() . 'contacts.id=' . db_prefix() . 'reminders.staff');
    $CI->db->where(['isnotified' => 0, 'rel_type' => 'certifications']);
    $reminders     = $CI->db->get(db_prefix() . 'reminders')->result_array();
    $notifiedUsers = [];

    foreach ($reminders as $reminder) {
        if (date('Y-m-d H:i:s') >= $reminder['date']) {

            $CI->db->where('id', $reminder['id']);
            $CI->db->update(db_prefix() . 'reminders', [
                'isnotified' => 1,
            ]);

            foreach (get_contacts_notification_clients_certifications($reminder['rel_id']) as $contact) {



                // $rel_data   = get_relation_data('contact', $reminder['staff']);
                // $rel_values = get_relation_values($rel_data, 'contact');

                // $notificationLink = str_replace(admin_url(), '', $rel_values['link']);
                // $notificationLink = ltrim($notificationLink, '/');

                // $notified = add_notification([
                //     'fromcompany'     => true,
                //     'touserid'        => $reminder['staff'],
                //     'description'     => 'not_new_reminder_for',
                //     'link'            => $notificationLink,
                //     'additional_data' => serialize([
                //         $rel_values['name'] . ' - ' . strip_tags(mb_substr($reminder['description'], 0, 50)) . '...',
                //     ]),
                // ]);

                // if ($notified) {
                //     array_push($notifiedUsers, $reminder['staff']);
                // }

                $template = mail_template('staff_reminder', $contact['email'], $contact['id'], $reminder);

                if ($reminder['notify_by_email'] == 1) {
                    $template->send();
                }

                //$CI->app_sms->trigger(SMS_TRIGGER_STAFF_REMINDER, $reminder['phonenumber'], $template->get_merge_fields());
            }
        }
    }

    //pusher_trigger_notification($notifiedUsers);
}

function permision_certifications()
{
    $CI = &get_instance();
    $CI->load->view('trust_seal/certifications/permision');
}

function get_certificacion_notification($contactid)
{
    $CI = &get_instance();
    return  $CI->db
        ->where('contactid', $contactid)
        ->get(db_prefix() . 'permision_reminder_certifications')
        ->row();
}

function ts_permision_certifications_data_create($id)
{
    $CI = &get_instance();
    $affectedRows = false;

    $notifications_certifications_emails = $CI->input->post("notifications_certifications_emails") != null ?
        1 : 0;
    $permision_audit = $CI->input->post("permision_audit") != null ?
        1 : 0;


    $CI->db->insert(db_prefix() . 'permision_reminder_certifications', [
        'contactid'        => $id,
        'notifications_certifications_emails' =>  $notifications_certifications_emails,
        'permision_audit' => $permision_audit
    ]);

    if ($CI->db->affected_rows() > 0) {
        $affectedRows = true;
    }
}

function ts_permision_certifications_data_delete($id)
{
    $CI = &get_instance();
    $affectedRows = false;
    $CI->db->where('contactid', $id)
        ->delete(db_prefix() . 'permision_reminder_certifications');
    if ($CI->db->affected_rows() > 0) {
        $affectedRows = true;
    }
}

function ts_permision_certifications_data($data)
{
    $CI = &get_instance();
    $notifications_certifications_emails = 0;
    $permision_audit = 0;

    $id = $CI->input->post("contactid");

    if (isset($data['notifications_certifications_emails'])) {
        $notifications_certifications_emails = 1;
        unset($data['notifications_certifications_emails']);
    }

    if (isset($data['permision_audit'])) {
        $permision_audit = 1;
        unset($data['permision_audit']);
    }

    $affectedRows = false;

    if ($id > 0) {

        if (
            $CI->db
            ->where('contactid', $id)
            ->get(db_prefix() . 'permision_reminder_certifications')
            ->num_rows() > 0
        ) {

            $CI->db->where('contactid', $id)->update(db_prefix() . 'permision_reminder_certifications', [
                'notifications_certifications_emails' => $notifications_certifications_emails,
                'permision_audit' => $permision_audit
            ]);

            if ($CI->db->affected_rows() > 0) {
                $affectedRows = true;
            }
        } else {
            $CI->db->insert(db_prefix() . 'permision_reminder_certifications', [
                'contactid'        => $id,
                'notifications_certifications_emails' =>  $notifications_certifications_emails,
                'permision_audit' => $permision_audit
            ]);
            if ($CI->db->affected_rows() > 0) {
                $affectedRows = true;
            }
        }
    }

    return $data;
}


//before_client_added

function ts_before_client_added_data($data)
{
    $CI = &get_instance();
    $affectedRows = false;
    $id = $data['id'];
    //file_client_logo
    $client_email =  $CI->input->post("client_email");
    $client_description =  $CI->input->post("client_description");
    $client_logo_name = $CI->input->post("client_logo");
    $slug = create_Slug($CI->input->post("company"));

    if ($id > 0) {

        if (
            $CI->db
            ->where('userid', $id)
            ->get(db_prefix() . 'extra_fields_clients')
            ->num_rows() > 0
        ) {

            $fieldsLogo =  [];

            $nombreFile = _saveFileFromBase64($id);
            if ($nombreFile != "") {
                $fieldsLogo = ['logo' => $nombreFile];
            }



            $CI->db->where('userid', $id)->update(db_prefix() . 'extra_fields_clients', array_merge([
                'email' => $client_email,
                'descriptions' => $client_description,
                'slug' => $slug
            ], $fieldsLogo));

            if ($CI->db->affected_rows() > 0) {
                $affectedRows = true;
            }
        } else {

            $fieldsLogo =  [];

            $nombreFile = _saveFileFromBase64($id, $client_logo_name);
            if ($nombreFile != "") {
                $fieldsLogo = ['logo' => $nombreFile];
            }


            $CI->db->insert(db_prefix() . 'extra_fields_clients', array_merge([
                'userid'        => $id,
                'email' =>  $client_email,
                'descriptions' => $client_description,
                'slug' => $slug
            ], $fieldsLogo));


            if ($CI->db->affected_rows() > 0) {
                $affectedRows = true;
            }
        }
    }

    return $affectedRows;
}

function create_Slug($string)
{
    return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
}


function get_extra_data_customer($customerid)
{
    $CI = &get_instance();
    return  $CI->db
        ->where('userid', $customerid)
        ->get(db_prefix() . 'extra_fields_clients')
        ->row();
}

function ts_before_client_added_filter_data($data)
{
    $CI = &get_instance();
    $client_email = "";
    $client_description = "";
    $client_logo = "";
    $client_logo_name = "";
    $file_client_logo = "";
    $client_razon_social = "";

    $id = $CI->uri->segment(4);

    if (isset($data['client_email'])) {
        $client_email = $data['client_email'];
        unset($data['client_email']);
    }

    if (isset($data['client_description'])) {
        $client_description = $data['client_description'];
        unset($data['client_description']);
    }

    if (isset($data['client_logo'])) {
        $client_logo = $data['client_logo'];
        unset($data['client_logo']);
    }

    if (isset($data['file_client_logo'])) {
        $file_client_logo = $data['file_client_logo'];
        unset($data['file_client_logo']);
    }

    // if (isset($data['slug'])) {
    //     $slug = $data['slug'];
    //     unset($data['slug']);
    // }

    return $data;
}

function ts_change_route_menu($items)
{
    $menu_change =
        [
            "knowledge-base"
        ];

    $menu_new_route =
        [
            "knowledge-base" => get_option('superadmin_knowledgebase_link')
        ];


    foreach ($items as $key => $value) {
        if (in_array($key, $menu_change)) {
            if ($menu_new_route[$key] != "") {
                $items[$key]['href'] = $menu_new_route[$key];
            }
        }
    }

    return $items;
}


function ts_change_article_for_guia($items)
{
    $search = [
        'kb_article', 'kb_article_lowercase', 'kb_article_new_article', 'kb_article_description', 'kb_no_articles_found', 'kb_dt_article_name', 'kb_group_add_edit_note', 'internal_article', 'related_knowledgebase_articles', 'kb_reports', 'als_kb_articles_submenu'
    ];

    if (in_array($items['line'], $search)) {
        $items['line'] =  sprintf("%s_rp", $items['line']);
    }

    return $items;
}

function ts_after_get_language_text($data)
{
    if($data['line'] === 'client_vat_number' || $data['line'] === 'clients_vat' || $data['line'] === 'company_vat_number'){
        $data['formatted_line'] = 'RNC';
    }

    return $data;
}
