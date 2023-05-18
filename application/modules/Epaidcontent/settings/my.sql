/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_epaidcontent', 'epaidcontent', 'SNS - Paid User Content', '', '{"route":"admin_default","module":"epaidcontent","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('epaidcontent_admin_main_settings', 'epaidcontent', 'Global Settings', '', '{"route":"admin_default","module":"epaidcontent","controller":"settings"}', 'epaidcontent_admin_main', '', 1);
