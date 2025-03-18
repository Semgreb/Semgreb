<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_103 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $vulnerabilities = db_prefix() . 'vulnerabilities';
      $CI->db->query("ALTER TABLE `$vulnerabilities` CHANGE `state` `state` INT(2) NOT NULL;");
   }
}
