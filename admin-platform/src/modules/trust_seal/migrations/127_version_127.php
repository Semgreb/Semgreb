<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_127 extends App_module_migration
{
    public function up()
    {
        $CI = &get_instance();
        $char_set_db = $CI->db->char_set;

        $seals_tablename = db_prefix() . 'emailtemplates';

        $CI->db->where(['type' => 'trust_seal', 'language' => 'spanish']);
        $responseComplaintEmail = $CI->db->get($seals_tablename);

        if ($responseComplaintEmail->num_rows() == 0) {


            $CI->db->query("INSERT INTO `$seals_tablename` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
        ('trust_seal', 'trust_seal-assigned-to-client', 'spanish', 'New Certification Assigned', 'Se le ha asignado una nueva certificación', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se te ha asignado una nueva certificación.</p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Encabezado</strong>: {trust_seal_certification_subject}</span><br /><span><span style=\"font-size: medium;\"><strong>Certificación</strong>: {trust_seal_certification}</span></span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {trust_seal_certification_state}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Mensaje de certificación:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_certification_message}</span><br /><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
        ('trust_seal', 'trust_seal-audit-to-client ', 'spanish', 'New Audit Completed', 'Se ha completado una nueva auditoría.', '<p><span style=\"font-size: 12pt;\">Hola</span></p>\r\n<p>Se ha completado una nueva auditoría.<br /><br /><span style=\"font-size: 12pt;\"><strong>Encabezado</strong>: {trust_seal_audit_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Sello</strong>: {trust_seal_audit}</span><br /><span style=\"font-size: 12pt;\"><strong>Estado</strong>: {trust_seal_audit_state}</span><br /><span style=\"font-size: 12pt;\"><strong>Calificación</strong>: {trust_seal_audit_qualification}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Descripción de la auditoria:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_description}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\"><strong>Detalle de la auditoria:</strong></span><br /><span style=\"font-size: 12pt;\">{trust_seal_audit_message}</span></p>\r\n<p><br /><span style=\"font-size: 12pt;\">Atentamente,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0);");
        }
    }
}
