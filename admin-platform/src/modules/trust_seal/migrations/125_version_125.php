<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_125 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;
      $extra_fields_clients = db_prefix() . 'extra_fields_clients';
      $CI->db->query("ALTER TABLE $extra_fields_clients ADD slug mediumtext NULL AFTER descriptions;");
   }
}
