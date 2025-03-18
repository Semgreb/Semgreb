<?php

use Cocur\Slugify\Bridge\ZF2\Module;

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Complaints
Description: Default module to admin complaints
Version: 1.0.1
Requires at least: 2.3.*
*/

define('COMPLAINTS_MODULE_NAME', 'complaints');
define('COMPLAINTS_ATTACHMENTS_FOLDER', FCPATH . 'uploads/complaints' . '/');

require_once(__DIR__ . '/hooks/hooks.php');

$CI = &get_instance();

hooks()->add_action('admin_init', 'complaints_init_menu_items');
hooks()->add_action('admin_init', 'complaints_permissions');
hooks()->add_action('after_email_templates', 'add_complaints_email_template');

/**
 * Load the module helper
 */
$CI->load->helper(COMPLAINTS_MODULE_NAME . '/complaints');
/**
 * Register activation module hook
 */
register_activation_hook(COMPLAINTS_MODULE_NAME, 'complaints_activation_hook');
register_merge_fields(COMPLAINTS_MODULE_NAME . '/merge_fields/complaint_merge_fields');
add_module_support(COMPLAINTS_MODULE_NAME, 'config');
/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(COMPLAINTS_MODULE_NAME, [COMPLAINTS_MODULE_NAME]);
