/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sescredit', 'sescredit', 'SNS - Credits & Activity Poin...', '', '{"route":"admin_default","module":"sescredit","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sescredit_admin_main_settings', 'sescredit', 'Global Settings', '', '{"route":"admin_default","module":"sescredit","controller":"settings"}', 'sescredit_admin_main', '', 1);


INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('sescredit_admin_main_manageModule', 'sescredit', 'Manage Module', '', '{"route":"admin_default","module":"sescredit","controller":"settings","action":"manage-module"}', 'sescredit_admin_main', '', 6);


DROP TABLE IF EXISTS `engine4_sescredit_managemodules`;
CREATE TABLE `engine4_sescredit_managemodules` (
`managemodule_id` int(11) unsigned NOT NULL auto_increment,
`module` varchar (255) not null ,
`type` varchar (45) not  null ,
`title` varchar (255) not null ,
`min_credit` int (11) NOT NULL,
`min_checkout_price` int (11) NOT NULL,
`limit_use` varchar(255) NOT NULL,
`enabled` tinyint(1) NOT NULL default "1",
PRIMARY KEY (`managemodule_id`),
KEY (`type`,`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;


INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('estore','sesproduct_order', 'SNS - Stores Marketplace Plugin', 0, 0, '0', 1);


INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('estorepackage','estorepackage', 'SNS - Store Directories Plugin - Packages for Allowing Store Creation Extension', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sespagepackage','sespagepackage', 'SNS - Page Directories - Packages for Allowing Page Creation Extension', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sesgrouppackage','sesgrouppackage', 'SNS - Group Communities - Packages for Allowing Group Creation Extension', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sesbusinesspackage','sesbusinesspackage', 'SNS - Business Directories - Packages for Allowing Business Creation Extension', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sescontestpackage','sescontestpackage', 'SNS - Advanced Contests - Packages for Allowing Contest Creation Plugin', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sescontestjoinfees','sescontestjoinfees', 'SNS - Advanced Contests - Contests Joining Fees & Payments System Plugin', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sescommunityads','sescommunityads', 'SNS - Community Advertisements Plugin', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('sesevent','sesevent', 'SNS - Advanced Events Plugin', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('courses','courses', 'Courses - Learning Management System', 0, 0, '0', 1);

INSERT IGNORE INTO `engine4_sescredit_managemodules` ( `module`,`type`, `title`, `min_credit`, `min_checkout_price`, `limit_use`, `enabled`) VALUES ('egifts','egifts', 'SNS - Gifts', 0, 0, '0', 1);
