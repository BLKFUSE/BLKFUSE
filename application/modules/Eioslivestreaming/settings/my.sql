 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eioslivestreaming
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2020-06-01  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('elivestreaming', 'Live Streaming', 'Live Streaming', '5.1.0', 1, 'extra');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("eioslivestreaming_admin_main_eioslivestreaming", "eioslivestreaming", "SNS - Live Streaming in iOS Mobile App", "", '{"route":"admin_default","module":"eioslivestreaming","controller":"settings"}', "core_admin_main_plugins", "", 800),
("eioslivestreaming_admin_main_iosse", "eioslivestreaming", "iOS Live Streaming Activation", "", '{"route":"admin_default","module":"eioslivestreaming","controller":"settings"}', "elivestreaming_admin_main", "", 160);
