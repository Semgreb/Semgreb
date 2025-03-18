<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration
{
   public function up()
   {
      add_option('url_analisis', 'http://40.117.102.146');
      add_option('url_container', 'http://40.117.102.146:5000/');
      add_option('key_analisys', 123456789);
      add_option('key_container', 123456789);
      add_option('port_init', 3001);
   }
}
