<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Complaints extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('complaints_model');
        $this->load->model('tickets_model');
    }

    public function index($status = '', $userid = '')
    {
        close_setup_menu();

        if (!has_permission('complaints', '', 'view')) {
            access_denied('Complaints');
        }

        if (!is_numeric($status)) {
            $status = '';
        }

        if ($this->input->is_ajax_request()) {
            $this->load->library('ssp');

            $extraWhere = "";
            $_statuses = [];
            $field = 'complaintsstatusid';

            foreach ($this->complaints_model->get_complaint_status() as $status) {
                $value = "complaint_status_" . $status[$field];
                if ($this->input->post($value)) {
                    array_push($_statuses, $status[$field]);
                }
            }

            if (count($_statuses) > 0) {
                $extraWhere = ' status IN (' . implode(', ', $_statuses) . ')';
            }

            $assignees  = $this->complaints_model->get_complaints_assignes_disctinct();
            $_assignees = [];
            foreach ($assignees as $__assignee) {
                if ($this->input->post('complaint_assignee_' . $__assignee['assigned'])) {
                    array_push($_assignees, $__assignee['assigned']);
                }
            }

            if (count($_assignees) > 0) {
                $extraWhere =  SSP::assign_conditions_query(
                    $extraWhere,
                    ' AND assigned IN (' . implode(', ', $_assignees) . ')',
                    ' assigned IN (' . implode(', ', $_assignees) . ')'
                );
            }

            $table = db_prefix() . 'complaints';
            $primaryKey = 'cp.complaintid';

            $columns = array(
                array('db' => 'cp.complaintid', 'dt' => '0', 'field' => 'complaintid', 'formatter' => function ($d, $row) {
                    $url   = admin_url('complaints/complaint/' . $row['complaintid']);
                    $content = '<a href="' . $url . '" class="valign">' . $d . '</a>';
                    return $content;
                }),
                array('db' => 'cp.subject', 'dt' => '1', 'field' => 'subject', 'formatter' => function ($d, $row) {
                    return formatt_render_subject($d, $row);
                }),
                array(
                    'db' => 'cs.firstname', 'dt' => '2', 'field' => 'firstname',
                    'formatter' => function ($d, $row) {
                        return  $row['firstname'] . " " . $row['lastname'];
                    }
                ),
                array('db' => 'cs.lastname', 'dt' => '', 'field' => 'lastname'),
                array(
                    'db' => 'cp.service', 'dt' => '3', 'field' => 'service',
                    'formatter' => function ($d, $row) {
                        return  complaint_service_translate($d);
                    }
                ),
                array(
                    'db' => 'cp.status', 'dt' => '4', 'field' => 'status', 'formatter' => function ($d, $row) {
                        return  complaint_status_translate($d, true);
                    }
                ),
                array(
                    'db' => 'cp.priority', 'dt' => '5', 'field' => 'priority',
                    'formatter' => function ($d, $row) {
                        return  ticket_priority_translate($d);
                    }
                ),
                array(
                    'db' => 'cp.lastreply', 'dt' => '6', 'field' => 'lastreply',
                    'formatter' => function ($d, $row) {
                        return  $d == "" ?   _l('complaint_no_reply_yet') : $d;
                    }
                ),
                array(
                    'db' => 'cp.date', 'dt' => '7', 'field' => 'date',
                    'formatter' => function ($d, $row) {
                        return  substr($d, 0, 10);
                    }
                ),
                array('db' => 'cp.complaintkey', 'dt' => null, 'field' => 'complaintkey'),

            );

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $joinQuery = "FROM " . db_prefix() . "complaints cp
          JOIN " . db_prefix() . "consumers cs ON ( cs.consumerid = cp.contactid )";


            if ($userid > 0) {
                $extraWhere =  SSP::assign_conditions_query(
                    $extraWhere,
                    " AND cp.userid = $userid",
                    " cp.userid = $userid"
                );
            }

            if ($this->input->post('merged_complaints')) {
                $extraWhere =  SSP::assign_conditions_query(
                    $extraWhere,
                    ' AND merged_complaint_id IS NOT NULL',
                    ' merged_complaint_id IS NOT NULL'
                );
            } else {

                $extraWhere =  SSP::assign_conditions_query(
                    $extraWhere,
                    ' AND merged_complaint_id IS NULL',
                    ' merged_complaint_id IS NULL'
                );
            }

            if ($this->input->post('my_complaints')) {
                $extraWhere =  SSP::assign_conditions_query(
                    $extraWhere,
                    ' AND assigned = ' . get_staff_user_id(),
                    ' assigned = ' . get_staff_user_id()
                );
            }

            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns,  $joinQuery, $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        } else {

            $data['chosen_ticket_status']              = $status;
            $data['weekly_complaints_opening_statistics'] = json_encode($this->complaints_model->get_weekly_complaints_opening_statistics());
            $data['title']                             = _l('support_complaints');
            $this->load->model('departments_model');
            $data['statuses'] = $this->complaints_model->get_complaint_status();
            $data['staff_deparments_ids'] =
                $this->departments_model->get_staff_departments(get_staff_user_id(), true);
            $data['departments']          = $this->departments_model->get();
            $data['priorities']           = $this->tickets_model->get_priority();
            $data['services']             = $this->complaints_model->get_service();
            $data['ticket_assignees']     =
                $this->tickets_model->get_tickets_assignes_disctinct();
            $data['bodyclass']            = 'tickets-page';
            add_admin_tickets_js_assets();
            $data['default_tickets_list_statuses'] = hooks()->apply_filters('default_tickets_list_statuses', [1, 2, 4]);
            $this->load->view('list', $data);
        }
    }

    public function add($userid = false)
    {
        if (!has_permission('complaints', '', 'create')) {
            access_denied('Complaints');
        }

        if ($this->input->post()) {
            $data            = $this->input->post();
            $data['message'] = html_purify($this->input->post('message', false));
            $data['complaintkey'] = app_generate_hash();
            $id              = $this->complaints_model->add($data, get_staff_user_id());
            if ($id) {
                set_alert('success', _l('new_complaint_added_successfully', $id));
                redirect(admin_url('complaints/complaint/' . $id));
            }
        }
        if ($userid !== false) {
            $data['userid'] = $userid;
            $data['client'] = $this->clients_model->get($userid);
        }
        // Load necessary models
        $this->load->model('knowledge_base_model');
        $this->load->model('departments_model');

        $data['departments']        = $this->departments_model->get();
        $data['predefined_replies'] = $this->tickets_model->get_predefined_reply();
        $data['priorities']         = $this->tickets_model->get_priority();
        $data['services']           = $this->complaints_model->get_service();
        $whereStaff                 = [];
        if (get_option('access_tickets_to_none_staff_members') == 0) {
            $whereStaff['is_not_staff'] = 0;
        }
        $data['staff']     = $this->staff_model->get('', $whereStaff);
        $data['articles']  = $this->knowledge_base_model->get();
        $data['bodyclass'] = 'ticket';
        $data['title']     = _l('new_ticket');

        if ($this->input->get('project_id') && $this->input->get('project_id') > 0) {
            // request from project area to create new ticket
            $data['project_id'] = $this->input->get('project_id');
            $data['userid']     = get_client_id_by_project_id($data['project_id']);
            if (total_rows(db_prefix() . 'contacts', ['active' => 1, 'userid' => $data['userid']]) == 1) {
                $contact = $this->clients_model->get_contacts($data['userid']);
                if (isset($contact[0])) {
                    $data['contact'] = $contact[0];
                }
            }
        } elseif ($this->input->get('contact_id') && $this->input->get('contact_id') > 0 && $this->input->get('userid')) {
            $contact_id = $this->input->get('contact_id');
            if (total_rows(db_prefix() . 'contacts', ['active' => 1, 'id' => $contact_id]) == 1) {
                $contact = $this->clients_model->get_contact($contact_id);
                if ($contact) {
                    $data['contact'] = (array) $contact;
                }
            }
        }
        // add_admin_tickets_js_assets();
        $this->app_scripts->add('complaints-js', module_dir_url('complaints', 'js/complaints.js'), 'admin', ['app-js']);
        $this->load->view('complaints/add', $data);
    }

    public function complaint($id)
    {
        if (!has_permission('complaints', '', 'view')) {
            access_denied('Complaints');
        }

        if (!$id) {
            redirect(admin_url('complaints/add'));
        }

        $data['complaint']         = $this->complaints_model->get_complaint_by_id($id);
        $data['merged_complaints'] = $this->complaints_model->get_merged_complaints_by_primary_id($id);

        if (!$data['complaint']) {
            blank_page(_l('complaint_not_found'));
        }

        if (get_option('staff_access_only_assigned_departments') == 1) {
            if (!is_admin()) {
                $this->load->model('departments_model');
                $staff_departments = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                if (!in_array($data['ticket']->department, $staff_departments)) {
                    set_alert('danger', _l('ticket_access_by_department_denied'));
                    redirect(admin_url('access_denied'));
                }
            }
        }

        if ($this->input->post()) {
            $returnToTicketList = false;
            $data               = $this->input->post();

            if (isset($data['ticket_add_response_and_back_to_list'])) {
                $returnToTicketList = true;
                unset($data['ticket_add_response_and_back_to_list']);
            }

            $data['message'] = html_purify($this->input->post('message', false));
            $replyid         = $this->complaints_model->add_reply($data, $id, get_staff_user_id());

            if ($replyid) {
                set_alert('success', _l('replied_to_ticket_successfully', $id));
            }
            if (!$returnToTicketList) {
                redirect(admin_url('complaints/complaint/' . $id));
            } else {
                set_complaint_open(0, $id);
                redirect(admin_url('complaints'));
            }
        }
        // Load necessary models
        $this->load->model('knowledge_base_model');
        $this->load->model('departments_model');

        $data['statuses']                       = $this->complaints_model->get_complaint_status();
        $data['statuses']['callback_translate'] = 'complaint_status_translate';

        $data['departments']        = $this->departments_model->get();
        $data['predefined_replies'] = $this->complaints_model->get_predefined_reply();
        $data['priorities']         = $this->tickets_model->get_priority();
        $data['services']           = $this->complaints_model->get_service();
        $whereStaff                 = [];
        if (get_option('access_tickets_to_none_staff_members') == 0) {
            $whereStaff['is_not_staff'] = 0;
        }
        $data['staff']                = $this->staff_model->get('', $whereStaff);
        $data['articles']             = $this->knowledge_base_model->get();
        $data['complaint_replies']       = $this->complaints_model->get_complaint_replies($id);
        $data['bodyclass']            = 'top-tabs ticket single-ticket';
        $data['title']                = $data['complaint']->subject;
        $data['complaint']->complaint_notes = $this->misc_model->get_notes($id, 'complaint');
        $this->app_scripts->add('complaints-js', module_dir_url('complaints', 'js/complaints.js'), 'admin', ['app-js']);
        $this->load->view('complaints/admin/single', $data);
    }

    public function update_staff_replying($ticketId, $userId = '')
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $this->complaints_model->update_staff_replying($ticketId, $userId)]);
            die;
        }
    }


    public function check_staff_replying($ticketId)
    {
        if ($this->input->is_ajax_request()) {
            $ticket            = $this->complaints_model->get_staff_replying($ticketId);
            $isAnotherReplying = $ticket->staff_id_replying !== null && $ticket->staff_id_replying !== get_staff_user_id();
            echo json_encode([
                'is_other_staff_replying' => $isAnotherReplying,
                'message'                 => $isAnotherReplying ? _l('staff_is_currently_replying', get_staff_full_name($ticket->staff_id_replying)) : '',
            ]);
            die;
        }
    }

    public function get_consumer()
    {

        $consumer =  get_relation_data_complaint_module('consumer');

        if ($this->input->post('rel_id')) {
            $rel_id = $this->input->post('rel_id');
        } else {
            $rel_id = '';
        }

        $relOptions = init_relation_options_complaint($consumer, 'consumer', $rel_id);
        echo json_encode($relOptions);
    }

    public function update_single_complaint_settings()
    {
        if (!has_permission('complaints', '', 'edit') && !has_permission('complaints', '', 'create')) {
            access_denied('Complaints');
        }

        if ($this->input->post()) {
            $this->session->mark_as_flash('active_tab');
            $this->session->mark_as_flash('active_tab_settings');

            if ($this->input->post('merge_complaint_ids') !== 0) {
                $complaintsToMerge = explode(',', $this->input->post('merge_complaint_ids'));

                $alreadyMergedTickets = $this->complaints_model->get_already_merged_complaints($complaintsToMerge);
                if (count($alreadyMergedTickets) > 0) {
                    echo json_encode([
                        'success' => false,
                        'message' => _l('cannot_merge_tickets_with_ids', implode(',', $alreadyMergedTickets)),
                    ]);

                    die();
                }
            }

            $success = $this->complaints_model->update_single_complaint_settings($this->input->post());
            if ($success) {
                $this->session->set_flashdata('active_tab', true);
                $this->session->set_flashdata('active_tab_settings', true);
                if (get_option('staff_access_only_assigned_departments') == 1) {
                    $complaint = $this->complaints_model->get_complaint_by_id($this->input->post('complaintid'));
                    $this->load->model('departments_model');
                    $staff_departments = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                    if (!in_array($complaint->department, $staff_departments) && !is_admin()) {
                        set_alert('success', _l('complaint_settings_updated_successfully_and_reassigned', $complaint->department_name));
                        echo json_encode([
                            'success'               => $success,
                            'department_reassigned' => true,
                        ]);
                        die();
                    }
                }
                set_alert('success', _l('complaint_settings_updated_successfully'));
            }
            echo json_encode([
                'success' => $success,
            ]);
            die();
        }
    }

    public function delete($complaintid)
    {
        if (!has_permission('complaints', '', 'delete')) {
            access_denied('Complaints');
        }

        if (!$complaintid) {
            redirect(admin_url('complaints'));
        }

        $response = $this->complaints_model->delete($complaintid);

        if ($response == true) {
            set_alert('success', _l('deleted', _l('complaint')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('complaint_lowercase')));
        }

        if (strpos($_SERVER['HTTP_REFERER'], 'complaints/complaint') !== false) {
            redirect(admin_url('complaints'));
        } else {
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    public function delete_attachment($id)
    {
        if (is_admin() || (!is_admin() && get_option('allow_non_admin_staff_to_delete_ticket_attachments') == '1')) {
            if (get_option('staff_access_only_assigned_departments') == 1 && !is_admin()) {
                $attachment = $this->complaints_model->get_complaint_attachments($id);
                $ticket     = $this->complaints_model->get_complaint_by_id($attachment->ticketid);

                $this->load->model('departments_model');
                $staff_departments = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                if (!in_array($ticket->department, $staff_departments)) {
                    set_alert('danger', _l('ticket_access_by_department_denied'));
                    redirect(admin_url('access_denied'));
                }
            }

            $this->complaints_model->delete_complaint_attachment($id);
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

    public function edit_message()
    {
        if ($this->input->post()) {
            $data         = $this->input->post();
            $data['data'] = html_purify($this->input->post('data', false));

            if ($data['type'] == 'reply') {
                $this->db->where('id', $data['id']);
                $this->db->update(db_prefix() . 'complaints_replies', [
                    'message' => $data['data'],
                ]);
            } elseif ($data['type'] == 'complaint') {
                $this->db->where('complaintid', $data['id']);
                $this->db->update(db_prefix() . 'complaints', [
                    'message' => $data['data'],
                ]);
            }
            if ($this->db->affected_rows() > 0) {
                set_alert('success', _l('ticket_message_updated_successfully'));
            }
            redirect(admin_url('complaints/complaint/' . $data['main_complaint']));
        }
    }

    /* Add new task or update existing */
    public function task_complaint($id = '')
    {
        if (!has_permission('tasks', '', 'edit') && !has_permission('tasks', '', 'create')) {
            ajax_access_denied();
        }

        $this->load->model('tasks_model');
        $this->load->model('misc_model');


        $data = [];
        // FOr new task add directly from the projects milestones
        if ($this->input->get('milestone_id')) {
            $this->db->where('id', $this->input->get('milestone_id'));
            $milestone = $this->db->get(db_prefix() . 'milestones')->row();
            if ($milestone) {
                $data['_milestone_selected_data'] = [
                    'id'       => $milestone->id,
                    'due_date' => _d($milestone->due_date),
                ];
            }
        }
        if ($this->input->get('start_date')) {
            $data['start_date'] = $this->input->get('start_date');
        }
        if ($this->input->post()) {

            $data                = $this->input->post();
            $fromComplaintId = null;

            if (isset($data['complaint_to_task'])) {
                $fromComplaintId = $data['complaint_to_task'];
                unset($data['complaint_to_task']);
            }

            $data['description'] = html_purify($this->input->post('description', false));
            if ($id == '') {
                if (!has_permission('tasks', '', 'create')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }
                $id      = $this->tasks_model->add($data);
                $_id     = false;
                $success = false;
                $message = '';
                if ($id) {
                    $this->complaints_model->task_complaint_add($id, $fromComplaintId);
                    $success       = true;
                    $_id           = $id;
                    $message       = _l('added_successfully', _l('task'));
                    $uploadedFiles = handle_task_attachments_array($id);
                    if ($uploadedFiles && is_array($uploadedFiles)) {
                        foreach ($uploadedFiles as $file) {
                            $this->misc_model->add_attachment_to_database($id, 'task', [$file]);
                        }
                    }
                }
                echo json_encode([
                    'success' => $success,
                    'id'      => $_id,
                    'message' => $message,
                ]);
            } else {
                if (!has_permission('tasks', '', 'edit')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }
                $success = $this->tasks_model->update($data, $id);
                $message = '';
                if ($success) {
                    $message = _l('updated_successfully', _l('task'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                    'id'      => $id,
                ]);
            }
            die;
        }

        $data['milestones']         = [];
        $data['checklistTemplates'] = $this->tasks_model->get_checklist_templates();
        if ($id == '') {
            $title = _l('add_new', _l('task_lowercase'));
        } else {
            $data['task'] = $this->tasks_model->get($id);
            if ($data['task']->rel_type == 'project') {
                $data['milestones'] = $this->projects_model->get_milestones($data['task']->rel_id);
            }
            $title = _l('edit', _l('task_lowercase')) . ' ' . $data['task']->name;
        }

        $data['project_end_date_attrs'] = [];
        if ($this->input->get('rel_type') == 'project' && $this->input->get('rel_id') || ($id !== '' && $data['task']->rel_type == 'project')) {
            $project = $this->projects_model->get($id === '' ? $this->input->get('rel_id') : $data['task']->rel_id);

            if ($project->deadline) {
                $data['project_end_date_attrs'] = [
                    'data-date-end-date' => $project->deadline,
                ];
            }
        }
        $data['members'] = $this->staff_model->get();
        $data['id']      = $id;
        $data['title']   = $title;
        $this->load->view('complaints/admin/task_complaint', $data);
    }

    public function complaint_change_data()
    {
        if ($this->input->is_ajax_request()) {
            $contact_id = $this->input->post('contact_id');
            echo json_encode([
                'contact_data'          => get_relation_data_complaint_module('consumer', $contact_id),
                'customer_has_projects' => customer_has_projects(get_user_id_by_contact_id($contact_id)),
            ]);
        }
    }

    public function delete_complaint_reply($complaint_id, $reply_id)
    {
        if (!$reply_id) {
            redirect(admin_url('complaints'));
        }
        $response = $this->complaints_model->delete_complaint_reply($complaint_id, $reply_id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('complaint_reply')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('complaint_reply')));
        }
        redirect(admin_url('complaints/complaint/' . $complaint_id));
    }

    public function get_task($rel_id, $rel_type)
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('tasks_model');
            $this->load->library('ssp');

            $table = db_prefix() . 'tasks';
            $primaryKey = db_prefix() . 'tasks.id';

            $columns = array(

                array(
                    'db' => 'id', 'dt' => '0', 'field' => 'id', 'formatter' => function ($d, $row) {
                        return '<a href="' . admin_url('tasks/view/' . $row['id']) . '" onclick="init_task_modal(' . $row['id'] . '); return false;">' . $row['id'] . '</a>';
                    }

                ),
                array(
                    'db' => 'name', 'dt' => '1', 'field' => 'name',
                    'formatter' => function ($d, $row) {
                        $outputName = '';
                        $hasPermissionEdit   = has_permission('tasks', '', 'edit');
                        $hasPermissionDelete = has_permission('tasks', '', 'delete');

                        $not_finished_timer_by_current_staff = get_task_column($row['id'], 'not_finished_timer_by_current_staff')[0]->not_finished_timer_by_current_staff;
                        $is_assigned = get_task_column($row['id'], 'is_assigned')[0]->is_assigned;

                        if ($not_finished_timer_by_current_staff) {
                            $outputName .= '<span class="pull-left text-danger"><i class="fa-regular fa-clock fa-fw tw-mr-1"></i></span>';
                        }

                        $outputName .= '<a href="' . admin_url('tasks/view/' . $row['id']) . '" class="display-block main-tasks-table-href-name" onclick="init_task_modal(' . $row['id'] . '); return false;">' . $row['name'] . '</a>';

                        if ($row['recurring'] == 1) {
                            $outputName .= '<span class="label label-primary inline-block mtop4"> ' . _l('recurring_task') . '</span>';
                        }

                        $outputName .= '<div class="row-options">';

                        $class = 'text-success bold';
                        $style = '';

                        $tooltip = '';
                        if ($row['billed'] == 1 || !$is_assigned || $row['status'] == Tasks_model::STATUS_COMPLETE) {
                            $class = 'text-dark disabled';
                            $style = 'style="opacity:0.6;cursor: not-allowed;"';
                            if ($row['status'] == Tasks_model::STATUS_COMPLETE) {
                                $tooltip = ' data-toggle="tooltip" data-title="' . format_task_status($row['status'], false, true) . '"';
                            } elseif ($row['billed'] == 1) {
                                $tooltip = ' data-toggle="tooltip" data-title="' . _l('task_billed_cant_start_timer') . '"';
                            } elseif (!$is_assigned) {
                                $tooltip = ' data-toggle="tooltip" data-title="' . _l('task_start_timer_only_assignee') . '"';
                            }
                        }

                        if ($not_finished_timer_by_current_staff) {
                            $outputName .= '<a href="#" class="text-danger tasks-table-stop-timer" onclick="timer_action_complaint(this,' . $row['id'] . ',' . $not_finished_timer_by_current_staff  . '); return false;">' . _l('task_stop_timer') . '</a>';
                        } else {
                            $outputName .= '<span' . $tooltip . ' ' . $style . '>
                            <a href="#" class="' . $class . ' tasks-table-start-timer" onclick="timer_action_complaint(this,' . $row['id'] . '); return false;">' . _l('task_start_timer') . '</a>
                            </span>';
                        }

                        if ($hasPermissionEdit) {
                            $outputName .= '<span class="tw-text-neutral-300"> | </span><a href="#" onclick="edit_task_complaint(' . $row['id'] . '); return false">' . _l('edit') . '</a>';
                        }

                        if ($hasPermissionDelete) {
                            $outputName .= '<span class="tw-text-neutral-300"> | </span><a href="' . admin_url('tasks/delete_task/' . $row['id']) . '" class="text-danger _delete task-delete">' . _l('delete') . '</a>';
                        }
                        $outputName .= '</div>';

                        return $outputName;
                    }
                ),
                array('db' => 'status', 'dt' => '2', 'field' => 'status', 'formatter' => function ($d, $row) {

                    $current_user_is_creator = $row['addedfrom'] == get_staff_user_id() && $row['is_added_from_contact'] == 0 ? 1 : 0;
                    $current_user_is_assigned = get_task_column($row['id'], 'current_user_is_assigned')[0]->current_user_is_assigned;

                    $canChangeStatus = ($current_user_is_creator != '0' || $current_user_is_assigned || has_permission('tasks', '', 'edit'));
                    $status          = get_task_status_by_id($row['status']);
                    $outputStatus    = '';

                    $outputStatus .= '<span class="label" style="color:' . $status['color'] . ';border:1px solid ' . adjust_hex_brightness($status['color'], 0.4) . ';background: ' . adjust_hex_brightness($status['color'], 0.04) . ';" task-status-table="' . $row['status'] . '">';

                    $outputStatus .= $status['name'];

                    if ($canChangeStatus) {

                        $task_statuses = $this->tasks_model->get_statuses();

                        $outputStatus .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                        $outputStatus .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskStatus-' . $row['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        $outputStatus .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa-solid fa-chevron-down"></i></span>';
                        $outputStatus .= '</a>';

                        $outputStatus .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskStatus-' . $row['id'] . '">';
                        foreach ($task_statuses as $taskChangeStatus) {
                            if ($row['status'] != $taskChangeStatus['id']) {
                                $outputStatus .= '<li>
                                  <a href="#" onclick="task_mark_as_complaint(' . $taskChangeStatus['id'] . ',' . $row['id'] . '); return false;">
                                     ' . _l('task_mark_as', $taskChangeStatus['name']) . '
                                  </a>
                               </li>';
                            }
                        }
                        $outputStatus .= '</ul>';
                        $outputStatus .= '</div>';
                    }

                    $outputStatus .= '</span>';


                    return  $outputStatus;
                }),
                array('db' => 'startdate', 'dt' => '3', 'field' => 'startdate', 'formatter' => function ($d, $row) {
                    return  isNull($d);
                }),
                array('db' => 'duedate', 'dt' => '4', 'field' => 'duedate', 'formatter' => function ($d, $row) {
                    return  isNull($d);
                }),
                array(
                    'db' => 'id', 'dt' => '5', 'field' => 'id', 'formatter' => function ($d, $row) {
                        return  format_members_by_ids_and_names(get_task_column($d, 'assignees_ids')[0]->assignees_ids, get_task_column($d, 'assignees')[0]->assignees);
                    }
                ),
                array('db' => 'priority', 'dt' => '6', 'field' => 'priority', 'formatter' => function ($d, $row) {
                    return  render_tags(get_task_column($row['id'], 'tags')[0]->tags);
                }),
                array('db' => 'priority', 'dt' => '7', 'field' => 'priority', 'formatter' => function ($d, $row) {

                    $outputPriority = '<span style="color:' . task_priority_color($row['priority']) . ';" class="inline-block">' . task_priority($row['priority']);

                    if (has_permission('tasks', '', 'edit') && $row['status'] != Tasks_model::STATUS_COMPLETE) {
                        $outputPriority .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                        $outputPriority .= '<a href="#" style="font-size:14px;vertical-align:middle;" class="dropdown-toggle text-dark" id="tableTaskPriority-' . $row['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                        $outputPriority .= '<span data-toggle="tooltip" title="' . _l('task_single_priority') . '"><i class="fa-solid fa-chevron-down"></i></span>';
                        $outputPriority .= '</a>';

                        $tasksPriorities  = get_tasks_priorities();

                        $outputPriority .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tableTaskPriority-' . $row['id'] . '">';
                        foreach ($tasksPriorities as $priority) {
                            if ($row['priority'] != $priority['id']) {
                                $outputPriority .= '<li>
                                  <a href="#" onclick="task_change_priority_complaint(' . $priority['id'] . ',' . $row['id'] . '); return false;">
                                     ' . $priority['name'] . '
                                  </a>
                               </li>';
                            }
                        }
                        $outputPriority .= '</ul>';
                        $outputPriority .= '</div>';
                    }

                    $outputPriority .= '</span>';

                    return $outputPriority;
                }),

                array('db' => 'recurring', 'dt' => null, 'field' => 'recurring'),
                array('db' => 'billed', 'dt' => null, 'field' => 'billed'),
                array('db' => 'addedfrom', 'dt' => null, 'field' => 'addedfrom'),
                array('db' => 'is_added_from_contact', 'dt' => null, 'field' => 'is_added_from_contact')

            );

            $sql_details = array(
                'user' => $this->db->username,
                'pass' => $this->db->password,
                'db' => $this->db->database,
                'host' => $this->db->hostname
            );

            $extraWhere = ' rel_id="' . $this->db->escape_str($rel_id) . '" AND rel_type="' . $this->db->escape_str($rel_type) . '"';
            $data = SSP::simple($_POST, $sql_details, $table, $primaryKey, $columns, null, $extraWhere);
            $this->output->set_content_type('application/json', 'UTF-8')->set_output(json_encode($data));
        }
    }
}
