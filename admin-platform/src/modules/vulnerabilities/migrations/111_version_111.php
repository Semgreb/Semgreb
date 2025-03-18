<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'emailtemplates';

        $CI->db->where(['type' => 'vulnerabilities', 'language' => 'spanish']);
        $responseComplaintEmail = $CI->db->get($seals_tablename);

        if ($responseComplaintEmail->num_rows() == 0) {


            $CI->db->query("INSERT INTO `$seals_tablename` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
        ('vulnerabilities', 'vulnerabilities-assigned-to-client', 'spanish', 'New Scan Assigned', 'Se le ha asignado un nuevo análisis', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se te ha asignado un nuevo análisis.</p>\r\n<p><br /><span style=\"font-size: 12pt;\"><b>Asunto</b>: {vulnerabilities_scan_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Análisis</strong>: {vulnerabilities_scan}</span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {vulnerabilities_scan_state}</span><br /><br /><span><strong><span style=\"font-size: medium;\"><span style=\"font-size: 12pt;\">Detalles del análisis</span>:</span></strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_message}</span><br /><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('vulnerabilities', 'vulnerabilities-scan-to-client', 'spanish', 'New Scan Completed', 'Nuevo análisis completado.', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se ha completado un nuevo <span style=\"font-size: 12pt;\">análisis</span>.<br /><br /><span style=\"font-size: 12pt;\"><b>Asunto</b>: {vulnerabilities_scan_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Análisis</strong>: {vulnerabilities_scan}</span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {vulnerabilities_scan_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Calificación</strong>: {vulnerabilities_scan_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Descripción del <span>análisis</span>:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Detalle del <span>análisis</span>:</strong></span><br /><span style=\"font-size: 12pt;\">{vulnerabilities_scan_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0);");
        }
    }
}
