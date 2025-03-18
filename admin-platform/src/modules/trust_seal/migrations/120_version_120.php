<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_120 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $delete_tablename = db_prefix() . 'emailtemplates';
        $CI->db->query("DELETE FROM $delete_tablename WHERE `$delete_tablename`.`type` = 'trust_seal';");


        $insert = "INSERT INTO `tblemailtemplates` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
        ('trust_seal', 'trust_seal-assigned-to-client', 'english', 'New Certification Assigned', 'New Certification has been assigned to you', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new certification has been assigned to you.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {trust_seal_certification_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Certification</strong>: {trust_seal_certification}</span><br /><span style=\"font-size: 12pt;\"><strong>State</strong>: {trust_seal_certification_state}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Certification message:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_certification_message}</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
        ('trust_seal', 'trust_seal-audit-to-client', 'english', 'New Audit Completed', 'New audit has been completed', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new audit has been completed.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {trust_seal_audit_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Seal</strong>: {trust_seal_audit}</span><br /><span style=\"font-size: 12pt;\"><strong>State</strong>: {trust_seal_audit_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Qualification</strong>: {trust_seal_audit_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Audit description:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Audit details:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0);";

        $CI->db->query($insert);

        $audits_tablename = db_prefix() . 'audits';
        $CI->db->query("ALTER TABLE $audits_tablename ADD  notification tinyint(1) NOT NULL DEFAULT 0 AFTER auto_asignar;");
        $CI->db->query("ALTER TABLE $audits_tablename ADD  reminder tinyint(1) NOT NULL DEFAULT 0 AFTER notification;");

        $certifications_tablename = db_prefix() . 'certifications';
        $CI->db->query("ALTER TABLE $certifications_tablename ADD  notification tinyint(1) NOT NULL DEFAULT 0 AFTER `status`;");
        $CI->db->query("ALTER TABLE $certifications_tablename ADD  reminder tinyint(1) NOT NULL DEFAULT 0 AFTER notification;");

        $relations_reminder_certifications = db_prefix() . 'relations_reminder_certifications';
        if (!$CI->db->table_exists($relations_reminder_certifications)) {
            $CI->db->query("CREATE TABLE `$relations_reminder_certifications` (
    `id_certifications` int(11) UNSIGNED ZEROFILL NOT NULL,
    `id_reminders` int(11) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
        }

        $permision_reminder_certifications = db_prefix() . 'permision_reminder_certifications';
        if (!$CI->db->table_exists($permision_reminder_certifications)) {
            $CI->db->query("CREATE TABLE `$permision_reminder_certifications` (
    `contactid` int(11) NOT NULL DEFAULT 0,
    `notifications_certifications_emails` tinyint(1) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=$char_set_db;");
        }
    }
}
