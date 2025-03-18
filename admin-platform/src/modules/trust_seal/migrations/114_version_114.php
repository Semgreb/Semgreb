<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_114 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'certifications';
        $CI->db->query("ALTER TABLE `$seals_tablename` DROP `name`;");
        $CI->db->query("ALTER TABLE `$seals_tablename` ADD  `certificationkey` varchar(32) NOT NULL AFTER `date`;");
    }
}
