<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_112 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $tablename = db_prefix() . 'vulnerabilities';
      //$CI->db->query("ALTER TABLE `$tablename` CHANGE `trusts` `risk` varchar(45);");
      // $CI->db->query("ALTER TABLE `$tablename` ADD `state_spider` int(2) NOT NULL  AFTER `risk`;");
      $CI->db->query("ALTER TABLE `$tablename` ADD `spider_analisis_id` int(11) NOT NULL AFTER `state_spider`;");
   }
}
