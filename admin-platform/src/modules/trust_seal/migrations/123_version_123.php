<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_123 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;
        $seals_tablename = db_prefix() . 'extra_fields_clients';
        $CI->db->query("ALTER TABLE `$seals_tablename` ADD  `client_razon_social` text NULL AFTER `logo`;");
    }
}
