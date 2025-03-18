<?php

defined('BASEPATH') or exit('No direct script access allowed');

trait ComplaintTemplate
{
    protected function _subject()
    {
        /**
         * IMPORTANT
         * Do not change/remove this line, this is used for email piping so the software can recognize the ticket id.
         */
        if (substr($this->template->subject, 0, 10) != '[Complaint ID') {
            return $this->template->subject . ' [Complaint ID: ' . $this->complaintid . ']';
        }

        return parent::_subject();
    }

    protected function _reply_to()
    {
        $default = parent::_reply_to();

        // Should be loaded?
        if (!class_exists('complaints_model')) {
            $this->ci->load->model('complaints_model');
        }

        $ticket = $this->get_complaint_for_mail();

        if (!empty($ticket->department_email) && valid_email($ticket->department_email)) {
            return $ticket->department_email;
        }

        return $default;
    }

    protected function _from()
    {
        $default = parent::_from();

        $complaint = $this->get_complaint_for_mail();

        if (
            !empty($complaint->department_email)
            && $complaint->dept_email_from_header == 1
            && valid_email($complaint->department_email)
        ) {
            return [
                'fromname'  => $default['fromname'],
                'fromemail' => $complaint->department_email,
            ];
        }

        return $default;
    }

    private function get_complaint_for_mail()
    {
        $this->ci->db->select(db_prefix() . 'departments.email as department_email, email_from_header as dept_email_from_header')
            ->where('complaintid', $this->complaintid)
            ->join(db_prefix() . 'departments', db_prefix() . 'departments.departmentid=' . db_prefix() . 'complaints.department', 'left');

        return $this->ci->db->get(db_prefix() . 'complaints')->row();
    }

    private function add_complaint_attachments()
    {
        foreach ($this->complaint_attachments as $attachment) {
            $this->add_attachment([
                'attachment' => COMPLAINTS_ATTACHMENTS_FOLDER . $this->complaintid . '/' . $attachment['file_name'],
                'filename'   => $attachment['file_name'],
                'type'       => $attachment['filetype'],
                'read'       => true,
            ]);
        }
    }
}
