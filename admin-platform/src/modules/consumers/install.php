<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();


if (!$CI->db->table_exists(db_prefix() . 'consumers')) {
  $CI->db->query('CREATE TABLE ' . db_prefix() . 'consumers (
    `consumerid` int(11) NOT NULL AUTO_INCREMENT,
    `userid` int(11) NOT NULL,
    `document` int(11) NOT NULL,
    `firstname` varchar(191) NOT NULL,
    `lastname` varchar(191) NOT NULL,
    `email` varchar(100) NOT NULL,
    `phonenumber` varchar(100) NOT NULL,
    `datecreated` datetime NOT NULL,
    `dateupdate` datetime NOT NULL,
    `birthday_date` date NOT NULL,
    `invoice_emails` tinyint(1) NOT NULL DEFAULT 1,
    `estimate_emails` tinyint(1) NOT NULL DEFAULT 1,
    `credit_note_emails` tinyint(1) NOT NULL DEFAULT 1,
    `contract_emails` tinyint(1) NOT NULL DEFAULT 1,
    `task_emails` tinyint(1) NOT NULL DEFAULT 1,
    `project_emails` tinyint(1) NOT NULL DEFAULT 1,
    `complaints_emails` tinyint(1) NOT NULL DEFAULT 1,
     PRIMARY KEY (`consumerid`),
     KEY (`userid`)
  ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
