<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_105 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $tablename = db_prefix() . 'vulnerabilities';
      $CI->db->query("ALTER TABLE `$tablename` ADD `warnings` int(9) NOT NULL AFTER `analisis_id`;");
      $CI->db->query("ALTER TABLE `$tablename` ADD `trusts` int(2) NOT NULL  AFTER `warnings`;");
   }
}
