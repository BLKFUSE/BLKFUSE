 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my-upgrade-4.10.2-4.10.3.sql 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sesmember_admin_main_adminpicks", "sesmember", "Admin Picks Members", "", '{"route":"admin_default","module":"sesmember","controller":"manage", "action":"admin-picks"}', "sesmember_admin_main", "", 999),
("sesmember_main_editormembers", "sesmember", "Editor Members", "", '{"route":"sesmember_general","action":"editormembers"}', "sesmember_main", "", 999);

ALTER TABLE `engine4_users` ADD `adminpicks` TINYINT(1) NOT NULL DEFAULT "0", ADD `order` INT(11) NOT NULL DEFAULT "0";