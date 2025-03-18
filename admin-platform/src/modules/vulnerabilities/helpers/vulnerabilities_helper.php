<?php
function get_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\.]{1,63}\.?[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

function get_status_s_format($status)
{
    $statusColor = adjust_hex_brightness($status['status_color'], 0.4);
    $statusColorBorder = adjust_hex_brightness($status['status_color'], 0.04);

    return sprintf('<span class="label ticket-status-%s" style="border:1px solid  %s; color: %s; background:%s;">%s</label>', $status['status'], $statusColor, $status['status_color'], $statusColorBorder, $status['translate_name']);
}

function get_contacts_notification_clients_vulnerabilities($idClientes)
{
    $CI = &get_instance();
    $sql = 'SELECT ct.* FROM ' . db_prefix() . 'contacts ct LEFT JOIN ' . db_prefix() . 'permision_vulnerabilities pm ON pm.contactid = ct.id WHERE userid = ' . $idClientes . ' AND active = 1 AND pm.vulnerabilities = 1';
    $result = $CI->db->query($sql)->result_array();
    return $result;
}

function get_risk_or_confidence_format($status, $withoutBorder =  false)
{
    $statusColor = adjust_hex_brightness($status['status_color'], 0.4);

    if (!$withoutBorder) {
        $statusColorBorder = adjust_hex_brightness($status['status_color'], 0.04);
    } else {
        $statusColorBorder =  $statusColor;
    }

    return sprintf('<span class="label ticket-status-%s" style="border:1px solid  %s; color: %s; background:%s;">%s</label>', $status['status'], $statusColor, $status['status_color'], $statusColorBorder, $status['translate_name']);
}


function get_scan_profile()
{
    return [
        ['status' => "brute", 'translate_name' => _l('scan_type_brute'), 'status_color' => '#2563eb'],
        ['status' => "scan", 'translate_name' => _l('scan_type_scan'), 'status_color' => '#2563eb'],
        ['status' => "vulnerability", 'translate_name' => _l('scan_type_vulnerability'), 'status_color' => '#ff2d42'],
        // ['status' => "fast_scan.pw4af", 'translate_name' => "fast_scan.pw4af", 'status_color' => '#ff2d42'],
        // ['status' => "full_audit.pw4af", 'translate_name' => "full_audit.pw4af", 'status_color' => '#64748b'],
        // ['status' => "
        // full_audit_spider_man.pw4af", 'translate_name' => "
        // full_audit_spider_man.pw4af", 'status_color' => '#64748b'],
        // ['status' => "OWASP_TOP10.pw4af", 'translate_name' => "OWASP_TOP10.pw4af", 'status_color' => '#64748b'],
        // ['status' => "sitemap.pw4af", 'translate_name' => "sitemap.pw4af", 'status_color' => '#64748b'],
        // ['status' => "web_infrastructure.pw4af", 'translate_name' => "web_infrastructure.pw4af", 'status_color' => '#64748b']

    ];
}

function get_scan_profile_modulo($module_type)
{
    switch ($module_type) {

        case  "brute":
            return [
                ['status' => "ftp_brute", 'translate_name' => 'ftp_brute', 'status_color' => '#2563eb'],
                ['status' => "ftps_brute", 'translate_name' => 'ftps_brute', 'status_color' => '#2563eb'],
                ['status' => "pop3_brute", 'translate_name' => 'pop3_brute', 'status_color' => '#ff2d42'],
                ['status' => "pop3s_brute", 'translate_name' => 'pop3s_brute', 'status_color' => '#ff2d42'],
                ['status' => "smtp_brute", 'translate_name' => 'smtp_brute', 'status_color' => '#ff2d42'],
                ['status' => "smtps_brute", 'translate_name' => 'smtps_brute', 'status_color' => '#ff2d42'],
                ['status' => "ssh_brute", 'translate_name' => 'ssh_brute', 'status_color' => '#ff2d42'],
                ['status' => "telnet_brute", 'translate_name' => 'telnet_brute', 'status_color' => '#ff2d42'],
            ];
        case  "scan":
            return [
                ['status' =>  "admin_scan",          'translate_name' =>  "admin_scan", 'status_color' => '#2563eb'],
                ['status' =>  "drupal_modules_scan", 'translate_name' =>  "drupal_modules_scan", 'status_color' => '#2563eb'],
                ['status' =>  "drupal_theme_scan",   'translate_name' =>  "drupal_theme_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "drupal_version_scan", 'translate_name' =>  "drupal_version_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "http_redirect_scan",  'translate_name' =>  "http_redirect_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "http_status_scan",    'translate_name' =>  "http_status_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "icmp_scan",           'translate_name' =>  "icmp_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "joomla_template_scan", 'translate_name' =>  "joomla_template_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "joomla_user_enum_scan", 'translate_name' =>  "joomla_user_enum_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "joomla_version_scan", 'translate_name' =>  "joomla_version_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "moveit_version_scan", 'translate_name' =>  "moveit_version_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "pma_scan", 'translate_name' => "pma_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "port_scan", 'translate_name' => "port_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "subdomain_scan", 'translate_name' =>  "subdomain_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "viewdns_reverse_iplookup_scan", 'translate_name' => "viewdns_reverse_iplookup_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "waf_scan", 'translate_name' => "waf_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "web_technologies_scan", 'translate_name' => "web_technologies_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "wordpress_version_scan", 'translate_name' => "wordpress_version_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "wp_plugin_scan", 'translate_name' => "wp_plugin_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "wp_theme_scan", 'translate_name' => "wp_theme_scan", 'status_color' => '#ff2d42'],
                ['status' =>  "wp_timethumbs_scan", 'translate_name' => "wp_timethumbs_scan", 'status_color' => '#ff2d42'],
            ];
        case  "vulnerability":
            return [
                ['status' => "accela_cve_2021_34370_vuln", 'translate_name' =>  "accela_cve_2021_34370_vuln", 'status_color' => '#2563eb'],
                ['status' => "adobe_coldfusion_cve_2023_26360_vuln", 'translate_name' =>  "adobe_coldfusion_cve_2023_26360_vuln", 'status_color' => '#2563eb'],
                ['status' => "apache_cve_2021_41773_vuln", 'translate_name' =>  "apache_cve_2021_41773_vuln", 'status_color' => '#2563eb'],
                ['status' => "apache_cve_2021_42013_vuln", 'translate_name' =>  "apache_cve_2021_42013_vuln", 'status_color' => '#2563eb'],
                ['status' => "apache_struts_vuln", 'translate_name' =>  "apache_struts_vuln", 'status_color' => '#2563eb'],
                ['status' => "aviatrix_cve_2021_40870_vuln", 'translate_name' =>  "aviatrix_cve_2021_40870_vuln", 'status_color' => '#2563eb'],
                ['status' => "cisco_hyperflex_cve_2021_1497_vuln", 'translate_name' =>  "cisco_hyperflex_cve_2021_1497_vuln", 'status_color' => '#2563eb'],
                ['status' => "citrix_cve_2019_19781_vuln", 'translate_name' =>  "citrix_cve_2019_19781_vuln", 'status_color' => '#2563eb'],
                ['status' => "citrix_cve_2023_24488_vuln", 'translate_name' =>  "citrix_cve_2023_24488_vuln", 'status_color' => '#2563eb'],
                ['status' => "clickjacking_vuln", 'translate_name' =>  "clickjacking_vuln", 'status_color' => '#2563eb'],
                ['status' => "cloudron_cve_2021_40868_vuln", 'translate_name' =>  "cloudron_cve_2021_40868_vuln", 'status_color' => '#2563eb'],
                ['status' => "content_security_policy_vuln", 'translate_name' =>  "content_security_policy_vuln", 'status_color' => '#2563eb'],
                ['status' => "content_type_options_vuln", 'translate_name' =>  "content_type_options_vuln", 'status_color' => '#2563eb'],
                ['status' => "cyberoam_netgenie_cve_2021_38702_vuln", 'translate_name' =>  "cyberoam_netgenie_cve_2021_38702_vuln", 'status_color' => '#2563eb'],
                ['status' => "exponent_cms_cve_2021_38751_vuln", 'translate_name' =>  "exponent_cms_cve_2021_38751_vuln", 'status_color' => '#2563eb'],
                ['status' => "f5_cve_2020_5902_vuln", 'translate_name' =>  "f5_cve_2020_5902_vuln", 'status_color' => '#2563eb'],
                ['status' => "forgerock_am_cve_2021_35464_vuln", 'translate_name' =>  "forgerock_am_cve_2021_35464_vuln", 'status_color' => '#2563eb'],
                ['status' => "galera_webtemp_cve_2021_40960_vuln", 'translate_name' =>  "galera_webtemp_cve_2021_40960_vuln", 'status_color' => '#2563eb'],
                ['status' => "grafana_cve_2021_43798_vuln", 'translate_name' =>  "grafana_cve_2021_43798_vuln", 'status_color' => '#2563eb'],
                ['status' => "graphql_vuln", 'translate_name' =>  "graphql_vuln", 'status_color' => '#2563eb'],
                ['status' => "gurock_testrail_cve_2021_40875_vuln", 'translate_name' =>  "gurock_testrail_cve_2021_40875_vuln", 'status_color' => '#2563eb'],
                ['status' => "hoteldruid_cve_2021-37833_vuln", 'translate_name' =>  "hoteldruid_cve_2021-37833_vuln", 'status_color' => '#2563eb'],
                ['status' => "http_cookie_vuln", 'translate_name' =>  "http_cookie_vuln", 'status_color' => '#2563eb'],
                ['status' => "http_cors_vuln", 'translate_name' =>  "http_cors_vuln", 'status_color' => '#2563eb'],
                ['status' => "http_options_enabled_vuln", 'translate_name' =>  "http_options_enabled_vuln", 'status_color' => '#2563eb'],
                ['status' => "justwirting_cve_2021_41878_vuln", 'translate_name' =>  "justwirting_cve_2021_41878_vuln", 'status_color' => '#2563eb'],
                ['status' => "log4j_cve_2021_44228_vuln", 'translate_name' =>  "log4j_cve_2021_44228_vuln", 'status_color' => '#2563eb'],
                ['status' => "maxsite_cms_cve_2021_35265_vuln", 'translate_name' =>  "maxsite_cms_cve_2021_35265_vuln", 'status_color' => '#2563eb'],
                ['status' => "msexchange_cve_2021_26855_vuln", 'translate_name' =>  "msexchange_cve_2021_26855_vuln", 'status_color' => '#2563eb'],
                ['status' => "msexchange_cve_2021_34473_vuln", 'translate_name' =>  "msexchange_cve_2021_34473_vuln", 'status_color' => '#2563eb'],
                ['status' => "novnc_cve_2021_3654_vuln", 'translate_name' =>  "novnc_cve_2021_3654_vuln", 'status_color' => '#2563eb'],
                ['status' => "omigod_cve_2021_38647_vuln", 'translate_name' =>  "omigod_cve_2021_38647_vuln", 'status_color' => '#2563eb'],
                ['status' => "payara_cve_2021_41381_vuln", 'translate_name' =>  "payara_cve_2021_41381_vuln", 'status_color' => '#2563eb'],
                ['status' => "phpinfo_cve_2021_37704_vuln", 'translate_name' =>  "phpinfo_cve_2021_37704_vuln", 'status_color' => '#2563eb'],
                ['status' => "placeos_cve_2021_41826_vuln", 'translate_name' =>  "placeos_cve_2021_41826_vuln", 'status_color' => '#2563eb'],
                ['status' => "prestashop_cve_2021_37538_vuln", 'translate_name' =>  "prestashop_cve_2021_37538_vuln", 'status_color' => '#2563eb'],
                ['status' => "puneethreddyhc_sqli_cve_2021_41648_vuln", 'translate_name' =>  "puneethreddyhc_sqli_cve_2021_41648_vuln", 'status_color' => '#2563eb'],
                ['status' => "puneethreddyhc_sqli_cve_2021_41649_vuln", 'translate_name' =>  "puneethreddyhc_sqli_cve_2021_41649_vuln", 'status_color' => '#2563eb'],
                ['status' => "qsan_storage_xss_cve_2021_37216_vuln", 'translate_name' =>  "qsan_storage_xss_cve_2021_37216_vuln", 'status_color' => '#2563eb'],
                ['status' => "server_version_vuln", 'translate_name' =>  "server_version_vuln", 'status_color' => '#2563eb'],
                ['status' => "strict_transport_security_vuln", 'translate_name' =>  "strict_transport_security_vuln", 'status_color' => '#2563eb'],
                ['status' => "subdomain_takeover_vuln", 'translate_name' =>  "subdomain_takeover_vuln", 'status_color' => '#2563eb'],
                ['status' => "tieline_cve_2021_35336_vuln", 'translate_name' =>  "tieline_cve_2021_35336_vuln", 'status_color' => '#2563eb'],
                ['status' => "tjws_cve_2021_37573_vuln", 'translate_name' =>  "tjws_cve_2021_37573_vuln", 'status_color' => '#2563eb'],
                ['status' => "vbulletin_cve_2019_16759_vuln", 'translate_name' =>  "vbulletin_cve_2019_16759_vuln", 'status_color' => '#2563eb'],
                ['status' => "wp_plugin_cve_2021_38314_vuln", 'translate_name' =>  "wp_plugin_cve_2021_38314_vuln", 'status_color' => '#2563eb'],
                ['status' => "wp_plugin_cve_2021_39316_vuln", 'translate_name' =>  "wp_plugin_cve_2021_39316_vuln", 'status_color' => '#2563eb'],
                ['status' => "wp_plugin_cve_2021_39320_vuln", 'translate_name' =>  "wp_plugin_cve_2021_39320_vuln", 'status_color' => '#2563eb'],
                ['status' => "wp_xmlrpc_bruteforce_vuln", 'translate_name' =>  "wp_xmlrpc_bruteforce_vuln", 'status_color' => '#2563eb'],
                ['status' => "wp_xmlrpc_dos_vuln", 'translate_name' =>  "wp_xmlrpc_dos_vuln", 'status_color' => '#2563eb'],
                ['status' => "wp_xmlrpc_pingback_vuln", 'translate_name' =>  "wp_xmlrpc_pingback_vuln", 'status_color' => '#2563eb'],
                ['status' => "x_powered_by_vuln", 'translate_name' =>  "x_powered_by_vuln", 'status_color' => '#2563eb'],
                ['status' => "x_xss_protection_vuln", 'translate_name' =>  "x_xss_protection_vuln", 'status_color' => '#2563eb'],
                ['status' => "xdebug_rce_vuln", 'translate_name' =>  "xdebug_rce_vuln", 'status_color' => '#2563eb'],
                ['status' => "zoho_cve_2021_40539_vuln", 'translate_name' =>  "zoho_cve_2021_40539_vuln", 'status_color' => '#2563eb']
            ];
    }
}


function get_risk_scan()
{
    return [
        ['status' => 'INFORMATIONAL', 'translate_name' => _l('risk_info'), 'status_color' => '#22c55e'],
        ['status' => 'LOW', 'translate_name' => _l('risk_low'), 'status_color' => '#2563eb'],
        ['status' => 'MEDIUM', 'translate_name' => _l('risk_middle'), 'status_color' => '#CA8A04'],
        ['status' => 'HIGH', 'translate_name' =>  _l('risk_high'), 'status_color' => '#ff2d42'],
    ];
}

function get_trust_scan()
{
    return [
        ['status' => 'INFORMATIONAL', 'translate_name' => _l('risk_info'), 'status_color' => '#22c55e'],
        ['status' => 'LOW', 'translate_name' => _l('risk_low'), 'status_color' => '#ff2d42'],
        ['status' => 'MEDIUM', 'translate_name' => _l('risk_middle'), 'status_color' => '#CA8A04'],
        ['status' => 'HIGH', 'translate_name' =>  _l('risk_high'), 'status_color' => '#22c55e'],
    ];
}

function get_color_risk_scan($indexColor)
{
    $tmpColor = [
        'INFORMATIONAL' => '#22c55e',
        'LOW'  => '#2563eb',
        'MEDIUM' => '#CA8A04',
        'HIGH' => '#ff2d42'
    ];

    return $tmpColor[$indexColor];
}

function get_status_scan()
{
    return [
        ['status' => 1, 'translate_name' => _l('in_process'), 'status_color' => '#2563eb'], //En progreso
        ['status' => 2, 'translate_name' => _l('canceled'), 'status_color' => '#ff2d42'], //Cancelado
        ['status' => 3, 'translate_name' =>  _l('finalized'), 'status_color' => '#22c55e'], //Finalizado
        ['status' => 4, 'translate_name' =>  _l('vulnerabilities_pending'), 'status_color' => '#CA8A04'], //Pendiente
        ['status' => 5, 'translate_name' =>  _l('vulnerabilities_generate_environme'), 'status_color' => '#94a3b8'], //Creando ambiente
    ];
}

function get_reports_vulnerabilities()
{
    return [
        ['code' => 1, 'translate_name' => _l('report_vulnerabilities_1')],
        // ['code' => 2, 'translate_name' => _l('report_vulnerabilities_2')],
        ['code' => 3, 'translate_name' => _l('report_vulnerabilities_3')]
        // ['code' => 4, 'translate_name' => _l('report_vulnerabilities_4')],
        // ['code' => 5, 'translate_name' => _l('report_vulnerabilities_5')],
        // ['code' => 6, 'translate_name' => _l('report_vulnerabilities_6')],
        // ['code' => 7, 'translate_name' => _l('report_vulnerabilities_7')],
        // ['code' => 8, 'translate_name' => _l('report_vulnerabilities_8')]
        // ['code' => 4, 'translate_name' => _l('report_audits_4')]
    ];
}

function formatt_render_details_alert($colunm, $aRow)
{
    $content = '<a href="javascript:void(0);"  onclick="shoDetailsAlert(\'' . $aRow['id'] . '\',\'' . $aRow['id_client'] . '\',\'' . $aRow['web_site'] . '\')"  data-client="' . $aRow['id_client'] . '"  data-web="' . $aRow['web_site'] . '"  data-id="' . $aRow['id'] . '"   class="valign btn_details_modal_alert">' . $colunm . '</a>';

    return $content;
}


function formatt_render_web_site($colunm, $aRow)
{
    $url   = admin_url("vulnerabilities/knowledge_base/") . $aRow['id'];

    $content = '<a href="' . $url . '" class="valign">' . $colunm . '</a>';
    $content .= '<div class="row-options">';
    //$content .= '<a href="' . $url . '">' . _l('view') . '</a>';



    if (!in_array($aRow['state'], [1, 4, 5])) {
        if (has_permission('vulnerabilities', '', 'create')) {
            $content .= '<a href="#" class="btn_current_scan"   data-action="1"  data-idscanspider="' . $aRow['spider_analisis_id'] . '" data-idscan="' . $aRow['analisis_id'] . '"  data-id_analyzes="' . $aRow['id'] . '" data-client="' . $aRow['id_client'] . '" data-url="' . $colunm . '"  >' . _l('table_new_vulnerabilities') . '</a>';
        }
    }

    if (in_array($aRow['state'], [1, 4, 5])) {
        if (has_permission('vulnerabilities', '', 'edit')) {
            $content .= '<a href="#"  class="btn_current_scan" data-action="3"  data-idscanspider="' . $aRow['spider_analisis_id'] . '" data-idscan="' . $aRow['analisis_id'] . '"  data-id_analyzes="' . $aRow['id'] . '" data-client="' . $aRow['id_client'] . '" data-url="' . $colunm . '" >' . _l('table_stop_vulnerabilities') . '</a>';
        }
    }
    if (has_permission('vulnerabilities', '', 'delete')) {
        $content .= ' | <a href="#" class="btn_current_scan"  data-action="2" data-idscanspider="' . $aRow['spider_analisis_id'] . '" data-idscan="' . $aRow['analisis_id'] . '"  data-id_analyzes="' . $aRow['id'] . '" data-client="' . $aRow['id_client'] . '" data-url="' . $colunm . '" style="color:#ff2d42;">' . _l('table_remove_vulnerabilities') . '</a>';
    }


    $content .= '</div>';

    return  $content;
}


function send_notifications_and_emails_scan($id_client, $analisis_id, $id_, $finalizado = false)
{
    $CI = &get_instance();

    $contactsClients = get_contacts_notification_clients_vulnerabilities($id_client);
    if (!$finalizado) {
        $template = "vulnerabilities_assigned_to_client";
    } else {
        $template = "new_scan_completed";
    }

    foreach ($contactsClients as $value) {
        send_mail_template($template, "vulnerabilities", $value['email'], get_staff_user_id(), $id_, $id_client, get_contact_user_id(), $value['id']);
    }


    $CI->load->model('clients_model');
    $adminsClients = $CI->clients_model->get_admins($id_client);


    foreach ($adminsClients as $value) {

        $contact = $CI->clients_model->get_contact($value['staff_id']);

        send_mail_template($template, "vulnerabilities", $contact->email, get_staff_user_id(),  $id_, $id_client, get_contact_user_id(), $value['staff_id']);

        $current_subject =  !$finalizado ? 'email_subject_vulnerabilities_init' : 'email_subject_vulnerabilities_completed';

        $notified          = add_notification([
            'description'     =>  $current_subject,
            'touserid'        => $value['staff_id'],
            'fromcompany'     => 1,
            'fromuserid'      => 0,
            'link'            => 'vulnerabilities/vulnerabilities/knowledge_base/' . $id_,
            'additional_data' => serialize([
                _l($current_subject),
            ]),
        ]);

        if ($notified) {
            pusher_trigger_notification([$value['staff_id']]);
        }
    }
}

/***************API FUNCTION *********************************/

function run_reminder_vulnerabilities($manually)
{

    if ($manually == true) {
        if (!extension_loaded('suhosin')) {
            @ini_set('memory_limit', '-1');
        }
    }

    Run_Check_state_analisis();
}

function ClearWebSiteUrl($url)
{
    $url = strtolower(trim($url));
    $customUrl = "https://$url";
    return $customUrl;
}

function Save_Queue_To_Run($id_client, $web_site)
{
    $id_analyzes = StoreScanPending($id_client, $web_site, "(state=1 OR state_spider=1)");
    if (is_numeric($id_analyzes)) {
        Save_Queue_Analyzes_Vulnerabilities(['id_analyzes' => $id_analyzes]);
        return "";
    } else {
        return $id_analyzes;
    }
}

function StoreScanPending($id_cliente, $url, $extraWhere = "")
{
    $CI = &get_instance();
    $url = strtolower(trim($url));

    if (
        $CI->vulnerabilities_model->verify_scan_exist($id_cliente, $url,  $extraWhere)
        == null
    ) { //Si no existe un scan activo por target

        $dataSave = [
            'id_client' =>  $id_cliente,
            'web_site' =>  $url,
            'date' =>  date('Y-m-d H:i:s')
        ];

        $dataSave = array_merge($dataSave, ['warnings' => 0, 'state_spider' => 0, 'spider_analisis_id' => 0, 'analisis_id' => 0, 'state' => 4, 'state_reading' => 0, 'risk' => '']);

        $rs = $CI->vulnerabilities_model->verify_scan_exist($id_cliente, $url);

        if ($rs  == null) {
            $id_analyzes = $CI->vulnerabilities_model->save_Analysis($dataSave);
        } else {

            $id_analyzes =  $rs->id;
            $CI->vulnerabilities_model->update_last_scan($id_cliente, $url, $dataSave);
        }

        Delete_Alert_WebSites($id_analyzes); //Limpiando

        return $id_analyzes;
    } else {
        $listMsG = "- " . _l('vulnerabilities_msm_scan_in_process', $url) . "<br/>";
    }

    return $listMsG;
}

function StoreAnalisiInit($id_cliente, $url)
{
    $CI = &get_instance();

    $dataSave = [
        'id_client' =>  $id_cliente,
        'web_site' => $url,
        'date' =>  date('Y-m-d H:i:s')
    ];

    $rs = $CI->vulnerabilities_model->verify_scan_exist($id_cliente, $url);

    if ($rs  == null) {
        $id_analyzes = $CI->vulnerabilities_model->save_Analysis($dataSave);
    } else {

        $id_analyzes =  $rs->id;
        $CI->vulnerabilities_model->update_last_scan($id_cliente, $url, $dataSave);
    }

    return $id_analyzes;
}


function RunSpiderQueue()
{
    $CI = &get_instance();
    $listQueue = Get_Queue_Analyzes_Vulnerabilities();
    if ($listQueue != null) {

        foreach ($listQueue as $itemsAnalisis) {
            $analisis_vulnerabilities = $CI->vulnerabilities_model->get_analisys($itemsAnalisis->id_analyzes);
            if ($analisis_vulnerabilities != null) {
                GenerateScanSpiderInit($analisis_vulnerabilities->id_client, $itemsAnalisis->id_analyzes);
            }
        }
    }

    RunningQueueAndContainer();
}

function RunningQueueAndContainer($extraWhere = "")
{
    $listQueue = Get_Analisis_Pending_With_Container($extraWhere);

    if ($listQueue != null) {
        // v.id, v.web_site, v.id_client, cv.host_port
        foreach ($listQueue as $itemsAnalisis) {

            $portRun = $itemsAnalisis->host_port;
            $url = $itemsAnalisis->web_site;
            $id_cliente = $itemsAnalisis->id_client;

            if (Container_Manager::status_container($portRun)->status != null) {

                $url = strtolower(trim($url));
                $customUrl = ClearWebSiteUrl($url);

                Owasp_Zap::setPort($portRun);
                $scan = Owasp_Zap::create_spider_scan($customUrl);

                if (isset($scan->scan)) {
                    StoreScanInit($scan, $id_cliente, $url);
                }
            }
        }
    }
}

function GenerateScanSpiderInit($id_client, $id_analyzes)
{
    $CI = &get_instance();
    $listMsG = "";
    // TO DO
    //Consulta la configuracion para ver si se puede crear el analisis
    $rsContainerConfig = Get_Container_Config_Vulnerabilities();
    $container_running =  Container_Manager::count_container();

    if ($rsContainerConfig->number_container > $container_running) {
        $portRun = Get_Container_Host_Port_Vulnerabilities();
        $rsContainer = Container_Manager::create_container($portRun);

        if (
            isset($rsContainer->running)
            && $rsContainer->running == true
        ) {

            Container_Manager::delete_container($portRun);
        }

        if (isset($rsContainer->container)) {

            Save_Container_Vulnerabilities([
                'id_analyzes' => $id_analyzes,
                'host_port' => $rsContainer->container->host_port,
                'api_key' => $rsContainer->container->api_key,
                'container_name' => $rsContainer->container->container_name,
            ]);

            Delete_Queue_Vulnerabilities($id_analyzes);

            $CI->vulnerabilities_model->update_scan(['state' => '5'], ['id' => $id_analyzes]);

            send_notifications_and_emails_scan($id_client, $id_analyzes, $id_analyzes);
        } else {
            $listMsG = "- " . _l('vulnerabilities_msm_container') . "<br/>";
        }
    }

    return $listMsG;
}

function ScanInitOuValidations($url, $id_cliente, $extraWhere = "")
{
    $CI = &get_instance();
    $customUrl = ClearWebSiteUrl($url);
    $scan = Owasp_Zap::create_scan($customUrl);
    return StoreScanInit($scan, $id_cliente, $url, true);
}

function GenerateScanInit($url, $id_cliente, $extraWhere = "")
{
    $CI = &get_instance();
    $url = strtolower(trim($url));
    $listMsG = "";

    if (
        $CI->vulnerabilities_model->verify_scan_exist($id_cliente, $url,  $extraWhere)
        == null
    ) { //Si no existe un scan activo por target

        $customUrl = ClearWebSiteUrl($url);
        $scan = Owasp_Zap::create_scan($customUrl);
        StoreScanInit($scan, $id_cliente, $url, true);
    } else {
        $listMsG = "- " . _l('vulnerabilities_msm_scan_in_process', $url) . "<br/>";
    }

    return $listMsG;
}


function StoreScanInit($scan, $id_cliente, $url, $spiderOrscan = false)
{
    $CI = &get_instance();
    if (isset($scan->scan)) {

        $dataSave = [
            'id_client' =>  $id_cliente,
            'web_site' => $url,
            'date' =>  date('Y-m-d H:i:s')
        ];

        if (!$spiderOrscan) {
            $dataSave = array_merge($dataSave, ['state_spider' => '1', 'state' => '1', 'warnings' => 0, 'state_reading' => 0, 'risk' => '', 'spider_analisis_id' => $scan->scan, 'analisis_id' => 0]);
        } else {
            $dataSave = array_merge($dataSave, ['state_spider' => '3', 'state' => '1', 'analisis_id' => $scan->scan]);
        }

        if ($CI->vulnerabilities_model->verify_scan_exist($id_cliente, $url) == null) {

            $CI->vulnerabilities_model->save_Analysis($dataSave);
        } else {

            $CI->vulnerabilities_model->update_last_scan($id_cliente, $url, $dataSave);
        }

        return $scan->scan;
    }

    return 0;
}

function Run_Frequency_Scan()
{
    $srContainer = Get_Container_Config_Vulnerabilities();

    if ($srContainer->frequency == 0) {
        return;
    }

    $CI = &get_instance();
    $now = time(); //
    $whereConditions = "state = 3";
    $rs = Get_Analisys_Conditions($whereConditions);
    if (count($rs) > 0) {
        foreach ($rs as $value) {
            $your_date = strtotime($value['date']);
            $datediff = $now - $your_date;
            $dayDiferencia = round($datediff / (60 * 60 * 24));
            if ($dayDiferencia  >= $srContainer->frequency) {
                //Correr frecuencia en este parte
                //TO DO 
                Save_Queue_To_Run($value['id_client'], $value['web_site']);
            }
        }
    }
}

function Check_state_analisis($id, $setMemoryLimit = false)
{
    $CI = &get_instance();

    if ($setMemoryLimit) {
        if (!extension_loaded('suhosin')) {
            @ini_set('memory_limit', '-1');
        }
    }

    $listWarning = [];
    $listCount = 0;
    foreach (get_risk_scan() as $status) {
        $listWarning[ucfirst(strtolower($status['status']))] = $listCount;
        $listCount++;
    }

    $analisis_vulnerabilities = $CI->vulnerabilities_model->get_analisys($id);
    $statusAnalisis =  0;
    $analisisId = 0;
    $startCount = 1;
    $startLimit = 500;
    $totalAlertas = 0;
    $cantidadAlertas = 0;

    if (
        $analisis_vulnerabilities != null
        &&  $analisis_vulnerabilities->state != 4
    ) {
        //id_analyzes, host_port, api_key, container_name
        $rsContainerRun = Get_Container_Vulnerabilities($id);

        if (
            $rsContainerRun  != null
            && Container_Manager::status_container($rsContainerRun->host_port)->status != null
        ) {

            Owasp_Zap::setPort($rsContainerRun->host_port);

            if (
                $analisis_vulnerabilities->state_spider == 1
            ) {
                Delete_Alert_WebSites($id);
            }

            if ($analisis_vulnerabilities->state == 1) {
                $startCount = total_rows_alert_db(db_prefix() . 'list_alert_vulnerabilities', 'id_analyzes=' . $id);
                $cantidadAlertas = $startCount;
            }

            $analisisId = $analisis_vulnerabilities->analisis_id;

            if ($analisis_vulnerabilities->state_spider == 1) {
                $scan = Owasp_Zap::scan_spider_view_status($analisis_vulnerabilities->spider_analisis_id);
                $statusAnalisis = $scan->status;
            }

            if ($statusAnalisis == 100) {

                if ($analisis_vulnerabilities->state_spider == 1) {
                    //TO DO
                    $analisisId = ScanInitOuValidations($analisis_vulnerabilities->web_site, $analisis_vulnerabilities->id_client);
                }
            }

            if ($analisisId >= 0 && $analisis_vulnerabilities->state_spider == 3) {
                if ($analisis_vulnerabilities->state == 1) {

                    if ($analisis_vulnerabilities->state_reading == 0) {

                        $whereConditions = ['id' => $id];
                        $CI->vulnerabilities_model->update_scan(['state_reading' => 1], $whereConditions);
                        // Delete_Alert_WebSites($id);
                        $customUrl = ClearWebSiteUrl($analisis_vulnerabilities->web_site);
                        $rsAlert = Owasp_Zap::scan_alert_by_url($customUrl, $startCount, $startLimit);

                        $datosAlert = [];
                        $riesgoMayor = "";
                        $maxRiesgo = 0;

                        if (isset($rsAlert->alerts)) {
                            $totalAlertas = count($rsAlert->alerts);
                            foreach ($rsAlert->alerts  as $value) {
                                $cantidadAlertas++;
                                $tmptag  = "";
                                $value->id_analyzes = $id;
                                $value->web_site = $analisis_vulnerabilities->web_site;
                                $value->id_client = $analisis_vulnerabilities->id_client;
                                //$value->date = date('Y-m-d');
                                $value->tags = json_encode($value->tags);
                                // $listWarning[$value->risk]++;
                                //$value = json_decode(json_encode($value), true);

                                Save_Alert_WebSites($value);

                                $riesgo = ucfirst(strtolower($value->risk));

                                if ($maxRiesgo < $listWarning[$riesgo]) {
                                    $maxRiesgo = $listWarning[$riesgo];
                                    $riesgoMayor = $riesgo;
                                }
                            }

                            $whereConditions = ['id' => $id];
                            $CI->vulnerabilities_model->update_scan(['warnings' => $cantidadAlertas, 'risk' => $riesgoMayor], $whereConditions);
                        }

                        Fill_Progress_Plugin($analisisId, $id, $analisis_vulnerabilities->id_client);
                        $whereConditions = ['id' => $id];
                        $CI->vulnerabilities_model->update_scan(['state_reading' => 0], $whereConditions);
                    }
                    // echo "<pre>";
                    // print_r($datosAlert);
                    // die();
                    //TO DO ACTUALIZAR LA DB
                    $scan = Owasp_Zap::scan_status($analisisId);
                    $statusAnalisis = isset($scan->status) ?  $scan->status : 0;
                }

                if ($statusAnalisis == 100 && $totalAlertas == 0) {

                    $whereConditions = ['id' => $id];
                    $CI->vulnerabilities_model->update_scan(['state' => '3'], $whereConditions);

                    //Eliminando contenedor
                    Container_Manager::delete_container($rsContainerRun->host_port);
                    //Eliminando el contenedor de la base de datos
                    Delete_Container_Vulnerabilities($id);

                    send_notifications_and_emails_scan($analisis_vulnerabilities->id_client, $id, $id, true);
                }
            }
        }
    }

    $data['analisis_vulnerabilities'] = $analisis_vulnerabilities;
    $data['statusAnalisis'] = $statusAnalisis;
    $data['id_analyzes'] = $id;

    return $data;
}

function Fill_Progress_Plugin($analisisId, $id, $id_client)
{
    $cans_plugins = Owasp_Zap::scan_progress_status($analisisId);
    if (count($cans_plugins->scanProgress) >= 1) {
        Delete_Alert_Plugins("id_analyzes = $id");
        foreach ($cans_plugins->scanProgress[1]->HostProcess as $item) {

            $items = $item->Plugin;

            Save_Alert_Plugins([
                'id_analyzes' => $id,
                'plugin' => $items[0],
                'version' => $items[2],
                'state' => $items[3],
                'request' => $items[4],
                'warnings' => $items[6],
                'id_client' => $id_client
            ]);
        }
    }
}

/*************************************Query Scann */


function Get_Analisys_Conditions($where = "")
{
    $CI = &get_instance();

    $CI->db->select('*');
    $CI->db->from(db_prefix() . 'vulnerabilities');
    $CI->db->order_by("date", "asc");

    if ($where != "")
        $CI->db->where($where);

    return $CI->db->get()->result_array();
}


function Save_Queue_Analyzes_Vulnerabilities($datos)
{
    if (Verify_Is_Queue_Exist($datos['id_analyzes']) == null) {

        $CI = &get_instance();

        $CI->db->trans_start(); // Inicia la transacción
        $CI->db->insert(db_prefix() . 'analyzes_queue_vulnerabilities', $datos);
        $CI->db->trans_complete(); // Finaliza la transacción

        if ($CI->db->trans_status() === FALSE) {
            // Si ocurrió un error en la transacción, se deshacen las consultas
            $CI->db->trans_rollback();
            return false;
        } else {
            // Si la transacción se completó correctamente, se confirman las consultas
            $CI->db->trans_commit();
            return true;
        }
    }
}

function Get_Queue_Analyzes_Vulnerabilities()
{
    $CI = &get_instance();
    $CI->db->select('id_analyzes, date');
    $CI->db->from(db_prefix() . 'analyzes_queue_vulnerabilities');
    $CI->db->order_by("date", "asc");
    $query = $CI->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->result();
        return $row;
    } else {
        $row = null;
        return $row;
    }
}

function Delete_Queue_Vulnerabilities($id_analyzes)
{
    $CI = &get_instance();
    $CI->db->where('id_analyzes', $id_analyzes);
    $CI->db->delete(db_prefix() . 'analyzes_queue_vulnerabilities');
    if ($CI->db->affected_rows() > 0) {
        // log_activity('Vul - clients_web_sites delete_reminder Deleted [ID:' . $idCertifications . ']');

        return true;
    }

    return false;
}

function Verify_Is_Queue_Exist($id)
{
    $CI = &get_instance();
    $CI->db->select('id_analyzes');
    $CI->db->from(db_prefix() . 'analyzes_queue_vulnerabilities');
    $CI->db->where('id_analyzes', $id);

    $CI->db->limit(1);

    $query = $CI->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row;
    } else {
        $row = null;
        return $row;
    }
}

function Get_Container_Config_Vulnerabilities()
{
    $objConfig =  new stdClass();
    $objConfig->frequency = get_option("frequency");
    $objConfig->number_container = get_option("number_container");
    $objConfig->url_analisis = get_option("url_analisis");
    $objConfig->url_container = get_option("url_container");
    $objConfig->key_analisys = get_option("key_analisys");
    $objConfig->key_container = get_option("key_container");
    $objConfig->port_init = get_option("port_init");

    return $objConfig;
}

function Update_Container_Config_Vulnerabilities($data, $id)
{
    $CI = &get_instance();
    $CI->db->where('id', $id);
    $CI->db->update(db_prefix() . 'analyzes_config_vulnerabilities', $data);
    if ($CI->db->affected_rows() > 0) {
        // log_activity('Complaint Service Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');

        return true;
    }

    return false;
}

function Get_Container_Host_Port_Vulnerabilities()
{
    $puertoInicial = get_option("port_init");
    $CI = &get_instance();
    $CI->db->select_max('host_port');
    $CI->db->from(db_prefix() . 'analyzes_container_vulnerabilities');

    $CI->db->limit(1);

    $query = $CI->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row->host_port != null ? ($row->host_port + 1) : ($puertoInicial + 1);
    } else {
        $row = ($puertoInicial + 1);
        return $row;
    }
}

function Get_Container_Vulnerabilities($id_vulnerability)
{
    $CI = &get_instance();
    $CI->db->select('id_analyzes, host_port, api_key, container_name');
    $CI->db->from(db_prefix() . 'analyzes_container_vulnerabilities');
    $CI->db->where(['id_analyzes' => $id_vulnerability]); //en procesos

    $CI->db->limit(1);

    $query = $CI->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row;
    } else {
        $row = null;
        return $row;
    }
}

function Delete_Container_Vulnerabilities($id_analyzes)
{
    $CI = &get_instance();
    $CI->db->where('id_analyzes', $id_analyzes);
    $CI->db->delete(db_prefix() . 'analyzes_container_vulnerabilities');
    if ($CI->db->affected_rows() > 0) {
        // log_activity('Vul - clients_web_sites delete_reminder Deleted [ID:' . $idCertifications . ']');

        return true;
    }

    return false;
}

function Get_Analisis_Pending_With_Container($extraWhere = "")
{
    $CI = &get_instance();
    $query = $CI->db->query('SELECT  v.id, v.web_site, v.id_client, cv.host_port FROM ' . db_prefix() . 'vulnerabilities v JOIN ' . db_prefix() . 'analyzes_container_vulnerabilities cv ON  v.id = cv.id_analyzes
    WHERE v.state = 5 ' . $extraWhere);

    if ($query->num_rows() > 0) {
        $rs = $query->result();
        return $rs;
    } else {
        return null;
    }
}

function Save_Container_Vulnerabilities($datos)
{
    $CI = &get_instance();

    $CI->db->trans_start(); // Inicia la transacción
    $CI->db->insert(db_prefix() . 'analyzes_container_vulnerabilities', $datos);
    $CI->db->trans_complete(); // Finaliza la transacción

    if ($CI->db->trans_status() === FALSE) {
        // Si ocurrió un error en la transacción, se deshacen las consultas
        $CI->db->trans_rollback();
        return false;
    } else {
        // Si la transacción se completó correctamente, se confirman las consultas
        $CI->db->trans_commit();
        return true;
    }
}


function if_state_process_vulnerability($id_vulnerability)
{
    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->from(db_prefix() . 'vulnerabilities');
    $CI->db->where(['id' => $id_vulnerability, 'state' => 1]); //en procesos

    $CI->db->limit(1);

    $query = $CI->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row;
    } else {
        $row = null;
        return $row;
    }
}

function is_exists_details($id_vulnerability, $knowledge_base_id)
{
    $CI = &get_instance();

    $CI->db->select('*');
    $CI->db->from(db_prefix() . 'detalles_vulnerabilities');
    $CI->db->where(['id_vulnerability' => $id_vulnerability, 'knowledge_base_id' => $knowledge_base_id]);

    $CI->db->limit(1);

    $query = $CI->db->get();

    if ($query->num_rows() > 0) {
        $row = $query->row();
        return $row;
    } else {
        $row = null;
        return $row;
    }
}

function update_analisis_detalles_h($id_vulnerability, $knowledge_base_id, $data)
{
    $CI = &get_instance();
    $CI->db->where(['id_vulnerability' => $id_vulnerability, 'knowledge_base_id' => $knowledge_base_id]);
    $CI->db->update(db_prefix() . 'detalles_vulnerabilities', $data);
    if ($CI->db->affected_rows() > 0) {
        // log_activity('Trust Seal Updated [ID:' . $id . ']');
        return true;
    }

    return false;
}

function save_analisis_detalles_h($datos)
{
    $CI = &get_instance();

    $CI->db->trans_start(); // Inicia la transacción
    $CI->db->insert(db_prefix() . 'detalles_vulnerabilities', $datos);
    $CI->db->trans_complete(); // Finaliza la transacción

    if ($CI->db->trans_status() === FALSE) {
        // Si ocurrió un error en la transacción, se deshacen las consultas
        $CI->db->trans_rollback();
        return false;
    } else {
        // Si la transacción se completó correctamente, se confirman las consultas
        $CI->db->trans_commit();
        return true;
    }
}

function Get_Alert_Conditions($where = [])
{
    $alertDb = GetCustomConect();
    $alertDb->select('*');
    $alertDb->from(db_prefix() . 'list_alert_vulnerabilities');
    $alertDb->where($where);
    $alertDb->limit(1);
    return $alertDb->get()->result();
    //->result_array();
}

function Get_Alert_WebSite($id_analyzes, $fields)
{
    $alertDb = GetCustomConect();
    $alertDb->select($fields);
    $alertDb->from(db_prefix() . 'list_alert_vulnerabilities');
    $alertDb->where('id_analyzes', $id_analyzes);

    $query =  $alertDb->get();

    if ($query->num_rows() > 0) {
        $result = $query->result();
        return $result;
    } else {
        $result = null;
        return $result;
    }
}

function Delete_Alert_WebSites($id_analyzes)
{
    $alertDb = GetCustomConect();
    $alertDb->where('id_analyzes', $id_analyzes);
    $alertDb->delete(db_prefix() . 'list_alert_vulnerabilities');
    if ($alertDb->affected_rows() > 0) {
        // log_activity('Vul - clients_web_sites delete_reminder Deleted [ID:' . $idCertifications . ']');

        return true;
    }

    return false;
}

function total_rows_alert_db($table, $where = [])
{
    $alertDb = GetCustomConect();
    if (is_array($where)) {
        if (sizeof($where) > 0) {
            $alertDb->where($where);
        }
    } elseif (strlen($where) > 0) {
        $alertDb->where($where);
    }

    return  $alertDb->count_all_results($table);
}

function Get_Alert_Plugins($id_analyzes, $fields)
{
    $alertDb = GetCustomConect();
    $alertDb->select($fields);
    $alertDb->from(db_prefix() . 'list_alert_vulnerabilities_progress');
    $alertDb->where('id_analyzes', $id_analyzes);

    $query =  $alertDb->get();

    if ($query->num_rows() > 0) {
        $result = $query->result();
        return $result;
    } else {
        $result = null;
        return $result;
    }
}

function Save_Alert_WebSites($datos)
{
    $alertDb = GetCustomConect();
    $alertDb->trans_start(); // Inicia la transacción
    $alertDb->insert(db_prefix() . 'list_alert_vulnerabilities', $datos);
    $alertDb->trans_complete(); // Finaliza la transacción



    if ($alertDb->trans_status() === FALSE) {
        // Si ocurrió un error en la transacción, se deshacen las consultas
        $alertDb->trans_rollback();


        return false;
    } else {
        // Si la transacción se completó correctamente, se confirman las consultas
        $alertDb->trans_commit();

        return true;
    }
}

function Delete_Alert_Plugins($whereConditions)
{
    $alertDb = GetCustomConect();
    $alertDb->where($whereConditions);
    $alertDb->delete(db_prefix() . 'list_alert_vulnerabilities_progress');
    if ($alertDb->affected_rows() > 0) {
        // log_activity('Vul - clients_web_sites delete_reminder Deleted [ID:' . $idCertifications . ']');

        return true;
    }

    return false;
}

function Save_Alert_Plugins($datos)
{
    $alertDb = GetCustomConect();
    $alertDb->trans_start(); // Inicia la transacción
    $alertDb->insert(db_prefix() . 'list_alert_vulnerabilities_progress', $datos);
    $alertDb->trans_complete(); // Finaliza la transacción



    if ($alertDb->trans_status() === FALSE) {
        // Si ocurrió un error en la transacción, se deshacen las consultas
        $alertDb->trans_rollback();


        return false;
    } else {
        // Si la transacción se completó correctamente, se confirman las consultas
        $alertDb->trans_commit();

        return true;
    }
}

function Run_Check_state_analisis()
{
    $CI = &get_instance();
    $CI->load->model('vulnerabilities/vulnerabilities_model');
    $CI->load->library(['vulnerabilities/Owasp_Zap', 'vulnerabilities/Container_Manager']);

    RunSpiderQueue();

    $CI->db->select('id');
    $CI->db->from(db_prefix() . 'vulnerabilities');
    $CI->db->where("state_spider = 1  OR state = 1"); //en procesos


    foreach ($CI->db->get()->result_array() as $value) {
        Check_state_analisis($value['id']);
    }

    Run_Frequency_Scan();
}

function GetCustomConect()
{
    $CI = &get_instance();
    return  $CI->db;
    // $config['hostname'] = $CI->db->hostname;
    // $config['username'] = $CI->db->username;
    // $config['password'] = $CI->db->password;
    // $config['database'] = 'ALERT_CONTROL';
    // $config['dbdriver'] = 'mysqli';
    // $config['dbprefix'] = $CI->db->dbprefix;
    // $config['pconnect'] = $CI->db->db_debug;
    // $config['db_debug'] = $CI->db->db_debug;
    // $config['cache_on'] = $CI->db->cache_on;
    // $config['cachedir'] = '';
    // $config['char_set'] = $CI->db->char_set;
    // $config['dbcollat'] = $CI->db->dbcollat;

    // return $CI->load->database($config, true);
}
