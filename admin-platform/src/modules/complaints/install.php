<?php

defined('BASEPATH') or exit('No direct script access allowed');
$CI = &get_instance();

if (!$CI->db->table_exists(db_prefix() . 'complaints')) {

  $CI->db->query('CREATE TABLE `' . db_prefix() . "complaints` (
    `complaintid` int(11) UNSIGNED ZEROFILL NOT NULL,
    `adminreplying` int(11) NOT NULL DEFAULT 0,
    `userid` int(11) NOT NULL,
    `contactid` int(11) NOT NULL DEFAULT 0,
    `merged_complaint_id` int(11) DEFAULT NULL,
    `email` text DEFAULT NULL,
    `name` text DEFAULT NULL,
    `department` int(11) NOT NULL,
    `priority` int(11) NOT NULL,
    `status` int(11) NOT NULL,
    `service` int(11) DEFAULT NULL,
    `complaintkey` varchar(32) NOT NULL,
    `subject` varchar(191) NOT NULL,
    `message` text DEFAULT NULL,
    `admin` int(11) DEFAULT NULL,
    `date` datetime NOT NULL,
    `project_id` int(11) NOT NULL DEFAULT 0,
    `lastreply` datetime DEFAULT NULL,
    `clientread` int(11) NOT NULL DEFAULT 0,
    `adminread` int(11) NOT NULL DEFAULT 0,
    `assigned` int(11) NOT NULL DEFAULT 0,
    `staff_id_replying` int(11) DEFAULT NULL,
    `cc` varchar(191) DEFAULT NULL,
    `id_distribution_channel` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE `' . db_prefix() . 'complaints`
  ADD PRIMARY KEY (`complaintid`),
  ADD KEY `service` (`service`),
  ADD KEY `department` (`department`),
  ADD KEY `status` (`status`),
  ADD KEY `userid` (`userid`),
  ADD KEY `priority` (`priority`),
  ADD KEY `contactid` (`contactid`);');

  $CI->db->query('ALTER TABLE `' . db_prefix() . 'complaints`
  MODIFY `complaintid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI->db->table_exists(db_prefix() . 'distribution_channel')) {

  $CI->db->query('CREATE TABLE `' . db_prefix() . 'distribution_channel` (
`id_distribution_channel` int(2) NOT NULL,
`description` varchar(50) NOT NULL,
`status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'distribution_channel
ADD PRIMARY KEY (`id_distribution_channel`);');


  $CI->db->query("INSERT INTO " . db_prefix() . "distribution_channel(`id_distribution_channel`, `description`, `status`) VALUES (1,'App movil',1);");

  $CI->db->query("INSERT INTO " . db_prefix() . "distribution_channel(`id_distribution_channel`, `description`, `status`) VALUES (2,'Sitio web',1);");

  $CI->db->query("INSERT INTO " . db_prefix() . "distribution_channel(`id_distribution_channel`, `description`, `status`) VALUES (3,'Ventanilla',1);");

  $CI->db->query("INSERT INTO " . db_prefix() . "distribution_channel(`id_distribution_channel`, `description`, `status`) VALUES (4,'Otros',1);");
}

if (!$CI->db->table_exists(db_prefix() . 'complaints_services')) {

  $CI->db->query('CREATE TABLE ' . db_prefix() . 'complaints_services (
      `serviceid` int(11) NOT NULL,
      `name` varchar(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_services
   ADD PRIMARY KEY (`serviceid`);');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_services
   MODIFY `serviceid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');

  $CI->db->query("INSERT INTO " . db_prefix() . "complaints_services       (`serviceid`, `name`) VALUES (1, 'Prueba');");
}

if (!$CI->db->table_exists(db_prefix() . 'complaints_status')) {

  $CI->db->query('CREATE TABLE ' . db_prefix() . 'complaints_status (
    `complaintsstatusid` int(11) NOT NULL,
    `name` varchar(50) NOT NULL,
    `isdefault` int(11) NOT NULL DEFAULT 0,
    `statuscolor` varchar(7) DEFAULT NULL,
    `statusorder` int(11) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');


  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_status
    ADD PRIMARY KEY (`complaintsstatusid`);');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_status
   MODIFY `complaintsstatusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');

  $CI->db->query("INSERT INTO `" . db_prefix() . "complaints_status` (`complaintsstatusid`, `name`, `isdefault`, `statuscolor`, `statusorder`) VALUES
  (1, 'Open', 1, '#ff2d42', 1),
  (2, 'In progress', 1, '#22c55e', 2),
  (3, 'Answered', 1, '#2563eb', 3),
  (4, 'On Hold', 1, '#64748b', 4),
  (5, 'Closed', 1, '#03a9f4', 5);");
}

if (!$CI->db->table_exists(db_prefix() . 'complaints_predefined_replies')) {

  $CI->db->query('CREATE TABLE ' . db_prefix() . 'complaints_predefined_replies (
    `id` int(11) NOT NULL,
    `name` varchar(191) NOT NULL,
    `message` text NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_predefined_replies
   ADD PRIMARY KEY (`id`);');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_predefined_replies
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

if (!$CI->db->table_exists(db_prefix() . 'complaints_pipe_log')) {

  $CI->db->query('CREATE TABLE ' . db_prefix() . 'complaints_pipe_log (
    `id` int(11) NOT NULL,
    `date` datetime NOT NULL,
    `email_to` varchar(100) NOT NULL,
    `name` varchar(191) NOT NULL,
    `subject` varchar(191) NOT NULL,
    `message` mediumtext NOT NULL,
    `email` varchar(100) NOT NULL,
    `status` varchar(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_pipe_log
   ADD PRIMARY KEY (`id`);');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_pipe_log
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

if (!$CI->db->table_exists(db_prefix() . 'complaints_attachments')) {

  $CI->db->query('CREATE TABLE ' . db_prefix() . 'complaints_attachments (
    `id` int(11) NOT NULL,
    `complaintid` int(11) NOT NULL,
    `replyid` int(11) DEFAULT NULL,
    `file_name` varchar(191) NOT NULL,
    `filetype` varchar(50) DEFAULT NULL,
    `dateadded` datetime NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_attachments
   ADD PRIMARY KEY (`id`);');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_attachments
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

if (!$CI->db->table_exists(db_prefix() . 'complaints_replies')) {

  $CI->db->query('CREATE TABLE ' . db_prefix() . 'complaints_replies (
    `id` int(11) NOT NULL,
  `complaintid` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `contactid` int(11) NOT NULL DEFAULT 0,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `date` datetime NOT NULL,
  `message` text DEFAULT NULL,
  `attachment` int(11) DEFAULT NULL,
  `admin` int(11) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_replies
   ADD PRIMARY KEY (`id`);');

  $CI->db->query('ALTER TABLE ' . db_prefix() . 'complaints_replies
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;');
}

if ($CI->db->table_exists(db_prefix() . 'emailtemplates')) {

  $CI->db->where('type', 'complaint');
  $responseComplaintEmail = $CI->db->get(db_prefix() . 'emailtemplates');

  if ($responseComplaintEmail->num_rows() == 0) {

    $insert = "INSERT INTO `tblemailtemplates` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
    ('complaint', 'new-complaint-opened-admin', 'english', 'New Complaint Opened (Opened by Staff, Sent to Customer)', 'New Support Complaint Opened', '<p><span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">New support complaint has been opened.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject:</strong> {complaint_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department:</strong> {complaint_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {complaint_priority}<br /></span><br /><span style=\"font-size: 12pt;\"><strong>Complaint message:</strong></span><br /><span style=\"font-size: 12pt;\">{complaint_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{complaint_public_url}\">#{complaint_id}</a><br /><br />Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply', 'english', 'Complaint Reply (Sent to Customer)', 'New Complaint Reply', '<p><span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">You have a new complaint reply to complaint <a href=\"{complaint_public_url}\">#{complaint_id}</a></span><br /><br /><span style=\"font-size: 12pt;\"><strong>Complaint Subject:</strong> {complaint_subject}<br /></span><br /><span style=\"font-size: 12pt;\"><strong>Complaint message:</strong></span><br /><span style=\"font-size: 12pt;\">{complaint_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the complaint on the following link: <a href=\"{complaint_public_url}\">#{complaint_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-autoresponse', 'english', 'New Complaint Opened - Autoresponse', 'New Support Complaint Opened', '<p><span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Thank you for contacting our support team. A support complaint has now been opened for your request. You will be notified when a response is made by email.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject:</strong> {complaint_subject}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Department</strong>: {</span><span style=\"font-size: 12pt;\">complaint_department</span><span style=\"font-size: 12pt;\">}</span></p>\r\n<p><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {complaint_priority}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Complaint message:</strong></span><br /><span style=\"font-size: 12pt;\">{complaint_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the complaint on the following link: <a href=\"{complaint_public_url}\">#{complaint_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-created-staff', 'english', 'New Complaint Created (Opened by Customer, Sent to Staff Members)', 'New Complaint Created', '<p><span style=\"font-size: 12pt;\">A new support complaint has been opened.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {complaint_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {complaint_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority</strong>: {complaint_priority}<br /></span><br /><span style=\"font-size: 12pt;\"><strong>Complaint message:</strong></span><br /><span style=\"font-size: 12pt;\">{complaint_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the complaint on the following link: <a href=\"{complaint_url}\">#{complaint_id}</a></span><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply-to-admin', 'english', 'Complaint Reply (Sent to Staff)', 'New Support Complaint Reply', '<p><span style=\"font-size: 12pt;\">A new support complaint reply from {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {complaint_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {complaint_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority</strong>: {complaint_priority}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Complaint message:</strong></span><br /><span style=\"font-size: 12pt;\">{complaint_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{complaint_url}\">#{complaint_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'auto-close-complaint', 'english', 'Auto Close Complaint', 'Complaint Auto Closed', '<p><span style=\"font-size: 12pt;\">Hi {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Complaint {complaint_subject} has been auto close due to inactivity.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Complaint #</strong>: <a href=\"{complaint_public_url}\">{complaint_id}</a></span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {complaint_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority:</strong> {complaint_priority}</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-assigned-to-admin', 'english', 'New Complaint Assigned (Sent to Staff)', 'New support complaint has been assigned to you', '<p><span style=\"font-size: 12pt;\">Hi</span></p>\r\n<p><span style=\"font-size: 12pt;\">A new support complaint has been assigned to you.</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Subject</strong>: {complaint_subject}</span><br /><span style=\"font-size: 12pt;\"><strong>Department</strong>: {complaint_department}</span><br /><span style=\"font-size: 12pt;\"><strong>Priority</strong>: {complaint_priority}</span><br /><br /><span style=\"font-size: 12pt;\"><strong>Complaint message:</strong></span><br /><span style=\"font-size: 12pt;\">{complaint_message}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the ticket on the following link: <a href=\"{complaint_url}\">#{complaint_id}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-opened-admin', 'bulgarian', 'New Complaint Opened (Opened by Staff, Sent to Customer) [bulgarian]', 'New Support Complaint Opened', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply', 'bulgarian', 'Complaint Reply (Sent to Customer) [bulgarian]', 'New Complaint Reply', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-autoresponse', 'bulgarian', 'New Complaint Opened - Autoresponse [bulgarian]', 'New Support Complaint Opened', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-created-staff', 'bulgarian', 'New Complaint Created (Opened by Customer, Sent to Staff Members) [bulgarian]', 'New Complaint Created', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply-to-admin', 'bulgarian', 'Complaint Reply (Sent to Staff) [bulgarian]', 'New Support Complaint Reply', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'auto-close-complaint', 'bulgarian', 'Auto Close Complaint [bulgarian]', 'Complaint Auto Closed', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-assigned-to-admin', 'bulgarian', 'New Complaint Assigned (Sent to Staff) [bulgarian]', 'New support complaint has been assigned to you', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-opened-admin', 'dutch', 'New Complaint Opened (Opened by Staff, Sent to Customer) [dutch]', 'New Support Complaint Opened', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply', 'dutch', 'Complaint Reply (Sent to Customer) [dutch]', 'New Complaint Reply', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-autoresponse', 'dutch', 'New Complaint Opened - Autoresponse [dutch]', 'New Support Complaint Opened', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-created-staff', 'dutch', 'New Complaint Created (Opened by Customer, Sent to Staff Members) [dutch]', 'New Complaint Created', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply-to-admin', 'dutch', 'Complaint Reply (Sent to Staff) [dutch]', 'New Support Complaint Reply', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'auto-close-complaint', 'dutch', 'Auto Close Complaint [dutch]', 'Complaint Auto Closed', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-assigned-to-admin', 'dutch', 'New Complaint Assigned (Sent to Staff) [dutch]', 'New support complaint has been assigned to you', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-opened-admin', 'czech', 'New Complaint Opened (Opened by Staff, Sent to Customer) [czech]', 'New Support Complaint Opened', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-reply', 'czech', 'Complaint Reply (Sent to Customer) [czech]', 'New Complaint Reply', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'complaint-autoresponse', 'czech', 'New Complaint Opened - Autoresponse [czech]', 'New Support Complaint Opened', '', '{companyname} | CRM', '', 0, 1, 0),
    ('complaint', 'new-complaint-created-staff', 'czech', 'New Complaint Created (Opened by Customer, Sent to Staff Members) [czech]', 'New Complaint Created', '', '{companyname} | CRM', '', 0, 1, 0);";

    $CI->db->query($insert);
  }
}
