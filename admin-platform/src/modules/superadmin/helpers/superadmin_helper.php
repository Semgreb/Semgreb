<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * get status modules wh
 * @param  string $module_name 
 * @return boolean             
 */
function get_status_modules_sa($module_name)
{    
    $CI = &get_instance();
   
    $sql = 'select * from '.db_prefix().'modules where module_name = "'.$module_name.'" AND active =1 ';
    $module = $CI->db->query($sql)->row();
    
    if($module){
        return true;
    }else{
        return false;
    }
}

function get_menu_setting()
{
    return [ 
        'sales' => [
            'dashboard' => [1,5, 0, 4]
           ,'report' => [0]
           ,'name_menu' => _l('als_sales')
           ,'children_hidden_complete' => [
                'invoices'
                ,'estimates'
                ,'proposals'
           ]
        ],
        'expenses' => [
             'dashboard' => null
            ,'report' => [1, 2]
            ,'name_menu' => _l('expenses')
        ],
        'estimates' => [
            'menu_setup_key' => 'finance'
        ],
        'purchase' => [
            'dashboard' => [13]
        ],
        'tasks' => [
            'dashboard' => [6,0]
           ,'report' => null
           ,'name_menu' => _l('task')
           ,'user_data_widget_tab' => "#home_tab_tasks"
       ],
       'knowledge-base' => [
           'dashboard' => null
           ,'report' => [6]
           ,'name_menu' => _l('als_kb')
           ,'quick_menu' => 'knowledge_base'
        ],
        'projects' => [
            'dashboard' => [8, 10, 0]
           ,'report' => [1, 2]
           ,'name_menu' => _l('projects')
           ,'user_data_widget_tab' => "#home_my_projects"
          
        ],
        'leads' => [
            'dashboard' => [7]
           ,'report' => [4]
           ,'name_menu' => _l('leads')
           ,'quick_menu' => 'is_staff_member'
        ],
        'support' => [
            'dashboard' => [12, 9]
           ,'report' => null
           ,'name_menu' => _l('support')
           ,'quick_menu' => 'ticket'
          ,'user_data_widget_tab' => "#home_tab_tickets"
        ],
        'estimate_request' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('estimate_request')
        ],
        'contracts' => [
            'dashboard' => [11]
           ,'report' => null
           ,'name_menu' => _l('contracts')
        ],
        'subscriptions' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('subscriptions')
        ],
        'purchase-contract' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('contracts')
        ],
        'purchase-debit-note' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('pur_debit_note')
        ],
        'purchase-invoices' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('invoices')
        ],
        'purchase-items' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('items')
        ],
        'purchase-order' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('purchase_order')
        ],
        'purchase-quotation' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('quotations')
        ],
        'purchase-request' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('purchase_request')
        ],
        'purchase-settings' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('setting')
        ],
        'purchase_reports' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('reports')
        ],
        'return-order' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('pur_return_orders')
        ],
        'vendors-items' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('vendor_item')
        ],    
        'activity-log' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('als_activity_log_submenu')
        ],      
        'bulk-pdf-exporter' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('bulk_pdf_exporter')
        ], 
        'media' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('als_media')
        ], 
        'ticket-pipe-log' => [
            'dashboard' => null
           ,'report' => null
           ,'name_menu' => _l('ticket_pipe_log')
        ], 
        'config_superadmin' => [
             'sales'
            ,'expenses'
            ,'estimate_request'
            ,'leads'
            ,'purchase'
            ,'knowledge-base'
            ,'subscriptions'
            ,'contracts'
            ,'projects'
            ,'tasks'
            ,'support'
            ,'utilities'
        ]
    ];
}