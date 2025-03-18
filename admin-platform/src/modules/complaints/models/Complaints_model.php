<?php

use modules\complaints\services\MergeComplaints;

defined('BASEPATH') or exit('No direct script access allowed');

class Complaints_model extends App_Model
{
    private $piping = false;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param  integer (optional)
     * @return object
     * Get single goal
     */
    public function get($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('complaintid', $id);

            return $this->db->get(db_prefix() . 'complaints')->row();
        }

        // if ($exclude_notified == true) {
        //     $this->db->where('notified', 0);
        // }

        return $this->db->get(db_prefix() . 'complaints')->result_array();
    }

    public function get_all_complaints()
    {


        $this->db->order_by('end_date', 'asc');
        $complaints = $this->db->get(db_prefix() . 'complaints')->result_array();

        // foreach ($goals as $key => $val) {
        //     $goal = get_goal_type($val['goal_type']);

        //     if (!$goal || $goal && isset($goal['dashboard']) && $goal['dashboard'] === false) {
        //         unset($goals[$key]);
        //         continue;
        //     }

        //     $goals[$key]['achievement']    = $this->calculate_goal_achievement($val['id']);
        //     $goals[$key]['goal_type_name'] = format_goal_type($val['goal_type']);
        // }

        return array_values($complaints);
    }

    // Ticket services
    public function get_service($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('serviceid', $id);

            return $this->db->get(db_prefix() . 'complaints_services')->row();
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'complaints_services')->result_array();
    }


    /**
     * Add new ticket to database
     * @param mixed $data  ticket $_POST data
     * @param mixed $admin If admin adding the ticket passed staff id
     */
    public function add($data, $admin = null, $pipe_attachments = false)
    {
        if ($admin !== null) {
            $data['admin'] = $admin;
            unset($data['ticket_client_search']);
        }

        if (isset($data['assigned']) && $data['assigned'] == '') {
            $data['assigned'] = 0;
        }

        // if (isset($data['project_id']) && $data['project_id'] == '') {
        //     $data['project_id'] = 0;
        // }
        // if ($admin == null) {
        // if (isset($data['email'])) {
        //     $data['userid']    = 0;
        //     $data['contactid'] = 0;
        // } else {
        //     // Opened from customer portal otherwise is passed from pipe or admin area
        //     if (!isset($data['userid']) && !isset($data['contactid'])) {
        //         $data['userid']    = get_client_user_id();
        //         $data['contactid'] = get_contact_user_id();
        //     }
        // }
        $data['status'] = 2;
        // }

        if (isset($data['custom_fields'])) {
            $custom_fields = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        // CC is only from admin area
        $cc = '';
        if (isset($data['cc'])) {
            $cc = $data['cc'];
            unset($data['cc']);
        }

        $data['date']      = date('Y-m-d H:i:s');
        $data['status']    = 1;
        $data['message']   = trim($data['message']);
        $data['subject']   = trim($data['subject']);
        if ($this->piping == true) {
            $data['message'] = preg_replace('/\v+/u', '<br>', $data['message']);
        }

        // Admin can have html
        if ($admin == null && hooks()->apply_filters('ticket_message_without_html_for_non_admin', true)) {
            $data['message'] = _strip_tags($data['message']);
            $data['subject'] = _strip_tags($data['subject']);
            $data['message'] = nl2br_save_html($data['message']);
        }

        if (!isset($data['userid'])) {
            $data['userid'] = 0;
        }
        if (isset($data['priority']) && $data['priority'] == '' || !isset($data['priority'])) {
            $data['priority'] = 0;
        }

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        $data['message'] = remove_emojis($data['message']);
        $data            = hooks()->apply_filters('before_ticket_created', $data, $admin);

        $this->db->insert(db_prefix() . 'complaints', $data);
        $complaintid = $this->db->insert_id();
        if ($complaintid) {
            handle_tags_save($tags, $complaintid, 'complaint');

            if (isset($custom_fields)) {
                handle_custom_fields_post($complaintid, $custom_fields);
            }

            if (isset($data['assigned']) && $data['assigned'] != 0) {
                if ($data['assigned'] != get_staff_user_id()) {
                    $notified = add_notification([
                        'description'     => 'not_ticket_assigned_to_you',
                        'touserid'        => $data['assigned'],
                        'fromcompany'     => 1,
                        'fromuserid'      => 0,
                        'link'            => 'complaints/complaint/' . $complaintid,
                        'additional_data' => serialize([
                            $data['subject'],
                        ]),
                    ]);

                    if ($notified) {
                        pusher_trigger_notification([$data['assigned']]);
                    }

                    send_mail_template('complaint_assigned_to_staff', 'complaints', get_staff($data['assigned'])->email, $data['assigned'], $complaintid, $data['userid'], $data['contactid']);
                }
            }
            if ($pipe_attachments != false) {

                $this->process_pipe_attachments($pipe_attachments, $complaintid);
            } else {

                $attachments = handle_complaint_attachments($complaintid);

                if ($attachments) {
                    $this->insert_complaint_attachments_to_database($attachments, $complaintid);
                }
            }

            $_attachments = $this->get_complaint_attachments($complaintid);


            $isContact = false;

            $email = isset($data['email']) ? $data['email'] : "";

            $template = 'complaint_created_to_customer';
            if ($admin == null) {
                $template      = 'complaint_autoresponse';
                $notifiedUsers = [];


                // $staffToNotify = $this->getStaffMembersForTicketNotification($data['department'], $data['assigned'] ?? 0);


                // foreach ($staffToNotify as $member) {
                //     send_mail_template('complaint_created_to_staff', $complaintid, $data['userid'], $data['contactid'], $member, $_attachments);
                //     if (get_option('receive_notification_on_new_ticket') == 1) {
                //         $notified = add_notification([
                //             'description'     => 'not_new_ticket_created',
                //             'touserid'        => $member['staffid'],
                //             'fromcompany'     => 1,
                //             'fromuserid'      => 0,
                //             'link'            => 'complaints/complaint/' . $complaintid,
                //             'additional_data' => serialize([
                //                 $data['subject'],
                //             ]),
                //         ]);
                //         if ($notified) {
                //             $notifiedUsers[] = $member['staffid'];
                //         }
                //     }
                // }

                // pusher_trigger_notification($notifiedUsers);
            } else {
                if ($cc) {
                    $this->db->where('complaintid', $complaintid);
                    $this->db->update('complaints', ['cc' => is_array($cc) ? implode(',', $cc) : $cc]);
                }
            }

            $sendEmail = true;

            if ($isContact && total_rows(db_prefix() . 'consumers', ['complaints_emails' => 1, 'id' => $data['contactid']]) == 0) {
                $sendEmail = false;
            }

            if ($sendEmail) {
                $complaint = $this->get_complaint_by_id($complaintid);

                send_mail_template($template, "complaints", $complaint, $email, $admin == null ? [] : $_attachments, $cc);
            }

            // hooks()->do_action('ticket_created', $complaintid);
            log_activity('New Complaint Created [ID: ' . $complaintid . ']');

            return $complaintid;
        }

        return false;
    }

    private function process_pipe_attachments($attachments, $complaint_id, $reply_id = '')
    {
        if (!empty($attachments)) {
            $ticket_attachments = [];
            $allowed_extensions = array_map(function ($ext) {
                return strtolower(trim($ext));
            }, explode(',', get_option('ticket_attachments_file_extensions')));

            $path = FCPATH . 'uploads/complaint_attachments' . '/' . $complaint_id . '/';

            foreach ($attachments as $attachment) {
                $filename      = $attachment['filename'];
                $filenameparts = explode('.', $filename);
                $extension     = end($filenameparts);
                $extension     = strtolower($extension);
                if (in_array('.' . $extension, $allowed_extensions)) {
                    $filename = implode(array_slice($filenameparts, 0, 0 - 1));
                    $filename = trim(preg_replace('/[^a-zA-Z0-9-_ ]/', '', $filename));

                    if (!$filename) {
                        $filename = 'attachment';
                    }

                    if (!file_exists($path)) {
                        mkdir($path, 0755);
                        $fp = fopen($path . 'index.html', 'w');
                        fclose($fp);
                    }

                    $filename = unique_filename($path, $filename . '.' . $extension);
                    file_put_contents($path . $filename, $attachment['data']);

                    array_push($ticket_attachments, [
                        'file_name' => $filename,
                        'filetype'  => get_mime_by_extension($filename),
                    ]);
                }
            }

            $this->insert_complaint_attachments_to_database($ticket_attachments, $complaint_id, $reply_id);
        }
    }

    /**
     * Insert ticket attachments to database
     * @param  array  $attachments array of attachment
     * @param  mixed  $ticketid
     * @param  boolean $replyid If is from reply
     */
    public function insert_complaint_attachments_to_database($attachments, $ticketid, $replyid = false)
    {
        foreach ($attachments as $attachment) {
            $attachment['complaintid']  = $ticketid;
            $attachment['dateadded'] = date('Y-m-d H:i:s');
            if ($replyid !== false && is_int($replyid)) {
                $attachment['replyid'] = $replyid;
            }
            $this->db->insert(db_prefix() . 'complaints_attachments', $attachment);
        }
    }


    /**
     * Get ticket by id and all data
     * @param  mixed  $id     ticket id
     * @param  mixed $userid Optional - Tickets from USER ID
     * @return object
     */
    public function get_complaint_by_id($id, $userid = '')
    {
        $this->db->select('*, ' . db_prefix() . 'complaints.userid, ' . db_prefix() . 'complaints.name as from_name, ' . db_prefix() . 'complaints.email as complaint_email, ' . db_prefix() . 'departments.name as department_name, ' . db_prefix() . 'tickets_priorities.name as priority_name, statuscolor, ' . db_prefix() . 'complaints.admin, ' . db_prefix() . 'services.name as service_name, service, ' . db_prefix() . 'complaints_status.name as status_name, ' . db_prefix() . 'complaints.complaintid, ' . db_prefix() . 'consumers.firstname as user_firstname, ' . db_prefix() . 'consumers.lastname as user_lastname, ' . db_prefix() . 'staff.firstname as staff_firstname, ' . db_prefix() . 'staff.lastname as staff_lastname, lastreply, message, ' . db_prefix() . 'complaints.status, subject, department, priority, ' . db_prefix() . 'consumers.email, adminread, clientread, date');
        $this->db->from(db_prefix() . 'complaints');
        $this->db->join(db_prefix() . 'departments', db_prefix() . 'departments.departmentid = ' . db_prefix() . 'complaints.department', 'left');
        $this->db->join(db_prefix() . 'complaints_status', db_prefix() . 'complaints_status.complaintsstatusid = ' . db_prefix() . 'complaints.status', 'left');
        $this->db->join(db_prefix() . 'services', db_prefix() . 'services.serviceid = ' . db_prefix() . 'complaints.service', 'left');
        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'complaints.userid', 'left');
        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'complaints.admin', 'left');
        $this->db->join(db_prefix() . 'consumers', db_prefix() . 'consumers.consumerid = ' . db_prefix() . 'complaints.contactid', 'left');
        $this->db->join(db_prefix() . 'tickets_priorities', db_prefix() . 'tickets_priorities.priorityid = ' . db_prefix() . 'complaints.priority', 'left');

        if (strlen($id) === 32) {
            $this->db->where(db_prefix() . 'complaints.complaintkey', $id);
        } else {
            $this->db->where(db_prefix() . 'complaints.complaintid', $id);
        }

        if (is_numeric($userid)) {
            $this->db->where(db_prefix() . 'complaints.userid', $userid);
        }

        $complaint = $this->db->get()->row();
        if ($complaint) {
            $complaint->submitter = $complaint->contactid != 0 ?
                ($complaint->user_firstname . ' ' . $complaint->user_lastname) :
                $complaint->from_name;

            if (!($complaint->admin == null || $complaint->admin == 0)) {
                $complaint->opened_by = $complaint->staff_firstname . ' ' . $complaint->staff_lastname;
            }

            $complaint->attachments = $this->get_complaint_attachments($complaint->complaintid);
        }


        return $complaint;
    }

    /**
     * Get ticket attachments from database
     * @param  mixed $id      ticket id
     * @param  mixed $replyid Optional - reply id if is from from reply
     * @return array
     */
    public function get_complaint_attachments($id, $replyid = '')
    {
        $this->db->where('complaintid', $id);
        $this->db->where('replyid', is_numeric($replyid) ? $replyid : null);

        return $this->db->get('complaints_attachments')->result_array();
    }

    public function get_complaint_replies($id)
    {
        $ticket_replies_order = get_option('ticket_replies_order');
        // backward compatibility for the action hook
        $ticket_replies_order = hooks()->apply_filters('ticket_replies_order', $ticket_replies_order);

        $this->db->select(db_prefix() . 'complaints_replies.id,' . db_prefix() . 'complaints_replies.name as from_name,' . db_prefix() . 'complaints_replies.email as reply_email, ' . db_prefix() . 'complaints_replies.admin, ' . db_prefix() . 'complaints_replies.userid,' . db_prefix() . 'staff.firstname as staff_firstname, ' . db_prefix() . 'staff.lastname as staff_lastname,' . db_prefix() . 'consumers.firstname as user_firstname,' . db_prefix() . 'consumers.lastname as user_lastname,message,date,contactid');


        $this->db->from(db_prefix() . 'complaints_replies');

        $this->db->join(db_prefix() . 'clients', db_prefix() . 'clients.userid = ' . db_prefix() . 'complaints_replies.userid', 'left');

        $this->db->join(db_prefix() . 'staff', db_prefix() . 'staff.staffid = ' . db_prefix() . 'complaints_replies.admin', 'left');

        $this->db->join(db_prefix() . 'consumers', db_prefix() . 'consumers.consumerid = ' . db_prefix() . 'complaints_replies.contactid', 'left');

        $this->db->where('complaintid', $id);

        $this->db->order_by('date', $ticket_replies_order);

        $replies = $this->db->get()->result_array();

        $i  = 0;


        foreach ($replies as $reply) {
            if ($reply['admin'] !== null || $reply['admin'] != 0) {
                // staff reply
                $replies[$i]['submitter'] = $reply['staff_firstname'] . ' ' . $reply['staff_lastname'];
            } else {

                if ($reply['contactid'] != 0) {
                    $replies[$i]['submitter'] = $reply['user_firstname'] . ' ' . $reply['user_lastname'];
                } else {
                    $replies[$i]['submitter'] = $reply['from_name'];
                }
            }
            unset($replies[$i]['staff_firstname']);
            unset($replies[$i]['staff_lastname']);
            unset($replies[$i]['user_firstname']);
            unset($replies[$i]['user_lastname']);
            $replies[$i]['attachments'] = $this->get_complaint_attachments($id, $reply['id']);
            $i++;
        }

        return $replies;
    }

    public function get_complaint_status($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('complaintsstatusid', $id);

            return $this->db->get(db_prefix() . 'complaints_status')->row();
        }
        $this->db->order_by('statusorder', 'asc');

        return $this->db->get(db_prefix() . 'complaints_status')->result_array();
    }

    public function add_service($data)
    {
        $this->db->insert(db_prefix() . 'complaints_services', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Complaint Service Added [ID: ' . $insert_id . '.' . $data['name'] . ']');
        }

        return $insert_id;
    }

    public function update_service($data, $id)
    {
        $this->db->where('serviceid', $id);
        $this->db->update(db_prefix() . 'complaints_services', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Complaint Service Updated [ID: ' . $id . ' Name: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }


    public function delete_service($id)
    {
        if (is_reference_in_table('service', db_prefix() . 'complaints', $id)) {
            return [
                'referenced' => true,
            ];
        }
        $this->db->where('serviceid', $id);
        $this->db->delete(db_prefix() . 'complaints_services');
        if ($this->db->affected_rows() > 0) {
            log_activity('Complaints Service Deleted [ID: ' . $id . ']');

            return true;
        }

        return false;
    }


    public function get_merged_complaints_by_primary_id($primaryTicketId)
    {
        return $this->db->where('merged_complaint_id', $primaryTicketId)->get(db_prefix() . 'complaints')->result_array();
    }

    public function get_predefined_reply($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'complaints_predefined_replies')->row();
        }

        return $this->db->get(db_prefix() . 'complaints_predefined_replies')->result_array();
    }

    public function add_reply($data, $id, $admin = null, $pipe_attachments = false)
    {
        if (isset($data['assign_to_current_user'])) {
            $assigned = get_staff_user_id();
            unset($data['assign_to_current_user']);
        }

        $unsetters = [
            'note_description',
            'department',
            'priority',
            'subject',
            'assigned',
            'project_id',
            'service',
            'status_top',
            'attachments',
            'DataTables_Table_0_length',
            'DataTables_Table_1_length',
            'custom_fields',
        ];

        foreach ($unsetters as $unset) {
            if (isset($data[$unset])) {
                unset($data[$unset]);
            }
        }

        if ($admin !== null) {
            $data['admin'] = $admin;
            $status        = $data['status'];
        } else {
            $status = 1;
        }

        if (isset($data['status'])) {
            unset($data['status']);
        }

        $cc = '';
        if (isset($data['cc'])) {
            $cc = $data['cc'];
            unset($data['cc']);
        }

        // if complaint is merged
        $complaint           = $this->get($id);
        $data['complaintid'] = ($complaint && $complaint->merged_complaint_id != null) ? $complaint->merged_complaint_id : $id;
        $data['date']     = date('Y-m-d H:i:s');
        $data['message']  = trim($data['message']);

        if ($this->piping == true) {
            $data['message'] = preg_replace('/\v+/u', '<br>', $data['message']);
        }

        // admin can have html
        if ($admin == null && hooks()->apply_filters('ticket_message_without_html_for_non_admin', true)) {
            $data['message'] = _strip_tags($data['message']);
            $data['message'] = nl2br_save_html($data['message']);
        }

        if (!isset($data['userid'])) {
            $data['userid'] = 0;
        }

        $data['message'] = remove_emojis($data['message']);
        $data            = hooks()->apply_filters('before_ticket_reply_add', $data, $id, $admin);

        $this->db->insert(db_prefix() . 'complaints_replies', $data);

        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            /**
             * When a ticket is in status "In progress" and the customer reply to the ticket
             * it changes the status to "Open" which is not normal.
             *
             * The ticket should keep the status "In progress"
             */
            $this->db->select('status');
            $this->db->where('complaintid', $id);
            $old_ticket_status = $this->db->get(db_prefix() . 'complaints')->row()->status;

            $newStatus = hooks()->apply_filters(
                'ticket_reply_status',
                ($old_ticket_status == 2 && $admin == null ? $old_ticket_status : $status),
                ['ticket_id' => $id, 'reply_id' => $insert_id, 'admin' => $admin, 'old_status' => $old_ticket_status]
            );

            if (isset($assigned)) {
                $this->db->where('complaintid', $id);
                $this->db->update(db_prefix() . 'complaints', [
                    'assigned' => $assigned,
                ]);
            }

            if ($pipe_attachments != false) {
                $this->process_pipe_attachments($pipe_attachments, $id, $insert_id);
            } else {
                $attachments = handle_complaint_attachments($id);
                if ($attachments) {
                    $this->insert_complaint_attachments_to_database($attachments, $id, $insert_id);
                }
            }

            $_attachments = $this->get_complaint_attachments($id, $insert_id);

            log_activity('New Ticket Reply [ReplyID: ' . $insert_id . ']');

            $this->db->where('complaintid', $id);
            $this->db->update(db_prefix() . 'complaints', [
                'lastreply'  => date('Y-m-d H:i:s'),
                'status'     => $newStatus,
                'adminread'  => 0,
                'clientread' => 0,
            ]);

            if ($old_ticket_status != $newStatus) {
                hooks()->do_action('after_ticket_status_changed', [
                    'id'     => $id,
                    'status' => $newStatus,
                ]);
            }

            $complaint    = $this->get_complaint_by_id($id);
            $userid    = $complaint->userid;
            $isContact = false;
            $this->load->model('consumers/consumers_model');
            if ($complaint->userid != 0 && $complaint->contactid != 0) {
                $email     = $this->consumers_model->get($complaint->contactid)->email;
                $isContact = true;
            } else {
                $email = $complaint->email;
            }
            if ($admin == null) {
                $this->load->model('departments_model');
                $this->load->model('staff_model');

                $notifiedUsers = [];
                $staff         = $this->getStaffMembersForTicketNotification($complaint->department, $complaint->assigned);
                foreach ($staff as $staff_key => $member) {
                    send_mail_template('complaint_new_reply_to_staff', 'complaints', $complaint, $member, $_attachments);
                    if (get_option('receive_notification_on_new_ticket_replies') == 1) {
                        $notified = add_notification([
                            'description'     => 'not_new_ticket_reply',
                            'touserid'        => $member['staffid'],
                            'fromcompany'     => 1,
                            'fromuserid'      => 0,
                            'link'            => 'tickets/ticket/' . $id,
                            'additional_data' => serialize([
                                $complaint->subject,
                            ]),
                        ]);
                        if ($notified) {
                            array_push($notifiedUsers, $member['staffid']);
                        }
                    }
                }
                pusher_trigger_notification($notifiedUsers);
            } else {
                $this->update_staff_replying($id);

                $total_staff_replies = total_rows(db_prefix() . 'complaints_replies', ['admin is NOT NULL', 'complaintid' => $complaint->complaintid]);
                if (
                    $complaint->assigned == 0 &&
                    get_option('automatically_assign_ticket_to_first_staff_responding') == '1' &&
                    $total_staff_replies == 1
                ) {
                    $this->db->where('complaintid', $id);
                    $this->db->update(db_prefix() . 'complaints', ['assigned' => $admin]);
                }

                $sendEmail = true;
                if ($isContact && total_rows(db_prefix() . 'consumers', ['complaints_emails' => 1, 'consumerid' => $complaint->contactid]) == 0) {
                    $sendEmail = false;
                }
                if ($sendEmail) {
                    send_mail_template('complaint_new_reply_to_customer', 'complaints', $complaint, $email, $_attachments, $cc);
                }
            }

            if ($cc) {
                // imported reply
                if (is_array($cc)) {
                    if ($complaint->cc) {
                        $currentCC = explode(',', $complaint->cc);
                        $cc        = array_unique([$cc, $currentCC]);
                    }
                    $cc = implode(',', $cc);
                }
                $this->db->where('complaintid', $id);
                $this->db->update('complaints', ['cc' => $cc]);
            }
            hooks()->do_action('after_ticket_reply_added', [
                'data'    => $data,
                'id'      => $id,
                'admin'   => $admin,
                'replyid' => $insert_id,
            ]);

            return $insert_id;
        }

        return false;
    }

    public function update_staff_replying($complaintId, $userId = '')
    {
        $complaint = $this->get($complaintId);

        if ($userId === '') {
            return $this->db->where('complaintid', $complaintId)
                ->set('staff_id_replying', null)
                ->update(db_prefix() . 'complaints');
        }

        if ($complaint->staff_id_replying !== $userId && !is_null($complaint->staff_id_replying)) {
            return false;
        }

        if ($complaint->staff_id_replying === $userId) {
            return true;
        }

        return $this->db->where('complaintid', $complaintId)
            ->set('staff_id_replying', $userId)
            ->update(db_prefix() . 'complaints');
    }

    private function getStaffMembersForTicketNotification($department, $assignedStaff = 0)
    {
        $this->load->model('departments_model');
        $this->load->model('staff_model');

        $staffToNotify = [];
        if ($assignedStaff != 0 && get_option('staff_related_ticket_notification_to_assignee_only') == 1) {
            $member = $this->staff_model->get($assignedStaff, ['active' => 1]);
            if ($member) {
                $staffToNotify[] = (array) $member;
            }
        } else {
            $staff = $this->staff_model->get('', ['active' => 1]);
            foreach ($staff as $member) {
                if (get_option('access_tickets_to_none_staff_members') == 0 && !is_staff_member($member['staffid'])) {
                    continue;
                }
                $staff_departments = $this->departments_model->get_staff_departments($member['staffid'], true);
                if (in_array($department, $staff_departments)) {
                    $staffToNotify[] = $member;
                }
            }
        }

        return $staffToNotify;
    }

    /**
     * Update ticket data / admin use
     * @param  mixed $data ticket $_POST data
     * @return boolean
     */
    public function update_single_complaint_settings($data)
    {
        $affectedRows = 0;
        $data         = hooks()->apply_filters('before_ticket_settings_updated', $data);
        $complaintBeforeUpdate = $this->get_complaint_by_id($data['complaintid']);

        if (isset($data['merge_complaint_ids'])) {
            $complaints = explode(',', $data['merge_complaint_ids']);
            if ($this->merge($data['complaintid'], $complaintBeforeUpdate->status, $complaints)) {
                $affectedRows++;
            }
            unset($data['merge_complaint_ids']);
        }

        if (isset($data['custom_fields']) && count($data['custom_fields']) > 0) {
            if (handle_custom_fields_post($data['ticketid'], $data['custom_fields'])) {
                $affectedRows++;
            }
            unset($data['custom_fields']);
        }

        $tags = '';
        if (isset($data['tags'])) {
            $tags = $data['tags'];
            unset($data['tags']);
        }

        if (handle_tags_save($tags, $data['complaintid'], 'complaint')) {
            $affectedRows++;
        }

        if (isset($data['priority']) && $data['priority'] == '' || !isset($data['priority'])) {
            $data['priority'] = 0;
        }

        if ($data['assigned'] == '') {
            $data['assigned'] = 0;
        }

        // if (isset($data['project_id']) && $data['project_id'] == '') {
        //     $data['project_id'] = 0;
        // }

        if (isset($data['contactid']) && $data['contactid'] != '') {
            $data['name']  = null;
            $data['email'] = null;
        }

        $this->db->where('complaintid', $data['complaintid']);
        $this->db->update(db_prefix() . 'complaints', $data);
        if ($this->db->affected_rows() > 0) {
            hooks()->do_action(
                'ticket_settings_updated',
                [
                    'ticket_id'       => $data['complaintid'],
                    'original_ticket' => $complaintBeforeUpdate,
                    'data'            => $data,
                ]
            );
            $affectedRows++;
        }

        $sendAssignedEmail = false;

        $current_assigned = $complaintBeforeUpdate->assigned;
        if ($current_assigned != 0) {
            if ($current_assigned != $data['assigned']) {
                if ($data['assigned'] != 0 && $data['assigned'] != get_staff_user_id()) {
                    $sendAssignedEmail = true;
                    $notified          = add_notification([
                        'description'     => 'not_ticket_reassigned_to_you',
                        'touserid'        => $data['assigned'],
                        'fromcompany'     => 1,
                        'fromuserid'      => 0,
                        'link'            => 'complaints/complaint/' . $data['complaintid'],
                        'additional_data' => serialize([
                            $data['subject'],
                        ]),
                    ]);
                    if ($notified) {
                        pusher_trigger_notification([$data['assigned']]);
                    }
                }
            }
        } else {
            if ($data['assigned'] != 0 && $data['assigned'] != get_staff_user_id()) {
                $sendAssignedEmail = true;
                $notified          = add_notification([
                    'description'     => 'not_ticket_assigned_to_you',
                    'touserid'        => $data['assigned'],
                    'fromcompany'     => 1,
                    'fromuserid'      => 0,
                    'link'            => 'complaints/complaint/' . $data['complaintid'],
                    'additional_data' => serialize([
                        $data['subject'],
                    ]),
                ]);

                if ($notified) {
                    pusher_trigger_notification([$data['assigned']]);
                }
            }
        }
        if ($sendAssignedEmail === true) {
            $this->db->where('staffid', $data['assigned']);
            $assignedEmail = $this->db->get(db_prefix() . 'staff')->row()->email;
            send_mail_template('complaint_assigned_to_staff', 'complaints', $assignedEmail, $data['assigned'], $data['complaintid'], $data['userid'], $data['contactid']);
        }
        if ($affectedRows > 0) {
            log_activity('Complaint Updated [ID: ' . $data['complaintid'] . ']');

            return true;
        }

        return false;
    }

    /**
     * Check whether the given ticketid is already merged into another primary ticket
     *
     * @param  int  $id
     *
     * @return boolean
     */
    public function is_merged($id)
    {
        return total_rows('complaints', "complaintid={$id} and merged_complaint_id IS NOT NULL") > 0;
    }

    /**
     * @param $primary_ticket_id
     * @param $status
     * @param  array  $ids
     *
     * @return bool
     */
    public function merge($primary_complaint_id, $status, array $ids)
    {
        if ($this->is_merged($primary_complaint_id)) {
            return false;
        }

        if (($index = array_search($primary_complaint_id, $ids)) !== false) {
            unset($ids[$index]);
        }

        if (count($ids) == 0) {
            return false;
        }


        return (new MergeComplaints($primary_complaint_id, $ids))
            ->markPrimaryTicketAs($status)
            ->merge();
    }

    /**
     * @param array $tickets id's of tickets to check
     * @return array
     */
    public function get_already_merged_complaints($complaints)
    {
        if (count($complaints) === 0) {
            return [];
        }

        $alreadyMerged = [];
        foreach ($complaints as $complaintId) {
            if ($this->is_merged((int) $complaintId)) {
                $alreadyMerged[] = $complaintId;
            }
        }

        return $alreadyMerged;
    }

    public function get_staff_replying($complaintId)
    {
        $this->db->select('complaintid,staff_id_replying');
        $this->db->where('complaintid', $complaintId);

        return $this->db->get(db_prefix() . 'complaints')->row();
    }

    public function delete_complaint_attachment($id)
    {
        $deleted = false;
        $this->db->where('id', $id);
        $attachment = $this->db->get(db_prefix() . 'complaints_attachments')->row();
        if ($attachment) {
            if (unlink(COMPLAINTS_ATTACHMENTS_FOLDER . $attachment->complaintid . '/' . $attachment->file_name)) {
                $this->db->where('id', $attachment->id);
                $this->db->delete(db_prefix() . 'complaints_attachments');
                $deleted = true;
            }
            // Check if no attachments left, so we can delete the folder also
            $other_attachments = list_files(COMPLAINTS_ATTACHMENTS_FOLDER . $attachment->complaintid);
            if (count($other_attachments) == 0) {
                delete_dir(COMPLAINTS_ATTACHMENTS_FOLDER . $attachment->complaintid);
            }
        }

        return $deleted;
    }

    public function delete($complaintid)
    {
        $affectedRows = 0;
        hooks()->do_action('before_ticket_deleted', $complaintid);
        // final delete ticket
        $this->db->where('complaintid', $complaintid);
        $this->db->delete(db_prefix() . 'complaints');
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;
        }
        if ($this->db->affected_rows() > 0) {
            $affectedRows++;

            $this->db->where('merged_complaint_id', $complaintid);
            $this->db->set('merged_complaint_id', null);
            $this->db->update(db_prefix() . 'complaints');

            $this->db->where('complaintid', $complaintid);
            $attachments = $this->db->get(db_prefix() . 'complaints_attachments')->result_array();
            if (count($attachments) > 0) {
                if (is_dir(COMPLAINTS_ATTACHMENTS_FOLDER . $complaintid)) {
                    if (delete_dir(COMPLAINTS_ATTACHMENTS_FOLDER . $complaintid)) {
                        foreach ($attachments as $attachment) {
                            $this->db->where('id', $attachment['id']);
                            $this->db->delete(db_prefix() . 'complaints_attachments');
                            if ($this->db->affected_rows() > 0) {
                                $affectedRows++;
                            }
                        }
                    }
                }
            }

            $this->db->where('relid', $complaintid);
            $this->db->where('fieldto', 'complaints');
            $this->db->delete(db_prefix() . 'customfieldsvalues');

            // Delete replies
            $this->db->where('complaintid', $complaintid);
            $this->db->delete(db_prefix() . 'complaints_replies');

            $this->db->where('rel_id', $complaintid);
            $this->db->where('rel_type', 'complaint');
            $this->db->delete(db_prefix() . 'notes');

            $this->db->where('rel_id', $complaintid);
            $this->db->where('rel_type', 'complaint');
            $this->db->delete(db_prefix() . 'taggables');

            $this->db->where('rel_type', 'complaint');
            $this->db->where('rel_id', $complaintid);
            $this->db->delete(db_prefix() . 'reminders');

            // Get related tasks
            $this->db->where('rel_type', 'complaint');
            $this->db->where('rel_id', $complaintid);
            $tasks = $this->db->get(db_prefix() . 'tasks')->result_array();
            foreach ($tasks as $task) {
                $this->tasks_model->delete_task($task['id']);
            }
        }
        if ($affectedRows > 0) {
            log_activity('Complaints Deleted [ID: ' . $complaintid . ']');
            hooks()->do_action('after_ticket_deleted', $complaintid);
            return true;
        }

        return false;
    }


    public function task_complaint_add($insert_id, $fromComplaintId)
    {
        if ($fromComplaintId !== null) {
            $ticket_attachments = $this->db->query('SELECT * FROM ' . db_prefix() . 'complaints_attachments WHERE complaintid=' . $this->db->escape_str($fromComplaintId) . ' OR (complaintid=' . $this->db->escape_str($fromComplaintId) . ' AND replyid IN (SELECT id FROM ' . db_prefix() . 'complaints_replies WHERE complaintid=' . $this->db->escape_str($fromComplaintId) . '))')->result_array();

            if (count($ticket_attachments) > 0) {
                $task_path = get_upload_path_by_type('task') . $insert_id . '/';
                _maybe_create_upload_path($task_path);

                foreach ($ticket_attachments as $ticket_attachment) {
                    $path = COMPLAINTS_ATTACHMENTS_FOLDER . $fromComplaintId . '/' . $ticket_attachment['file_name'];
                    if (file_exists($path)) {
                        $f = fopen($path, FOPEN_READ);
                        if ($f) {
                            $filename = unique_filename($task_path, $ticket_attachment['file_name']);
                            $fpt      = fopen($task_path . $filename, 'w');
                            if ($fpt && fwrite($fpt, stream_get_contents($f))) {
                                $this->db->insert(db_prefix() . 'files', [
                                    'rel_id'         => $insert_id,
                                    'rel_type'       => 'task',
                                    'file_name'      => $filename,
                                    'filetype'       => $ticket_attachment['filetype'],
                                    'staffid'        => get_staff_user_id(),
                                    'dateadded'      => date('Y-m-d H:i:s'),
                                    'attachment_key' => app_generate_hash(),
                                ]);
                            }
                            if ($fpt) {
                                fclose($fpt);
                            }
                            fclose($f);
                        }
                    }
                }
            }
        }
    }

    public function delete_complaint_reply($complaint_id, $reply_id)
    {
        hooks()->do_action('before_delete_complaint_reply', ['complaint_id' => $complaint_id, 'reply_id' => $reply_id]);

        $this->db->where('id', $reply_id);
        $this->db->delete(db_prefix() . 'complaints_replies');

        if ($this->db->affected_rows() > 0) {
            // Get the reply attachments by passing the reply_id to get_ticket_attachments method
            $attachments = $this->get_complaint_attachments($complaint_id, $reply_id);
            if (count($attachments) > 0) {
                foreach ($attachments as $attachment) {
                    $this->delete_complaint_attachment($attachment['id']);
                }
            }

            return true;
        }

        return false;
    }


    /**
     * @return array
     * Used in home dashboard page
     * Displays weekly ticket openings statistics (chart)
     */
    public function get_weekly_complaints_opening_statistics()
    {
        $departments_ids = [];
        if (!is_admin()) {
            if (get_option('staff_access_only_assigned_departments') == 1) {
                $this->load->model('departments_model');
                $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                $departments_ids      = [];
                if (count($staff_deparments_ids) == 0) {
                    $departments = $this->departments_model->get();
                    foreach ($departments as $department) {
                        array_push($departments_ids, $department['departmentid']);
                    }
                } else {
                    $departments_ids = $staff_deparments_ids;
                }
            }
        }

        $chart = [
            'labels'   => get_weekdays(),
            'datasets' => [
                [
                    'label'           => _l('home_weekend_ticket_opening_statistics'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor'     => '#c53da9',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                ],
            ],
        ];

        $monday = new DateTime(date('Y-m-d', strtotime('monday this week')));
        $sunday = new DateTime(date('Y-m-d', strtotime('sunday this week')));

        $thisWeekDays = get_weekdays_between_dates($monday, $sunday);

        $byDepartments = count($departments_ids) > 0;
        if (isset($thisWeekDays[1])) {
            $i = 0;
            foreach ($thisWeekDays[1] as $weekDate) {
                $this->db->like('DATE(date)', $weekDate, 'after');
                $this->db->where(db_prefix() . 'complaints.merged_complaint_id IS NULL', null, false);
                if ($byDepartments) {
                    $this->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                }
                $chart['datasets'][0]['data'][$i] = $this->db->count_all_results(db_prefix() . 'complaints');

                $i++;
            }
        }

        return $chart;
    }

    public function get_complaints_assignes_disctinct()
    {
        return $this->db->query('SELECT DISTINCT(assigned) as assigned FROM ' . db_prefix() . 'complaints WHERE assigned != 0 AND merged_complaint_id IS NULL')->result_array();
    }
}
