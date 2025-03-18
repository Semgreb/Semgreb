<?php
error_reporting(0);

defined('BASEPATH') or exit('No direct script access allowed');


class Vulnerabilities extends AdminController
{

    const SAVE_SCAN = 1;
    const REMOVE_SCAN = 2;
    const STOP_SCAN = 3;
    const STOP_ALL_SCAN = 4;
    const GENERATE_ALL_SCAN = 5;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('vulnerabilities_model');
        $this->load->model('clients_model');
    }

    /* List all announcements */
    public function index()
    {
        close_setup_menu();

        if (!has_permission('vulnerabilities', '', 'view')) {
            access_denied('Vulnerabilities');
        }


        // set_status_list_scan(['state' => 1]);

        if ($this->input->is_ajax_request()) {

            $this->load->library('ssp');

            $table = db_prefix() . 'vulnerabilities';
            $primaryKey = db_prefix() . 'vulnerabilities.id';

            $columns = array(
                array(
                    'db' => 'analisis_id', 'dt' => '', 'field' => 'analisis_id',
                ),
                array(
                    'db' => 'spider_analisis_id', 'dt' => '', 'field' => 'spider_analisis_id',
                ),
                array(
                    'db' => 'id', 'dt' => '0', 'field' => 'id',
                    'formatter' => function ($d, $row) {

                        $url   = admin_url("vulnerabilities/knowledge_base/") . $row['id'];
                        $content = '<a href="' . $url . '" class="valign">' . $d . '</a>';

                        return $content;
                        //sprintf("%03d", $d);
                    }
                ),
                array(
                    'db' => 'web_site', 'dt' => '1', 'field' => 'web_site',
                    'formatter' => function ($d, $row) {
                        return formatt_render_web_site($d, $row);
                    }
                ),
                array(
                    'db' => 'id_client', 'dt' => '2', 'field' => 'id_client',
                    'formatter' => function ($d, $row) {
                        if ($d > 0) {
                            $company = $this->vulnerabilities_model->getClientValues($d)[0]->company;

                            $link    = admin_url('clients/client/' . $d);

                            return "<a href='$link'>" . $company . "</a>";
                        } else {
                            return "";
                        }
                    }
                ),

                array('db' => 'date', 'dt' => '3', 'field' => 'date'),
                array('db' => 'warnings', 'dt' => '4', 'field' => 'warnings', 'formatter' => function ($d, $row) {
                    return $d . " " . strtolower(_l('table_warning_vulnerabilities'));
                    //sprintf("%03d", $d);
                }),
                array(
                    'db' => 'risk', 'dt' => '5', 'field' => 'risk',
                    'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_risk_scan() as $qualification) {
                            if ($qualification['status'] == strtoupper($d)) {
                                $status = get_risk_or_confidence_format($qualification);
                                break;
                            }
                        }
                        return $status;
                    }
                ),
                array(
                    'db' => 'state', 'dt' => '6', 'field' => 'state',
                    'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_status_scan() as $qualification) {
                            if ($qualification['status'] == $d) {
                                $status = get_status_s_format($qualification);
                                break;
                            }
                        }
                        return $status;
                    }
                ),
            );
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);

            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {
            Run_Frequency_Scan();
            $this->app_scripts->add('vulnerabilities-js', module_dir_url('vulnerabilities', 'assets/js/global.js'), 'admin', ['app-js']);
            $data = [];
            $this->load->view('index', $data);
        }
    }

    public function store($action = self::SAVE_SCAN)
    {
        $data = $this->input->post();
        $this->load->library(['Owasp_Zap', 'Container_Manager']);


        switch ($action) {

            case self::SAVE_SCAN:

                if (!has_permission('vulnerabilities', '', 'create')) {
                    access_denied('Vulnerabilities');
                }

                $id_cliente = $data['clientid'];
                //$name_client = $data['name_client'];
                $web_sites = $data['web_site'];
                //$no_vulnerability = $this->vulnerabilities_model->get_no_vulnerability();
                $data = new stdClass();
                $extraWhere = "(state=1 OR state_spider=1)";

                if (strpos($web_sites, ',') !== false) {
                    $urlArray = explode(',', $web_sites);
                    $listMsG = "";

                    foreach ($urlArray as $url) {
                        // $listMsG  .= 
                        $url = get_domain($url);
                        //Agregarlo a la cola de pendientes como pendiente
                        $listMsG .= Save_Queue_To_Run($id_cliente, $url);
                        //GenerateScanSpiderInit($url, $id_cliente,  $extraWhere);
                    }

                    set_alert('danger',   $listMsG);
                } else {

                    $listMsG = "";

                    $web_sites = get_domain($web_sites);
                    //Agregarlo a la cola de pendientes como pendiente
                    $listMsG =  Save_Queue_To_Run($id_cliente, $web_sites);
                    // $listMsG = GenerateScanSpiderInit($web_sites, $id_cliente,  $extraWhere);
                    set_alert('danger',   $listMsG);
                }

                RunSpiderQueue();

                break;

            case self::STOP_SCAN:
                $scanId = $data['idscan'];
                $scanSpiderId = $data['idspiderscan'];
                $whereConditions = [];
                $id_analyzes = $data['id_analyzes'];
                $whereConditions = ['id' => $id_analyzes];
                $isOk = "OK";
                $rsContainerRun = Get_Container_Vulnerabilities($id_analyzes);

                if (
                    $rsContainerRun != null
                ) {
                    if (Container_Manager::status_container($rsContainerRun->host_port)->status == null) {
                        Delete_Container_Vulnerabilities($id_analyzes);
                        return;
                    }

                    Owasp_Zap::setPort($rsContainerRun->host_port);

                    $cans = Owasp_Zap::scan_spider_stop($scanSpiderId);

                    if ($scanId > 0) {
                        $cans = Owasp_Zap::scan_stop($scanId);
                    }

                    $isOk = $cans->Result;

                    Container_Manager::delete_container($rsContainerRun->host_port);
                    //Eliminando el contenedor de la base de datos
                    Delete_Container_Vulnerabilities($id_analyzes);
                }

                if (strtoupper($isOk) === "OK") {
                    Delete_Queue_Vulnerabilities($id_analyzes);
                    $this->vulnerabilities_model->update_scan(['state_spider' => '2', 'state' => '2'], $whereConditions);
                }

                break;

            case self::STOP_ALL_SCAN:

                $id_cliente = isset($data['clientid']) && $data['clientid'] > 0 ? $data['clientid'] : 0;
                $rsOk = "";

                if ($id_cliente <= 0) {

                    $extraWhere = "(state=1 OR state_spider=1 OR state=4 OR state=5)";
                    $rs = $this->vulnerabilities_model->get_analisys_conditions($extraWhere);

                    foreach ($rs as $value) {
                        $rsContainerRun = Get_Container_Vulnerabilities($value['id']);

                        if (
                            $rsContainerRun != null
                        ) {
                            if (Container_Manager::status_container($rsContainerRun->host_port)->status == null) {
                                Delete_Container_Vulnerabilities($value['id']);
                                continue;
                            }

                            Owasp_Zap::setPort($rsContainerRun->host_port);
                            $cans = Owasp_Zap::scan_spider_stop_all();
                            $cans = Owasp_Zap::scan_stop_all();

                            if (isset($cans->Result)) {
                                $rsOk = strtoupper($cans->Result);
                            }


                            Container_Manager::delete_container($rsContainerRun->host_port);
                            //Eliminando el contenedor de la base de datos
                            Delete_Container_Vulnerabilities($value['id']);
                        }

                        Delete_Queue_Vulnerabilities($value['id']);
                        $this->vulnerabilities_model->update_scan(['state_spider' => '2', 'state' => '2'], ['id' => $value['id']]);
                    }
                } else {

                    $extraWhere = "(state=1 OR state_spider=1 OR state=4 OR state=5)";

                    if ($id_cliente > 0) {
                        $extraWhere .= " AND (id_client = $id_cliente)";
                    }
                    //clientid

                    $rs = $this->vulnerabilities_model->get_analisys_conditions($extraWhere);

                    foreach ($rs as $value) {

                        if ($id_cliente > 0) {
                            $rsContainerRun = Get_Container_Vulnerabilities($value['id']);
                            if (
                                $rsContainerRun != null
                            ) {

                                if (Container_Manager::status_container($rsContainerRun->host_port)->status == null) {
                                    Delete_Container_Vulnerabilities($value['id']);
                                    continue;
                                }

                                Owasp_Zap::setPort($rsContainerRun->host_port);

                                $cans = Owasp_Zap::scan_spider_stop($value['spider_analisis_id']);
                                $rsOk = strtoupper($cans->Result);

                                if ($value['analisis_id'] > 0) {
                                    $cans = Owasp_Zap::scan_stop($value['analisis_id']);
                                    $rsOk = strtoupper($cans->Result);
                                }

                                Container_Manager::delete_container($rsContainerRun->host_port);
                                //Eliminando el contenedor de la base de datos
                                Delete_Container_Vulnerabilities($value['id']);
                            }

                            Delete_Queue_Vulnerabilities($value['id']);
                            $this->vulnerabilities_model->update_scan(['state_spider' => '2', 'state' => '2'], ['id' => $value['id']]);
                        }
                    }
                }

                break;

            case self::REMOVE_SCAN:

                $scanId = $data['idscan'];
                $scanSpiderId = $data['idspiderscan'];
                $id_analyzes = $data['id_analyzes'];
                $whereConditions = [];
                $whereConditions = ['id' => $id_analyzes];

                $rsContainerRun = Get_Container_Vulnerabilities($id_analyzes);

                if (
                    $rsContainerRun != null
                ) {
                    if (Container_Manager::status_container($rsContainerRun->host_port)->status == null) {
                        Delete_Container_Vulnerabilities($id_analyzes);
                    } else {


                        Owasp_Zap::setPort($rsContainerRun->host_port);


                        $cans = Owasp_Zap::scan_spider_remove($scanSpiderId);


                        if ($scanId > 0) {
                            $cans = Owasp_Zap::scan_remove($scanId);
                        }


                        Delete_Queue_Vulnerabilities($id_analyzes);
                        $this->vulnerabilities_model->delete_scan($whereConditions);
                        Delete_Alert_Plugins("id_analyzes = $id_analyzes");

                        Container_Manager::delete_container($rsContainerRun->host_port);
                        //Eliminando el contenedor de la base de datos
                        Delete_Container_Vulnerabilities($id_analyzes);
                    }
                } else {
                    Delete_Queue_Vulnerabilities($id_analyzes);
                    $this->vulnerabilities_model->delete_scan($whereConditions);
                }
                break;

            case self::GENERATE_ALL_SCAN:

                $id_cliente = isset($data['clientid']) && $data['clientid'] > 0 ? $data['clientid'] : 0;
                $extraWhere = "(state_spider=3 OR state_spider=2)";

                if ($id_cliente > 0) {
                    $extraWhere .= " AND (id_client = $id_cliente)";
                }

                $rs = $this->vulnerabilities_model->get_analisys_conditions($extraWhere);
                $listMsG = "";
                foreach ($rs as $value) {
                    $listMsG  .= Save_Queue_To_Run($value['id_client'], $value['web_site']);
                }
                set_alert('danger',   $listMsG);

                RunSpiderQueue();

                break;
        }


        if ($this->input->get('client')) {
            $clientTab = $this->input->get('client');
            redirect(admin_url('clients/client/' . $clientTab . "?group=vulnerabilities"));
        } else {
            redirect(admin_url('vulnerabilities'));
        }
    }

    public function scan_progress($id_analisis)
    {
        if ($id_analisis > 0) {
            // $this->load->library('Owasp_Zap');
            // $cans = Owasp_Zap::scan_progress_status($id_analisis);

            $cans  = Get_Alert_Plugins($id_analisis, "*");
        }

        echo  json_encode($cans);
    }

    public function scan_specific_details()
    {
        $dataPost = $this->input->post();
        $where = [
            'id' => $dataPost['id_alert'],   'id_client' => $dataPost['id_client'],   'web_site' => $dataPost['target']
        ];
        $rs = Get_Alert_Conditions($where);
        //"id=" . $dataPost['id_alert'] . " AND id_client=" . $dataPost['id_client'] . " AND web_site='" . $dataPost['target'] . "'";
        $data['info'] = $rs[0];
        echo json_encode(['html' => $this->load->view('analysis_detail/content_details', $data, true)]);
    }

    public function knowledge_base($id)
    {
        if (!has_permission('vulnerabilities', '', 'view')) {
            access_denied('Vulnerabilities');
        }
        // set_status_list_scan(['state' => 1]);



        if (!$id) {
            redirect(admin_url('Vulnerabilities'));
        }

        $res = $this->vulnerabilities_model->get_analisys($id);

        if ($res  == null) {

            redirect(admin_url('Vulnerabilities'));
        }

        if ($this->input->is_ajax_request()) {
            $this->load->library('ssp');

            $table = db_prefix() . 'list_alert_vulnerabilities';
            $primaryKey = db_prefix() . 'list_alert_vulnerabilities.id_analyzes';

            $columns = array(
                array(
                    'db' => 'id_analyzes', 'dt' => '', 'field' => 'id_analyzes',
                ),
                array(
                    'db' => 'id_client', 'dt' => '', 'field' => 'id_client',
                ),
                array(
                    'db' => 'id', 'dt' => '0', 'field' => 'id',
                    'formatter' => function ($d, $row) {
                        return $d;
                        //sprintf("%03d", $d);
                    }
                ),
                array(
                    'db' => 'alert', 'dt' => '1', 'field' => 'alert',
                    'formatter' => function ($d, $row) {
                        //return $d;
                        return formatt_render_details_alert($d, $row);
                    }
                ),
                array(
                    'db' => 'web_site', 'dt' => '2', 'field' => 'web_site',
                    'formatter' => function ($d, $row) {
                        return $d;
                    }
                ),
                array(
                    'db' => 'risk', 'dt' => '3', 'field' => 'risk',
                    'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_risk_scan() as $qualification) {
                            if ($qualification['status'] == strtoupper($d)) {
                                $status = get_risk_or_confidence_format($qualification);
                                break;
                            }
                        }

                        return $status;
                    }
                ),
                array(
                    'db' => 'confidence', 'dt' => '4', 'field' => 'confidence',
                    'formatter' => function ($d, $row) {

                        $status = '';
                        foreach (get_trust_scan() as $qualification) {
                            if ($qualification['status'] == strtoupper($d)) {
                                $status = get_risk_or_confidence_format($qualification);
                                break;
                            }
                        }

                        return $status;
                    }
                )
            );
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' =>  $this->db->database,
                'host' => $this->db->hostname
            );

            $extraWhere =  ' id_analyzes = ' . $this->db->escape_str($id);

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, [], $extraWhere);

            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {

            $this->load->library(['Owasp_Zap', 'Container_Manager']);
            $extraWhere =  ' AND v.id = ' . $this->db->escape_str($id);
            RunningQueueAndContainer($extraWhere);
            $data = Check_state_analisis($id, true);
            $this->app_scripts->add('vulnerabilities-js', module_dir_url('vulnerabilities', 'assets/js/global.js'), 'admin', ['app-js']);
            $this->load->view('analysis_detail/index', $data);
        }
    }

    public function saveWebSites($id)
    {
        $web_sites = $_POST['web_sites'];
        $id_cliente = $id;

        $urlArray = [];

        $this->vulnerabilities_model->delete_websites($id_cliente);

        if (strpos($web_sites, ',') !== false) {
            $urlArray = explode(',', $web_sites);
            foreach ($urlArray as $url) {
                // if ($this->vulnerabilities_model->verify_url_exist($id_cliente, $url) == null) {
                $data =  [
                    'id_client' => $id_cliente,
                    'web_site' => $url,
                ];

                $this->vulnerabilities_model->save_WebSites($data);
                // }
            }
        } else {
            //if ($this->vulnerabilities_model->verify_url_exist($id_cliente, $web_sites) == null) {
            $data =  [
                'id_client' => $id_cliente,
                'web_site' => $web_sites,
            ];

            $this->vulnerabilities_model->save_WebSites($data);
            // }
        }

        redirect(admin_url('clients/client/' . $id . '?group=vulnerabilities&tab=settings2'));
    }

    public function details_analisys($id)
    {

        if ($this->input->is_ajax_request()) {

            $this->load->library('ssp');

            $table = db_prefix() . 'detalles_vulnerabilities';
            $primaryKey = db_prefix() . 'detalles_vulnerabilities.id';

            $columns = array(
                array(
                    'db' => 'id', 'dt' => '0', 'field' => 'id',
                    'formatter' => function ($d, $row) {
                        return sprintf("#%03d", $d);
                    }
                ),
                array('db' => 'mensaje', 'dt' => '1', 'field' => 'mensaje'),
                array('db' => 'etiqueta', 'dt' => '2', 'field' => 'etiqueta'),
                array(
                    'db' => 'tipo', 'dt' => '3', 'field' => 'tipo',
                    'formatter' => function ($d, $row) {
                        return '<span class="badge badge-pill badge-info">' . $d . '</span>';
                    }
                ),
            );

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $extraWhere = "id_vulnerability = $id";

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, null, $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {

            //set_status_list_scan(['state' => 1, 'analisis_id' => $id]);

            $data['informative_count'] = $this->vulnerabilities_model->get_informative_detail_count($id);
            $data['analysis'] = $this->vulnerabilities_model->get_analisys($id);

            $this->load->view('analysis_detail/index', $data);
        }
    }

    public function getClients()
    {
        $clients = array(
            array('id' => 1, 'name' => 'Jossy Emil Devers'),
            array('id' => 2, 'name' => 'Wilfredis Laureano Pichardo'),
            array('id' => 3, 'name' => 'Diego Antauro Mejia'),
            array('id' => 4, 'name' => 'Wender Robinson Batista')
        );

        return $clients;
    }


    public function getAnalises($id)
    {
        if ($this->input->is_ajax_request()) {

            $this->load->library('ssp');

            $table = db_prefix() . 'vulnerabilities';
            $primaryKey = db_prefix() . 'vulnerabilities.id';

            $columns = array(
                array(
                    'db' => 'analisis_id', 'dt' => '', 'field' => 'analisis_id',
                ),
                array(
                    'db' => 'spider_analisis_id', 'dt' => '', 'field' => 'spider_analisis_id',
                ),
                array(
                    'db' => 'id_client', 'dt' => '2', 'field' => 'id_client'
                ),
                array(
                    'db' => 'id', 'dt' => '0', 'field' => 'id',
                    'formatter' => function ($d, $row) {
                        $url   = admin_url("vulnerabilities/knowledge_base/") . $row['id'];
                        $content = '<a href="' . $url . '" class="valign">' . $d . '</a>';

                        return $content;
                        //sprintf("#%03d", $d);
                    }
                ),
                array(
                    'db' => 'web_site', 'dt' => '1', 'field' => 'web_site',
                    'formatter' => function ($d, $row) {
                        return formatt_render_web_site($d, $row);
                    }
                ),
                array('db' => 'date', 'dt' => '2', 'field' => 'date'),
                array('db' => 'warnings', 'dt' => '3', 'field' => 'warnings'),
                array(
                    'db' => 'risk', 'dt' => '4', 'field' => 'risk',
                    'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_risk_scan() as $qualification) {
                            if ($qualification['status'] == strtoupper($d)) {
                                $status = get_risk_or_confidence_format($qualification);
                                break;
                            }
                        }

                        return $status;
                    }
                ),
                array(
                    'db' => 'state', 'dt' => '5', 'field' => 'state',
                    'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_status_scan() as $qualification) {
                            if ($qualification['status'] == $d) {
                                $status = get_status_s_format($qualification);
                                break;
                            }
                        }
                        return $status;
                    }
                ),
            );
            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            //$data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);
            $extraWhere = "id_client = $id";
            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, null, $extraWhere);

            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        }
    }

    public function getClientWebSites()
    {
        $id = $_GET['id'];
        $webSites =  $this->vulnerabilities_model->getClientWebSites($id);
        $urls = [];
        if ($webSites != null) {
            foreach ($webSites as $value) {
                $urls[] = $value->web_site;
            }
            $urlString = implode(',', $urls);
            echo json_encode($urlString);
        } else {
            echo json_encode("");
        }
    }

    public function GetModule($module)
    {
        echo json_encode(get_scan_profile_modulo($module));
    }

    public function config_scan()
    {
        if (!has_permission('settings', '', 'view')) {
            access_denied('settings');
        }

        $this->load->library('form_validation');

        if ($this->input->post()) {

            if (!has_permission('settings', '', 'edit')) {
                access_denied('settings');
            }

            $this->form_validation->set_rules('number_container', _l('vulnerabilities_confi_scan_count_container'), 'required');

            if ($this->form_validation->run() !== false) {
                $data  = $this->input->post();
                // $data['message'] = html_purify($this->input->post('message', false));
                //     $id = $data['configid'];
                update_option('frequency', $data['frequency']);
                update_option('number_container', $data['number_container']);

                update_option('url_analisis', $data['url_analisis']);
                update_option('url_container', $data['url_container']);
                update_option('key_analisys', $data['key_analisys']);
                update_option('key_container', $data['key_container']);
                update_option('port_init', $data['port_init']);


                set_alert('success', _l('vulnerabilities_confi_save'));
                redirect(admin_url('vulnerabilities/config_scan'));
            } else {
                $error_string = $this->form_validation->error_string();
                set_alert('danger', $error_string);
            }
        }


        $rs = Get_Container_Config_Vulnerabilities();
        $datas['config_analyzes'] = [];

        if ($rs != null) {
            $datas['config_analyzes'] = $rs;
        }

        $this->load->view('config/add', $datas);
    }
}
