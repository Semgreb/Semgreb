<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $audits_tablename = db_prefix() . 'audits';
        if (!$CI->db->table_exists($audits_tablename)) {
            $CI->db->query("CREATE TABLE `$audits_tablename` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `id_customer` int(2) NOT NULL,
            `id_seal` int(2) NOT NULL,
            `description` text DEFAULT NULL,
            `date` datetime DEFAULT current_timestamp(),
            `status` int(2) NOT NULL DEFAULT 1,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }
        
     }
}
