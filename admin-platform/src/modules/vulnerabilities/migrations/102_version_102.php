<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_102 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $permision_vulnerabilities = db_prefix() . 'permision_vulnerabilities';
      if (!$CI->db->table_exists($permision_vulnerabilities)) {
         $CI->db->query("CREATE TABLE `$permision_vulnerabilities` (
    `contactid` int(11) NOT NULL DEFAULT 0,
    `vulnerabilities` tinyint(1) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
      }
   }
}
