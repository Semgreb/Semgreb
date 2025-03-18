<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();

if (!$CI->db->table_exists(db_prefix() . 'vulnerabilities')) {
  $tblVulnerabilities =  db_prefix() . "vulnerabilities";
  $CI->db->query("CREATE TABLE `$tblVulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_vulnerability` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `client` varchar(191) NOT NULL,
  `id_web_site` int(11) NOT NULL,
  `web_site` text NOT NULL,
  `date` date NOT NULL,
  `vulnerability` text NOT NULL,
  `state` int(2) NOT NULL,
  `analisis_id` int(11) NOT NULL,
  `warnings` int(9) NOT NULL,
  `risk` varchar(45),
  `state_spider` int(2) NOT NULL,
  `spider_analisis_id` int(11) NOT NULL,
  `state_reading` tinyint(1) NOT NULL DEFAULT 0,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1  DEFAULT CHARSET=" . $CI->db->char_set . ";");

  // $CI->db->query('ALTER TABLE `' . db_prefix() . 'vulnerabilities`
  // ADD PRIMARY KEY (`id`);');

  // $CI->db->query('ALTER TABLE `' . db_prefix() . 'vulnerabilities`
  // MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI->db->table_exists(db_prefix() . 'detalles_vulnerabilities')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "detalles_vulnerabilities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_vulnerability` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `etiqueta` varchar(191) NOT NULL,
  `tipo` varchar(191) NOT NULL,
  `knowledge_base_id` int(11) NOT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=" . $CI->db->char_set . ';');

  // $CI->db->query('ALTER TABLE `' . db_prefix() . 'detalles_vulnerabilities`
  // ADD PRIMARY KEY (`id`);');

  // $CI->db->query('ALTER TABLE `' . db_prefix() . 'detalles_vulnerabilities`
  // MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI->db->table_exists(db_prefix() . 'clients_web_sites')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "clients_web_sites` (
`id_client` int(11) NOT NULL,
`web_site` varchar(50) NOT NULL,
KEY (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

  //   $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients_web_sites`
  // ADD PRIMARY KEY (`id_client`);');

  //   $CI->db->query('ALTER TABLE `' . db_prefix() . 'clients_web_sites`
  // MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

$permision_vulnerabilities = db_prefix() . 'permision_vulnerabilities';
if (!$CI->db->table_exists($permision_vulnerabilities)) {
  $CI->db->query("CREATE TABLE `$permision_vulnerabilities` (
    `contactid` int(11) NOT NULL DEFAULT 0,
    `vulnerabilities` tinyint(1) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";");
}

if (!$CI->db->table_exists(db_prefix() . 'analyzes_queue_vulnerabilities')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "analyzes_queue_vulnerabilities` (
  `id_analyzes` int(11) NOT NULL,
  `date` datetime DEFAULT current_timestamp()  
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'analyzes_container_vulnerabilities')) {
  $CI->db->query('CREATE TABLE `' . db_prefix() . "analyzes_container_vulnerabilities` (
  `id_analyzes` int(11) NOT NULL,
  `host_port` int(9) NOT NULL,
  `api_key` varchar(140) NOT NULL,
  `container_name` varchar(140) NOT NULL,
  `message` varchar(245) NOT NULL,
  `date` datetime DEFAULT current_timestamp()  
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if ($CI->db->table_exists(db_prefix() . 'emailtemplates')) {
  $CI->db->where('type', 'vulnerabilities');
  $responseComplaintEmail = $CI->db->get(db_prefix() . 'emailtemplates');

  if ($responseComplaintEmail->num_rows() == 0) {

    $insert = "INSERT INTO `" . db_prefix() . "emailtemplates` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
    ('vulnerabilities', 'vulnerabilities-assigned-to-client', 'spanish', 'New Scan Assigned', 'Se le ha asignado un nuevo análisis', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se te ha asignado un nuevo análisis.</p>\r\n<p><br /><span style=\"font-size: 12pt;\"><b>Asunto</b>: {vulnerabilities_scan_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Análisis</strong>: {vulnerabilities_scan}</span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {vulnerabilities_scan_state}</span><br /><br /><span><strong><span style=\"font-size: medium;\"><span style=\"font-size: 12pt;\">Detalles del análisis</span>:</span></strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_message}</span><br /><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('vulnerabilities', 'vulnerabilities-scan-to-client', 'spanish', 'New Scan Completed', 'Nuevo análisis completado.', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se ha completado un nuevo <span style=\"font-size: 12pt;\">análisis</span>.<br /><br /><span style=\"font-size: 12pt;\"><b>Asunto</b>: {vulnerabilities_scan_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Análisis</strong>: {vulnerabilities_scan}</span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {vulnerabilities_scan_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Calificación</strong>: {vulnerabilities_scan_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Descripción del <span>análisis</span>:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Detalle del <span>análisis</span>:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('vulnerabilities', 'vulnerabilities-assigned-to-client', 'english', 'New Scan Assigned', 'New Scan has been assigned to you', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new scan has been assigned to you.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {vulnerabilities_scan_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Scan</strong>: {vulnerabilities_scan}</span><br /><span style=\"font-size: 12pt;\"><strong>State</strong>: {vulnerabilities_scan_state}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Scan message:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_message}</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('vulnerabilities', 'vulnerabilities-scan-to-client', 'english', 'New Scan Completed', 'New scan has been completed', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new scan has been completed.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {vulnerabilities_scan_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Scan</strong>: {vulnerabilities_scan}</span><br /><span style=\"font-size: 12pt;\"><strong>State</strong>: {vulnerabilities_scan_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Qualification</strong>: {vulnerabilities_scan_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Scan description:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Scan details:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0);";

    $CI->db->query($insert);
  }
}

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
         `url`  text ,
         `other` text ,
         `tags` text ,
         `reference` text ,
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
 ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

$tablename = db_prefix() . 'list_alert_vulnerabilities_progress';
if (!$CI->db->table_exists($tablename)) {
  $CI->db->query('CREATE TABLE ' . $tablename . ' (
         `id_analyzes` int(11) NOT NULL,
         `id_client` int(11) ,
         `plugin`  varchar(345) ,
         `version`  varchar(100) ,
         `state`  varchar(100) ,
         `request` int(11) ,
         `warnings` int(11) ,
         `web_site` text ,
         `date` datetime DEFAULT current_timestamp()  
 ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}
