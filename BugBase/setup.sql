-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 21, 2012 at 06:44 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `ost_api_key`
--

CREATE TABLE IF NOT EXISTS `ost_api_key` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `isactive` tinyint(1) NOT NULL default '1',
  `ipaddr` varchar(16) NOT NULL default '',
  `apikey` varchar(255) NOT NULL default '',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `ipaddr` (`ipaddr`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_api_key`
--

INSERT INTO `ost_api_key` (`id`, `isactive`, `ipaddr`, `apikey`, `updated`, `created`) VALUES
(1, 1, '192.168.1.5', 'siri!', '2010-12-15 17:00:05', '2010-12-15 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `ost_config`
--

CREATE TABLE IF NOT EXISTS `ost_config` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `isonline` tinyint(1) unsigned NOT NULL default '0',
  `timezone_offset` float(3,1) NOT NULL default '0.0',
  `enable_daylight_saving` tinyint(1) unsigned NOT NULL default '0',
  `staff_ip_binding` tinyint(1) unsigned NOT NULL default '1',
  `staff_max_logins` tinyint(3) unsigned NOT NULL default '4',
  `staff_login_timeout` int(10) unsigned NOT NULL default '2',
  `staff_session_timeout` int(10) unsigned NOT NULL default '30',
  `client_max_logins` tinyint(3) unsigned NOT NULL default '4',
  `client_login_timeout` int(10) unsigned NOT NULL default '2',
  `client_session_timeout` int(10) unsigned NOT NULL default '30',
  `max_page_size` tinyint(3) unsigned NOT NULL default '25',
  `max_open_tickets` tinyint(3) unsigned NOT NULL default '0',
  `max_file_size` int(11) unsigned NOT NULL default '1048576',
  `autolock_minutes` tinyint(3) unsigned NOT NULL default '3',
  `overdue_grace_period` int(10) unsigned NOT NULL default '0',
  `alert_email_id` tinyint(4) unsigned NOT NULL default '0',
  `default_email_id` tinyint(4) unsigned NOT NULL default '0',
  `default_dept_id` tinyint(3) unsigned NOT NULL default '0',
  `default_priority_id` tinyint(2) unsigned NOT NULL default '2',
  `default_template_id` tinyint(4) unsigned NOT NULL default '1',
  `default_smtp_id` tinyint(4) unsigned NOT NULL default '0',
  `spoof_default_smtp` tinyint(1) unsigned NOT NULL default '0',
  `clickable_urls` tinyint(1) unsigned NOT NULL default '1',
  `allow_priority_change` tinyint(1) unsigned NOT NULL default '0',
  `use_email_priority` tinyint(1) unsigned NOT NULL default '0',
  `enable_captcha` tinyint(1) unsigned NOT NULL default '0',
  `enable_auto_cron` tinyint(1) unsigned NOT NULL default '0',
  `enable_mail_fetch` tinyint(1) unsigned NOT NULL default '0',
  `enable_email_piping` tinyint(1) unsigned NOT NULL default '0',
  `send_sql_errors` tinyint(1) unsigned NOT NULL default '1',
  `send_mailparse_errors` tinyint(1) unsigned NOT NULL default '1',
  `send_login_errors` tinyint(1) unsigned NOT NULL default '1',
  `save_email_headers` tinyint(1) unsigned NOT NULL default '1',
  `strip_quoted_reply` tinyint(1) unsigned NOT NULL default '1',
  `log_ticket_activity` tinyint(1) unsigned NOT NULL default '1',
  `ticket_autoresponder` tinyint(1) unsigned NOT NULL default '0',
  `message_autoresponder` tinyint(1) unsigned NOT NULL default '0',
  `ticket_notice_active` tinyint(1) unsigned NOT NULL default '0',
  `ticket_alert_active` tinyint(1) unsigned NOT NULL default '0',
  `ticket_alert_admin` tinyint(1) unsigned NOT NULL default '1',
  `ticket_alert_dept_manager` tinyint(1) unsigned NOT NULL default '1',
  `ticket_alert_dept_members` tinyint(1) unsigned NOT NULL default '0',
  `message_alert_active` tinyint(1) unsigned NOT NULL default '0',
  `message_alert_laststaff` tinyint(1) unsigned NOT NULL default '1',
  `message_alert_assigned` tinyint(1) unsigned NOT NULL default '1',
  `message_alert_dept_manager` tinyint(1) unsigned NOT NULL default '0',
  `note_alert_active` tinyint(1) unsigned NOT NULL default '0',
  `note_alert_laststaff` tinyint(1) unsigned NOT NULL default '1',
  `note_alert_assigned` tinyint(1) unsigned NOT NULL default '1',
  `note_alert_dept_manager` tinyint(1) unsigned NOT NULL default '0',
  `overdue_alert_active` tinyint(1) unsigned NOT NULL default '0',
  `overdue_alert_assigned` tinyint(1) unsigned NOT NULL default '1',
  `overdue_alert_dept_manager` tinyint(1) unsigned NOT NULL default '1',
  `overdue_alert_dept_members` tinyint(1) unsigned NOT NULL default '0',
  `auto_assign_reopened_tickets` tinyint(1) unsigned NOT NULL default '1',
  `show_assigned_tickets` tinyint(1) unsigned NOT NULL default '0',
  `show_answered_tickets` tinyint(1) NOT NULL default '0',
  `hide_staff_name` tinyint(1) unsigned NOT NULL default '0',
  `overlimit_notice_active` tinyint(1) unsigned NOT NULL default '0',
  `email_attachments` tinyint(1) unsigned NOT NULL default '1',
  `allow_attachments` tinyint(1) unsigned NOT NULL default '0',
  `allow_email_attachments` tinyint(1) unsigned NOT NULL default '0',
  `allow_online_attachments` tinyint(1) unsigned NOT NULL default '0',
  `allow_online_attachments_onlogin` tinyint(1) unsigned NOT NULL default '0',
  `random_ticket_ids` tinyint(1) unsigned NOT NULL default '1',
  `log_level` tinyint(1) unsigned NOT NULL default '2',
  `log_graceperiod` int(10) unsigned NOT NULL default '12',
  `upload_dir` varchar(255) NOT NULL default '',
  `allowed_filetypes` varchar(255) NOT NULL default '.doc, .pdf',
  `time_format` varchar(32) NOT NULL default ' h:i A',
  `date_format` varchar(32) NOT NULL default 'm/d/Y',
  `datetime_format` varchar(60) NOT NULL default 'm/d/Y g:i a',
  `daydatetime_format` varchar(60) NOT NULL default 'D, M j Y g:ia',
  `reply_separator` varchar(60) NOT NULL default '-- do not edit --',
  `admin_email` varchar(125) NOT NULL default '',
  `helpdesk_title` varchar(255) NOT NULL default 'osTicket Support Ticket System',
  `helpdesk_url` varchar(255) NOT NULL default '',
  `api_passphrase` varchar(125) NOT NULL default '',
  `ostversion` varchar(16) NOT NULL default '',
  `updated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `isoffline` (`isonline`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_config`
--

INSERT INTO `ost_config` (`id`, `isonline`, `timezone_offset`, `enable_daylight_saving`, `staff_ip_binding`, `staff_max_logins`, `staff_login_timeout`, `staff_session_timeout`, `client_max_logins`, `client_login_timeout`, `client_session_timeout`, `max_page_size`, `max_open_tickets`, `max_file_size`, `autolock_minutes`, `overdue_grace_period`, `alert_email_id`, `default_email_id`, `default_dept_id`, `default_priority_id`, `default_template_id`, `default_smtp_id`, `spoof_default_smtp`, `clickable_urls`, `allow_priority_change`, `use_email_priority`, `enable_captcha`, `enable_auto_cron`, `enable_mail_fetch`, `enable_email_piping`, `send_sql_errors`, `send_mailparse_errors`, `send_login_errors`, `save_email_headers`, `strip_quoted_reply`, `log_ticket_activity`, `ticket_autoresponder`, `message_autoresponder`, `ticket_notice_active`, `ticket_alert_active`, `ticket_alert_admin`, `ticket_alert_dept_manager`, `ticket_alert_dept_members`, `message_alert_active`, `message_alert_laststaff`, `message_alert_assigned`, `message_alert_dept_manager`, `note_alert_active`, `note_alert_laststaff`, `note_alert_assigned`, `note_alert_dept_manager`, `overdue_alert_active`, `overdue_alert_assigned`, `overdue_alert_dept_manager`, `overdue_alert_dept_members`, `auto_assign_reopened_tickets`, `show_assigned_tickets`, `show_answered_tickets`, `hide_staff_name`, `overlimit_notice_active`, `email_attachments`, `allow_attachments`, `allow_email_attachments`, `allow_online_attachments`, `allow_online_attachments_onlogin`, `random_ticket_ids`, `log_level`, `log_graceperiod`, `upload_dir`, `allowed_filetypes`, `time_format`, `date_format`, `datetime_format`, `daydatetime_format`, `reply_separator`, `admin_email`, `helpdesk_title`, `helpdesk_url`, `api_passphrase`, `ostversion`, `updated`) VALUES
(1, 1, -5.0, 1, 1, 4, 2, 0, 4, 2, 0, 25, 0, 1000000000, 0, 0, 2, 1, 1, 2, 1, 0, 0, 1, 0, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 1, 1, 1, 0, 1, 2, 12, '/home/softbc19/public_html/support/Attachments', '.*', ' h:i A', 'm/d/Y', 'm/d/Y g:i a', 'D, M j Y g:ia', '-- do not edit --', 'chad@softwaretech.com', 'Software Techniques Support Ticket System', 'http://softwaretech.com/support/', '', '1.6 ST', '2010-12-15 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `ost_department`
--

CREATE TABLE IF NOT EXISTS `ost_department` (
  `dept_id` int(11) unsigned NOT NULL auto_increment,
  `tpl_id` int(10) unsigned NOT NULL default '0',
  `email_id` int(10) unsigned NOT NULL default '0',
  `autoresp_email_id` int(10) unsigned NOT NULL default '0',
  `manager_id` int(10) unsigned NOT NULL default '0',
  `dept_name` varchar(32) NOT NULL default '',
  `dept_signature` tinytext NOT NULL,
  `ispublic` tinyint(1) unsigned NOT NULL default '1',
  `ticket_auto_response` tinyint(1) NOT NULL default '1',
  `message_auto_response` tinyint(1) NOT NULL default '0',
  `can_append_signature` tinyint(1) NOT NULL default '1',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`dept_id`),
  UNIQUE KEY `dept_name` (`dept_name`),
  KEY `manager_id` (`manager_id`),
  KEY `autoresp_email_id` (`autoresp_email_id`),
  KEY `tpl_id` (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ost_department`
--

INSERT INTO `ost_department` (`dept_id`, `tpl_id`, `email_id`, `autoresp_email_id`, `manager_id`, `dept_name`, `dept_signature`, `ispublic`, `ticket_auto_response`, `message_auto_response`, `can_append_signature`, `updated`, `created`) VALUES
(1, 0, 1, 0, 0, 'Tester', 'Test Dept', 1, 1, 1, 1, '2010-12-15 17:00:05', '2010-12-15 17:00:05');
-- --------------------------------------------------------

--
-- Table structure for table `ost_email`
--

CREATE TABLE IF NOT EXISTS `ost_email` (
  `email_id` int(11) unsigned NOT NULL auto_increment,
  `noautoresp` tinyint(1) unsigned NOT NULL default '0',
  `priority_id` tinyint(3) unsigned NOT NULL default '2',
  `dept_id` tinyint(3) unsigned NOT NULL default '0',
  `email` varchar(125) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `userid` varchar(125) NOT NULL default '',
  `userpass` varchar(125) NOT NULL default '',
  `mail_active` tinyint(1) NOT NULL default '0',
  `mail_host` varchar(125) NOT NULL default '',
  `mail_protocol` enum('POP','IMAP') NOT NULL default 'POP',
  `mail_encryption` enum('NONE','SSL') NOT NULL default 'NONE',
  `mail_port` int(6) default NULL,
  `mail_fetchfreq` tinyint(3) NOT NULL default '5',
  `mail_fetchmax` tinyint(4) NOT NULL default '30',
  `mail_delete` tinyint(1) NOT NULL default '0',
  `mail_errors` tinyint(3) NOT NULL default '0',
  `mail_lasterror` datetime default NULL,
  `mail_lastfetch` datetime default NULL,
  `smtp_active` tinyint(1) default '0',
  `smtp_host` varchar(125) NOT NULL default '',
  `smtp_port` int(6) default NULL,
  `smtp_secure` tinyint(1) NOT NULL default '1',
  `smtp_auth` tinyint(1) NOT NULL default '1',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`email_id`),
  UNIQUE KEY `email` (`email`),
  KEY `priority_id` (`priority_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `ost_email`
--

INSERT INTO `ost_email` (`email_id`, `noautoresp`, `priority_id`, `dept_id`, `email`, `name`, `userid`, `userpass`, `mail_active`, `mail_host`, `mail_protocol`, `mail_encryption`, `mail_port`, `mail_fetchfreq`, `mail_fetchmax`, `mail_delete`, `mail_errors`, `mail_lasterror`, `mail_lastfetch`, `smtp_active`, `smtp_host`, `smtp_port`, `smtp_secure`, `smtp_auth`, `created`, `updated`) VALUES
(1, 0, 2, 1, 'ross@rnorvell', 'Support', 'ross@rnorvell.com', 'H9cqNVGMIC+StkpjA4VUa5BFYni4rWh61xkP1WNEvBc=', 0, '', 'POP', 'NONE', 0, 5, 30, 0, 0, NULL, NULL, 0, '', 0, 1, 1, '2010-12-15 17:00:05', '2010-12-15 18:17:16'),

-- --------------------------------------------------------

--
-- Table structure for table `ost_email_banlist`
--

CREATE TABLE IF NOT EXISTS `ost_email_banlist` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `submitter` varchar(126) NOT NULL default '',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_email_banlist`
--

INSERT INTO `ost_email_banlist` (`id`, `email`, `submitter`, `added`) VALUES
(1, 'test@example.com', 'System', '2010-12-15 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `ost_email_template`
--

CREATE TABLE IF NOT EXISTS `ost_email_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `cfg_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `notes` text,
  `ticket_autoresp_subj` varchar(255) NOT NULL default '',
  `ticket_autoresp_body` text NOT NULL,
  `ticket_notice_subj` varchar(255) NOT NULL default '',
  `ticket_notice_body` text NOT NULL,
  `ticket_alert_subj` varchar(255) NOT NULL default '',
  `ticket_alert_body` text NOT NULL,
  `message_autoresp_subj` varchar(255) NOT NULL default '',
  `message_autoresp_body` text NOT NULL,
  `message_alert_subj` varchar(255) NOT NULL default '',
  `message_alert_body` text NOT NULL,
  `note_alert_subj` varchar(255) NOT NULL default '',
  `note_alert_body` text NOT NULL,
  `assigned_alert_subj` varchar(255) NOT NULL default '',
  `assigned_alert_body` text NOT NULL,
  `ticket_overdue_subj` varchar(255) NOT NULL default '',
  `ticket_overdue_body` text NOT NULL,
  `ticket_overlimit_subj` varchar(255) NOT NULL default '',
  `ticket_overlimit_body` text NOT NULL,
  `ticket_reply_subj` varchar(255) NOT NULL default '',
  `ticket_reply_body` text NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tpl_id`),
  KEY `cfg_id` (`cfg_id`),
  FULLTEXT KEY `message_subj` (`ticket_reply_subj`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ost_email_template`
--

INSERT INTO `ost_email_template` (`tpl_id`, `cfg_id`, `name`, `notes`, `ticket_autoresp_subj`, `ticket_autoresp_body`, `ticket_notice_subj`, `ticket_notice_body`, `ticket_alert_subj`, `ticket_alert_body`, `message_autoresp_subj`, `message_autoresp_body`, `message_alert_subj`, `message_alert_body`, `note_alert_subj`, `note_alert_body`, `assigned_alert_subj`, `assigned_alert_body`, `ticket_overdue_subj`, `ticket_overdue_body`, `ticket_overlimit_subj`, `ticket_overlimit_body`, `ticket_reply_subj`, `ticket_reply_body`, `created`, `updated`) VALUES
(1, 1, 'Bugbase Default Template', 'Default BugBase templates', 'Support Ticket Opened [#%ticket]', '%name,\r\n\r\nA request for support has been created and assigned ticket #%ticket. A representative will follow-up with you as soon as possible.\r\n\r\nYou can view this ticket''s progress online here: %url/view.php?e=%email&t=%ticket.\r\n\r\nIf you wish to send additional comments or information regarding this issue, please don''t open a new ticket. Simply login using the link above and update the ticket.\r\n\r\n%signature', '[#%ticket] %subject', '%name,\r\n\r\nOur customer care team has created a ticket, #%ticket on your behalf, with the following message.\r\n\r\n%message\r\n\r\nIf you wish to provide additional comments or information regarding this issue, please don''t open a new ticket. You can update or view this ticket''s progress online here: %url/view.php?e=%email&t=%ticket.\r\n\r\n%signature', 'New Ticket Alert', '%staff,\r\n\r\nNew ticket #%ticket created.\r\n-------------------\r\nName: %name\r\nEmail: %email\r\nDept: %dept\r\n\r\n%message\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.\r\n\r\n- Your friendly Customer Support System - powered by osTicket.', '[#%ticket] Message Added', '%name,\r\n\r\nYour reply to support request #%ticket has been noted.\r\n\r\nYou can view this support request progress online here: %url/view.php?e=%email&t=%ticket.\r\n\r\n%signature', 'New Message Alert', '%staff,\r\n\r\nNew message appended to ticket #%ticket\r\n\r\n----------------------\r\nName: %name\r\nEmail: %email\r\nDept: %dept\r\n\r\n%message\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.\r\n\r\n- Your friendly Customer Support System - powered by osTicket.', 'New Internal Note Alert', '%staff,\r\n\r\nInternal note appended to ticket #%ticket\r\n\r\n----------------------\r\nName: %name\r\n\r\n%note\r\n-------------------\r\n\r\nTo view/respond to the ticket, please login to the support ticket system.\r\n\r\n- Your friendly Customer Support System - powered by osTicket.', 'Ticket #%ticket Assigned to you', '%assignee,\r\n\r\n%assigner has assigned ticket #%ticket to you!\r\n\r\n%message\r\n\r\nTo view complete details, simply login to the support system.\r\n\r\n- Your friendly Support Ticket System - powered by osTicket.', 'Stale Ticket Alert', '%staff,\r\n\r\nA ticket, #%ticket assigned to you or in your department is seriously overdue.\r\n\r\n%url/scp/tickets.php?id=%id\r\n\r\nWe should all work hard to guarantee that all tickets are being addressed in a timely manner. Enough baby talk...please address the issue or you will hear from me again.\r\n\r\n\r\n- Your friendly (although with limited patience) Support Ticket System - powered by osTicket.', 'Support Ticket Denied', '%name\r\n\r\nNo support ticket has been created. You''ve exceeded maximum number of open tickets allowed.\r\n\r\nThis is a temporary block. To be able to open another ticket, one of your pending tickets must be closed. To update or add comments to an open ticket simply login using the link below.\r\n\r\n%url/view.php?e=%email\r\n\r\nThank you.\r\n\r\nSupport Ticket System', '[#%ticket] %subject', '%name,\r\n\r\nA customer support staff member has replied to your support request, #%ticket with the following response:\r\n\r\n%response\r\n\r\nWe hope this response has sufficiently answered your questions. If not, please do not send another email. Instead, reply to this email or login to your account for a complete archive of all your support requests and responses.\r\n\r\n%url/view.php?e=%email&t=%ticket\r\n\r\n%signature', '2010-12-15 17:00:05', '2010-12-15 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `ost_groups`
--

CREATE TABLE IF NOT EXISTS `ost_groups` (
  `group_id` int(10) unsigned NOT NULL auto_increment,
  `group_enabled` tinyint(1) unsigned NOT NULL default '1',
  `group_name` varchar(50) NOT NULL default '',
  `dept_access` varchar(255) NOT NULL default '',
  `can_create_tickets` tinyint(1) unsigned NOT NULL default '1',
  `can_edit_tickets` tinyint(1) unsigned NOT NULL default '1',
  `can_delete_tickets` tinyint(1) unsigned NOT NULL default '0',
  `can_close_tickets` tinyint(1) unsigned NOT NULL default '0',
  `can_transfer_tickets` tinyint(1) unsigned NOT NULL default '1',
  `can_ban_emails` tinyint(1) unsigned NOT NULL default '0',
  `can_manage_kb` tinyint(1) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`group_id`),
  KEY `group_active` (`group_enabled`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `ost_groups`
--

INSERT INTO `ost_groups` (`group_id`, `group_enabled`, `group_name`, `dept_access`, `can_create_tickets`, `can_edit_tickets`, `can_delete_tickets`, `can_close_tickets`, `can_transfer_tickets`, `can_ban_emails`, `can_manage_kb`, `created`, `updated`) VALUES
(1, 1, 'Admins', '1', 1, 1, 1, 1, 1, 1, 1, '2010-12-15 17:00:05', '2010-12-15 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `ost_help_topic`
--

CREATE TABLE IF NOT EXISTS `ost_help_topic` (
  `topic_id` int(11) unsigned NOT NULL auto_increment,
  `isactive` tinyint(1) unsigned NOT NULL default '1',
  `noautoresp` tinyint(3) unsigned NOT NULL default '0',
  `priority_id` tinyint(3) unsigned NOT NULL default '0',
  `dept_id` tinyint(3) unsigned NOT NULL default '0',
  `topic` varchar(32) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`topic_id`),
  UNIQUE KEY `topic` (`topic`),
  KEY `priority_id` (`priority_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `ost_help_topic`
--

INSERT INTO `ost_help_topic` (`topic_id`, `isactive`, `noautoresp`, `priority_id`, `dept_id`, `topic`, `created`, `updated`) VALUES
(3, 1, 0, 2, 1, 'Test Help Topic', '2010-12-15 18:30:32', '2010-12-15 18:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `ost_kb_premade`
--

CREATE TABLE IF NOT EXISTS `ost_kb_premade` (
  `premade_id` int(10) unsigned NOT NULL auto_increment,
  `dept_id` int(10) unsigned NOT NULL default '0',
  `isenabled` tinyint(1) unsigned NOT NULL default '1',
  `title` varchar(125) NOT NULL default '',
  `answer` text NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`premade_id`),
  UNIQUE KEY `title_2` (`title`),
  KEY `dept_id` (`dept_id`),
  KEY `active` (`isenabled`),
  FULLTEXT KEY `title` (`title`,`answer`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ost_kb_premade`
--

INSERT INTO `ost_kb_premade` (`premade_id`, `dept_id`, `isenabled`, `title`, `answer`, `created`, `updated`) VALUES
(1, 0, 1, 'What is BugBase (sample)?', '\r\nBugBase is a widely-used open source support ticket system, an attractive alternative to higher-cost and complex customer support systems - simple, lightweight, reliable, open source, web-based and easy to setup and use.', '2010-12-15 17:00:05', '2010-12-15 17:00:05'),
(2, 0, 1, 'Sample (with variables)', '\r\n%name,\r\n\r\nYour ticket #%ticket created on %createdate is in %dept department.\r\n\r\n', '2010-12-15 17:00:05', '2010-12-15 17:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `ost_staff`
--

CREATE TABLE IF NOT EXISTS `ost_staff` (
  `staff_id` int(11) unsigned NOT NULL auto_increment,
  `group_id` int(10) unsigned NOT NULL default '0',
  `dept_id` int(10) unsigned NOT NULL default '0',
  `username` varchar(32) NOT NULL default '',
  `firstname` varchar(32) default NULL,
  `lastname` varchar(32) default NULL,
  `passwd` varchar(128) default NULL,
  `email` varchar(128) default NULL,
  `phone` varchar(24) NOT NULL default '',
  `phone_ext` varchar(6) default NULL,
  `mobile` varchar(24) NOT NULL default '',
  `signature` tinytext NOT NULL,
  `isactive` tinyint(1) NOT NULL default '1',
  `isadmin` tinyint(1) NOT NULL default '0',
  `isvisible` tinyint(1) unsigned NOT NULL default '1',
  `onvacation` tinyint(1) unsigned NOT NULL default '0',
  `daylight_saving` tinyint(1) unsigned NOT NULL default '0',
  `append_signature` tinyint(1) unsigned NOT NULL default '0',
  `change_passwd` tinyint(1) unsigned NOT NULL default '0',
  `timezone_offset` float(3,1) NOT NULL default '0.0',
  `max_page_size` int(11) unsigned NOT NULL default '0',
  `auto_refresh_rate` int(10) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastlogin` datetime default NULL,
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`staff_id`),
  UNIQUE KEY `username` (`username`),
  KEY `dept_id` (`dept_id`),
  KEY `issuperuser` (`isadmin`),
  KEY `group_id` (`group_id`,`staff_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `ost_staff`
--

INSERT INTO `ost_staff` (`staff_id`, `group_id`, `dept_id`, `username`, `firstname`, `lastname`, `passwd`, `email`, `phone`, `phone_ext`, `mobile`, `signature`, `isactive`, `isadmin`, `isvisible`, `onvacation`, `daylight_saving`, `append_signature`, `change_passwd`, `timezone_offset`, `max_page_size`, `auto_refresh_rate`, `created`, `lastlogin`, `updated`) VALUES
(1, 1, 1, 'ross', 'Ross', 'Norvell', '2abeeaea6218c41d98e0802cd6b12a1f', 'ross@rnorvell.com', '', '', '', '', 1, 1, 1, 0, 1, 0, 0, -5.0, 25, 0, '2010-12-15 17:00:05', '2012-02-23 15:55:04', '2010-12-30 14:30:41');
-- --------------------------------------------------------

--
-- Table structure for table `ost_syslog`
--

CREATE TABLE IF NOT EXISTS `ost_syslog` (
  `log_id` int(11) unsigned NOT NULL auto_increment,
  `log_type` enum('Debug','Warning','Error') NOT NULL default 'Debug',
  `title` varchar(255) NOT NULL default '',
  `log` text NOT NULL,
  `logger` varchar(64) NOT NULL default '',
  `ip_address` varchar(16) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`log_id`),
  KEY `log_type` (`log_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;

--
-- Dumping data for table `ost_syslog`
--

INSERT INTO `ost_syslog` (`log_id`, `log_type`, `title`, `log`, `logger`, `ip_address`, `created`, `updated`) VALUES
(1, 'Debug', 'osTicket installed!', 'Congratulations osTicket basic installation completed!\n\nThank you for choosing osTicket!', '', '127.0.0.1', '2010-12-15 17:00:05', '2010-12-15 17:00:05');
-- --------------------------------------------------------

--
-- Table structure for table `ost_ticket`
--

CREATE TABLE IF NOT EXISTS `ost_ticket` (
  `ticket_id` int(11) unsigned NOT NULL auto_increment,
  `ticketID` int(11) unsigned NOT NULL default '0',
  `dept_id` int(10) unsigned NOT NULL default '1',
  `priority_id` int(10) unsigned NOT NULL default '2',
  `topic_id` int(10) unsigned NOT NULL default '0',
  `staff_id` int(10) unsigned NOT NULL default '0',
  `email` varchar(120) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  `subject` varchar(64) NOT NULL default '[no subject]',
  `helptopic` varchar(255) default NULL,
  `phone` varchar(16) default NULL,
  `phone_ext` varchar(8) default NULL,
  `ip_address` varchar(16) NOT NULL default '',
  `status` enum('open','closed') NOT NULL default 'open',
  `source` enum('Web','Email','Phone','Other') NOT NULL default 'Other',
  `isoverdue` tinyint(1) unsigned NOT NULL default '0',
  `isanswered` tinyint(1) unsigned NOT NULL default '0',
  `duedate` datetime default NULL,
  `reopened` datetime default NULL,
  `closed` datetime default NULL,
  `lastmessage` datetime default NULL,
  `lastresponse` datetime default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`ticket_id`),
  UNIQUE KEY `email_extid` (`ticketID`,`email`),
  KEY `dept_id` (`dept_id`),
  KEY `staff_id` (`staff_id`),
  KEY `status` (`status`),
  KEY `priority_id` (`priority_id`),
  KEY `created` (`created`),
  KEY `closed` (`closed`),
  KEY `duedate` (`duedate`),
  KEY `topic_id` (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1790 ;

--
-- Dumping data for table `ost_ticket`
--

INSERT INTO `ost_ticket` (`ticket_id`, `ticketID`, `dept_id`, `priority_id`, `topic_id`, `staff_id`, `email`, `name`, `subject`, `helptopic`, `phone`, `phone_ext`, `ip_address`, `status`, `source`, `isoverdue`, `isanswered`, `duedate`, `reopened`, `closed`, `lastmessage`, `lastresponse`, `created`, `updated`) VALUES
(343, 160960, 1, 2, 0, 0, 'r@example.com', 'Example', 'Ticket Example', '', '', '', '24.149.92.140', 'closed', 'Email', 0, 1, NULL, NULL, '2011-01-20 20:37:58', '2011-01-20 20:34:42', '2011-01-20 20:37:58', '2011-01-20 20:34:42', '2011-01-20 20:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `ost_timezone`
--

CREATE TABLE IF NOT EXISTS `ost_timezone` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `offset` float(3,1) NOT NULL default '0.0',
  `timezone` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `ost_timezone`
--

INSERT INTO `ost_timezone` (`id`, `offset`, `timezone`) VALUES
(1, -12.0, 'Eniwetok, Kwajalein'),
(2, -11.0, 'Midway Island, Samoa'),
(3, -10.0, 'Hawaii'),
(4, -9.0, 'Alaska'),
(5, -8.0, 'Pacific Time (US & Canada)'),
(6, -7.0, 'Mountain Time (US & Canada)'),
(7, -6.0, 'Central Time (US & Canada), Mexico City'),
(8, -5.0, 'Eastern Time (US & Canada), Bogota, Lima'),
(9, -4.0, 'Atlantic Time (Canada), Caracas, La Paz'),
(10, -3.5, 'Newfoundland'),
(11, -3.0, 'Brazil, Buenos Aires, Georgetown'),
(12, -2.0, 'Mid-Atlantic'),
(13, -1.0, 'Azores, Cape Verde Islands'),
(14, 0.0, 'Western Europe Time, London, Lisbon, Casablanca'),
(15, 1.0, 'Brussels, Copenhagen, Madrid, Paris'),
(16, 2.0, 'Kaliningrad, South Africa'),
(17, 3.0, 'Baghdad, Riyadh, Moscow, St. Petersburg'),
(18, 3.5, 'Tehran'),
(19, 4.0, 'Abu Dhabi, Muscat, Baku, Tbilisi'),
(20, 4.5, 'Kabul'),
(21, 5.0, 'Ekaterinburg, Islamabad, Karachi, Tashkent'),
(22, 5.5, 'Bombay, Calcutta, Madras, New Delhi'),
(23, 6.0, 'Almaty, Dhaka, Colombo'),
(24, 7.0, 'Bangkok, Hanoi, Jakarta'),
(25, 8.0, 'Beijing, Perth, Singapore, Hong Kong'),
(26, 9.0, 'Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
(27, 9.5, 'Adelaide, Darwin'),
(28, 10.0, 'Eastern Australia, Guam, Vladivostok'),
(29, 11.0, 'Magadan, Solomon Islands, New Caledonia'),
(30, 12.0, 'Auckland, Wellington, Fiji, Kamchatka');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
