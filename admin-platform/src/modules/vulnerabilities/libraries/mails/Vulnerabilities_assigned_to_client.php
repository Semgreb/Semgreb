<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APP_MODULES_PATH . 'trust_seal/libraries/mails/traits/TrustSealTemplate.php');

class Vulnerabilities_assigned_to_client extends App_mail_template
{
    use TrustSealTemplate;

    protected $for = 'staff';

    protected $client_email;

    protected $staffid;

    protected $analisiid;

    protected $client_id;

    protected $contact_id;

    public $slug = 'vulnerabilities-assigned-to-client';

    public $rel_type = 'vulnerabilities';

    public function __construct($client_email, $staffid, $analisiid, $client_id, $contact_id)
    {
        parent::__construct();

        $this->client_email = $client_email;
        $this->staffid      = $staffid;
        $this->analisiid      = $analisiid;
        $this->client_id    = $client_id;
        $this->contact_id   = $contact_id;
    }

    public function build()
    {

        $this->to($this->client_email)
            ->set_rel_id($this->analisiid)
            ->set_staff_id($this->staffid)
            ->set_merge_fields('client_merge_fields', $this->client_id, $this->contact_id)
            ->set_merge_fields('vulnerabilities_merge_fields', $this->slug, $this->analisiid);
    }
}
