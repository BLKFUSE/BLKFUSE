/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sesbday', 'SNS - Birthday Plugin', '', '4.10.3', 1, 'extra') ;

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sesbday', 'sesbday', 'SNS - Birthday Plugin', '', '{"route":"admin_default","module":"sesbday","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sesbday_admin_main_settings', 'sesbday', 'Global Settings', '', '{"route":"admin_default","module":"sesbday","controller":"settings"}', 'sesbday_admin_main', '', 1),
('sesbday_admin_main_birthday', 'sesbday', 'Birthday Email Template', '', '{"route":"admin_default","module":"sesbday","controller":"birthday"}', 'sesbday_admin_main', '', 2);

INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
('sesbday_birthday_email', 'sesbday', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description],[birthday_content],[birthday_subject]');
-- --------------------------------------------------------

--
-- Table structure for table `engine4_sesbday_wishes`
--

DROP TABLE IF EXISTS `engine4_sesbday_wishes`;
CREATE TABLE `engine4_sesbday_wishes` (
  `wish_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL,
  `subject_id` int(11) unsigned NOT NULL,
  `creation_date` datetime,
  PRIMARY KEY (`wish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;

INSERT IGNORE INTO `engine4_core_tasks` ( `title`, `module`, `plugin`, `timeout`, `processes`, `semaphore`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES
( 'SNS - Birthday Plugin - Birthday Wish', 'sesbday', 'Sesbday_Plugin_Task_Jobs',86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_sesbday_birthdayemailsends`
--
DROP TABLE IF EXISTS `engine4_sesbday_birthdayemailsends`;
CREATE TABLE `engine4_sesbday_birthdayemailsends` (
  `birthdayemailsend_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `creation_date` date NOT NULL,
  PRIMARY KEY (`birthdayemailsend_id`),
  KEY `uniqueKey` (`creation_date`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
      ("sesbday_birthday", "sesbday", '{item:$subject} wish you {var:$actionLink}.', 0, "", 1),
	   ("sesbday_tobirthday", "sesbday", 'Today {item:$object} Birthday.', 0, "", 1);
