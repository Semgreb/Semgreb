<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APP_MODULES_PATH . 'complaints/libraries/mails/traits/ComplaintTemplate.php');

class Complaint_created_to_customer extends App_mail_template
{
    use ComplaintTemplate;

    protected $for = 'customer';

    protected $complaint;

    protected $email;

    protected $complaintid;

    protected $complaint_attachments;

    public $slug = 'new-complaint-opened-admin';

    public $rel_type = 'complaint';

    public function __construct($complaint, $email, $complaint_attachments, $cc)
    {
        parent::__construct();

        $this->complaint             = $complaint;
        $this->email              = $email;
        $this->complaintid           = $complaint->complaintid;
        $this->complaint_attachments = $complaint_attachments;
        $this->cc                 = $cc;
    }

    public function build()
    {

        $this->add_complaint_attachments();

        $this->to($this->email)
            ->set_rel_id($this->complaint->complaintid)
            ->set_merge_fields('client_merge_fields', $this->complaint->userid, $this->complaint->contactid)
            ->set_merge_fields('complaint_merge_fields', $this->slug, $this->complaint->complaintid);
    }
}
