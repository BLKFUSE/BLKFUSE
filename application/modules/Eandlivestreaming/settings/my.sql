 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eandlivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2019-11-07 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('elivestreaming', 'Live Streaming', 'Live Streaming', '5.1.0', 1, 'extra');
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("eandlivestreaming_admin_main_eandlivestreaming", "eandlivestreaming", "SNS - Live Streaming in Android Mobile App", "", '{"route":"admin_default","module":"eandlivestreaming","controller":"settings"}', "core_admin_main_plugins", "", 800),
("eandlivestreaming_admin_main_andset", "eandlivestreaming", "Android Live Streaming Activation", "", '{"route":"admin_default","module":"eandlivestreaming","controller":"settings"}', "elivestreaming_admin_main", "", 170);
