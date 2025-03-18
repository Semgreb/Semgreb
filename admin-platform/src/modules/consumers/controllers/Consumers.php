<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Consumers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('consumers_model');
    }

    public function index($status = '', $userid = '')
    {
        close_setup_menu();

        if (!has_permission('consumers', '', 'view')) {
            access_denied('Consumers');
        }

        if ($this->input->is_ajax_request()) {

            $this->load->library('ssp');

            $table = db_prefix() . 'consumers';
            $primaryKey = db_prefix() . 'consumers.consumerid';

            $columns = array(
                array('db' => 'lastname', 'dt' => null, 'field' => 'lastname'),
                array(
                    'db' => 'consumerid', 'dt' => '0', 'field' => 'consumerid'
                ),

                array('db' => 'firstname', 'dt' => '1', 'field' => 'firstname', 'formatter' => function ($d, $row) {

                    $url   = admin_url('consumers/add/' . $row['consumerid']);

                    $content = '<a href="' . $url . '" class="valign">' . $d . " " . $row['lastname'] . '</a>';

                    $content .= '<div class="row-options">';

                    $content .= '<a href="' . $url . '">' . _l('view') . '</a>';

                    // if (has_permission('consumers', '', 'edit')) {
                    //     $content .= ' | <a href="' . $url . '?tab=settings">' . _l('edit') . '</a>';
                    // }

                    if (has_permission('consumers', '', 'delete')) {
                        $content .= ' | <a href="' . admin_url('consumers/delete/' . $row['consumerid']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                    }

                    $content .= '</div>';

                    return $content;
                }),


                // array('db' => 'lastname', 'field' => 'lastname'),

                array('db' => 'birthday_date', 'dt' => '2', 'field' => 'birthday_date'),

                array('db' => 'email', 'dt' => '3', 'field' => 'email'),

                array('db' => 'phonenumber', 'dt' => '4', 'field' => 'phonenumber'),

                array('db' => 'datecreated', 'dt' => '5', 'field' => 'datecreated', 'formatter' => function ($d, $row) {
                    return  substr($d, 0, 10);
                }),

                array('db' => 'dateupdate', 'dt' => '6', 'field' => 'dateupdate'),

            );

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns);

            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
            return;
        }

        $data[""] = "";
        $this->load->view('list', $data);
    }

    public function add($id = 0)
    {
        $this->load->library('form_validation');

        if ($this->input->post()) {


            $this->form_validation->set_rules('document', _l('consumer_open_complaint_id'), 'required');
            $this->form_validation->set_rules('firstname', _l('consumer_open_complaint_firstname'), 'required|max_length[60]');
            $this->form_validation->set_rules('lastname', _l('consumer_open_complaint_lastname'), 'required|max_length[60]');
            $this->form_validation->set_rules('birthday_date', _l('consumer_open_complaint_birthday_date'), 'required');
            $this->form_validation->set_rules('phonenumber', _l('consumer_open_complaint_phonenumber'), 'required|integer|max_length[10]');
            $this->form_validation->set_rules('email', _l('consumer_open_complaint_email'), 'required');

            if ($this->form_validation->run() !== false) {
                $data  = $this->input->post();
                // $data['message'] = html_purify($this->input->post('message', false));

                if ($data['consumerid'] <= 0) {

                    if (!has_permission('consumers', '', 'create')) {
                        access_denied('Consumers');
                    }

                    $id  = $this->consumers_model->add([
                        "document" => $data['document'], "firstname" => $data['firstname'], "lastname" => $data['lastname'], "birthday_date" => $data['birthday_date'], "email" => $data['email'], "phonenumber" => $data['phonenumber']
                    ]);

                    if ($id) {

                        set_alert('success', _l('new_consumer_added_successfully'));
                        redirect(admin_url('consumers/add/' . $id));
                    }
                } else {


                    if (!has_permission('consumers', '', 'edit')) {
                        access_denied('Consumers');
                    }

                    $this->consumers_model->update_consumer([
                        "document" => $data['document'], "firstname" => $data['firstname'], "lastname" => $data['lastname'], "birthday_date" => $data['birthday_date'], "email" => $data['email'], "phonenumber" => $data['phonenumber']
                    ], $id);

                    if ($id) {
                        set_alert('success', _l('updated_consumer_added_successfully'));
                        redirect(admin_url('consumers/add/' . $id));
                    }
                }
            } else {
                $error_string = $this->form_validation->error_string();
                set_alert('danger', $error_string);
            }
        }

        $data["consumer"] = [];

        if ($id > 0) {

            $data["consumer"] = $this->consumers_model->get($id);
        }

        $this->load->view('consumers/add', $data);
    }

    public function get_client()
    {

        $consumer =  get_relation_data_complaint_module('customer');

        if ($this->input->post('rel_id')) {
            $rel_id = $this->input->post('rel_id');
        } else {
            $rel_id = '';
        }

        $relOptions = init_relation_options_complaint($consumer, 'customer', $rel_id);
        echo json_encode($relOptions);
    }

    public function delete($complaintid)
    {
        if (!has_permission('consumers', '', 'delete')) {
            access_denied('Consumers');
        }

        if (!$complaintid) {
            redirect(admin_url('consumers'));
        }

        $response = $this->consumers_model->delete($complaintid);

        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced_c', $complaintid));
        } else if ($response == true) {
            set_alert('success', _l('deleted', _l('consumer')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('consumer')));
        }

        if (strpos($_SERVER['HTTP_REFERER'], 'consumers/index') !== false) {
            redirect(admin_url('index'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
}
