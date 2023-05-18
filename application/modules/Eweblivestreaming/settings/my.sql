 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eweblivestreaming
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2020-07-05  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('elivestreaming', 'Live Streaming', 'Live Streaming', '5.1.0', 1, 'extra');

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("eweblivestreaming_admin_main_eweblivestreaming", "eweblivestreaming", "SNS - Live Streaming in Website", "", '{"route":"admin_default","module":"eweblivestreaming","controller":"settings"}', "core_admin_main_plugins", "", 800),
("eweblivestreaming_admin_main_webse", "eweblivestreaming", "Live Streaming Activation", "", '{"route":"admin_default","module":"eweblivestreaming","controller":"settings"}', "elivestreaming_admin_main", "", 180);
