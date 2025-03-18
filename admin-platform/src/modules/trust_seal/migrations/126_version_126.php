<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_126 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'audits';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
        $seals_tablename = db_prefix() . 'certifications';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
        $seals_tablename = db_prefix() . 'seals';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
        $seals_tablename = db_prefix() . 'exams';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
    }
}
