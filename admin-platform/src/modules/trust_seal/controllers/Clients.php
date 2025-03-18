<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\projects\Gantt;
use app\services\ValidatesContact;

class Clients extends ClientsController
{
    /**
     * @since 2.3.3
     */
    use ValidatesContact;

    public function __construct()
    {
        parent::__construct();
    }

    public function certifications($status = '')
    {

        if (!has_contact_permission('certifications')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("knowledge-base"));
        }

        $this->load->model('certifications_model');
        $this->load->model('seals_model');



        $where = 'cf.id_customer=' . get_client_user_id();
        // if (!can_logged_in_contact_view_all_tickets()) {
        //     $where .= ' AND ' . db_prefix() . 'tickets.contactid=' . get_contact_user_id();
        // }

        $data['show_submitter_on_table'] = show_ticket_submitter_on_clients_area_table();

        $defaultStatuses = [1, 2];
        //hooks()->apply_filters('customers_area_list_default_ticket_statuses', [1, 2]);
        // By default only open tickets




        if (!is_numeric($status)) {
            $where .= ' AND cf.status IN (' . implode(', ', $defaultStatuses) . ')';
        }

        $data['list_statuses'] = [4, 5];

        //$vars['ticket_statuses'] = $this->ci->tickets_model->get_ticket_status();
        set_certifications_expired(get_client_user_id());

        $data['bodyclass']     = 'tickets';
        $data['certifications']       = $this->certifications_model->getJoin('', $where);
        $data['title']         = _l('all_certifications');
        $data['sealsList'] = $this->seals_model->get('', ['status' => 1]);
        $data['clientId'] =  get_client_user_id();
        $this->data($data);
        $this->view('trust_seal/certifications/clients/certifications');
        $this->layout();
    }

    public function audits($status = '')
    {
        if (!has_contact_permission('certifications')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("knowledge-base"));
        }

        $this->load->model('audits_model');

        $where = db_prefix() . 'audits.id_customer=' . get_client_user_id();
        // if (!can_logged_in_contact_view_all_tickets()) {
        //     $where .= ' AND ' . db_prefix() . 'tickets.contactid=' . get_contact_user_id();
        // }

        $data['show_submitter_on_table'] = show_ticket_submitter_on_clients_area_table();

        $defaultStatuses = hooks()->apply_filters('customers_area_list_default_ticket_statuses', [1, 2]);
        // By default only open tickets




        if (!is_numeric($status)) {
            $where .= ' AND status IN (' . implode(', ', $defaultStatuses) . ')';
        } else {
            $where .= ' AND status=' . $this->db->escape_str($status);
        }

        $data['list_statuses'] = is_numeric($status) ? [$status] : $defaultStatuses;



        //$vars['ticket_statuses'] = $this->ci->tickets_model->get_ticket_status();

        $data['bodyclass']     = 'tickets';
        $data['audits']       = $this->audits_model->get('', $where);
        $data['title']         = _l('audits_summary');
        $data['clientId'] =  get_client_user_id();
        $this->data($data);
        $this->view('trust_seal/audits/clients/audits');
        $this->layout();
    }

    public function seals($status = '')
    {
        if (!has_contact_permission('certifications')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("knowledge-base"));
        }

        $this->load->model('trust_seal_model');

        // if (!can_logged_in_contact_view_all_tickets()) {
        //     $where .= ' AND ' . db_prefix() . 'tickets.contactid=' . get_contact_user_id();
        // }

        $defaultStatuses = hooks()->apply_filters('customers_area_list_default_ticket_statuses', [1]);
        // By default only open tickets
        $data['list_statuses'] = is_numeric($status) ? [$status] : $defaultStatuses;

        $data['bodyclass']     = 'tickets';
        $data['seals']       = $this->trust_seal_model->getOnlySealPublicCustomer(get_client_user_id());
        $data['title']         = _l('clients_tickets_heading');
        $this->data($data);
        $this->view('trust_seal/seals/clients/seals');
        $this->layout();
    }

    public function audidetails($id = '')
    {
        if (!has_contact_permission('certifications')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("knowledge-base"));
        }

        $this->load->model('audits_model');
        $this->load->model('seals_model');
        $this->load->model('exams_model');

        //audit
        $data['status'] = $this->audits_model->status();
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



        $title =  _l('audit_lowercase') . ' ' . (int)$audit->id;

        $data['exams_group'] = $this->exams_model->get_exams_group($audit->id_seal, $audit->id);
        //_l('edit', _l('audit_lowercase')) . ' ' . $audit->id;

        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['customers'] = $this->audits_model->get_all_customers_for_select();
        $data['seals'] = $this->audits_model->get_all_seals_for_select();
        $this->data($data);
        $this->view('trust_seal/audits/clients/audits_details');
        $this->layout();
    }

    /* Add new role or edit existing one */
    public function certificationdetails($id = '')
    {
        // if (!has_permission('roles', '', 'view')) {
        //     access_denied('roles');
        // }
        $this->load->model('certifications_model');

        $data['status'] = $this->certifications_model->status();
        $certification               = $this->certifications_model->get($id);
        $data['certification']       = $certification;
        $title              =  $certification->id;
        //_l('edit', _l('certification_lowercase')) . ' ' . $certification->id;

        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = '#' . $title;
        $data['customers'] = $this->certifications_model->get_all_customers_for_select();
        $data['seals'] = $this->certifications_model->get_all_seals_for_select();

        $this->data($data);
        $this->view('trust_seal/certifications/clients/certification_details');
        $this->layout();
    }

    public function sealdetails($id)
    {
        $this->load->model('seals_model');

        if ($id == '') {

            $title = _l('add_new', _l('seal_lowercase'));
        } else {

            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('trust_seal', 'seals/table_documents'));
            }

            $data['status'] = $this->seals_model->status();
            $seal               = $this->seals_model->get($id);
            $data['document']   = $this->seals_model->getDocumentsSealClient($id);
            $data['seal']       = $seal;
            $title              = $seal->title;
        }

        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['exams'] = $this->seals_model->get_all_exams_for_select();
        $this->data($data);
        $this->view('trust_seal/seals/clients/seals_details');
        $this->layout();
    }

    public function request_seals($id = '')
    {
        $this->load->model('seals_model');

        if ($id == '') {

            $title = _l('add_new', _l('seal_lowercase'));
        } else {

            $data['status'] = $this->seals_model->status();
            $seal               = $this->seals_model->get($id);
            $data['document']   = $this->seals_model->getDocumentsSealClient($id);
            $data['seal']       = $seal;
            $title              = $seal->title;
        }

        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['sealsList'] = $this->seals_model->get('', ['status' => 1]);
        $this->data($data);
        $this->view('trust_seal/seals/clients/request_seals');
        $this->layout();
    }

    public function open_ticket($idSello)
    {
        if (!has_contact_permission('certifications')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("knowledge-base"));
        }

        $this->load->model('projects_model');
        $this->load->model('seals_model');
        $data             = [];
        $data['departament_current']    = 1;
        $data['projects'] = $this->projects_model->get_projects_for_ticket(get_client_user_id());
        $data['title']    = _l('new_ticket');
        $data['seal'] = $this->seals_model->get($idSello);
        $data['list_documents_seal'] = $this->seals_model->getFileSeals(null, ['id_seal' => $idSello]);
        $this->data($data);
        $this->view('trust_seal/seals/clients/open_ticket');
        $this->layout();
    }

    public function view_audits($id = 0)
    {
        if (!has_contact_permission('certifications')) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url("knowledge-base"));
        }


        $this->load->model('audits_model');
        $this->load->model('seals_model');
        $this->load->model('exams_model');

       
        $audit               = $this->audits_model->get($id);
        $data['audit']       = $audit;
        $exams_groups = $this->seals_model->get_seal_exams_groups($audit->id_seal);  

        if (isset($exams_groups)) {
            foreach ($exams_groups as $group) {

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

        $current_seal = $this->seals_model->get($audit->id_seal);
        $title =  _l('audit') . ', ' . $current_seal->title;
        $data['current_seal'] = $current_seal;
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $this->data($data);
        $this->view('trust_seal/audits/clients/view_audits');
        $this->layout();
    }
}
