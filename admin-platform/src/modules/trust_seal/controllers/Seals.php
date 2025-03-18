<?php
error_reporting(0);

defined('BASEPATH') or exit('No direct script access allowed');

class Seals extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('seals_model');
    }

    //Seals
    public function manage_seals()
    {
        if (!has_permission('seals', '', 'view')) {
            access_denied('Seals');
        }

        if ($this->input->is_ajax_request()) {

            $idClientes = $this->input->get('idClientes');

            $this->load->library('ssp');

            $table = db_prefix() . 'seals';
            $primaryKey = 'id';

            $columns = array(
                array('db' => 'id', 'dt' => '0', 'field' => 'id', 'formatter' => function ($d, $row) {

                    $content = '<a href="' . admin_url('trust_seal/seals/view/' . $row['id']) . '">' . $d . '</a>';
                    return $content;
                }),
                array('db' => 'title', 'dt' => '1', 'field' => 'title', 'formatter' => function ($d, $row) {

                    $content =  get_fields_name($row);
                    return $content;
                }),
                array('db' => 'exams', 'dt' => '2', 'field' => 'exams', 'formatter' => function ($d, $row) {
                    return   '1 ' . _l('exams');
                }),
                array(
                    'db' => 'date_start', 'dt' => '3', 'field' => 'date_start',
                    'formatter' => function ($d, $row) {
                        return '<p>' . date_format(date_create($row['date_start']), 'd-m-Y') . '</p>';
                    }
                ),

                array(
                    'db' => 'docs', 'dt' => '4', 'field' => 'docs',
                    'formatter' => function ($d, $row) {
                        return $row['docs'] . ' ' . _l('seal_attach');
                    }
                ),
                array(
                    'db' => 'status', 'dt' => '5', 'field' => 'status', 'formatter' => function ($d, $row) {

                        $status = '';

                        foreach (get_status_seals() as $qualification) {
                            if ($qualification['status'] == $row['status']) {
                                $status  = get_status_audits_format($qualification);
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


            $extraWhere = "";

            if ($idClientes > 0) {
                $extraWhere =  ' id_customer = ' . $this->db->escape_str($idClientes);
            }

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns,  [], $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {
            $data['seal'] = $this->seals_model->get_all_seals();
            $data['title'] = _l('all_seals');

            $this->load->view('trust_seal/seals/manage', $data);
        }
    }

    /* Add new role or edit existing one */
    public function seal($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('seals', '', 'create')) {
                    access_denied('Seals');
                }
                $data = $this->input->post();
                $data['status'] = 2;
                $id = $this->seals_model->add($data);
                if ($id) {
                    set_alert('success', _l('added_successfully', _l('seals')));
                    redirect(admin_url('trust_seal/seals/view/' . $id));
                }
            } else {
                if (!has_permission('seals', '', 'edit')) {
                    access_denied('Seals');
                }
                $success = $this->seals_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('role')));
                }
                redirect(admin_url('trust_seal/seals/view/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('seal_lowercase'));
        } else {
            $seal               = $this->seals_model->get($id);
            $data['seal']       = $seal;
            // $data['seals']      = $this->seals_model->get_seals($id);
            $title              = _l('edit', _l('seal_lowercase')) . ' ' . $seal->title;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['exams'] = $this->seals_model->get_all_exams_for_select();
        $this->load->view('seals/seal', $data);
    }

    /* View */
    public function view($id = '')
    {
        if ($this->input->post()) {

            if (!has_permission('seals', '', 'edit')) {
                access_denied('Seals');
            }
     
            $actionRun = isset($this->input->post()['action']) ? $this->input->post()['action'] :
                '';

            if ($actionRun == "update_seal") {

                $data['title'] = $this->input->post()['title'];
                $data['exams'] = $this->input->post()['exams'];
                $data['status'] = $this->input->post()['status'];
                $data['requirements'] = $this->input->post()['requirements'];
                $data['description'] = $this->input->post()['description'];
                $data['date_start'] = $this->input->post()['date_start'];

                $this->seals_model->clear_seal_exams($id); 

                if(isset($_POST['groups_exams']))
                {
                   foreach($_POST['groups_exams'] as $value)      
                   {
                        $this->seals_model->add_seal_exams([
                            'id_seal' => $id
                            ,'id_exams' => $value
                        ], $id);
                   }
                }

                $seal_id = $this->seals_model->update_seal($data, $id);
                set_alert('success', _l('updated_successfully', _l('seal')));
                redirect(admin_url('trust_seal/seals/view/' . $id . '?tab=detail'));
            } else if ($actionRun == "add_badge") {

                set_alert('success', _l('updated_successfully', _l('seal')));
                redirect(admin_url('trust_seal/seals/view/' . $id . '?tab=badge'));
            } else if ($actionRun == "add_documents") {

                set_alert('success', _l('updated_successfully', _l('seal')));
                redirect(admin_url('trust_seal/seals/view/' . $id . '?tab=documents'));
            }
        }

        if ($id == '') {
            $title = _l('add_new', _l('seal_lowercase'));
        } else {

            if ($this->input->is_ajax_request()) {
                $this->app->get_table_data(module_views_path('trust_seal', 'seals/table_documents'));
                die();
            }

            $data['status'] = $this->seals_model->status();
            $seal               = $this->seals_model->get($id);
            $data['seal']       = $seal;
            $title              = _l('edit', _l('seal_lowercase')) . ' ' . $seal->title;
            $exams_groups        = $this->seals_model->get_seal_exams_groups($id);
            $data['exams_groups']= $exams_groups;
        }
        $this->app_scripts->add('tinymce-stickytoolbar', site_url('assets/plugins/tinymce-stickytoolbar/stickytoolbar.js'));
        $data['title'] = $title;
        $data['exams'] = $this->seals_model->get_all_exams_for_select();
        $this->load->view('seals/view', $data);
    }

    /* Delete role from database */
    public function delete_seal($id)
    {
        $id  = (int) $id;

        if (!has_permission('seals', '', 'delete')) {
            access_denied('Seals');
        }

        if (!$id) {
            redirect(admin_url('trust_seal/seals/manage_seals'));
        }
        $response = $this->seals_model->delete($id);

        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced_c', _l('seal_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted_seal', _l('seal')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('seal_lowercase')));
        }
        redirect(admin_url('trust_seal/seals/manage_seals'));
    }

    public function attachment_active($id)
    {
        $id  = (int) $id;

        if (isset($_FILES['file']['name']) && ($_FILES['file']['name'] != '')) {
            $this->upload_logo_seals($id);
            $data['logo_active'] = $_FILES['file']['name'];
            $this->seals_model->update_seal($data, $id);
        }
        if (isset($_GET["logo"])) {
            unlink(base_url(PATH_SEALS . '/' . $id . '/' . $_GET["logo"]));
            $data['logo_active'] = $_FILES['file']['name'];
            $this->seals_model->update_seal($data, $id);
            set_alert('success', _l('clients_seal_create_seal_delete', _l('seal_badge')));
            redirect(admin_url('trust_seal/seals/view/' . $id . '?tab=badge'));
        }
    }

    public function attachment_inactive($id)
    {
        $id  = (int) $id;

        if (isset($_FILES['file']['name']) && ($_FILES['file']['name'] != '')) {
            $this->upload_logo_seals($id);
            $data['logo_inactive'] = $_FILES['file']['name'];
            $this->seals_model->update_seal($data, $id);
        }
        if (isset($_GET["logo"])) {
            unlink(base_url(PATH_SEALS . '/' . $id . '/' . $_GET["logo"]));
            $data['logo_inactive'] = $_FILES['file']['name'];
            $this->seals_model->update_seal($data, $id);
            set_alert('success', _l('clients_seal_create_seal_delete', _l('seal_badge')));
            redirect(admin_url('trust_seal/seals/view/' . $id . '?tab=badge'));
        }
    }

    public function upload_logo_seals($id)
    {
        $id  = (int) $id;

        $path = PATH_SEALS . $id . '/';

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $temp = explode(".", $_FILES["file"]["name"]);
        $newfilename = $path . round(microtime(true)) . '.' . end($temp);
        $tmpFilePath = $_FILES['file']['tmp_name'];
        $filename = $_FILES['file']['name'];
        $newFilePath = $path . $filename;

        if (move_uploaded_file($tmpFilePath, $newFilePath)) {
            return $newfilename;
        }
    }

    public function delete_file($id, $id_seal)
    {
        $id  = (int) $id;

        if (!has_permission('seals', '', 'delete')) {
            access_denied('Seals');
        }

        if (isset($_GET["file"])) {
            unlink(base_url(PATH_SEALS . '/' . $id . '/' . $_GET["file"]));
            $this->seals_model->delete_file($id);
            set_alert('success', _l('deleted', _l('file')));
            redirect(admin_url('trust_seal/seals/view/' . $id_seal . '?tab=documents'));
        }
    }

    public function add_logo_clients($id)
    {

        $id  = (int) $id;

        if (isset($_FILES['file_client_logo']) && _perfex_upload_error($_FILES['file_client_logo']['error'])) {
            header('HTTP/1.0 400 Bad error');
            echo _perfex_upload_error($_FILES['file_client_logo']['error']);
        }
        if (isset($_FILES['file_client_logo']['name']) && $_FILES['file_client_logo']['name'] != '') {
            //  hooks()->do_action('before_upload_contract_attachment', $id);

            $path =  $path = PATH_SEALS . "logo_client_api/" . $id . '/';
            // Get the temp file path
            $tmpFilePath = $_FILES['file_client_logo']['tmp_name'];
            // Make sure we have a filepath
            if (!empty($tmpFilePath) && $tmpFilePath != '') {
                _maybe_create_upload_path($path);
                $filename    = unique_filename($path, $_FILES['file_client_logo']['name']);
                $newFilePath = $path . $filename;
                // Upload the file into the company uploads dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    //  $CI           = &get_instance();
                    $attachment   = [];
                    $attachment[] = [
                        'file_name' => $filename,
                        'filetype'  => $_FILES['file_client_logo']['type'],
                    ];
                    //$CI->misc_model->add_attachment_to_database($id, 'contract', $attachment);

                    return true;
                }
            }
        }

        return false;
    }

    public function attachment_files($id)
    {
        $id  = (int) $id;

        if (isset($_FILES['file']['name']) && ($_FILES['file']['name'] != '')) {

            $path = PATH_SEALS . $id . '/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            // $temp = explode(".", $_FILES["file"]["name"]);
            // $newfilename = $path . round(microtime(true)) . '.' . end($temp);
            $tmpFilePath = $_FILES['file']['tmp_name'];
            $filename = $_FILES['file']['name'];
            $newFilePath = $path . $filename;

            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                if ($this->input->post()) {

                    $data['id_seal'] = $id;
                    $data['file'] = $_FILES['file']['name'];
                    $this->seals_model->add_file($data);
                    set_alert('success', _l('add_new', _l('file')));

                    redirect(admin_url('trust_seal/seals/view/' . $id . '?tab=documents'));
                }
            }
        }
    }


    public function config_seal()
    {
        if (!has_permission('settings', '', 'view')) {
            access_denied('settings');
        }



        $this->load->library('form_validation');

        if ($this->input->post()) {

            if (!has_permission('settings', '', 'edit')) {
                access_denied('settings');
            }

            $this->form_validation->set_rules('seal_default_priority', _l('ticket_dt_priority'), 'required');
            $this->form_validation->set_rules('seal_default_service', _l('service'), 'required');
            $this->form_validation->set_rules('seal_default_departamens', _l('department'), 'required');

            if ($this->form_validation->run() !== false) {
                $data  = $this->input->post();
                // $data['message'] = html_purify($this->input->post('message', false));
                //     $id = $data['configid'];

                update_option('seal_default_priority', $data['seal_default_priority']);
                update_option('seal_default_service', $data['seal_default_service']);
                update_option('seal_default_departamens', $data['seal_default_departamens']);

                update_option('seal_api_user', $data['seal_api_user']);
                update_option('seal_api_password', $data['seal_api_password']);

                set_alert('success', _l('vulnerabilities_confi_save'));
                redirect(admin_url('trust_seal/seals/config_seal'));
            } else {
                $error_string = $this->form_validation->error_string();
                set_alert('danger', $error_string);
            }
        }


        $rs = Get_Seal_Config();
        $data['config_analyzes'] = $rs;
        $this->load->model('tickets_model');
        $this->load->model('departments_model');

        $data['departments']          = $this->departments_model->get();
        $data['priorities']           = $this->tickets_model->get_priority();
        $data['services']             = $this->tickets_model->get_service();

        $this->load->view('seals/config_add', $data);
    }
}
