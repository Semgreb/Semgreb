<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Trust_seal extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('trust_seal_model');
        // $this->load->model('exams_model');
        // $this->load->model('seals_model');

        if (!is_admin()) {
            access_denied('Trust Seal');
        }
    }
   
}
