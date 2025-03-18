<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_119 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'keys';

        $CI->db->query("CREATE TABLE `$seals_tablename` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `key` varchar(40) NOT NULL,
            `level` int(2) NOT NULL,
            `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
            `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
            `ip_addresses` text DEFAULT NULL,
            `date_created` datetime NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
    }
}
