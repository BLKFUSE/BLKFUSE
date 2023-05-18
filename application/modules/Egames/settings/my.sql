 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my.sql 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_egames', 'egames', 'SNS - Games Plugin', '', '{"route":"admin_default","module":"egames","controller":"settings","action":"index"}', 'core_admin_main_plugins', '', 999),
('egames_admin_main_settings', 'egames', 'Global Settings', '', '{"route":"admin_default","module":"egames","controller":"settings","action":"index"}', 'egames_admin_main', '', 1),
("egames_main_browse", "egames", "Browse Games", "Egames_Plugin_Menus::canViewEgames", '{"route":"egames_general","action":"browse","icon":"fas fa-dice"}', "egames_main", "", 2),
("egames_main_manage", "egames", "My Games", "Egames_Plugin_Menus::canCreateEgames", '{"route":"egames_general","action":"manage","icon":"fa fa-user"}', "egames_main", "", 3),
("egames_main_create", "egames", "Add New Game", "Egames_Plugin_Menus::canCreateEgames", '{"route":"egames_general","action":"create","icon":"fa fa-plus"}', "egames_main", "", 4);
