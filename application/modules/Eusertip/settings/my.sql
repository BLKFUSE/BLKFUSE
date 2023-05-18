/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_eusertip', 'eusertip', 'SNS - User Paid Tip', '', '{"route":"admin_default","module":"eusertip","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('eusertip_admin_main_settings', 'eusertip', 'Global Settings', '', '{"route":"admin_default","module":"eusertip","controller":"settings"}', 'eusertip_admin_main', '', 1);
