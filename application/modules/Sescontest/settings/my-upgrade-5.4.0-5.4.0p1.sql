INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('sescontestjoinfees', 'SNS - Advanced Contests - Contests Joining Fees & Payments System Plugin', 'SNS - Advanced Contests - Contests Joining Fees & Payments System Plugin', '5.3.3', 1, 'extra'),
("sescontestjurymember", "SNS - Advanced Contests - Voting by Jury Members Plugin", "SNS - Advanced Contests - Voting by Jury Members Plugin", "5.3.3", 1, "extra"),
("sescontestpackage", "SNS - Advanced Contests - Packages for Allowing Contest Creation Plugin", "SNS - Advanced Contests - Packages for Allowing Contest Creation Plugin", "5.3.3", 1, "extra");

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES ("sescontest_admin_main_extension", "sescontest", "Extensions", "", '{"route":"admin_default","module":"sescontest","controller":"settings", "action": "extensions"}', "sescontest_admin_main", "", 999);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sescontest_admin_packagesetting", "sescontestpackage", "Package Settings", "", '{"route":"admin_default","module":"sescontestpackage","controller":"package","action":"settings"}', "sescontest_admin_main", "", 2),
("sescontest_admin_subpackagesetting", "sescontestpackage", "Package Settings", "", '{"route":"admin_default","module":"sescontestpackage","controller":"package","action":"settings"}', "sescontest_admin_packagesetting", "", 1),
("sescontest_main_manage_package", "sescontest", "My Packages", "Sescontest_Plugin_Menus", '{"route":"sescontest_general","action":"packages"}', "sescontest_main", "", 7);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sescontestjurymember_admin_main", "sescontestjurymember", "Voting by Jury Members", "", '{"route":"admin_default","module":"sescontestjurymember","controller":"settings"}', "sescontest_admin_main", "", 996),
('sescontestjurymember_admin_main_settings', 'sescontestjurymember', 'Global Settings', '', '{"route":"admin_default","module":"sescontestjurymember","controller":"settings"}', 'sescontestjurymember_admin_main', '', 1);

INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
("sescontestjoinfees_admin_main", "sescontestjoinfees", "Contest Joining Fees", "", '{"route":"admin_default","module":"sescontestjoinfees","controller":"settings", "action":"extension"}', "sescontest_admin_main", "", 995),
('sescontestjoinfees_admin_main_settings', 'sescontestjoinfees', 'Global Settings', '', '{"route":"admin_default","module":"sescontestjoinfees","controller":"settings","action":"extension"}', 'sescontestjoinfees_admin_main', '', 1),
("sescontestjoinfees_admin_main_currency", "sescontestjoinfees", "Manage Currency", "Sescontestjoinfees_Plugin_Menus::canViewMultipleCurrency", '{"route":"admin_default","module":"sesmultiplecurrency","controller":"settings","action":"currency","target":"_blank"}', "sescontestjoinfees_admin_main", "", 5),
("sescontestjoinfees_main_myorders", "sescontestjoinfees", "My Orders", "Sescontestjoinfees_Plugin_Menus::canViewOrders", '{"route":"sescontestjoinfees_user_order","controller":"index","action":"view"}', "sescontest_main", "", 10);
