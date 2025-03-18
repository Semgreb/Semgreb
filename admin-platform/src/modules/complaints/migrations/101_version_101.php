<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $complaints_status_tablename = db_prefix() . 'complaints_status';
        $CI->db->query("UPDATE `$complaints_status_tablename` SET `statuscolor` = '#ff2d42' WHERE `$complaints_status_tablename`.`complaintsstatusid` = 5;");
        $CI->db->query("UPDATE `$complaints_status_tablename` SET `statuscolor` = '#03a9f4' WHERE `$complaints_status_tablename`.`complaintsstatusid` = 1;");
    }
}
