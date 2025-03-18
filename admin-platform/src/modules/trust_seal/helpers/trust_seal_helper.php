<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * get status modules wh
 * @param  string $module_name 
 * @return boolean             
 */
function ts_get_status_module($module_name)
{
    $CI = &get_instance();

    $sql = 'select * from ' . db_prefix() . 'modules where module_name = "' . $module_name . '" AND active =1 ';
    $module = $CI->db->query($sql)->row();

    if ($module) {
        return true;
    } else {
        return false;
    }
}

function set_certifications_expired($id)
{
    $CI = &get_instance();

    $CI->db->select('*');
    $CI->db->from(db_prefix() . 'certifications');
    $CI->db->where('id_customer', $id);
    foreach ($CI->db->get()->result_array() as $value) {
        if (
            $value['date_expiration'] < date('Y-m-d')
            &&  $value['status'] != 2
        ) {
            $CI->db->where('id', $value['id']);
            $CI->db->update(db_prefix() . 'certifications', ['status' => 2]);
            if ($CI->db->affected_rows() > 0) {
                log_activity('Certification Updated [ID:' . $value['id'] . ']');
            }
        }
    }
}

function get_contacts_notification_clients_certifications($idClientes)
{
    $CI = &get_instance();
    $sql = 'SELECT * FROM ' . db_prefix() . 'contacts ct LEFT JOIN ' . db_prefix() . 'permision_reminder_certifications pm ON pm.contactid = ct.id WHERE userid = ' . $idClientes . ' AND active = 1 AND pm.notifications_certifications_emails = 1';
    $result = $CI->db->query($sql)->result_array();
    return $result;
}

function get_contacts_notification_clients_audits($idClientes)
{
    $CI = &get_instance();
    $sql = 'SELECT ct.* FROM ' . db_prefix() . 'contacts ct LEFT JOIN ' . db_prefix() . 'permision_reminder_certifications pm ON pm.contactid = ct.id WHERE userid = ' . $idClientes . ' AND active = 1 AND pm.permision_audit = 1';
    $result = $CI->db->query($sql)->result_array();
    return $result;
}


function get_contact_notification_audits_status($idContacto, $and_where)
{
    $CI = &get_instance();
    $sql = 'SELECT * FROM ' . db_prefix() . 'contacts ct LEFT JOIN ' . db_prefix() . 'permision_reminder_certifications pm ON pm.contactid = ct.id WHERE ct.id = ' . $idContacto . ' AND ct.active = 1 AND ' . $and_where;
    $result = $CI->db->query($sql)->num_rows() > 0 ? true : false;
    return $result;
}

function if_have_comment($idAudit, $idQuestion)
{
    $CI = &get_instance();

    $CI->db->select('id');
    $CI->db->from(db_prefix() . 'audit_comments');
    $CI->db->where('id_audit', $idAudit);
    $CI->db->where('id_question', $idQuestion);

    if ($CI->db->get()->num_rows() > 0) {
        return true;
    } else {
        return false;
    }
}

function get_clients_area_certifications_summary($statuses)
{
    foreach ($statuses as $key => $status) {
        $where = ['id_customer' => get_client_user_id(), 'status' => $status['status']];
        // if (!can_logged_in_contact_view_all_tickets()) {
        //     $where[db_prefix() . 'tickets.contactid'] = get_contact_user_id();
        // }
        $statuses[$key]['total_certifications']   = total_rows(db_prefix() . 'certifications', $where);
        $statuses[$key]['translated_name'] = $status['translate_name'];
        $statuses[$key]['url']             = site_url('clients/tickets/' . $status['status']);
    }

    return hooks()->apply_filters('clients_area_tickets_summary', $statuses);
}

function get_clients_area_audits_summary($statuses)
{
    foreach ($statuses as $key => $status) {
        $where = ['id_customer' => get_client_user_id(), 'status' => $status['status']];
        // if (!can_logged_in_contact_view_all_tickets()) {
        //     $where[db_prefix() . 'tickets.contactid'] = get_contact_user_id();
        // }
        $statuses[$key]['total_audits']   = total_rows(db_prefix() . 'audits', $where);
        $statuses[$key]['translated_name'] = $status['translate_name'];
        $statuses[$key]['url']             = site_url('clients/tickets/' . $status['status']);
    }

    return hooks()->apply_filters('clients_area_tickets_summary', $statuses);
}

function get_clients_area_audits_summary_qualification($statuses)
{
    foreach ($statuses as $key => $status) {
        $where = ['id_customer' => get_client_user_id(), 'qualification' => $status['qualification']];
        // if (!can_logged_in_contact_view_all_tickets()) {
        //     $where[db_prefix() . 'tickets.contactid'] = get_contact_user_id();
        // }
        $statuses[$key]['total_audits']   = total_rows(db_prefix() . 'audits', $where);
        $statuses[$key]['translated_name'] = $status['translate_name'];
        $statuses[$key]['url']             = site_url('clients/tickets/' . $status['qualification']);
    }

    return hooks()->apply_filters('clients_area_tickets_summary', $statuses);
}

// $lang['certification_published'] = 'Publicada';
// $lang['certification_expired'] = 'Expirada';
// $lang['certification_suspended'] = 'Suspendida';

function get_status_certifications()
{
    return [
        ['status' => 1, 'translate_name' => _l('certification_published'), 'status_color' => '#2563eb'],
        ['status' => 2, 'translate_name' => _l('certification_expired'), 'status_color' => '#ff2d42'],
        ['status' => 5, 'translate_name' => _l('certification_archived'), 'status_color' => '#fbae51'],
        ['status' => 4, 'translate_name' => _l('certification_draft'), 'status_color' => '#64748b'],
        ['status' => 3, 'translate_name' => _l('certification_suspended'), 'status_color' => '#64748b']

    ];
}


function get_status_exams()
{
    return [
        ['status' => 4, 'translate_name' => _l('exams_process'), 'status_color' => '#22c55e'],
        ['status' => 1, 'translate_name' => _l('exams_public'), 'status_color' => '#2563eb'],
        ['status' => 3, 'translate_name' => _l('exams_inactive'), 'status_color' => '#64748b'],
        ['status' => 2, 'translate_name' => _l('exams_draft'), 'status_color' => '#03a9f4']
    ];
}

function get_status_seals()
{
    return [
        ['status' => 4, 'translate_name' => _l('seal_active'), 'status_color' => '#22c55e'],
        ['status' => 1, 'translate_name' => _l('seal_public'), 'status_color' => '#3b82f6'],
        ['status' => 3, 'translate_name' => _l('seal_private'), 'status_color' => '#64748b'],
        ['status' => 2, 'translate_name' => _l('seal_suspended'), 'status_color' => '#ff2d42']
    ];
}

function get_status_audits()
{
    /**Borrador
En proceso
Completado
Archivado */

    return [
        ['status' => 1, 'translate_name' => _l('audit_status_1'), 'status_color' => '#3b82f6'],
        ['status' => 2, 'translate_name' => _l('audit_status_2'), 'status_color' => '#22c55e'],
        ['status' => 3, 'translate_name' => _l('audit_status_3'), 'status_color' => '#ff2d42'],
        ['status' => 4, 'translate_name' => _l('audit_status_4'), 'status_color' => '#03a9f4']
    ];
}

function get_qualification_audits()
{
    return [
        ['qualification' => 1, 'translate_name' => _l('audit_qualification_1'), 'statuscolor' => '#64748b'],
        ['qualification' => 2, 'translate_name' => _l('audit_qualification_2'), 'statuscolor' => '#22c55e'],
        ['qualification' => 3, 'translate_name' => _l('audit_qualification_3'), 'statuscolor' => '#ff2d42'],
        ['qualification' => 4, 'translate_name' => _l('audit_qualification_4'), 'statuscolor' => '#03a9f4']
    ];
}

function force_filter($data)
{
    $value = $data['search']['value'];
    if (!empty($value)) {

        switch (strtolower($value)) {
            case strtolower(_l('audit_qualification_1')):
                $data['search']['value'] = 1;
                break;
            case strtolower(_l('audit_qualification_2')):
                $data['search']['value'] = 2;
                break;
            case strtolower(_l('audit_qualification_3')):
                $data['search']['value'] = 3;
                break;
            case strtolower(_l('audit_qualification_4')):
                $data['search']['value'] = 4;
                break;
        }
    }

    return $data;
}

//    'rules'   => 'trim|required|min_length[6]|max_length[25]|matches[conf_password]|xss_clean|callback_is_password_strong'

function is_password_strong($password = '')
{
    $CI = &get_instance();
    $password = trim($password);
    $regex_lowercase = '/[a-z]/';
    $regex_uppercase = '/[A-Z]/';
    $regex_number = '/[0-9]/';
    $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';

    if (empty($password)) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field is required.');
        return FALSE;
    }
    if (preg_match_all($regex_lowercase, $password) < 1) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field must be at least one lowercase letter.');
        return FALSE;
    }
    if (preg_match_all($regex_uppercase, $password) < 1) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field must be at least one uppercase letter.');
        return FALSE;
    }
    if (preg_match_all($regex_number, $password) < 1) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field must have at least one number.');
        return FALSE;
    }
    if (preg_match_all($regex_special, $password) < 1) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field must have at least one special character.' . ' ' . htmlentities('!@#$%^&*()\-_=+{};:,<.>§~'));
        return FALSE;
    }
    if (strlen($password) < 5) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field must be at least 5 characters in length.');
        return FALSE;
    }
    if (strlen($password) > 32) {
        $CI->form_validation->set_message('is_password_strong', 'The {field} field cannot exceed 32 characters in length.');
        return FALSE;
    }
    return TRUE;
}

function get_qualification_format($qualification)
{
    $statusColor = adjust_hex_brightness($qualification['statuscolor'], 0.4);
    $statusColorBorder = adjust_hex_brightness($qualification['statuscolor'], 0.04);

    return sprintf('<span class="label ticket-status-%s" style="border:1px solid  %s; color: %s; background:%s;">%s</label>', $qualification['qualification'], $statusColor, $qualification['statuscolor'], $statusColorBorder, $qualification['translate_name']);
}


function get_status_audits_format($status)
{
    $statusColor = adjust_hex_brightness($status['status_color'], 0.4);
    $statusColorBorder = adjust_hex_brightness($status['status_color'], 0.04);

    return sprintf('<span class="label ticket-status-%s" style="border:1px solid  %s; color: %s; background:%s;">%s</label>', $status['status'], $statusColor, $status['status_color'], $statusColorBorder, $status['translate_name']);
}


function get_fields_id_audits($aRow, $idClientes, $column)
{
    $_data = '<a href="' . admin_url('trust_seal/audits/audit/' . $aRow['id']) . '">' . $column . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/audits/audit/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('audits', '', 'delete') && $idClientes == 0) {
        $_data .= ' | <a href="' . admin_url('trust_seal/audits/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    return $_data;
}

function get_fields_id_certifications($aRow, $idClientes, $column)
{
    $idInt =  $aRow['id'];
    // if ($idClientes > 0) {
    $idInt =  (int) $aRow['id'];
    // }

    $_data = '<a href="' . admin_url('trust_seal/certifications/certification/' . $aRow['id']) . '">' . $column . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/certifications/certification/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('certifications', '', 'delete') && $idClientes == 0) {
        $_data .= ' | <a href="' . admin_url('trust_seal/certifications/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    return $_data;
}

function get_fields_id_exams($aRow)
{
    $_data = '<a href="' . admin_url('trust_seal/exams/view_exam/' . $aRow['id']) . '">' . $aRow['name'] . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/exams/view_exam/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('exams', '', 'delete')) {
        $_data .= ' | <a href="' . admin_url('trust_seal/exams/delete_exam/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    return $_data;
}

function get_fields_name($aRow)
{
    $_data = '<a href="' . admin_url('trust_seal/seals/view/' . $aRow['id']) . '">' . $aRow['title'] . '</a>';
    $_data .= '<div class="row-options">';
    $_data .= '<a href="' . admin_url('trust_seal/seals/view/' . $aRow['id']) . '">' . _l('view') . '</a>';

    if (has_permission('seals', '', 'delete')) {
        $_data .= ' | <a href="' . admin_url('trust_seal/seals/delete_seal/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $_data .= '</div>';

    return $_data;
}

function redirect_auto_certificacitions($datos)
{
    if (
        $datos['auto_asignar'] == 1
    ) {
        $CI = &get_instance();
        $CI->session->set_flashdata('clientes', $datos['id_customer']);
        $CI->session->set_flashdata('sellos', $datos['id_seal']);
        redirect(admin_url('trust_seal/certifications/certification'));
    }
}

function add_announcements_audit($data)
{
    // $CI = &get_instance();
    // $data['id_seal']      = $data['id_seal'];
    // $data['userid']       = $data['userid'];
    // $data['showtostaff']  = 1;
    // $data['dateadded']    = date('Y-m-d H:i:s');
    // $insert_id = $data['id_seal'];

    // $CI->db->insert(db_prefix() . 'announcements_audits', $data);

    // if ($insert_id) {
    //     return $insert_id;
    // }

    // return false;
}

function get_date_reminder($fecha)
{
    //['description'] = _l('trust_seal_audit_message_alert');
    $date_reminder = [
        ['fecha' => date('Y-m-d', strtotime($fecha . ' -2 months')), 'description' =>  _l('trust_seal_certification_reminder_2_month')],
        ['fecha' => date('Y-m-d', strtotime($fecha . ' -1 months')), 'description' =>  _l('trust_seal_certification_reminder_1_month')],
        ['fecha' =>  date('Y-m-d', strtotime($fecha . ' -15 days')), 'description' =>  _l('trust_seal_certification_reminder_15_days')],
        ['fecha' =>  date('Y-m-d', strtotime($fecha . ' -7 days')), 'description' =>  _l('trust_seal_certification_reminder_1_week')],
        ['fecha' =>  date('Y-m-d', strtotime($fecha . ' -3 days')), 'description' =>  _l('trust_seal_certification_reminder_3_days')],
        ['fecha' =>  date('Y-m-d', strtotime($fecha . ' -1 days')), 'description' =>  _l('trust_seal_certification_reminder_1_days')],
        ['fecha' => date('Y-m-d', strtotime($fecha)), 'description' =>  _l('trust_seal_certification_reminder_current_day')]
    ];
    return $date_reminder;
}


function get_reports_certifications()
{
    return [
        ['code' => 1, 'translate_name' => _l('report_certification_1')],
        ['code' => 2, 'translate_name' => _l('report_certification_2')],
        ['code' => 3, 'translate_name' => _l('report_certification_3')]
        // ['code' => 4, 'translate_name' => _l('report_certification_4')],
        // ['code' => 5, 'translate_name' => _l('report_certification_5')],
        // ['code' => 6, 'translate_name' => _l('report_certification_6')]
    ];
}

function get_reports_audits()
{
    return [
        ['code' => 1, 'translate_name' => _l('report_audits_1')],
        ['code' => 2, 'translate_name' => _l('report_audits_2')],
        ['code' => 3, 'translate_name' => _l('report_audits_3')]
        // ['code' => 4, 'translate_name' => _l('report_audits_4')]
    ];
}

function get_reports_filters()
{
    return [
        ['code' => 1, 'translate_name' => _l('report_period_1')],
        ['code' => 2, 'translate_name' => _l('report_period_2')],
        ['code' => 3, 'translate_name' => _l('report_period_3')],
        ['code' => 4, 'translate_name' => _l('report_period_4')]
        //['code' => 5, 'translate_name' => _l('report_period_5')]
    ];
}

function _saveFileFromBase64($id)
{
    if (isset($_FILES['file_client_logo']['name']) && $_FILES['file_client_logo']['name'] != '') {

        $path = PATH_SEALS . "logo_cliente_base/" . $id . '/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            fopen(rtrim($path, '/') . '/' . 'index.html', 'w');
        }

        $temp = explode(".", $_FILES["file_client_logo"]["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp);
        $tmpFilePath = $_FILES['file_client_logo']['tmp_name'];
        $newFilePath = $path . $newfilename;

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            return $newfilename;
        }
    } else {
        return "";
    }
}

function _saveBase64File($base64String, $output_file, &$ext)
{
    $explodePie = explode("|", $base64String);

    $base64_string = explode(";base64,", $explodePie[0]);

    $extension = explode("/", $base64_string[0]);

    $extension = explode(";", $extension[1]);

    $bin = base64_decode($explodePie[1], true);

    //    if (strpos($bin, '%PDF') !== 0 && strpos($bin, '%JPG') !== 0 && strpos($bin, '%PNG') !== 0 && strpos($bin, '%JPEG') !== 0) {
    //        throw new Exception('Falta la firma del archivo');
    //    }


    $ext = $extension[0];

    $m = file_put_contents("$output_file.{$extension[0]}", $bin);

    return $m;
}

function GenerateNiu($certification_fecha_publication, $certification_name,   $certification_id,  $company_id)
{
    $niu = "";
    $listFirtsLetter = "";

    foreach (explode(" ", $certification_name) as $value) {
        $listFirtsLetter .= substr($value, 0, 1);
    }

    $letters = strtoupper($listFirtsLetter);
    $year = substr($certification_fecha_publication, 0, 4);
    $niu  = "$company_id-$letters-$year-$certification_id";

    return $niu;
}

function insert_niu($id)
{
    $CI = &get_instance();

    $certification = $CI->certifications_model->get($id);
    if ($certification->id_seal > 0 &&  $certification->id_customer > 0) {
        $nameSeal = $CI->certifications_model->get_seal($certification->id_seal)[0]['title'];
        $CI->db->where('id', $id);
        $CI->db->update(db_prefix() . 'certifications', [
            'certificationkey'
            => GenerateNiu($certification->date,  $nameSeal, $id, $certification->id_customer)
        ]);
    }
}

function Get_Seal_Config()
{
    $objConfig =  new stdClass();
    $objConfig->seal_default_priority = get_option("seal_default_priority");
    $objConfig->seal_default_service = get_option("seal_default_service");
    $objConfig->seal_default_departamens = get_option("seal_default_departamens");

    $objConfig->seal_api_user = get_option("seal_api_user");
    $objConfig->seal_api_password = get_option("seal_api_password");
 

    return $objConfig;
}


function if_have_completed_audit_or_certifications_assign()
{
    // if (!is_client_logged_in()) {
    //     return [];
    // }

    // $CI = &get_instance();
    // $userid = get_client_user_id();
    // $staffid = get_contact_user_id();
    // $CI->load->model('trust_seal/audits_model');

    // foreach (get_announcements_audits($userid) as $value) {
    //     $certification_or_audit = $value['certification_or_audit'];
    //     $CI->db->select()
    //         ->from(db_prefix() . 'dismissed_announcements_audits')
    //         ->where([
    //             'staff' => $staffid, 'id_seal' => $value['id_seal'], 'certification_or_audit' => $certification_or_audit, "announcementid" => $value['announcementid']
    //         ]);
    //     $query = $CI->db->get();
    //     if ($query) {
    //         if ($query->num_rows() == 0) {
    //             $CI->load->view('trust_seal/message/alerts', ['userid' => $userid, 'announcementid' => $value['announcementid'], 'staffid' => $staffid, 'certification_or_audit' => $certification_or_audit, 'id_seal' => $value['id_seal'], 'seal' => $CI->audits_model->get_seal($value['id_seal'])[0]['title']]);
    //         }
    //     }
    // }
}




// function handle_contact_profile_image_upload($contact_id = '')
// {
//     if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != '') {
//         hooks()->do_action('before_upload_contact_profile_image');
//         if ($contact_id == '') {
//             $contact_id = get_contact_user_id();
//         }
//         $path = get_upload_path_by_type('contact_profile_images') . $contact_id . '/';
//         // Get the temp file path
//         $tmpFilePath = $_FILES['profile_image']['tmp_name'];
//         // Make sure we have a filepath
//         if (!empty($tmpFilePath) && $tmpFilePath != '') {
//             // Getting file extension
//             $extension = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));

//             $allowed_extensions = [
//                 'jpg',
//                 'jpeg',
//                 'png',
//             ];

//             $allowed_extensions = hooks()->apply_filters('contact_profile_image_upload_allowed_extensions', $allowed_extensions);

//             if (!in_array($extension, $allowed_extensions)) {
//                 set_alert('warning', _l('file_php_extension_blocked'));

//                 return false;
//             }
//             _maybe_create_upload_path($path);
//             $filename    = unique_filename($path, $_FILES['profile_image']['name']);
//             $newFilePath = $path . $filename;
//             // Upload the file into the company uploads dir
//             if (move_uploaded_file($tmpFilePath, $newFilePath)) {
//                 $CI                       = & get_instance();
//                 $config                   = [];
//                 $config['image_library']  = 'gd2';
//                 $config['source_image']   = $newFilePath;
//                 $config['new_image']      = 'thumb_' . $filename;
//                 $config['maintain_ratio'] = true;
//                 $config['width']          = hooks()->apply_filters('contact_profile_image_thumb_width', 320);
//                 $config['height']         = hooks()->apply_filters('contact_profile_image_thumb_height', 320);
//                 $CI->image_lib->initialize($config);
//                 $CI->image_lib->resize();
//                 $CI->image_lib->clear();
//                 $config['image_library']  = 'gd2';
//                 $config['source_image']   = $newFilePath;
//                 $config['new_image']      = 'small_' . $filename;
//                 $config['maintain_ratio'] = true;
//                 $config['width']          = hooks()->apply_filters('contact_profile_image_small_width', 32);
//                 $config['height']         = hooks()->apply_filters('contact_profile_image_small_height', 32);
//                 $CI->image_lib->initialize($config);
//                 $CI->image_lib->resize();

//                 $CI->db->where('id', $contact_id);
//                 $CI->db->update(db_prefix() . 'contacts', [
//                     'profile_image' => $filename,
//                 ]);
//                 // Remove original image
//                 unlink($newFilePath);

//                 return true;
//             }
//         }
//     }

//     return false;
// }

function get_progress_bar($statusBar)
{
    $ext = '<p class="tw-mb-0 tw-font-small tw-text-base tw-tracking-tight" style="font-size:.1.1rem;text-align: center;">'.
        '<span class="tw-text-neutral-500">'.$statusBar.'%</span>'.
     '</p><br/>';

    return    $ext.'<div class="progress progress-bar-mini">'.
    '<div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: '.$statusBar.'%" data-percent="'.$statusBar.'">'.
    '</div>'.
'</div>';
}

function get_count_quiz_complete($quiz)
{
    $count = 0;
    foreach($quiz as $q)
    {
        if(count($q['approved']) > 0 && ($q['approved']['approved'] == 1 || $q['approved']['approved'] == 0))
        {
            $count++;
        }
    }
    
    return $count;
}