<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Consumers
Description: Default module to admin consumers
Version: 1.0.0
Requires at least: 2.3.*
*/
define('CONSUMERS_MODULE_NAME', 'consumers');

require_once(__DIR__ . '/hooks/hooks.php');

$CI = &get_instance();
hooks()->add_action('admin_init', 'consumers_init_menu_items');
hooks()->add_action('admin_init', 'consumers_permissions');
/**
 * Register activation module hook
 */
register_activation_hook(CONSUMERS_MODULE_NAME, 'consumers_activation_hook');
/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(CONSUMERS_MODULE_NAME, [CONSUMERS_MODULE_NAME]);
