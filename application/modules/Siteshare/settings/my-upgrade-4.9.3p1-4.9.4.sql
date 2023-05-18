-- --------------------------------------------------------

--
-- Table structure for table `engine4_siteshare_socialshare_histories`
--

DROP TABLE IF EXISTS `engine4_siteshare_socialshare_histories`;
CREATE TABLE `engine4_siteshare_socialshare_histories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `service_type` varchar(128) NOT NULL,
  `share_url` varchar(512) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `service_type` (`service_type`),
  KEY `share_url` (`share_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;

INSERT IGNORE INTO `engine4_core_menuitems` ( `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("siteshare_admin_main_social_services_status", "siteshare", "Social Sites Share Stats", "", '{"route":"admin_default","module":"siteshare","controller":"manage", "action": "social-services-states"}', "siteshare_admin_main", "", 1, 0, 10);