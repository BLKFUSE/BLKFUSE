ALTER TABLE `engine4_activity_actions` ADD `sesapproved` TINYINT(1) NOT NULL DEFAULT "1";
INSERT IGNORE INTO `engine4_sesadvancedactivity_filterlists` (`filtertype`, `module`, `title`, `active`, `is_delete`, `order`, `file_id`) VALUES ('share', 'core', 'Share Feeds', '1', '0', 10, '0');