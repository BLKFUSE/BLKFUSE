 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: my-upgrade-4.9.3-4.9.4.sql 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("sesmember_admin_main_browsememberspage", "sesmember", "Browse Pages for Profile Types", "", '{"route":"admin_default","module":"sesmember","controller":"manage", "action":"manage-browsepage"}', "sesmember_admin_main", "", 1, 0, 999);