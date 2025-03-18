<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Complaint_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Complaint ID',
                'key'       => '{complaint_id}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint URL',
                'key'       => '{complaint_url}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint Public URL',
                'key'       => '{complaint_public_url}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Department',
                'key'       => '{complaint_department}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Department Email',
                'key'       => '{complaint_department_email}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Date Opened',
                'key'       => '{complaint_date}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint Subject',
                'key'       => '{complaint_subject}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint Message',
                'key'       => '{complaint_message}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint Status',
                'key'       => '{complaint_status}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint Priority',
                'key'       => '{complaint_priority}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Complaint Service',
                'key'       => '{complaint_service}',
                'available' => [
                    'complaint',
                ],
            ],
            [
                'name'      => 'Project name',
                'key'       => '{project_name}',
                'available' => [
                    'complaint',
                ],
            ],
        ];
    }

    /**
     * Merge fields for tickets
     * @param  string $template  template name, used to identify url
     * @param  mixed $ticket_id ticket id
     * @param  mixed $reply_id  reply id
     * @return array
     */
    public function format($template, $complaint_id, $reply_id = '')
    {
        $fields = [];

        $this->ci->db->where('complaintid', $complaint_id);
        $complaint = $this->ci->db->get(db_prefix() . 'complaints')->row();

        if (!$complaint) {
            return $fields;
        }

        // Replace contact firstname with the ticket name in case the ticket is not linked to any contact.
        // eq email or form imported.
        if (!empty($complaint->name)) {
            $fields['{contact_firstname}'] = $complaint->name;
        }

        if (!empty($complaint->email)) {
            $fields['{contact_email}'] = $complaint->email;
        }

        $fields['{complaint_priority}'] = '';
        $fields['{complaint_service}']  = '';

        $this->ci->db->where('departmentid', $complaint->department);
        $department = $this->ci->db->get(db_prefix() . 'departments')->row();

        if ($department) {
            $fields['{complaint_department}']       = $department->name;
            $fields['{complaint_department_email}'] = $department->email;
        }

        $languageChanged = false;
        if (
            !is_client_logged_in()
            && !empty($complaint->userid)
            && isset($GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS'])
            && !$GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_staff_id() // email to client
        ) {
            load_client_language($complaint->userid);
            $languageChanged = true;
        } else {
            if (isset($GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS'])) {
                $sending_to_staff_id = $GLOBALS['SENDING_EMAIL_TEMPLATE_CLASS']->get_staff_id();
                if ($sending_to_staff_id) {
                    load_admin_language($sending_to_staff_id);
                    $languageChanged = true;
                }
            }
        }

        $fields['{complaint_status}']   = complaint_status_translate($complaint->status);
        $fields['{complaint_priority}'] = ticket_priority_translate($complaint->priority);

        $custom_fields = get_custom_fields('complaints');
        foreach ($custom_fields as $field) {
            $fields['{' . $field['slug'] . '}'] = get_custom_field_value($complaint_id, $field['id'], 'complaints');
        }

        if (!is_client_logged_in() && $languageChanged) {
            load_admin_language();
        } elseif (is_client_logged_in() && $languageChanged) {
            load_client_language();
        }

        $this->ci->db->where('serviceid', $complaint->service);
        $service = $this->ci->db->get(db_prefix() . 'complaints_services')->row();

        if ($service) {
            $fields['{complaint_service}'] = $service->name;
        }

        $fields['{complaint_id}'] = $complaint_id;

        $customerTemplates = [
            'new-complaint-opened-admin',
            'complaint-reply',
            'complaint-autoresponse',
            'auto-close-complaint',
        ];

        if (in_array($template, $customerTemplates)) {
            $fields['{complaint_url}'] = site_url('complaints/clients_complaints/complaint/' . $complaint_id);
        } else {
            $fields['{complaint_url}'] = admin_url('complaints/complaint/' . $complaint_id);
        }

        $reply = false;
        if ($template == 'complaint-reply-to-admin' || $template == 'complaint-reply') {
            $this->ci->db->where('complaintid', $complaint_id);
            $this->ci->db->limit(1);
            $this->ci->db->order_by('date', 'desc');
            $reply                      = $this->ci->db->get(db_prefix() . 'complaints_replies')->row();
            $fields['{complaint_message}'] = $reply->message;
        } else {
            $fields['{complaint_message}'] = $complaint->message;
        }

        $fields['{complaint_date}']       = _dt($complaint->date);
        $fields['{complaint_subject}']    = $complaint->subject;
        $fields['{complaint_public_url}'] = get_complaint_public_url($complaint);
        //$fields['{project_name}']      = get_project_name_by_id($complaint->project_id);


        return hooks()->apply_filters('complaint_merge_fields', $fields, [
            'id'       => $complaint_id,
            'reply_id' => $reply_id,
            'template' => $template,
            'complaint'   => $complaint,
            'reply'    => $reply,
        ]);
    }
}
