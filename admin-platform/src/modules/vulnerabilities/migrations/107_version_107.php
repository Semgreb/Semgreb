<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_107 extends App_module_migration
{
   public function up()
   {
      $CI = &get_instance();
      $char_set_db = $CI->db->char_set;
      $tablename = db_prefix() . 'list_alert_vulnerabilities';

      if (!$CI->db->table_exists($tablename)) {
         $CI->db->query('CREATE TABLE ' . $tablename . ' (
         `id_analyzes` int(11) NOT NULL,
         `sourceid` int(11) ,
         `method` varchar(4) ,
         `evidence` text ,
         `pluginId` int(11) ,
         `cweid` int(11) ,
         `confidence` varchar(25) ,
         `wascid` int(11) ,
         `description` text ,
         `messageId` int(11) ,
         `inputVector`  varchar(191) ,
         `url`  text,
         `other` text ,
         `tags` text ,
         `reference`  text ,
         `solution` text ,
         `alert`  varchar(191) ,
         `param`  varchar(191) ,
         `attack`  text ,
         `name`  varchar(191) ,
         `risk`  varchar(191) ,
         `id`  int(11) ,
         `alertRef`  varchar(20),
         `id_client` int(11) ,
         `web_site` text ,
         `date` datetime DEFAULT current_timestamp()  
       ) ENGINE=InnoDB DEFAULT CHARSET=' . $char_set_db . ';');
      }
   }
}
