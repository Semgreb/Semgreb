<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APP_MODULES_PATH . 'complaints/libraries/mails/traits/ComplaintTemplate.php');

class Complaint_assigned_to_staff extends App_mail_template
{
    use ComplaintTemplate;

    protected $for = 'staff';

    protected $staff_email;

    protected $staffid;

    protected $complaintid;

    protected $client_id;

    protected $contact_id;

    public $slug = 'complaint-assigned-to-admin';

    public $rel_type = 'complaint';

    public function __construct($staff_email, $staffid, $complaintid, $client_id, $contact_id)
    {
        parent::__construct();

        $this->staff_email = $staff_email;
        $this->staffid     = $staffid;
        $this->complaintid    = $complaintid;
        $this->client_id   = $client_id;
        $this->contact_id  = $contact_id;
    }

    public function build()
    {

        $this->to($this->staff_email)
            ->set_rel_id($this->complaintid)
            ->set_staff_id($this->staffid)
            ->set_merge_fields('client_merge_fields', $this->client_id, $this->contact_id)
            ->set_merge_fields('complaint_merge_fields', $this->slug, $this->complaintid);
    }
}
