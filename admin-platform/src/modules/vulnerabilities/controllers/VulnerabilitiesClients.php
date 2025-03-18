<?php

use PhpOffice\PhpSpreadsheet\Chart\Layout;

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\projects\Gantt;
use app\services\ValidatesContact;

class VulnerabilitiesClients extends ClientsController
{
    /**
     * @since  2.3.3
     */
    use ValidatesContact;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('vulnerabilities_model');
        $this->load->model('clients_model');
        $this->load->library('Owasp_Zap');
    }


    /* List all announcements */
    public function index()
    {


        if (!has_contact_permission('vulnerabilities')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("vulnerabilities/VulnerabilitiesClients"));
        }

        $client_user_id = get_client_user_id();

        if ($this->input->is_ajax_request()) {

            $extraWhere = "";

            $this->load->library('ssp');

            $table = db_prefix() . 'vulnerabilities';
            $primaryKey = db_prefix() . 'vulnerabilities.id';

            $columns = array(
                array(
                    'db' => 'id',
                    'dt' => '0',
                    'field' => 'id',
                    'formatter' => function ($d, $row) {
                        $url   = "vulnerabilitiesClients/knowledge_base/" . $row['id'];
                        $content = '<a href="' . $url . '" class="valign">' . $d . '</a>';

                        return $content;
                    }
                ),
                array(
                    'db' => 'web_site',
                    'dt' => '1',
                    'field' => 'web_site',
                    'formatter' => function ($d, $row) {
                        $url   = "vulnerabilitiesClients/knowledge_base/" . $row['id'];
                        $content = '<a href="' . $url . '" class="valign">' . $d . '</a>';
                        return $content;
                    }
                ),
                array('db' => 'date', 'dt' => '2', 'field' => 'date'),
                //array('db' => 'vulnerability', 'dt' => '3', 'field' => 'vulnerability'),
                array('db' => 'warnings', 'dt' => '3', 'field' => 'warnings', 'formatter' => function ($d, $row) {
                    return $d . " " . strtolower(_l('table_warning_vulnerabilities'));
                    //sprintf("%03d", $d);
                }),
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
                    'db' => 'state',
                    'dt' => '5',
                    'field' => 'state',
                    'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_status_scan() as $qualification) {
                            if ($qualification['status'] == $d) {
                                $status = get_status_audits_format($qualification);
                                break;
                            }
                        }
                        return $status;
                    }
                ),
            );

            $extraWhere = " id_client = $client_user_id";

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, "", $extraWhere);

            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {


            $data['client_user_id'] = $client_user_id;

            //$this->getClients(get_client_user_id());
            $data['analysis_finalized'] = $this->vulnerabilities_model->get_Analysis_finalized($client_user_id);
            $data['analysis_in_process'] = $this->vulnerabilities_model->get_Analysis_in_process($client_user_id);
            $data['analysis_canceled'] = $this->vulnerabilities_model->get_Analysis_canceled($client_user_id);

            $this->data($data);
            $this->view('clients/index', $data);
            $this->layout();
        }
    }

    public function knowledge_base($id)
    {
        if (!has_contact_permission('vulnerabilities')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("vulnerabilities/VulnerabilitiesClients"));
        }

        if (!$id) {
            redirect(site_url('vulnerabilities/VulnerabilitiesClients'));
        }

        $res = $this->vulnerabilities_model->get_analisys($id);

        if ($res  == null) {
            redirect(site_url('vulnerabilities/VulnerabilitiesClients'));
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
            // $this->app_scripts->add('vulnerabilities-js', module_dir_url('vulnerabilities', 'assets/js/global.js'), 'admin', ['app-js']);
            $this->data($data);
            $this->view('clients/analysis_detail/index', $data);
            $this->layout();
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

        exit;
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

        $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode(['html' => $this->load->view('analysis_detail/content_details', $data, true)]));
    }
}
