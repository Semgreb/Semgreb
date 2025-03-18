<?php
error_reporting(0);

defined('BASEPATH') or exit('No direct script access allowed');

class Audits extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('audits_model');
    }

    //audit
    public function manage()
    {
        if (!has_permission('audits', '', 'view')) {
            access_denied('Audits');
        }

        if ($this->input->is_ajax_request()) {

            $idClientes = $this->input->get('idClientes');

            $indexCustomer = 2;
            $indexCalificacion = 3;
            $indexState = 4;



            if ($idClientes > 0) {


                $indexCustomer = null;
                $indexCalificacion = 2;
                $indexState = 3;
            }


            $this->load->library('ssp');

            $table = db_prefix() . 'audits';
            $primaryKey = 'au.id';

            $columns = array(
                array('db' => 'au.id_customer', 'dt' => null, 'field' => 'id_customer'),
                array('db' => 'au.id', 'dt' => '0', 'field' => 'id', 'formatter' => function ($d, $row) {

                    $content =   $_data = '<a href="' . admin_url('trust_seal/audits/audit/' . $row['id']) . '">' . $d . '</a>';

                    //get_fields_id_audits($row, $this->input->get('idClientes'));

                    return $content;
                }),
                array('db' => 'cl.company', 'dt' => $indexCustomer, 'field' => 'cl.company', 'as' => 'company', 'formatter' => function ($d, $row) {

                    if (isset($row['id_customer']) && $row['id_customer'] > 0) {
                        return  '<a href="' . admin_url('clients/client/' . $row['id_customer']) . '" class="mbot10 display-block">' .
                            $row['company'] . '</a>';
                    } else {
                        return "";
                    }
                }),

                array(
                    'db' => 'sl.title', 'dt' => '1', 'field' => 'sl.title', 'as' => 'title',
                    'formatter' => function ($d, $row) {
                        $content =  get_fields_id_audits($row, $this->input->get('idClientes'), $row['title']);

                        return $content;
                    }
                ),
                array(
                    'db' => 'au.qualification', 'dt' => $indexCalificacion, 'field' => 'au.qualification', 'formatter' => function ($d, $row) {

                        $status = '';

                        if (isset($row['status']) && $row['status'] > 0) {
                            foreach (get_qualification_audits() as $qualification) {
                                if ($qualification['qualification'] == $row['qualification']) {
                                    $status = get_qualification_format($qualification);
                                    break;
                                }
                            }
                        }
                        return $status;
                    }
                ),
                array(
                    'db' => 'au.status', 'dt' => $indexState, 'field' => 'au.status', 'formatter' => function ($d, $row) {

                        $status = '';

                        if (isset($row['status']) && $row['status'] > 0) {
                            foreach (get_status_audits() as $qualification) {
                                if ($qualification['status'] == $row['status']) {
                                    $status = get_status_audits_format($qualification);
                                    break;
                                }
                            }
                        }
                        return $status;
                    }
                )

            );

            $data = $_POST;

            $data = force_filter($data);

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $joinQuery = "FROM " . db_prefix() . "audits au
            LEFT JOIN " . db_prefix() . "seals sl ON ( sl.id = au.id_seal )
             LEFT JOIN " . db_prefix() . "clients cl ON ( cl.userid = au.id_customer )";

            $extraWhere = "";

            if ($idClientes > 0) {
                $extraWhere =  ' id_customer = ' . $this->db->escape_str($idClientes);
            }

            $data = SSP::simple($data, $sql_details, $table, $primaryKey, $columns,   $joinQuery, $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {



            $data['audits'] = $this->audits_model->get_all_audits();
            $data['title'] = _l('all_audits');

            $this->load->view('trust_seal/audits/manage', $data);
        }
    }

    /* Add new role or edit existing one */
    public function audit($id = 0, $client_selected = 0)
    {
        if ($this->input->post()) {

            $datos = $this->input->post();

            $datos['auto_asignar'] = isset($datos['auto_asignar']) ? 1 : 0;
            $datos['notification'] = isset($datos['notification']) ? 1 : 0;
            $datos['reminder'] = isset($datos['reminder']) ? 1 : 0;

            if ($id == 0) {
                if (!has_permission('audits', '', 'create')) {
                    access_denied('Audits');
                }

                $id = $this->audits_model->add($datos);
                if ($id) {
                    set_alert('success', _l('created_audits_successfully', _l('audit')));
                    redirect(admin_url('trust_seal/audits/audit/' . $id));
                }
            } else {

                if (!has_permission('audits', '', 'edit')) {
                    access_denied('Audits');
                }

                $success = $this->audits_model->update($datos, $id);

                if ($success) {
                    redirect_auto_certificacitions($datos);
                    set_alert('success', _l('updated_audits_successfully', _l('audit')));
                }
                redirect(admin_url('trust_seal/audits/audit/' . $id));
            }
        }

        $data['exist_audit'] = false;

        if ($id == 0) {
            $title = _l('add_new', _l('audit_lowercase'));
        } else {

            $this->load->model('seals_model');
            $this->load->model('exams_model');

            //audit
            $data['status'] = $this->audits_model->status();
            $data['qualification'] = $this->audits_model->qualifications();
            $audit               = $this->audits_model->get($id);
            $data['audit']       = $audit;
            //seal
            $rs = $this->seals_model->get($audit->id_seal);
            $id_exam = $rs != null ? $rs->exams : 0;

            //exams
            $data['exam'] = $this->exams_model->get($id_exam);
            $sections = $this->exams_model->get_sections($id_exam);
            foreach ($sections as $section) {

                $quizs = $this->exams_model->get_quizs($section['id']);

                $new_array_quizs = array();

                foreach ($quizs as $quiz) {
                    $approved = $this->audits_model->validate_audit_exam($audit->id, $audit->id_customer, $quiz['id']);
                    $quiz["approved"] = $approved;
                    array_push($new_array_quizs, $quiz);
                }

                array_push($section, ["quizs" => $new_array_quizs]);
                $data['sections'][] = $section;
            }

            $data['exist_audit'] = $this->audits_model->if_exist_certifications_with_this_seal_and_client($audit->id_seal, $audit->id_customer);

            $title = _l('edit', _l('audit_lowercase')) . ' ' . (int)$audit->id;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['client_selected'] = $client_selected;
        $data['customers'] = $this->audits_model->get_all_customers_for_select();
        $data['seals'] = $this->audits_model->get_all_seals_for_select();

        $this->load->view('audits/audit', $data);
    }

    /* Delete role from database */
    public function delete($id)
    {
        if (!has_permission('audits', '', 'delete')) {
            access_denied('Audits');
        }

        if (!$id) {
            redirect(admin_url('trust_seal/audits/manage'));
        }
        $response = $this->audits_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('audit_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted_audit', _l('audit')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('audit_lowercase')));
        }
        redirect(admin_url('trust_seal/audits/manage'));
    }

    public function add_audit_exam()
    {
        if (!has_permission('exams', '', 'create')) {
            access_denied('Audits');
        }

        if ($this->input->post()) {
            $id = $this->audits_model->add_audit_exam($this->input->post());
        }
    }

    public function add_comment($id)
    {
        if ($this->input->post()) {
            $idAudit = $id;
            $id = $this->audits_model->add_comment($this->input->post());
            set_alert('success', _l('created_audits_successfully', _l('trust_comments_firtsupper')));
            redirect(admin_url('trust_seal/audits/view_audits/' . $idAudit));
        }
    }

    public function get_comment($id_audit, $id_question)
    {
        if ($_GET) {

            $id_audit = (int) $id_audit;

            $tempArray = [];

            foreach ($this->audits_model->get_comment($id_audit, $id_question) as $value) {

                $value['name_staff'] = get_staff_full_name($value['contactid']);
                $value['profileComments'] = '<a href="javascript:void(0);" data-toggle="tooltip" title="' .  $value['name_staff'] . '" class="pull-left mright5">' . staff_profile_image($value['contactid'], [
                    'staff-profile-image-trust',
                ]) . '</a>';

                $tempArray[] = $this->load->view('audits/commentProfile',  $value, TRUE);
            }


            echo json_encode($tempArray);
        }
    }



    public function audit_t($id = '')
    {
        if ($id == '') {
            $title = _l('add_new', _l('audit_lowercase'));
        } else {

            $this->load->model('seals_model');
            $this->load->model('exams_model');

            //audit
            $data['status'] = $this->audits_model->status();
            $data['qualification'] = $this->audits_model->qualifications();
            $audit               = $this->audits_model->get($id);
            $data['audit']       = $audit;
            //seal
            $id_exam = $this->seals_model->get($audit->id_seal)->exams;

            //exams
            $data['exam'] = $this->exams_model->get($id_exam);
            $sections = $this->exams_model->get_sections($id_exam);
            foreach ($sections as $section) {

                $quizs = $this->exams_model->get_quizs($section['id']);

                $new_array_quizs = array();

                foreach ($quizs as $quiz) {
                    $approved = $this->audits_model->validate_audit_exam($audit->id, $audit->id_customer, $quiz['id']);
                    $quiz["approved"] = $approved;
                    array_push($new_array_quizs, $quiz);
                }

                array_push($section, ["quizs" => $new_array_quizs]);
                $data['sections'][] = $section;
            }

            $title = _l('edit', _l('audit_lowercase')) . ' ' . $audit->id;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['customers'] = $this->audits_model->get_all_customers_for_select();
        $data['seals'] = $this->audits_model->get_all_seals_for_select();
        $this->load->view('audits/email/audit_email', $data);
    }

    public function if_exist_certifications_with_this_seal_and_client($id_seal, $id_customer)
    {
        $data['exist_audit'] = $this->audits_model->if_exist_certifications_with_this_seal_and_client($id_seal, $id_customer);
        echo json_encode($data);
    }

    public function table_exams_group($id_seal, $id_audit)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->library('ssp');
            $this->load->model('exams_model');
            $this->load->model('seals_model');

            $table = db_prefix() . 'exams';
            $primaryKey = 'ex.id';

            $columns = array(
                array('db' => 'ex.id', 'dt' => '0', 'field' => 'id_exam',  'as' => 'id_exam', 'formatter' => function ($d, $row) {

                    $content = '<a href="' . admin_url('trust_seal/exams/view_exam/' . $row['id_exam']) . '">' . $d . '</a>';
                    return $content;
                }),
                array('db' => 'ex.id', 'dt' => '1', 'field' => 'id_exam1', 'formatter' => function ($d, $row) {
                    $content =  '<a href="' . admin_url('trust_seal/audits/view_audits/' . $row['id_audit']) . '">' . $row['name'] . '</a>';
                    return $content;
                }),
                array('db' => 'name', 'dt' => '2', 'field' => 'name', 'formatter' => function ($d, $row) {
                    
                    $result = $this->exams_model->get_exams_details_groups($row['id_audit'], $row['id_customer'], $row['id_exam'], $row['id_seal'])[0];
                    $total = $this->exams_model->get_count_quiz($row['id_seal'],$row['id_exam']);
                    $percentage = (int)(($result['COUNT_APPROBED']/$total)*100);
                    $percentage = $percentage > 100 ? 100 : $percentage;
                    return get_progress_bar($percentage);
                }),
                array(
                    'db' => 'ex.id', 'dt' => '3', 'field' => 'id_exam2',
                    'formatter' => function ($d, $row) {

                        $result = $this->exams_model->get_exams_details_groups($row['id_audit'], $row['id_customer'], $row['id_exam'], $row['id_seal'])[0];
                        $total = $this->exams_model->get_count_quiz($row['id_seal'],$row['id_exam']);
                        $completado = ($result['COUNT_APPROBED'] + $result['COUNT_FAILURE']);
                        $completado = $completado <= $total ? $completado : $total;
                        $percentage = (($completado/$total)*100);
                        return get_progress_bar((int)$percentage);

                    }
                ),
                array('db' => 'sm.id_seal', 'dt' => null, 'field' => 'id_seal')
                , array('db' => 'aud.id_customer', 'dt' => null, 'field' => 'id_customer')
                , array('db' => 'aud.id', 'dt' => null, 'as' => 'id_audit', 'field' => 'id_audit')
            );

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $extraWhere = "";

            $joinQuery = "FROM " . db_prefix() . "exams ex
            JOIN " . db_prefix() . "seal_exams sm ON ( sm.id_exams = ex.id )"
            ."JOIN " . db_prefix() . "audits aud ON ( sm.id_seal = aud.id_seal AND aud.id = $id_audit )";

            $extraWhere =  "sm.id_seal = $id_seal";

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery,  $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        }
    }

    public function view_audits($id = 0, $client_selected = 0)
    {
        $data['exist_audit'] = true;

        if ($id == 0) {
            $title = _l('add_new', _l('audit_lowercase'));
        } else {

            $this->load->model('seals_model');
            $this->load->model('exams_model');
            //audit
            $data['status'] = $this->audits_model->status();
            $data['qualification'] = $this->audits_model->qualifications();
            $audit               = $this->audits_model->get($id);
            $data['audit']       = $audit;

            $exams_groups = $this->seals_model->get_seal_exams_groups($audit->id_seal);  

            if (isset($exams_groups)) {
                foreach ($exams_groups as $group) {
                    //exams
                    $data['exams_groups'][] = $this->exams_model->get($group['id_exams']);
                    
                    $sections = $this->exams_model->get_sections($group['id_exams']);
                    foreach ($sections as $section) {

                        $quizs = $this->exams_model->get_quizs($section['id']);

                        $new_array_quizs = array();

                        foreach ($quizs as $quiz) {
                            $approved = $this->audits_model->validate_audit_exam($audit->id, $audit->id_customer, $quiz['id']);
                            $quiz["approved"] = $approved;
                            array_push($new_array_quizs, $quiz);
                        }

                        array_push($section, ["quizs" => $new_array_quizs]);
                        $data['sections'][$group['id_exams']][] = $section;
                    }
                }
            }

            $data['exist_audit'] = $this->audits_model->if_exist_certifications_with_this_seal_and_client($audit->id_seal, $audit->id_customer);
            $current_seal = $this->seals_model->get($audit->id_seal);
            $title = _l('edit', _l('audit_lowercase')) . ', ' . $current_seal->title;
        }

        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['client_selected'] = $client_selected;
        $data['customers'] = $this->audits_model->get_all_customers_for_select();
        $data['seals'] = $this->audits_model->get_all_seals_for_select();
        $data['current_seal'] = $current_seal;

        $this->load->view('audits/view_audits', $data);
    }
}
