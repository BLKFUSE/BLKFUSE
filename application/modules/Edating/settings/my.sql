 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_edating', 'edating', 'SNS - Dating', '', '{"route":"admin_default","module":"edating","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('edating_admin_main_settings', 'edating', 'Global Settings', '', '{"route":"admin_default","module":"edating","controller":"settings"}', 'edating_admin_main', '', 1);
