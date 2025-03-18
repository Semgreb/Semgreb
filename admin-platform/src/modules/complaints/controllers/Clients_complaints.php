<?php

defined('BASEPATH') or exit('No direct script access allowed');

use app\services\projects\Gantt;
use app\services\ValidatesContact;

class Clients_complaints extends ClientsController
{
    public $piping = false;
    /**
     * @since  2.3.3
     */
    // use ValidatesContact;

    public function __construct()
    {
        parent::__construct();
        parent::disableNavigation();
        parent::disableSubMenu();
        //parent::disableFooter();


        $this->load->model('complaints_model');

        //hooks()->do_action('after_clients_area_init', $this);
    }

    public function index()
    {
        $data['is_home'] = true;
        $this->load->model('reports_model');
        $data['payments_years'] = $this->reports_model->get_distinct_customer_invoices_years();

        $data['project_statuses'] = $this->projects_model->get_project_statuses();
        $data['title']            = get_company_name(get_client_user_id());
        $this->data($data);
        $this->view('home');
        $this->layout();
    }

    public function open_complaints($userIdHash)
    {
        $useridDecode = my_decode(urldecode($userIdHash));

        if ($useridDecode <= 0) {
            set_alert('warning', _l('access_denied'));
            redirect(site_url());
        }

        if ($this->input->post()) {

            $this->form_validation->set_rules('document', _l('consumer_open_complaint_id'), 'required');
            $this->form_validation->set_rules('firstname', _l('consumer_open_complaint_firstname'), 'required');
            $this->form_validation->set_rules('lastname', _l('consumer_open_complaint_lastname'), 'required');
            $this->form_validation->set_rules('birthday_date', _l('consumer_open_complaint_birthday_date'), 'required');
            $this->form_validation->set_rules('phonenumber', _l('consumer_open_complaint_phonenumber'), 'required');
            $this->form_validation->set_rules('email', _l('consumer_open_complaint_email'), 'required');
            $this->form_validation->set_rules('subject', _l('customer_complaint_subject'), 'required');
            $this->form_validation->set_rules('service', _l('clients_complaint_open_services'), 'required');

            $custom_fields = get_custom_fields('clients_complaint', [
                'show_on_client_portal' => 1,
                'required'              => 1,
            ]);

            foreach ($custom_fields as $field) {

                $field_name = 'custom_fields[' . $field['fieldto'] . '][' . $field['id'] . ']';
                if ($field['type'] == 'checkbox' || $field['type'] == 'multiselect') {
                    $field_name .= '[]';
                }
                $this->form_validation->set_rules($field_name, $field['name'], 'required');
            }

            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();
                $this->load->model('consumers/consumers_model');

                $consumerid = $this->consumers_model->add([
                    'document' => $data['document'], 'firstname' => $data['firstname'], 'lastname' => $data['lastname'], 'birthday_date' => $data['birthday_date'], 'email' => $data['email'], 'phonenumber' => $data['phonenumber']
                ]);

                if ($consumerid) {

                    $appGenerateHash = app_generate_hash();

                    $id = $this->complaints_model->add([
                        'complaintkey' => $appGenerateHash,
                        'subject'    => $data['subject'],
                        'email'    => $data['email'],
                        'priority'   => 1,
                        'service'    => isset($data['service']) && is_numeric($data['service'])
                            ? $data['service']
                            : null,
                        // 'project_id' => isset($data['project_id']) && is_numeric($data['project_id'])
                        //     ? $data['project_id']
                        //     : 0,
                        'custom_fields' => isset($data['custom_fields']) && is_array($data['custom_fields'])
                            ? $data['custom_fields']
                            : [],
                        'message'   => $data['message'],
                        'contactid' => $consumerid,
                        'userid'    => my_decode($data['userid']),
                    ]);


                    if ($id) {
                        set_alert('success', _l('new_complaint_added_successfully', $id));
                        redirect(site_url('complaints/clients_complaints/complaint/' . $appGenerateHash));
                    }
                } else {

                    set_alert('danger', _l('client_invalid_consumer'));
                    redirect(site_url('complaints/clients_complaints/open_complaints'));
                }
            } else {
                set_alert('danger', printf('%s', $this->form_validation->error_string()));
            }
        }

        $this->load->model('clients_model');

        $data             = [];
        $data['clients'] = $this->clients_model->get($useridDecode);
        //  $data['projects'] = $this->projects_model->get_projects_for_ticket(get_client_user_id());
        $data['services'] = $this->complaints_model->get_service();
        $data['title']    = _l('new_complaints');
        $data['userIdHash'] = urldecode($userIdHash);
        $this->data($data);
        $this->view('complaints/clients/open_complaint');
        $this->layout();
    }


    public function complaint($id)
    {
        // echo "-- > $id";

        // die();


        // if (!has_contact_permission('support')) {
        //     set_alert('warning', _l('access_denied'));
        //     redirect(site_url());
        // }

        if (!$id) {
            redirect(site_url());
        }

        $data['complaints'] = $this->complaints_model->get_complaint_by_id($id);
        $complaint = $data['complaints'];

        // if (!$data['complaints'] || $data['complaints']->userid != get_client_user_id()) {
        //     show_404();
        // }

        if ($data['complaints']->merged_complaint_id != null) {
            redirect(site_url('complaints/clients_complaints/complaint/' . $data['complaints']->merged_complaint_id));
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('message', _l('ticket_reply'), 'required');

            if ($this->form_validation->run() !== false) {
                $data = $this->input->post();

                $replyid = $this->complaints_model->add_reply([
                    'message'   => $data['message'],
                    // 'contactid' => get_contact_user_id(),
                    'userid'    => $complaint->userid,
                ], $complaint->complaintid);
                if ($replyid) {
                    set_alert('success', _l('replied_to_ticket_successfully', $complaint->complaintid));
                }
                redirect(site_url('complaints/clients_complaints/complaint/' . $id));
            }
        }

        $data['complaint_replies'] = $this->complaints_model->get_complaint_replies($complaint->complaintid);


        $this->app_scripts->add('clients-complaints-js', base_url($this->app_scripts->core_file('assets/js', 'main.js')));

        $data['title']         = $data['complaints']->subject;
        $this->data($data);
        $this->view('complaints/clients/single_complaint');
        $this->layout();
    }

    public function get_consumer($document)
    {
        // $document = $this->input->post('document');
        $this->load->model('consumers/consumers_model');
        $consumer = $this->consumers_model->getConsumerDocument($document);
        $lastConsumer =  $consumer;
        echo json_encode($lastConsumer);
        die();
    }
}
