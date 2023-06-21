 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_gift', 'egifts', 'SNS - Virtual Gifts', '', '{"route":"admin_default","module":"egifts","controller":"settings"}', 'core_admin_main_plugins', '',999),
('egifts_admin_main_settings', 'egifts', 'Global Settings', '', '{"route":"admin_default","module":"egifts","controller":"settings"}', 'egifts_admin_main', '', 1);
