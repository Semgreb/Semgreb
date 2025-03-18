<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Sello de Confianza
Description: Modulo enfocado en la administracion de sellos de confianza.
Version: 1.2.8
Requires at least: 3.*
*/

define('TRUSTSEAL_MODULE_NAME', 'trust_seal');
define('PATH_SEALS', 'uploads/seals/');

/**
 * Register language files, must be registered if the module is using languages
 */
require_once(__DIR__ . '/hooks/hooks.php');
require_once(__DIR__ . '/hooks/clients.php');


$CI = &get_instance();
$CI->load->helper(TRUSTSEAL_MODULE_NAME . '/trust_seal');

/**
 * Register activation module hook
 */
register_activation_hook(TRUSTSEAL_MODULE_NAME, 'ts_activation_hook');
/**
 * Hooks (Actions and Filters)
 */
hooks()->add_filter('theme_menu_items', 'ts_change_route_menu');
hooks()->add_filter('help_menu_item_link', 'ts_help_menu_item_link');
// hooks()->add_filter('customer_profile_tabs', 'ts_client_filtered_visible_tabs');
// hooks()->add_filter('get_contact_permissions', 'ts_contact_permissions');
hooks()->add_filter('project_tabs', 'ts_project_tabs');
hooks()->add_filter('before_update_contact', 'ts_permision_certifications_data');
hooks()->add_filter('before_create_contact', 'ts_permision_certifications_data');
hooks()->add_filter('before_client_added', 'ts_before_client_added_filter_data');
hooks()->add_filter('before_client_updated', 'ts_before_client_added_filter_data');
hooks()->add_filter('csrf_exclude_uris', 'ts_csrf_exclude_uris');
hooks()->add_filter('before_get_language_text', 'ts_change_article_for_guia');


hooks()->add_action('client_updated', 'ts_before_client_added_data');
hooks()->add_action('after_client_created', 'ts_before_client_added_data');

hooks()->add_action('contact_created', 'ts_permision_certifications_data_create');
hooks()->add_action('contact_deleted', 'ts_permision_certifications_data_delete');
hooks()->add_action('after_email_templates', 'add_trust_seal_email_template');
hooks()->add_action('app_admin_head', 'ts_app_admin_head');
hooks()->add_action('app_admin_footer', 'ts_app_admin_footer');
hooks()->add_action('app_customers_head', 'ts_app_client_head');
// hooks()->add_action('customers_content_container_start', 'if_have_completed_audit_or_certifications_assign');
register_language_files(TRUSTSEAL_MODULE_NAME, [TRUSTSEAL_MODULE_NAME]);
register_merge_fields(TRUSTSEAL_MODULE_NAME . '/merge_fields/trust_seal_merge_fields');
hooks()->add_action('admin_init', 'trust_seal_init_menu_items');
hooks()->add_action('admin_init', 'trust_seal_permissions');
hooks()->add_action('clients_init', 'trust_seal_init_menu_clients_items');
hooks()->add_action('app_customers_head', 'custom_field_profile_customer_rnc_validate');
hooks()->add_action('after_customer_profile_company_phone', 'custom_field_profile_customer');
hooks()->add_action('before_cron_run', 'run_reminder_client_certifications');
hooks()->add_action('after_contact_modal_content_loaded', 'permision_certifications');
hooks()->add_action('clients_authentication_constructor', 'custom_validate_register');
hooks()->add_filter('after_get_language_text', 'ts_after_get_language_text');







// /*
// |--------------------------------------------------------------------------
// | Config route Allowable Domains
// |--------------------------------------------------------------------------
// |
// | Used if $config['check_cors'] is set to TRUE and $config['allow_any_cors_domain']
// | is set to FALSE. Set all the allowable domains within the array
// |
// | e.g. $config['allowed_origins'] = ['http://www.example.com', 'https://spa.example.com']
// |
//  */

// $route2['dt'] = 'trust_seal/api/article_group_list';

// $CI->router->routes = array_merge($CI->router->routes, $route2);
