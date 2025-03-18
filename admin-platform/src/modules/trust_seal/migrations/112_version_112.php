<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_112 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'seals';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE `logo` `logo_active` text NULL;");
        $CI->db->query("ALTER TABLE `$seals_tablename` ADD `logo_inactive` text NULL AFTER `logo`;");
     }
}
