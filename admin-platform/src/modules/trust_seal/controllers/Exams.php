<?php
error_reporting(0);

defined('BASEPATH') or exit('No direct script access allowed');

class Exams extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('exams_model');
    }

    //Exams
    public function manage_exams()
    {
        if (!has_permission('exams', '', 'view')) {
            access_denied('Exams');
        }


        if ($this->input->is_ajax_request()) {
            //  $this->app->get_table_data(module_views_path('trust_seal', 'exams/table'));
            // die();
            $this->load->library('ssp');

            $table = db_prefix() . 'exams';
            $primaryKey = 'id';

            $columns = array(
                array('db' => 'id', 'dt' => '0', 'field' => 'id',  'formatter' => function ($d, $row) {

                    $content = '<a href="' . admin_url('trust_seal/exams/view_exam/' . $row['id']) . '">' . $d . '</a>';

                    return $content;
                }),
                array('db' => 'id', 'dt' => '1', 'field' => 'id', 'formatter' => function ($d, $row) {
                    $content =  get_fields_id_exams($row);
                    return $content;
                }),
                array('db' => 'name', 'dt' => '2', 'field' => 'name', 'formatter' => function ($d, $row) {
                    return $this->exams_model->get_sections_from_exam($row['id']);
                }),
                array(
                    'db' => 'id', 'dt' => '3', 'field' => 'id',
                    'formatter' => function ($d, $row) {
                        return  $this->exams_model->get_quizs_from_exam($row['id']);
                    }
                ),

                array(
                    'db' => 'status', 'dt' => '4', 'field' => 'status', 'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_status_exams() as $qualification) {
                            if ($qualification['status'] == $row['status']) {
                                $status = get_status_audits_format($qualification);
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
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );


            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {
            $data['exams'] = $this->exams_model->get_all_exams();
            $data['title'] = _l('all_exams');

            $this->load->view('trust_seal/exams/manage', $data);
        }
    }

    /* Add new role or edit existing one */
    public function exam($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('exams', '', 'create')) {
                    access_denied('Exams');
                }
                $data = $this->input->post();
                $data['status'] = 2;
                $id = $this->exams_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('exam')));
                    redirect(admin_url('trust_seal/exams/view_exam/' . $id . '?tab=section'));
                }
            } else {
                if (!has_permission('exams', '', 'edit')) {
                    access_denied('Exams');
                }
                $success = $this->roles_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('role')));
                }
                redirect(admin_url('trust_seal/exams/manage_exams'));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('exam_lowercase'));
        } else {
            $section               = $this->exams_model->get($id);
            $data['section']       = $section;
            $data['lessions']      = $this->exams_model->get_lessions($id);
            $title              = _l('edit', _l('section_lowercase')) . ' ' . $section->name;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $this->load->view('trust_seal/exams/exam', $data);
    }

    /* View */
    public function view_exam($id = '')
    {
        if ($this->input->post()) {

            if (!has_permission('exams', '', 'edit')) {
                access_denied('Exams');
            }


            $sections = $this->input->post()['sections'];
            $permitirModificar = true;
            //$section['section'][0]

            if (
                isset($this->input->post()['sections_update']) == true
                ||  isset($this->input->post()['sections']) == true
            ) {
                if (isset($this->input->post()['sections_update']) == true) {
                    $sections = $this->input->post()['sections_update'];
                }

                $permitirModificar = false;

                foreach ($sections as $section) {
                    if (isset($section['question']) == true && count($section['question']) > 0) {
                        $permitirModificar = true;
                        break;
                    }
                }
            }

            if ($permitirModificar) {

                if ($this->input->post()['update_exam'] == "1") {

                    $data['name'] = $this->input->post()['name'];
                    $data['status'] = $this->input->post()['status'];
                    $data['description'] = $this->input->post()['description'];

                    $exam_id = $this->exams_model->update_exam($data, $id);
                    set_alert('success', _l('updated_successfully', _l('exam')));
                    redirect(admin_url('trust_seal/exams/view_exam/' . $id . '?tab=detail'));
                } else if ($this->input->post()['update_exam'] == "0") {

                    if (isset($this->input->post()['sections_update']) == true) {

                        $section_update = $this->input->post()['sections_update'];

                        foreach ($section_update as $sectionUpdate) {

                            $section_data['exam_id'] = $sectionUpdate['section'][0];

                            foreach ($sectionUpdate['question'] as $quizUpdate) {
                                $quiz_data['section_id'] = $sectionUpdate['section'][0];
                                $quiz_data['name'] = $quizUpdate;
                                $this->exams_model->add_quiz($quiz_data);
                            }
                        }

                        set_alert('success', _l('updated_audits_successfully', _l('section')));
                    }

                    if (isset($this->input->post()['sections']) == true) {
                        $sections = $this->input->post()['sections'];
                        $i = 0;
                        foreach ($sections as $section) {
                            $i++;

                            if ($section['section'][0] != '') {
                                // var_dump($section['section'][0]);
                                // exit;
                                $section_data['exam_id'] = $id;
                                $section_data['name'] = $section['section'][0];
                                $section_id = $this->exams_model->add_section($section_data);

                                if (isset($section['question']) == true && count($section['question']) > 0) {

                                    foreach ($section['question'] as $quiz) {
                                        $quiz_data['section_id'] = $section_id;
                                        $quiz_data['name'] = $quiz[0];
                                        $this->exams_model->add_quiz($quiz_data);
                                    }
                                }
                            }
                        }

                        set_alert('success', _l('created_audits_successfully', _l('section')));
                    }

                    redirect(admin_url('trust_seal/exams/view_exam/' . $id . '?tab=section'));
                }
            } else {
                set_alert('warning', _l('seals_secction_error'));
                redirect(admin_url('trust_seal/exams/view_exam/' . $id . '?tab=section'));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('exam_lowercase'));
        } else {
            $data['status'] = $this->exams_model->status();
            $data['exam'] = $this->exams_model->get($id);
            $sections = $this->exams_model->get_sections($id);
            foreach ($sections as $section) {
                array_push($section, ["quizs" => $this->exams_model->get_quizs($section['id'])]);
                $data['sections'][] = $section;
            }

            $title =  (int)$data['exam']->id . ' ' .  $data['exam']->name;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $this->load->view('trust_seal/exams/view', $data);
    }

    /* Add new role or edit existing one */
    public function add_section($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {

                if (!has_permission('exams', '', 'create')) {
                    access_denied('Exams');
                }

                $id = $this->exams_model->add_lession($this->input->post());
                if ($id) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } else {
                if (!has_permission('exams', '', 'edit')) {
                    access_denied('Exams');
                }
                $success = $this->roles_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('role')));
                }
                redirect(admin_url('exams/exam/' . $id));
            }
        }
    }

    public function update_section($id)
    {
        if (!has_permission('exams', '', 'edit')) {
            access_denied('Exams');
        }

        $data['name'] = $this->input->post()['name'];
        $exam_id = $this->exams_model->update_section($data, $id);
    }

    /* Delete quiz from database */
    public function delete_section($id)
    {
        if (!has_permission('exams', '', 'delete')) {
            access_denied('Exams');
        }
        // if (!$id) {
        //     redirect(admin_url('trust_seal/manage_exams'));
        // }
        $response = $this->exams_model->delete_section($id);
        if ($response == true) {
            echo _l('deleted');
        } else {
            echo _l('problem_deleting');
        }
    }

    public function update_quiz($id)
    {
        if (!has_permission('exams', '', 'edit')) {
            access_denied('Exams');
        }

        $data['name'] = $this->input->post()['name'];
        $exam_id = $this->exams_model->update_quiz($data, $id);
    }

    /* Delete quiz from database */
    public function delete_quiz($id)
    {
        if (!has_permission('exams', '', 'delete')) {
            access_denied('Exams');
        }
        // if (!$id) {
        //     redirect(admin_url('trust_seal/manage_exams'));
        // }
        $response = $this->exams_model->delete_quiz($id);
        if ($response == true) {
            echo _l('deleted') . ' ' . _l('seals_quiz');
        } else {
            echo _l('problem_deleting');
        }
    }

    /* Delete role from database */
    public function delete_exam($id)
    {

        if (!has_permission('exams', '', 'delete')) {
            access_denied('exams');
        }
        if (!$id) {
            redirect(admin_url('trust_seal/exams/manage_exams'));
        }
        $response = $this->exams_model->delete_exam($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('exam_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted_exams', _l('exam')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('exam_lowercase')));
        }
        redirect(admin_url('trust_seal/exams/manage_exams'));
    }
}
