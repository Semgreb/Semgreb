<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_118 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'audit_comments';

        $CI->db->query("ALTER TABLE `$seals_tablename` ADD  `date` datetime DEFAULT current_timestamp() AFTER `comment`;");
        $CI->db->query("ALTER TABLE `$seals_tablename` ADD   `contactid` int(11) NOT NULL DEFAULT 0 AFTER `date`;");
    }
}
