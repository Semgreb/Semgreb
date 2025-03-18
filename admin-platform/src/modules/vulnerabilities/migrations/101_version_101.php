<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;

      $tablename = db_prefix() . 'clients_web_sites';
      $CI->db->query("ALTER TABLE $tablename DROP `id`;");
   }
}
