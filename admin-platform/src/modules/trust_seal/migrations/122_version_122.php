<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_122 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $extra_fields_clients = db_prefix() . 'extra_fields_clients';
        if (!$CI->db->table_exists($extra_fields_clients)) {
            $CI->db->query("CREATE TABLE `$extra_fields_clients` (
                            `userid` int(11) NOT NULL,
                            `email` text DEFAULT NULL,
                            `logo` text NULL,
                            `descriptions` text DEFAULT NULL
                            ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
        }
    }
}
