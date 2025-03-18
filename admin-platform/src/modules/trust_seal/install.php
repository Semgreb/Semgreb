<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();
$char_set_db = $CI->db->char_set;
$db_prefix = db_prefix();

$exams_tablename = db_prefix() . 'exams';
if (!$CI->db->table_exists($exams_tablename)) {
    $CI->db->query("CREATE TABLE `$exams_tablename` (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` varchar(150) NOT NULL,
        `description` varchar(150) NOT NULL,
        `status` int(2) NOT NULL DEFAULT 1,
        `active` int(2) NOT NULL DEFAULT 1,
        `date` date NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$sections_tablename = db_prefix() . 'sections';
if (!$CI->db->table_exists($sections_tablename)) {
    $CI->db->query("CREATE TABLE `$sections_tablename` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `exam_id` int(11) DEFAULT NULL,
        `name` varchar(150) DEFAULT NULL,
        `active` int(2) DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$quiz_tablename = db_prefix() . 'quiz';
if (!$CI->db->table_exists($quiz_tablename)) {
    $CI->db->query("CREATE TABLE `$quiz_tablename` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `section_id` int(11) NOT NULL,
        `name` varchar(150) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}


$seals_tablename = db_prefix() . 'seals';
if (!$CI->db->table_exists($seals_tablename)) {
    $CI->db->query("CREATE TABLE `$seals_tablename` (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `title` varchar(150) NOT NULL,
        `exams` text DEFAULT NULL,
        `requirements` text NULL,
        `description` varchar(250) DEFAULT NULL,
        `date_start` date DEFAULT NULL,
        `docs` int(11) DEFAULT 1,
        `logo_active` text NULL,
        `logo_inactive` text NULL,
        `visibility` int(11) NOT NULL DEFAULT 1,
        `status` int(11) NOT NULL DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");


    // $seals_tablename = db_prefix() . 'seals';
    // $CI->db->query("ALTER TABLE `$seals_tablename` CHANGE `logo` `logo_active` text NULL;");
    //  $CI->db->query("ALTER TABLE `$seals_tablename` ADD `logo_inactive` text NULL AFTER `logo`;");
}

$seal_files_tablename = db_prefix() . 'seal_files';
if (!$CI->db->table_exists($seal_files_tablename)) {
    $CI->db->query("CREATE TABLE `$seal_files_tablename` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_seal` int(2) NOT NULL,
    `file` text NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$certifications_tablename = db_prefix() . 'certifications';
if (!$CI->db->table_exists($certifications_tablename)) {
    $CI->db->query("CREATE TABLE `$certifications_tablename` (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `id_customer` int(11) NOT NULL,
        `id_seal` int(11) NOT NULL,
        `date_expiration` date DEFAULT NULL,
        `date` datetime DEFAULT current_timestamp(),
        `certificationkey` varchar(32) NOT NULL,
        `status` int(11) NOT NULL DEFAULT 1,
        `notification` tinyint(1) NOT NULL DEFAULT 0,
        `reminder` tinyint(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$audits_tablename = db_prefix() . 'audits';
if (!$CI->db->table_exists($audits_tablename)) {
    $CI->db->query("CREATE TABLE `$audits_tablename` (
        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `id_customer` int(2) NOT NULL,
        `id_seal` int(2) NOT NULL,
        `description` text DEFAULT NULL,
        `date` datetime DEFAULT current_timestamp(),
        `status` int(2) NOT NULL DEFAULT 1,
        `qualification` tinyint(1) NOT NULL DEFAULT 1,
        `auto_asignar` tinyint(1) NOT NULL DEFAULT 0,
        `notification` tinyint(1) NOT NULL DEFAULT 0,
        `reminder` tinyint(1) NOT NULL DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$audits_comments_tablename = db_prefix() . 'audit_comments';
if (!$CI->db->table_exists($audits_comments_tablename)) {
    $CI->db->query("CREATE TABLE `$audits_comments_tablename` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_audit` int(2) NOT NULL,
    `id_question` int(2) NOT NULL,
    `comment` text NULL,
    `date` datetime DEFAULT current_timestamp(),
    `contactid` int(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$audits_comments_tablename = db_prefix() . 'keys';
if (!$CI->db->table_exists($audits_comments_tablename)) {
    $CI->db->query("CREATE TABLE `$audits_comments_tablename` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user_id` int(11) NOT NULL,
        `key` varchar(40) NOT NULL,
        `level` int(2) NOT NULL,
        `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
        `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
        `ip_addresses` text DEFAULT NULL,
        `date_created` datetime NOT NULL,
         PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

$audits_exams_tablename = db_prefix() . 'audits_exams';
if (!$CI->db->table_exists($audits_exams_tablename)) {
    $CI->db->query("CREATE TABLE `$audits_exams_tablename` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `id_audit` int(2) NOT NULL,
    `id_customer` int(2) NOT NULL,
    `id_question` int(2) NOT NULL,
    `approved` boolean NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=$char_set_db;");
}

if ($CI->db->table_exists(db_prefix() . 'emailtemplates')) {
    $CI->db->where('type', 'trust_seal');
    $responseComplaintEmail = $CI->db->get(db_prefix() . 'emailtemplates');

    if ($responseComplaintEmail->num_rows() == 0) {

        $insert = "INSERT INTO `" . db_prefix() . "emailtemplates` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
        ('trust_seal', 'trust_seal-assigned-to-client', 'spanish', 'New Certification Assigned', 'Se le ha asignado una nueva certificación', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se te ha asignado una nueva certificación.</p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Asunto</strong>: {trust_seal_certification_subject}</span><br /><span><span style=\"font-size: medium;\"><strong>Certificación</strong>: {trust_seal_certification}</span></span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {trust_seal_certification_state}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Mensaje de certificación:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_certification_message}</span><br /><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
        ('trust_seal', 'trust_seal-audit-to-client ', 'spanish', 'New Audit Completed', 'Se ha completado una nueva auditoría.', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se ha completado una nueva auditoría.<br /><br /><span style=\"font-size: 12pt;\"><strong>Asunto</strong>: {trust_seal_audit_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Sello</strong>: {trust_seal_audit}</span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {trust_seal_audit_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Calificación</strong>: {trust_seal_audit_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Descripción de la auditoria:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Detalle de la auditoria:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
        ('trust_seal', 'trust_seal-assigned-to-client', 'english', 'New Certification Assigned', 'New Certification has been assigned to you', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new certification has been assigned to you.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {trust_seal_certification_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Certification</strong>: {trust_seal_certification}</span><br /><span style=\"font-size: 12pt;\"><strong>State</strong>: {trust_seal_certification_state}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Certification message:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_certification_message}</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
        ('trust_seal', 'trust_seal-audit-to-client', 'english', 'New Audit Completed', 'New audit has been completed', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new audit has been completed.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {trust_seal_audit_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Seal</strong>: {trust_seal_audit}</span><br /><span style=\"font-size: 12pt;\"><strong>State</strong>: {trust_seal_audit_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Qualification</strong>: {trust_seal_audit_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Audit description:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Audit details:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0);";

        $CI->db->query($insert);
    }
}

$relations_reminder_certifications = db_prefix() . 'relations_reminder_certifications';
if (!$CI->db->table_exists($relations_reminder_certifications)) {
    $CI->db->query("CREATE TABLE `$relations_reminder_certifications` (
    `id_certifications` int(11) UNSIGNED NOT NULL,
    `id_reminders` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
}


$permision_reminder_certifications = db_prefix() . 'permision_reminder_certifications';
if (!$CI->db->table_exists($permision_reminder_certifications)) {
    $CI->db->query("CREATE TABLE `$permision_reminder_certifications` (
    `contactid` int(11) NOT NULL DEFAULT 0,
    `notifications_certifications_emails` tinyint(1) NOT NULL DEFAULT 0,
    `permision_audit` tinyint(1) NOT NULL DEFAULT 0,
    `notifications_status_audit_emails` tinyint(1) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
}

$extra_fields_clients = db_prefix() . 'extra_fields_clients';
if (!$CI->db->table_exists($extra_fields_clients)) {
    $CI->db->query("CREATE TABLE `$extra_fields_clients` (
    `userid` int(11) NOT NULL,
    `email` text DEFAULT NULL,
    `logo` text NULL,
    `client_razon_social` text NULL,
    `descriptions` text DEFAULT NULL,
    `slug` mediumtext NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
}
