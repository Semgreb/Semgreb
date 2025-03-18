<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_113 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seal_files_tablename = db_prefix() . 'seal_files';
        if (!$CI->db->table_exists($seal_files_tablename)) {
            $CI->db->query("CREATE TABLE `$seal_files_tablename` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `id_seal` int(2) NOT NULL,
            `file` text NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }

        $audits_comments_tablename = db_prefix() . 'audit_comments';
        if (!$CI->db->table_exists($audits_comments_tablename)) {
            $CI->db->query("CREATE TABLE `$audits_comments_tablename` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `id_audit` int(2) NOT NULL,
            `id_question` int(2) NOT NULL,
            `comment` text NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }

     }
}
