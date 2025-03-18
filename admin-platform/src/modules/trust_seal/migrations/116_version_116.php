<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_116 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'certifications';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;");
    }
}
