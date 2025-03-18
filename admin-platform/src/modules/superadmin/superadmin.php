<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Super Admin
Description: Super Admin module is a tool that allows you to control the CRM chracteristics.
Version: 1.1.0
Requires at least: 2.3.*
Author: Malla Agency
Author URI: https://malla.agency/
*/

define('SUPERADMIN_MODULE_NAME', 'superadmin');
define('SUPERADMIN_STAFF_ID', 1);

/**
* Hooks (Actions and Filters)
*/
hooks()->add_filter('setup_menu_no_disable_items', 'superadmin_setup_menu_no_disable_items');
hooks()->add_filter('help_menu_item_link', 'superadmin_help_menu_item_link');
//hooks()->add_filter('before_staff_status_change', 'superadmin_before_staff_status_change'); // BUG: Solo recibe el $status y no el $id

//hooks()->add_action('admin_init', 'superadmin_permissions');
hooks()->add_action('app_admin_footer', 'superadmin_load_js');
hooks()->add_action('app_customers_head', 'superadmin_app_customers_head');
hooks()->add_action('app_customers_footer', 'superadmin_app_customers_footer');

hooks()->add_action('admin_init', 'superadmin_module_init_menu_items');
hooks()->add_action('admin_init', 'superadmin_disable_access');

hooks()->add_action('before_create_staff_member', 'superadmin_number_users');
hooks()->add_filter('before_staff_status_change', 'superadmin_active_users', 10, 2);
hooks()->add_filter('staff_table_sql_where', 'superadmin_filter_user');

hooks()->add_filter('settings_tabs', 'superadmin_core_settings_tabs');



hooks()->add_action('admin_init', 'superadmin_disable_report');
hooks()->add_filter('project_tabs', 'superadmin_project_tabs');
hooks()->add_filter('quick_actions_links', 'superadmin_quick_actions_links');
hooks()->add_filter('customer_profile_tabs', 'superadmin_customer_profile_tabs');
hooks()->add_filter('get_dashboard_widgets', 'superadmin_dashboard_widgets');
hooks()->add_filter('after_dashboard', 'superadmin_dashboard_render');
hooks()->add_filter('sidebar_menu_items', 'superadmin_settings_menus');
hooks()->add_filter('setup_menu_items', 'superadmin_setup_menu_items');










/**
* Register language files, must be registered if the module is using languages
*/
register_language_files(SUPERADMIN_MODULE_NAME, [SUPERADMIN_MODULE_NAME]);

$CI = & get_instance();
$CI->load->helper(SUPERADMIN_MODULE_NAME . '/superadmin');

/**
* Register activation module hook
*/
register_activation_hook(SUPERADMIN_MODULE_NAME, 'superadmin_activation_hook');

function superadmin_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}


/**
 * Manage Settings TABs Menu Options
 */
function superadmin_core_settings_tabs($groups)
{
    $tabs_settings_disable = json_decode(get_option('superadmin_tabs_setting_disable'), true);
    if(is_array($tabs_settings_disable)) {
        foreach($tabs_settings_disable as $tab) {
            unset($groups[$tab]);
        }
    }
     
    return $groups;
}
/**
 * Customer Head
 */
function superadmin_app_customers_head()
{
    $has_access = get_option('superadmin_customers_acces');
    
    if (!$has_access) 
    {
        $viewuri = $_SERVER['REQUEST_URI'];
        $url1 = strpos($viewuri, 'authentication/login');
        $url2 = strpos($viewuri, 'clients/');
        $doit = false;

        if ($url1) {   
            $doit = true;
        }
        else if ($url2) {   
            $doit = true;
        }

        if($doit)
        {
            $CI = &get_instance();
            $CI->load->view('superadmin/customer_block');
            die();
        }
    }
}

/**
 * Customer Footer
 */
function superadmin_app_customers_footer()
{
    require 'modules/superadmin/assets/js/client_js.php';
}


/**
 * Change the help link URL
 */
function superadmin_help_menu_item_link($url)
{
    return get_option('superadmin_help_link');
}

/**
 * Remove items menu from no disable items options MENU SETUP Module
 */
function superadmin_setup_menu_no_disable_items($data)
{
    $menu_setup_is_active = get_status_modules_sa('menu_setup');

    if($menu_setup_is_active)
    {
        $data = array_diff($data, array('setup-menu-options','main-menu-options','modules'));
    }

    return $data;
}

/**
 * superadmin permissions
 * @return capabilities 
 */
// function superadmin_permissions()
// {
//     $capabilities = [];

//     $capabilities['capabilities'] = [
//             'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
//             'create' => _l('permission_create'),
//             'edit'   => _l('permission_edit'),
//             'delete' => _l('permission_delete'),
//     ];

//     register_staff_capabilities('superadmin', $capabilities, _l('superadmin'));
// }


/**
 * superadmin load js
 * @return library 
 */
function superadmin_load_js(){
    
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri, '/admin/settings') === false)) {   
        require 'modules/superadmin/assets/js/admin_settings_js.php';
    }
}

/**
 * Init goals module menu items in setup in admin_init hook
 * @return null
 */
function superadmin_module_init_menu_items()
{
    if (get_staff_user_id() == SUPERADMIN_STAFF_ID) 
    {
        $CI = &get_instance();
        if (has_permission('superadmin', '', 'view')) 
        {
            $CI->app_menu->add_setup_menu_item('superadmin', [
                'name'     => _l('superadmin'),
                'collapse' => true, // Indicates that this item will have submitems
                'position' => 60,
            ]);            

            $CI->app_menu->add_setup_children_item('superadmin', [
                'slug'     => 'superadmin-general',
                'name'     => _l('general'),
                'href'     => admin_url('superadmin'),
                'position' => 1,
            ]);

            $CI->app_menu->add_setup_children_item('superadmin', [
                'slug'     => 'hidden-access',
                'name'     => _l('hidden_access'),
                'href'     => admin_url('superadmin/hidden_access'),
                'position' => 5,
            ]);
        }  
    }
}

function superadmin_disable_access()
{
    $has_access = get_option('superadmin_system_acces');

    if(get_staff_user_id() == SUPERADMIN_STAFF_ID || $has_access)
        return;
    
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if ((strpos($viewuri, '/admin/superadmin') === false)) {   
        require 'modules/superadmin/views/maintenance.php';
        die();
    }    
}

/**
 * Administramos la cantidad de usuarios (Activos)
 */
function superadmin_number_users($data)
{
    $CI = &get_instance();
    if ($CI->input->post()) 
    {
        $number_staff_members = count($CI->staff_model->get('', ['active' => 1]));
        $number_user_available = get_option('superadmin_number_users');

        if($number_staff_members >= $number_user_available){
            set_alert('danger', _l('superadmin_number_user_cant_save'));   
            redirect(admin_url('staff/'));
            //die();
        }
    }  
    
    return $data;
}

function superadmin_active_users($status, $id)
{
    $CI = &get_instance();
    if ($status == "1") 
    {
        $number_staff_members = count($CI->staff_model->get('', ['active' => 1]));
        $number_user_available = get_option('superadmin_number_users');

        if($number_staff_members >= $number_user_available){
            set_alert('danger', _l('superadmin_number_user_cant_save'));  
            return false;
        }
    }  
    
    return $status;
}

function superadmin_filter_user($data)
{
    return ['AND staffid != ' . SUPERADMIN_STAFF_ID];
}


function superadmin_setup_menu_items($items){

    $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    if(is_array($settings_menus_disable)) {
        foreach($settings_menus_disable as $tab) {
            $menus = explode('=', $tab);        
            foreach($items as $key => $children)
            {
                $menu_setup_key = get_menu_setting()[$menus[1]]['menu_setup_key'] ?? '';

                if($key == $menus[1])
                {
                    unset($items[$key]);

                }elseif($key == $menu_setup_key)
                {
                   unset($items[$key]);
                }

            }
        }
    }
    
    return $items;
}

function superadmin_settings_menus($items)
{
    $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    if(is_array($settings_menus_disable)) {
        foreach($settings_menus_disable as $tab) {
            
            $menus = explode('=', $tab);
            $total_child_disabled = 0;
            
            if(isset($items[$menus[0]]["children"]) && count($items[$menus[0]]["children"]) > 0){
                $count_child = count($items[$menus[0]]["children"]);
                foreach($items[$menus[0]]["children"] as $key => $children)
                {
                    if($children['slug'] == $menus[1])
                    {
                        unset($items[$menus[0]]["children"][$key]);
                        $total_child_disabled++;
                    }
                }

                if($count_child == $total_child_disabled)
                {
                    unset($items[$menus[0]]);
                }

            }else{

                unset($items[$menus[0]]);
            
                if(!is_null(get_menu_setting()[$menus[0]]['report']))
                {
                       $list_report = get_menu_setting()[$menus[0]]['report'];

                       foreach($list_report as $report)
                       {
                         unset($items["reports"]["children"][$report]);
                       }
                }
            }
        }
    }

    return $items;
}

function superadmin_project_tabs($items)
{
    $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    if(is_array($settings_menus_disable)) {
        foreach($settings_menus_disable as $tab) {
            $menus = explode('=', $tab);
             foreach($items[$menus[0]]["children"] as $key => $children)
             {
                  $lugs =str_replace("project_","",$children['slug']);
                  if($lugs == $menus[1])
                  {
                    unset($items[$menus[0]]["children"][$key]);
                  }
             }
        }
    }

    return $items;
}

function superadmin_quick_actions_links($items)
{
    $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    
    if(is_array($settings_menus_disable)) 
    {
        foreach($settings_menus_disable as $tab) 
        {    
            $menus = explode('=', $tab);
            foreach($items as $key => $children)
            {
                  $tag = isset($children['permission']) ? $children['permission'] : strtolower($children['name']);
                  $quick_menu = get_menu_setting()[$menus[1]]['quick_menu'] ?? '';
                  if($tag == $menus[1])
                  {
                     unset($items[$key]);

                  }elseif($tag == $quick_menu)
                  {
                     unset($items[$key]);
                  }

            }
        }
    }

    return $items;
}

function superadmin_customer_profile_tabs($items)
{
    $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    
    if(is_array($settings_menus_disable)) 
    {
        foreach($settings_menus_disable as $tab) 
        {    
            $menus = explode('=', $tab);

             foreach($items as $key => $children)
             { 
                  if($key == $menus[1])
                  {
                     unset($items[$key]);
                  }
             }
        }
    }

    return $items;
}

function superadmin_disable_report()
{ 
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if (!(strpos($viewuri, '/admin/reports/sales') === false)) {   
        require 'modules/superadmin/assets/js/superadmin_reports_setting_js.php';        
    }    
}

function superadmin_dashboard_widgets($items)
{
    $count = 0; $count_widget = 0;
    $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
    
    $widget_stast_id = [
        'invoices' => 1
        ,'estimates' => 2
        ,'proposals' => 3
    ];

    if(is_array($settings_menus_disable)) 
    {
        $i = -1;
        $count_widget = 0;
        foreach($settings_menus_disable as $tab) 
        {    
            $item = explode('=', $tab);

            $children_hidden_complete = get_menu_setting()[$item[0]]['children_hidden_complete'] ?? [];
            $list_count = count($children_hidden_complete);
            if($list_count > 0)
            {
                if(in_array($item[1], $children_hidden_complete))
                {
                    $count_widget++;
                }

                if($count_widget == $list_count)
                {
                    $items = superadmin_remove_dashboard($item[0], $items);
                }
            }
            else
            {
                $items = superadmin_remove_dashboard($item[1], $items);
            }
        }
    }

    return $items;
}

function superadmin_remove_dashboard($key, $items)
{
    if(isset(get_menu_setting()[$key]['dashboard']))
    {
        foreach(get_menu_setting()[$key]['dashboard'] as $id_widget)
        {
            unset($items[$id_widget]);
        }
    }

    return $items;
}

function superadmin_dashboard_render()
{
    require 'modules/superadmin/assets/js/superadmin_dashboard_setting_js.php';      
}