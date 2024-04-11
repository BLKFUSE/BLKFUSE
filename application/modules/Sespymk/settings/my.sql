INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_admin_main_plugins_sespymk', 'sespymk', 'SNS - People You May Know', '', '{"route":"admin_default","module":"sespymk","controller":"settings"}', 'core_admin_main_plugins', '', 999),
('sespymk_admin_main_settings', 'sespymk', 'Global Settings', '', '{"route":"admin_default","module":"sespymk","controller":"settings"}', 'sespymk_admin_main', '', 1);


CREATE TABLE `engine4_sespymk_ignores` (
  `ignore_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `owner_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`ignore_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;