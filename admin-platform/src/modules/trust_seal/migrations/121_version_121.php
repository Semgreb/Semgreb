<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_121 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $permision_reminder_certifications = db_prefix() . 'permision_reminder_certifications';
        $CI->db->query("ALTER TABLE $permision_reminder_certifications ADD permision_audit tinyint(1) NOT NULL DEFAULT 0 AFTER notifications_certifications_emails;");

        $CI->db->query("ALTER TABLE $permision_reminder_certifications ADD notifications_status_audit_emails tinyint(1) NOT NULL DEFAULT 0 AFTER permision_audit;");
    }
}
