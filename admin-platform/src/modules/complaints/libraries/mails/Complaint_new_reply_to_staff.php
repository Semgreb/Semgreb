<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APP_MODULES_PATH . 'complaints/libraries/mails/traits/ComplaintTemplate.php');

class Complaint_new_reply_to_staff extends App_mail_template
{
    use ComplaintTemplate;

    protected $for = 'staff';

    protected $complaint;

    protected $staff;

    protected $complaintid;

    protected $complaint_attachments;

    public $slug = 'complaint-reply-to-admin';

    public $rel_type = 'complaint';

    public function __construct($complaint, $staff, $complaint_attachments)
    {
        parent::__construct();

        $this->complaint             = $complaint;
        $this->staff              = $staff;
        $this->complaintid           = $complaint->complaintid;
        $this->complaint_attachments = $complaint_attachments;
    }

    public function build()
    {

        $this->add_complaint_attachments();

        $this->to($this->staff['email'])
            ->set_rel_id($this->complaint->complaintid)
            ->set_staff_id($this->staff['staffid'])
            ->set_merge_fields('client_merge_fields', $this->complaint->userid, $this->complaint->contactid)
            ->set_merge_fields('complaint_merge_fields', $this->slug, $this->complaint->complaintid);
    }
}
