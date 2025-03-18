<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('complaints_model');
    }

    public function index($status = '', $userid = '')
    {

        if (!is_numeric($status)) {
            $status = '';
        }

        if ($this->input->is_ajax_request()) {

            $this->load->library('ssp');

            $table = db_prefix() . 'complaints_services';
            $primaryKey = db_prefix() . 'complaints_services.serviceid';

            $columns = array(
                array('db' => 'serviceid', 'dt' => '0', 'field' => 'serviceid'),
                array(
                    'db' => 'name', 'dt' => '1', 'field' => 'name'
                ),
                array(
                    'db' => 'serviceid', 'dt' => '2', 'field' => 'options',
                    'formatter' => function ($d, $row) {

                        $options = icon_btn(
                            '#',
                            'fa-regular fa-pen-to-square',
                            'btn-default _btn_edit',
                            [
                                'data-url' => $d,
                                'data-id' => $d,
                                'data-name' => $row['name']
                            ],
                        );

                        $options .=  icon_btn('complaints/services/delete_service/' . $d, 'fa fa-remove', 'btn-danger _delete', [
                            'data-id' => $d,
                            'data-name' => $row['name']
                        ]);

                        return $options;
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

            $data['chosen_ticket_status']              = $status;
            $this->load->view('services/manage', $data);
        }
    }

    public function add($id = '')
    {
        if (!is_admin() && get_option('staff_members_save_tickets_predefined_replies') == '0') {
            access_denied('Complaint Services');
        }

        if ($this->input->post()) {
            $post_data = $this->input->post();
            if (!$this->input->post('id')) {


                $requestFromComplaintArea = isset($post_data['complaint_area']);
                if (isset($post_data['complaint_area'])) {
                    unset($post_data['complaint_area']);
                }
                $id = $this->complaints_model->add_service($post_data);

                if (!$requestFromComplaintArea) {

                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('clients_complaint_open_services')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id, 'name' => $post_data['name']]);
                }
            } else {

                $id = $post_data['id'];
                unset($post_data['id']);
                $success = $this->complaints_model->update_service($post_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('clients_complaint_open_services')));
                }
            }
            die;
        }
    }

    /* List all ticket services */
    // public function services()
    // {
    //     if (!is_admin()) {
    //         access_denied('Ticket Services');
    //     }
    //     if ($this->input->is_ajax_request()) {
    //         $aColumns = [
    //             'serviceid',
    //             'name',
    //         ];
    //         $sIndexColumn = 'serviceid';
    //         $sTable       = db_prefix() . 'services';
    //         $result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], [
    //             'serviceid',
    //         ]);
    //         $output  = $result['output'];
    //         $rResult = $result['rResult'];
    //         foreach ($rResult as $aRow) {
    //             $row = [];
    //             for ($i = 0; $i < count($aColumns); $i++) {
    //                 $_data = $aRow[$aColumns[$i]];
    //                 if ($aColumns[$i] == 'name') {
    //                     $_data = '<a href="#" onclick="edit_service(this,' . $aRow['serviceid'] . ');return false" data-name="' . $aRow['name'] . '">' . $_data . '</a>';
    //                 }
    //                 $row[] = $_data;
    //             }
    //             $options = icon_btn('#', 'fa-regular fa-pen-to-square', 'btn-default', [
    //                 'data-name' => $aRow['name'],
    //                 'onclick'   => 'edit_service(this,' . $aRow['serviceid'] . '); return false;',
    //             ]);
    //             $row[]              = $options .= icon_btn('tickets/delete_service/' . $aRow['serviceid'], 'fa fa-remove', 'btn-danger _delete');
    //             $output['aaData'][] = $row;
    //         }
    //         echo json_encode($output);
    //         die();
    //     }
    //     $data['title'] = _l('services');
    //     $this->load->view('admin/tickets/services/manage', $data);
    // }

    /* Add new service od delete existing one */
    public function service($id = '')
    {
        if (!is_admin() && get_option('staff_members_save_tickets_predefined_replies') == '0') {
            access_denied('Ticket Services');
        }

        if ($this->input->post()) {
            $post_data = $this->input->post();
            if (!$this->input->post('id')) {
                $requestFromTicketArea = isset($post_data['ticket_area']);
                if (isset($post_data['ticket_area'])) {
                    unset($post_data['ticket_area']);
                }
                $id = $this->tickets_model->add_service($post_data);
                if (!$requestFromTicketArea) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('service')));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id, 'name' => $post_data['name']]);
                }
            } else {
                $id = $post_data['id'];
                unset($post_data['id']);
                $success = $this->tickets_model->update_service($post_data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('service')));
                }
            }
            die;
        }
    }

    /* Delete ticket service from database */
    /* Delete ticket service from database */
    public function delete_service($id)
    {

        if (!is_admin()) {
            access_denied('Ticket Services');
        }
        if (!$id) {
            redirect(admin_url('tickets/services'));
        }


        $response = $this->complaints_model->delete_service($id);


        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('service_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('service')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('service_lowercase')));
        }
        redirect(admin_url('complaints/services'));
    }
}
