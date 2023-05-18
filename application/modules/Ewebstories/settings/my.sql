 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Ewebstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2020-03-20 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sesstories', 'Stories Plugin', '', '5.0.1', 1, 'extra');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("ewebstories_admin_main_ewebstories", "ewebstories", "SNS - Stories Feature in Website", "", '{"route":"admin_default","module":"ewebstories","controller":"settings"}', "core_admin_main_plugins", "", 800),
("ewebstories_admin_main_webse", "ewebstories", "Website Stories Activation", "", '{"route":"admin_default","module":"ewebstories","controller":"settings"}', "sesstories_admin_main", "", 180),
("sesstories_admin_main_bgtextstories", "sesstories", "Backgrounds For Text Stories", "", '{"route":"admin_default","module":"sesstories","controller":"managestories","action":"index"}', "sesstories_admin_main", "", 6);

CREATE TABLE IF NOT EXISTS `engine4_sesstories_backgrounds` (
  `background_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `file_id` int(11) unsigned NOT NULL,
  `order` INT(11) NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT "1",
  PRIMARY KEY  (`background_id`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
