/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: my.sql  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sescontestjoinfees', 'SNS - Advanced Contests - Contests Joining Fees & Payments System Plugin', 'SNS - Advanced Contests - Contests Joining Fees & Payments System Plugin', '5.3.3', 1, 'extra'),
("sescontestjurymember", "SNS - Advanced Contests - Voting by Jury Members Plugin", "SNS - Advanced Contests - Voting by Jury Members Plugin", "5.3.3", 1, "extra"),
("sescontestpackage", "SNS - Advanced Contests - Packages for Allowing Contest Creation Plugin", "SNS - Advanced Contests - Packages for Allowing Contest Creation Plugin", "5.3.3", 1, "extra");
 
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sescontest', 'sescontest', 'SNS - Advanced Contests', '', '{"route":"admin_default","module":"sescontest","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sescontest_admin_main_settings', 'sescontest', 'Global Settings', '', '{"route":"admin_default","module":"sescontest","controller":"settings"}', 'sescontest_admin_main', '', 1),
("sescontest_quick_create", "sescontest", "Create New Contest", "Sescontest_Plugin_Menus::canCreateContests", '{"route":"sescontest_general","action":"create","class":"buttonlink icon_sescontest_new"}', "sescontest_quick", "", 1);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sesbasic_admin_tooltip", "sesbasic", "Tooltip Settings", "", '{"route":"admin_default","module":"sesbasic","controller":"tooltip","action":"index"}', "sesbasic_admin_main", "", 4),
("sesbasic_admin_main_generaltooltip", "sesbasic", "General Settings", "", '{"route":"admin_default","module":"sesbasic","controller":"tooltip","action":"index"}', "sesbasic_admin_tooltipsettings", "", 1),
("sesbasic_admin_main_sescontest", "sesbasic", "Advanced Contests", "", '{"route":"admin_default","module":"sesbasic","controller":"tooltip","action":"index","modulename":"sescontest_contest"}', "sesbasic_admin_tooltipsettings", "", 2);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sescontestjoinfees_admin_main_currency", "sescontestjoinfees", "Manage Currency", "Sescontestjoinfees_Plugin_Menus::canViewMultipleCurrency", '{"route":"admin_default","module":"sesmultiplecurrency","controller":"settings","action":"currency","target":"_blank"}', "sescontestjoinfees_admin_main", "", 5),
("sescontestjoinfees_main_myorders", "sescontestjoinfees", "My Orders", "Sescontestjoinfees_Plugin_Menus::canViewOrders", '{"route":"sescontestjoinfees_user_order","controller":"index","action":"view"}', "sescontest_main", "", 10);
