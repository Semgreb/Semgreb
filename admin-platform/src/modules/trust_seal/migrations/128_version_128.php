<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_128 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_exams_tablename = db_prefix() . 'seal_exams';
        if (!$CI->db->table_exists($seals_exams_tablename)) {

            $CI->db->query("CREATE TABLE $seals_exams_tablename (
                            id_seal INT UNSIGNED NOT NULL,
                            id_exams INT UNSIGNED NOT NULL,
                            status INT(1) NULL,
                            PRIMARY KEY (id_seal, id_exams)) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");



            $seals_tablename = db_prefix() . 'seals';

            $CI->db->query("INSERT INTO $seals_exams_tablename
                            (id_seal,
                            id_exams,
                            status)
                            SELECT id, exams, 1 
                            FROM $seals_tablename;");

        }
     }
}
