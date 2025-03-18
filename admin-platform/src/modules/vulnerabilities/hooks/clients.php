<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */

defined('BASEPATH') or exit('No direct script access allowed');

function vulnerabilities__init_menu_items()
{
    if (has_contact_permission('support')) {
        // add_theme_menu_item('vulnerabilities',  [
        //     'name'     => _l('vulnerabilities'),
        //     'href'     => site_url('vulnerabilities/vulnerabilities/vulnerabilities_table'),
        //     'position' => 10,
        // ]);
    }
}

function vulnerabilities_init_menu_clients_items()
{
    if (has_contact_permission('vulnerabilities')) {
        add_theme_menu_item('vulnerabilities',  [
            'name'     => _l('vulnerabilities'),
            'href'     => site_url('vulnerabilities/vulnerabilitiesClients'),
            'position' => 10,
        ]);
    }
}

function analisis_detail_vulnerabilities_clients()
{
    //if(!has_client_permission('vulnerabilities')){
    $hook['post_controller_constructor'] = array(
        'class' => 'Clients_vulnerabilities',
        'function' => 'index',
        'filename' => 'clients.php',
        'filepath' => 'vulnerabilities/hooks',
    );

    // }
}

function load_scripts()
{
    $CI = &get_instance();
    $CI->load->view('vulnerabilities/scripts/functions_js');
}

//---------------------------------------------
// hooks()->add_action('after_customer_billing_and_shipping_tab', 'add_vulnerabilities_tab');

function add_vulnerabilities_tab($client)
{
?>
    <li role="presentation" class="">
        <a href="#vulnerabilities" aria-controls="vulnerabilities" role="tab" data-toggle="tab">
            <?php echo _l('vulnerabilities'); ?>
        </a>
    </li>
<?php
}

// hooks()->add_action('after_custom_profile_tab_content', 'load_vulnerabilities_data_and_view');

function load_vulnerabilities_data_and_view($client)
{
    $CI = &get_instance();
    $CI->load->model('vulnerabilities/vulnerabilities_model');
    $webSites =  $CI->vulnerabilities_model->getWebSites($client->userid);
    $urls = [];

    $data['webSites'] = "";

    if ($webSites != null) {
        foreach ($webSites as $url) {
            $urls[] = $url->web_site;
        }
        $urlString = implode(',', $urls);
        $data['webSites'] = $urlString;
    }

    $CI->load->view('vulnerabilities/clients/vulnerabilities_tab_content', $data);
}

// hooks()->add_filter('before_client_updated', 'ignore_web_sites');

function ignore_web_sites($data)
{
    unset($data['web_sites'], $data['client_id']);
    return $data;
}

// hooks()->add_action('client_updated', 'save_clients_web_sites');

function save_clients_web_sites($data)
{
    $CI = &get_instance();
    $CI->load->model('vulnerabilities/vulnerabilities_model');

    $web_sites = $_POST['web_sites'];
    $id_cliente = $_POST['client_id'];
    $urlArray = [];

    if (strpos($web_sites, ',') !== false) {
        $urlArray = explode(',', $web_sites);
        foreach ($urlArray as $url) {
            if ($CI->vulnerabilities_model->verify_url_exist($id_cliente, $url) == null) {
                $data = array(
                    'website' => [
                        'id_client' => $id_cliente,
                        'web_site' => $url,
                    ]
                );
                $CI->vulnerabilities_model->save_WebSites($data);
            }
        }
    } else {
        if ($CI->vulnerabilities_model->verify_url_exist($id_cliente, $web_sites) == null) {
            $data = array(
                'website' => [
                    'id_client' => $id_cliente,
                    'web_site' => $web_sites,
                ]
            );
            $CI->vulnerabilities_model->save_WebSites($data);
        }
    }
}
