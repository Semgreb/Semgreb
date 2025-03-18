<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_109 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;
      $tablename = db_prefix() . 'list_alert_vulnerabilities_progress';

      if (!$CI->db->table_exists($tablename)) {
         $CI->db->query('CREATE TABLE ' . $tablename . ' (
               `id_analyzes` int(11) NOT NULL,
               `id_client` int(11) ,
               `plugin`  varchar(345) ,
               `version`  varchar(100) ,
               `state`  varchar(100) ,
               `request` int(11),
               `warnings` int(11),
               `web_site` text,
               `date` datetime DEFAULT current_timestamp()  
       ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
      }

      add_option('frequency', 0);
      add_option('number_container', 1);

      if (!$CI->db->table_exists(db_prefix() . 'analyzes_queue_vulnerabilities')) {
         $CI->db->query('CREATE TABLE `' . db_prefix() . "analyzes_queue_vulnerabilities` (
         `id_analyzes` int(11) NOT NULL,
         `date` datetime DEFAULT current_timestamp()  
       ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
      }

      if (!$CI->db->table_exists(db_prefix() . 'analyzes_container_vulnerabilities')) {
         $CI->db->query('CREATE TABLE `' . db_prefix() . "analyzes_container_vulnerabilities` (
         `id_analyzes` int(11) NOT NULL,
         `host_port` int(9) NOT NULL,
         `api_key` varchar(140) NOT NULL,
         `container_name` varchar(140) NOT NULL,
         `message` varchar(245) NOT NULL,
         `date` datetime DEFAULT current_timestamp()  
       ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
      }
   }
}
