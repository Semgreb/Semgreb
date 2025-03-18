<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_117 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'audits';

        $CI->db->query("ALTER TABLE `$seals_tablename` ADD  `auto_asignar` tinyint(1) NOT NULL DEFAULT 0 AFTER `qualification`;");
    }
}
