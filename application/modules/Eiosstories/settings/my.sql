 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eiosstories
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2019-11-07 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sesstories', 'Stories Plugin', '', '5.0.1', 1, 'extra');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("eiosstories_admin_main_eiosstories", "eiosstories", "SNS - Stories Feature in iOS Mobile App", "", '{"route":"admin_default","module":"eiosstories","controller":"settings"}', "core_admin_main_plugins", "", 800),
("eiosstories_admin_main_iosse", "eiosstories", "iOS Stories Activation", "", '{"route":"admin_default","module":"eiosstories","controller":"settings"}', "sesstories_admin_main", "", 160);
