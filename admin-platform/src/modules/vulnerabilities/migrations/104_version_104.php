<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_104 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $tablename = db_prefix() . 'detalles_vulnerabilities';
      $CI->db->query("ALTER TABLE `$tablename` ADD `knowledge_base_id` int(11) NOT NULL AFTER `tipo`;");
   }
}
