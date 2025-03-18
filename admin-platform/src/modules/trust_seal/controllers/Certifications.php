<?php
error_reporting(0);

defined('BASEPATH') or exit('No direct script access allowed');

class Certifications extends AdminController
{
    private $lastIdCustomer;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('certifications_model');
    }
    //certification
    public function manage()
    {
        if (!has_permission('certifications', '', 'view')) {
            access_denied('Certifications');
        }

        if ($this->input->is_ajax_request()) {

            $this->lastIdCustomer = 0;

            $idClientes = $this->input->get('idClientes');


            $indexCustomer = 2;
            $indexDate = 3;
            $indexDate_expiration = 4;
            $indexState = 5;
            $indexVersello = 6;

            if ($idClientes > 0) {


                $indexCustomer = null;
                $indexDate = 2;
                $indexDate_expiration = 3;
                $indexState = 4;
                $indexVersello = 5;
            }

            $this->load->library('ssp');

            $table = db_prefix() . 'certifications';
            $primaryKey = 'cf.id';

            $columns = array(
                array('db' => 'cf.id_customer', 'field' => 'id_customer'),
                array('db' => 'sl.logo_active', 'field' => 'logo_active'),
                array('db' => 'cf.certificationkey', 'field' => 'certificationkey'),
                array('db' => 'cf.id_seal', 'field' => 'id_seal'),
                array('db' => 'sl.logo_inactive', 'field' => 'logo_inactive'),
                array('db' => 'cf.id', 'dt' => '0', 'field' => 'cf.id', 'formatter' => function ($d, $row) {

                    $content = '<a href="' . admin_url('trust_seal/certifications/certification/' . $row['id']) . '">' .  $row['certificationkey'] . '</a>';

                    if ($this->lastIdCustomer != $row['id_customer']) {
                        $this->lastIdCustomer = $row['id_customer'];
                        set_certifications_expired($row['id_customer']);
                    }

                    return $content;
                }),
                array(
                    'db' => 'sl.title', 'dt' => '1', 'field' => 'sl.title', 'as' => 'title',
                    'formatter' => function ($d, $row) {

                        // $descript = "";

                        // if (isset($row['id_seal']) && $row['id_seal'] > 0) {
                        //     $descript  = $this->certifications_model->get_seal($row['id_seal'])[0]['title'];
                        // }

                        $content =  get_fields_id_certifications($row, $this->input->get('idClientes'),  $row['title']);


                        return $content;
                        // if (isset($row['id_seal']) && $row['id_seal'] > 0) {
                        //     return   '<a href="' . admin_url('trust_seal/seals/view/' . $row['id_seal']) . '" class="mbot10 display-block">' . $this->certifications_model->get_seal($row['id_seal'])[0]['title'] . '</a>';
                        // } else {
                        //     return '';
                        // }
                    }
                ),
                array('db' => 'cl.company', 'dt' => $indexCustomer, 'field' => 'cl.company', 'as' => 'company', 'formatter' => function ($d, $row) {


                    if (isset($row['id_customer']) && $row['id_customer'] > 0) {
                        return '<a href="' . admin_url('clients/client/' . $row['id_customer']) . '" class="mbot10 display-block">' . $row['company'] . '</a>';
                    } else {
                        return '';
                    }
                }),
                array(
                    'db' => 'cf.date', 'dt' => $indexDate, 'field' => 'cf.date',
                    'formatter' => function ($d, $row) {
                        return '<p>' . date_format(date_create($row['date']), 'd-m-Y') . '</p>';
                    },
                ),
                array(
                    'db' => 'cf.date_expiration', 'dt' => $indexDate_expiration, 'field' => 'cf.date_expiration',
                    'formatter' => function ($d, $row) {
                        return '<p>' . date_format(date_create($row['date_expiration']), 'd-m-Y') . '</p>';
                    }
                ),
                array(
                    'db' => 'cf.status', 'dt' => $indexState, 'field' => 'cf.status', 'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_status_certifications() as $qualification) {
                            if ($qualification['status'] == $row['status']) {
                                $qualification['translate_name'] =  substr($qualification['translate_name'], 0, -1);
                                $status = get_status_audits_format($qualification);
                                break;
                            }
                        }
                        return $status;
                    }
                ),
                array(
                    'db' => 'cf.status', 'dt' => $indexVersello, 'field' => 'cf.status', 'formatter' => function ($d, $row) {

                        $urlActive = "";
                        $urlInactive = "";
                        $nameSeal = $this->certifications_model->get_seal($row['id_seal'])[0]['title'];

                        if ($row['logo_active'] != null)
                            $urlActive =  base_url(PATH_SEALS . '' . $row['id_seal'] . '/' . $row['logo_active']);

                        if ($row['logo_inactive'] != null)
                            $urlInactive =  base_url(PATH_SEALS . '' . $row['id_seal'] . '/' . $row['logo_inactive']);

                        $status = '<a href="javascript:void(0);"  data-nameseal="' . $nameSeal . '"  data-seal="' . $urlActive . '"  data-sealinactive="' . $urlInactive  . '"  class="btn btn-primary btn-sm btn_file_enable">'
                            . _l('btn_view_seal') .
                            '</a>';
                        // $status .= '&nbsp;&nbsp;<a href="javascript:void(0);"  data-seal="' . $urlInactive  . '"  class="btn btn-danger btn-sm btn_file_disabled">'
                        //     . _l('exams_inactive') .
                        //     '</a>';

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



            $extraWhere = "";

            if ($idClientes > 0) {
                $extraWhere =  ' cf.id_customer = ' . $this->db->escape_str($idClientes);
            }

            $joinQuery = "FROM " . db_prefix() . "certifications cf
            LEFT JOIN " . db_prefix() . "seals sl ON ( sl.id = cf.id_seal )
             LEFT JOIN " . db_prefix() . "clients cl ON ( cl.userid = cf.id_customer )
            ";
            //seals.id


            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns,  $joinQuery, $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {

            $data['list_statuses'] = [];
            $data['certifications'] = $this->certifications_model->get_all_certifications();
            $data['title'] = _l('all_certifications');

            $this->load->view('trust_seal/certifications/manage', $data);
        }
    }

    /* Add new role or edit existing one */
    public function certification($id = 0, $client_selected = 0)
    {
        if ($this->input->post()) {

            $data = $this->input->post();


            if ($id == 0) {
                if (!has_permission('certifications', '', 'create')) {
                    access_denied('Certifications');
                }


                $data['certificationkey'] = "";
                $id = $this->certifications_model->add($data);

                insert_niu($id);

                if ($id) {
                    set_alert('success', _l('clients_seal_create_seal', _l('certification')));
                    redirect(admin_url('trust_seal/certifications/certification/' . $id));
                }
            } else {
                if (!has_permission('certifications', '', 'edit')) {
                    access_denied('Certifications');
                }

                $data['id'] = $id;
                if ($data['status'] == 1) {
                    insert_niu($id);
                }
                $success = $this->certifications_model->update($data, $id);

                if ($success) {
                    set_alert('success', _l('clients_seal_update_seal', _l('certificationes')));
                }
                redirect(admin_url('trust_seal/certifications/certification/' . $id));
            }
        }
        if ($id == 0) {
            $title = _l('add_new', _l('certification_lowercase'));
        } else {
            $data['status'] = $this->certifications_model->status();
            $certification               = $this->certifications_model->get($id);
            $data['certification']       = $certification;
            $title              = _l('edit', _l('certification_lowercase')) . ' ' . (int)$certification->id;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));

        $data['sello'] = "";
        $data['cliente'] = "";

        if ($this->session->flashdata('clientes') !== null) {
            $data['sello'] = $this->session->flashdata('sellos');
            $data['cliente'] = $this->session->flashdata('clientes');
        }

        $data['title'] = $title;
        $data['client_selected'] = $client_selected;
        $data['customers'] = $this->certifications_model->get_all_customers_for_select();
        $data['seals'] = $this->certifications_model->get_all_seals_for_select();
        $this->load->view('certifications/certification', $data);
    }

    /* Delete role from database */
    public function delete($id)
    {
        if (!has_permission('certifications', '', 'delete')) {
            access_denied('Certifications');
        }

        if (!$id) {
            redirect(admin_url('trust_seal/certifications/manage'));
        }
        $response = $this->certifications_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('certification_lowercase')));
        } elseif ($response == true) {

            set_alert('success', _l('deleted_seal', _l('certificationes')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('certification_lowercase')));
        }
        redirect(admin_url('trust_seal/certifications/manage'));
    }

    public function get_reminders_certifications($id, $rel_type)
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data(APP_MODULES_PATH . 'trust_seal/views/tables/certifications', [
                'id'       => $id,
                'rel_type' => $rel_type,
            ]);
        }
    }
}
