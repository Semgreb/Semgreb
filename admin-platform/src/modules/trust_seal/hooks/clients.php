<?php

function ts_app_client_head()
{
    require 'modules/trust_seal/assets/css/header_client.php';

    $viewuri = $_SERVER['REQUEST_URI'];
    $CI = &get_instance();

    if ($CI->uri->segment(1) == "") {
        if (has_contact_permission('certifications')) {
            redirect(site_url('trust_seal/clients/certifications'));
        } else {
            redirect(site_url("knowledge-base"));
        }
    }
}

function trust_seal_init_menu_clients_items()
{
    if (has_contact_permission('certifications')) {
        add_theme_menu_item('certifications',  [
            'name'     => _l('certifications'),
            'href'     => site_url('trust_seal/clients/certifications'),
            'position' => 10,
        ]);
    }

    if (has_contact_permission('audits')) {
        add_theme_menu_item('audits',  [
            'name'     => _l('audits'),
            'href'     => site_url('trust_seal/clients/audits'),
            'position' => 10,
        ]);
    }
}

function custom_field_profile_customer()
{
    custom_field_view_customer();

    require_once 'modules/trust_seal/assets/js/rename_fields.php';
}

//clients_authentication_constructor

function custom_validate_register($controllador)
{
    $CI = &get_instance();

    if (
        $CI->uri->segment(1) == "authentication" &&
        $CI->uri->segment(2) == "register"
    ) {
        if ($CI->input->post()) {

            $data = $CI->input->post();
            $CI->form_validation->set_rules('passwordhidden', _l('clients_register_password'),    array(
                'required',
                array(
                    'is_password_strong',
                    function ($password) {
                        $returnValue = true;
                        $CI = &get_instance();
                        $password = trim($password);
                        $regex_lowercase = '/[a-z]/';
                        $regex_uppercase = '/[A-Z]/';
                        $regex_number = '/[0-9]/';
                        $regex_special = '/[!@#$%^&*()\-_=+{};:,<.>§~]/';

                        if (empty($password)) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_required'));
                            $returnValue = FALSE;
                        }
                        if (preg_match_all($regex_lowercase, $password) < 1) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_lowercase'));
                            $returnValue = FALSE;
                        }
                        if (preg_match_all($regex_uppercase, $password) < 1) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_uppercase'));
                            $returnValue = FALSE;
                        }
                        if (preg_match_all($regex_number, $password) < 1) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_number'));
                            $returnValue = FALSE;
                        }
                        if (preg_match_all($regex_special, $password) < 1) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_special'));
                            $returnValue = FALSE;
                        }
                        if (strlen($password) < 5) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_password_leng'));
                            $returnValue = FALSE;
                        }
                        if (strlen($password) > 32) {
                            $CI->form_validation->set_message('is_password_strong', _l('regex_password_leng_max'));
                            $returnValue = FALSE;
                        }
                        return $returnValue;
                    }
                )
            ));

            // if ($CI->form_validation->run() === false) {
            //     $error_string = trim(strip_tags($CI->form_validation->error_string()));
            //     set_alert('danger',  $error_string);
            //     $redUrl = site_url('authentication/register');

            //     redirect($redUrl);
            // } else {
            //     $CI->form_validation->reset_validation();
            // }
        }
    }
}

function custom_field_profile_customer_rnc_validate()
{
    $CI = &get_instance();
    if (
        $CI->uri->segment(1) == "authentication" &&
        $CI->uri->segment(2) == "register"
    ) {
        custom_field_profile_customer_rnc();
    }

    if (
        $CI->uri->segment(1) == "clients" &&
        $CI->uri->segment(2) == "project" &&
        $CI->uri->segment(3) > 0
    ) {

        require 'modules/trust_seal/assets/css/header_client_sale_project.php';
    }
}

function custom_field_profile_customer_rnc()
{
    require_once 'modules/trust_seal/assets/js/rename_fields_only_rnc.php';
}

//after_customer_profile_company_phone
function custom_field_view_customer()
{
    $CI = &get_instance();
    $CI->load->view('trust_seal/custom_fields_client');
}
