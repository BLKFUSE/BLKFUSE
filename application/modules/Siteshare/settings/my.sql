INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('siteshare', 'Advanced Share Plugin', 'Advanced Share Plugin', '5.1.0', 1, 'extra') ;

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

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_menuitems`
--

INSERT IGNORE INTO `engine4_core_menuitems` ( `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
("siteshare_admin_main_settings", "siteshare", "Global Settings", "", '{"route":"admin_default","module":"siteshare","controller":"settings"}', "siteshare_admin_main", "", 1, 0, 1),
("core_admin_main_plugins_siteshare", "siteshare", "Advanced Share Plugin", "", '{"route":"admin_default","module":"siteshare","controller":"settings"}', "core_admin_main_plugins", "", 1, 0, 999),
("siteshare_admin_main_faq", "siteshare", "FAQ", "", '{"route":"admin_default","module":"siteshare","controller":"settings","action":"faq"}', "siteshare_admin_main", "", 1, 0, 999),
("siteshare_admin_main_manage", "siteshare", "Manage Sharing Within Community", "", '{"route":"admin_default","module":"siteshare","controller":"manage"}', "siteshare_admin_main", "", 1, 0, 3),
("siteshare_admin_main_socialsites", "siteshare", "Manage Sharing Outside Community", "", '{"route":"admin_default","controller":"menus", "action":"index","target":"_blank", "params": {"name": "siteshare_social_link"}}', "siteshare_admin_main", "", 1, 0, 2),
("siteshare_social_link_facebook", "siteshare", "Facebook", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-facebook","uri":"https://www.facebook.com/sharer/sharer.php?u=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 1),
("siteshare_social_link_twitter", "siteshare", "Twitter", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-twitter","uri":"http://twitter.com/share?text=CONTENT_TITLE &url=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 2),
("siteshare_social_link_pinterest", "siteshare", "Pinterest", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-pinterest","uri":"http://pinterest.com/pin/create/button/?url=CONTENT_URI&media=CONTENT_MEDIA&description=CONTENT_DESCRIPTION","target":"_blank"}', "siteshare_social_link", "", 1, 0, 3),
("siteshare_social_link_linkedin", "siteshare", "Linkedin", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-linkedin","uri":"https://www.linkedin.com/shareArticle?mini=true&url=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 4),
("siteshare_social_link_googleplus", "siteshare", "Google+", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-google-plus","uri":"https://plus.google.com/share?url=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 5),
("siteshare_social_link_mailgoogle", "siteshare", "Gmail", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-envelope","uri":"https://mail.google.com/mail/u/1/?view=cm&fs=1&body=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 6),
("siteshare_social_link_whatsapp", "siteshare", "Whatsapp", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-whatsapp","uri":"whatsapp://send?text=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 7),
("siteshare_social_link_sms", "siteshare", "Message", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-commenting","uri":"sms://send?body=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 8),
("siteshare_social_link_skype", "siteshare", "Skype", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-skype","uri":"https://web.skype.com/share?url=CONTENT_URI&lang=en","target":"_blank"}', "siteshare_social_link", "", 1, 0, 9),
("siteshare_social_link_mail", "siteshare", "Email", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-envelope","uri":"/share/send-email?link=CONTENT_URI&subject=CONTENT_GUID", "target":"_blank"}', "siteshare_social_link", "", 1, 0, 10),
("siteshare_social_link_rediff", "siteshare", "Rediff", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-rediff","uri":"http://share.rediff.com/bookmark/addbookmark?bookmarkurl=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 11),
("siteshare_social_link_yahoomail", "siteshare", "Yahoo!", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-yahoo","uri":"http://compose.mail.yahoo.com/?body=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 12),
("siteshare_social_link_bookmark", "siteshare", "Google Bookmark", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-bookmark","uri":"https://www.google.com/bookmarks/mark?op=edit&output=popup&bkmk=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 13),
("siteshare_social_link_newsvine", "siteshare", "Newsvine", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-newsvine","uri":"http://www.newsvine.com/_wine/save?u=CONTENT_URI&h=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 14),
("siteshare_social_link_reddit", "siteshare", "Reddit", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-reddit-alien","uri":"http://www.reddit.com/submit?url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 15),
("siteshare_social_link_technorati", "siteshare", "Technorati", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-technorati","uri":"http://www.technorati.com/faves?add=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 0, 0, 16),
("siteshare_social_link_digg", "siteshare", "Digg", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-digg","uri":"http://digg.com/submit?phase=2&amp;url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 17),
("siteshare_social_link_delicious", "siteshare", "del.icio.us", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-delicious","uri":"http://del.icio.us/post?url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 18),
("siteshare_social_link_stumbleupon", "siteshare", "Stumbleupon", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-stumbleupon","uri":"http://www.stumbleupon.com/submit?url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 19),
("siteshare_social_link_friend_feed", "siteshare", "Friend Feed", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-friend_feed","uri":"http://friendfeed.com/share?url=CONTENT_URI","target":"_blank"}', "siteshare_social_link", "", 1, 0, 20),
("siteshare_social_link_myspace", "siteshare", "My Space", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-myspace","uri":"http://www.myspace.com/Modules/PostTo/Pages/?l=3&u=CONTENT_URI&t=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 21),
("siteshare_social_link_flipboard", "siteshare", "Flipboard", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-flipboard","uri":"https://share.flipboard.com/bookmarklet/popout?v=2&url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 22),
("siteshare_social_link_vk", "siteshare", "VK", "Siteshare_Plugin_Menus::setMenu", '{"class":"fa-vk","uri":"https://vk.com/share.php?url=CONTENT_URI&title=CONTENT_TITLE","target":"_blank"}', "siteshare_social_link", "", 1, 0, 23),
("siteshare_admin_main_social_services_status", "siteshare", "Social Sites Share Stats", "", '{"route":"admin_default","module":"siteshare","controller":"manage", "action": "social-services-states"}', "siteshare_admin_main", "", 1, 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_menus`
--

INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
("siteshare_social_link", "standard", "Social Share Link Menu");

-- --------------------------------------------------------

--
-- Table structure for table `engine4_siteshare_sharetypes`
--

DROP TABLE IF EXISTS `engine4_siteshare_sharetypes`;
CREATE TABLE `engine4_siteshare_sharetypes` (
  `sharetype_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `type` varchar(100) NOT NULL,
  `module_name` varchar(100) NOT NULL,
  `share_allow` varchar(10) NOT NULL,
  `notification_allow` varchar(10) NOT NULL,
  `enabled` tinyint(4) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 999,
  `params` text NOT NULL,
   PRIMARY KEY (`sharetype_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;


INSERT IGNORE INTO `engine4_siteshare_sharetypes` (`title`, `type`, `module_name`, `share_allow`, `notification_allow`, `enabled`, `order`, `params`) VALUES
("Share on your Profile", "timeline", "activity", "owner", "owner", 1, 1, '{"membership":"user","owner":"user_id","admin":null}'),
("Share on Friend Profile", "user", "user", "member", "owner", 1, 2, '{"membership":"user","owner":"user_id","admin":null}'),
("Share via Email", "email", "core", "owner", "owner", 1, 3, '{"membership":null,"owner":null,"admin":null}'),
("Share via Message", "message", "user", "owner", "owner", 1, 4, '{"membership":"user","owner":"user_id","admin":null}'),
("Share on Group Profile", "group", "group", "owner", "owner", 1, 5, '{"membership":1,"owner":"user_id","admin":1}'),
("Share On Event Profile", "event", "event", "owner", "owner", 1, 6, '{"membership":1,"owner":"user_id","admin":0}'),
("Share On Group Profile", "sitegroup_group", "sitegroup", "owner", "owner", 1, 999, '{"membership":1,"owner":"owner_id","admin":1}'),
("Share On Page Profile", "sitepage_page", "sitepage", "owner", "owner", 1, 999, '{"membership":1,"owner":"owner_id","admin":1}'),
("Share On Store Profile", "sitestore_store", "sitestore", "owner", "owner", 1, 999, '{"membership":1,"owner":"owner_id","admin":1}'),
("Share On Business Profile", "sitebusiness_business", "sitebusiness", "owner", "owner", 1, 999, '{"membership":1,"owner":"owner_id","admin":1}');

INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
("share_on_event", "event", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 1, 1, 0),
("share_on_group", "group", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 1, 1, 0),
("share_on_sitepage_page", "sitepage", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 4, 1, 0),
("share_on_sitegroup_group", "sitegroup", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 4, 1, 0),
("share_on_sitestore_store", "sitestore", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 1, 1, 0),
("share_on_sitebusiness_business", "sitebusiness", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 4, 1, 0),
("share_content", "siteshare", "{actors:$subject:$object} shared {var:$type}.\n\r{body:$body}", 1, 7, 1, 4, 1, 0);


INSERT IGNORE INTO `engine4_core_mailtemplates` (`type`, `module`, `vars`) VALUES
("SITESHARE_ITEM_EMAIL", "siteshare", "[host],[email],[sender_name],[item_media_type],[object_title],[object_link],[""message""]");


INSERT IGNORE INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
("sitegroup_share_self", "siteshare", "{item:$object} has shared.\r\n{body:$body}", 1, 7, 1, 1, 1, 0),
("sitepage_share_self", "siteshare", "{item:$object} has shared.\n{body:$body}", 1, 7, 1, 1, 1, 0);


INSERT IGNORE INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`, `default`) VALUES
("shared_user", "siteshare", "{item:$subject} has shared a {item:$object:$label} with you.", 0, "", 1),
("shared_content_self", "siteshare", "{item:$subject} has shared {item:$object:$label}.", 0, "", 1),
("shared_content", "siteshare", "{item:$subject} has shared a {item:$attachment:$label} in the {item:$object:$sharedOnTitle}.", 0, "", 1);