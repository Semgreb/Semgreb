<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APP_MODULES_PATH . 'complaints/libraries/mails/traits/ComplaintTemplate.php');

class Complaint_created_to_staff extends App_mail_template
{
    use ComplaintTemplate;

    protected $for = 'staff';

    protected $complaintid;

    protected $client_id;

    protected $contact_id;

    protected $staff;

    protected $complaint_attachments;

    public $slug = 'new-complaint-created-staff';

    public $rel_type = 'complaint';

    public function __construct($complaintid, $client_id, $contact_id, $staff, $complaint_attachments)
    {
        parent::__construct();

        $this->complaintid   = $complaintid;
        $this->client_id  = $client_id;
        $this->contact_id = $contact_id;
        $this->staff      = $staff;

        $this->complaint_attachments = $complaint_attachments;
    }

    public function build()
    {

        $this->add_complaint_attachments();

        $this->to($this->staff['email'])
            ->set_rel_id($this->complaintid)
            ->set_staff_id($this->staff['staffid'])
            ->set_merge_fields('client_merge_fields', $this->client_id, $this->contact_id)
            ->set_merge_fields('complaint_merge_fields', $this->slug, $this->complaintid);
    }
}
