<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_108 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $tablename = db_prefix() . 'vulnerabilities';
      $CI->db->query("ALTER TABLE `$tablename` ADD `state_reading` tinyint(1) NOT NULL DEFAULT 0 AFTER `state_spider`;");
   }
}
