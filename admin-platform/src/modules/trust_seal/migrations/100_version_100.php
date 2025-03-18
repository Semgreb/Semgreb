<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_100 extends App_module_migration
{
     public function up()
     {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $exams_tablename = db_prefix() . 'exams';
        if (!$CI->db->table_exists($exams_tablename)) 
        {
            $CI->db->query("CREATE TABLE `$exams_tablename` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(150) NOT NULL,
                `description` varchar(150) NOT NULL,
                `status` int(2) NOT NULL DEFAULT 1,
                `active` int(2) NOT NULL DEFAULT 1,
                `date` date NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }
        
        $sections_tablename = db_prefix() . 'sections';
        if (!$CI->db->table_exists($sections_tablename)) 
        {
            $CI->db->query("CREATE TABLE `$sections_tablename` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `exam_id` int(11) DEFAULT NULL,
                `name` varchar(150) DEFAULT NULL,
                `active` int(2) DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }
        
        $quiz_tablename = db_prefix() . 'quiz';
        if (!$CI->db->table_exists($quiz_tablename)) 
        {
            $CI->db->query("CREATE TABLE `$quiz_tablename` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `section_id` int(11) NOT NULL,
                `name` varchar(150) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }
        
        $seals_tablename = db_prefix() . 'seals';
        if (!$CI->db->table_exists($seals_tablename)) 
        {
            $CI->db->query("CREATE TABLE `$seals_tablename` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `title` varchar(150) NOT NULL,
                `exams` text DEFAULT NULL,
                `short_description` varchar(150) DEFAULT NULL,
                `description` varchar(250) DEFAULT NULL,
                `date_start` date DEFAULT NULL,
                `docs` int(11) DEFAULT 1,
                `logo` int(11) DEFAULT 1,
                `visibility` int(11) NOT NULL DEFAULT 1,
                `status` int(11) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }
        
        $certifications_tablename = db_prefix() . 'certifications';
        if (!$CI->db->table_exists($certifications_tablename)) {
            $CI->db->query("CREATE TABLE `$certifications_tablename` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `name` varchar(255) DEFAULT NULL,
                `id_customer` int(11) NOT NULL,
                `id_seal` int(11) NOT NULL,
                `date_expiration` date DEFAULT NULL,
                `date` datetime DEFAULT current_timestamp(),
                `status` int(11) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
        }
        
     }
}
