<?php

/**
 * This file is part of "NCF Module"
 * Copyright (c) 2020 "Malla Agency"
 * All rights reserved
 *
 * @author Malla Agency <info@malla.agency>
 * @version 1.0
 */ 

ini_set('display_errors', 1);

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * NCF module, is responsible for assigning a specific internal nomenclature to 
 * each invoice based on an NCF created and assigned to a specific client.
 *
 * @export
 * @class Superadmin
*/

class Superadmin extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('superadmin_model');

        if (!is_admin()) {
            access_denied('superadmin');
        }
    }

    public function index()
    {
        if ($this->input->post()) 
        {
            $post_data = $this->input->post();
            $success = $this->superadmin_model->update($post_data);

            //print_r($post_data['settings']['superadmin_tabs_setting_disable']);

            if ($success > 0) {
                set_alert('success', _l('settings_updated'));
                redirect(admin_url('superadmin/'));
            }
            else {
                set_alert('danger', _l('superadmin_tabs_setting_cant_save'));    
                return;
            }      
        }

        $data['title'] = _l('superadmin');

        $tabs = $this->app_tabs->get_settings_tabs();
        $data['settings_tabs'] = array_keys($tabs);
        
        $data['settings_tabs_disable'] = json_decode(get_option('superadmin_tabs_setting_disable'), true);

        $data['menus_settings'] = get_menu_setting()['config_superadmin']; //Only show sales menu options
        $data['list_menus'] = $this->app_menu->get_sidebar_menu_items();
        $settings_menus_disable = json_decode(get_option('superadmin_menus_setting_disable'), true);
        $data['settings_menus_disable'] = $settings_menus_disable;
        
        if(is_array($settings_menus_disable))
        {
            foreach($settings_menus_disable as $disabled_menu)
            {
                $item = explode('=', $disabled_menu);

                $data['list_menus'][$item[0]]['children'][] = [
                        'slug' => $item[1]
                        ,'name' => get_menu_setting()[$item[1]]['name_menu'] ?? _l($item[1])
                ];
                
            }
        }

        $this->load->view('general', $data);
    }

    
    public function hidden_access()
    {
        $data['title'] = _l('hidden_access');
        $this->load->view('hidden_access', $data);
    }
    
    public function maintenance()
    {
        $data['title'] = _l('maintenance');
        $this->load->view('maintenance', $data);
    }



    

}
