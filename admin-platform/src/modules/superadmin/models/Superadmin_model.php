<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Superadmin_model extends App_Model 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Update all settings
     * @param  array $data all settings
     * @return integer
     */
    public function update($data)
    {

        try {

            // print_r($data);
            // print_r($data['settings']['superadmin_help_link']);

            if (isset($data['settings']))
            {
                update_option('superadmin_system_acces', $data['settings']['superadmin_system_acces']);
                update_option('superadmin_customers_acces', $data['settings']['superadmin_customers_acces']);
                update_option('superadmin_system_info_acces', $data['settings']['superadmin_system_info_acces']);
                update_option('superadmin_number_users', $data['settings']['superadmin_number_users']);
                update_option('superadmin_help_link', $data['settings']['superadmin_help_link']);
                update_option('superadmin_knowledgebase_link', $data['settings']['superadmin_knowledgebase_link']);

                $settings_tabs = $data['settings']['superadmin_tabs_setting_disable'];

                $enable_tabs = array_keys($this->app_tabs->get_settings_tabs());
                $sa_disable_tabs = json_decode(get_option('superadmin_tabs_setting_disable'), true);
                $all_tabs = array_merge($enable_tabs,$sa_disable_tabs);

                $disable_tabs = array_diff($all_tabs, $settings_tabs);
                //print_r($disable_tabs);
                update_option('superadmin_tabs_setting_disable', json_encode($disable_tabs));  
                
                $settings_menus = $data['settings']['superadmin_menus_setting_disable'] ?? null;
                update_option('superadmin_menus_setting_disable', json_encode($settings_menus));  

            }
            return 1;

        } catch (Exception $e) {
            return 0;
        }
    }
}