<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_115 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'audits';

        // ALTER TABLE `tblaudits` CHANGE `id` `id` INT(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;");
        $CI->db->query("ALTER TABLE `$seals_tablename` ADD  `qualification` tinyint(1) NOT NULL DEFAULT 1 AFTER `status`;");

        $seals_tablename = db_prefix() . 'certifications';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;");
        $seals_tablename = db_prefix() . 'seals';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;");
        $seals_tablename = db_prefix() . 'exams';
        $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE  `id` `id` INT(11) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT;");
    }
}
