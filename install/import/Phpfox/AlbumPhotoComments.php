<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Phpfoximporter
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    AlbumPhotoComments.php 2015-07-30 00:00:00Z john $
 * @author     John
 */
class Install_Import_Phpfox_AlbumPhotoComments extends Install_Import_Phpfox_AbstractComments
{

  protected $_fromResourceType = 'photo';
  protected $_toResourceType = 'album_photo';
  protected $_isTableExist = array('engine4_album_albums');
  protected $_fromWhere = array('type_id=?' => 'photo');
  protected $_warningMessage = array("key" => 'general', 'value' => '<span style="font-weight:bolder;">Warning:</span>  Your SocialEngine PHP site have not installed some of the SocialEngine official core plugins, which leads to not migrating the related content of those plugins. You may ignore this message or you can install these plugins to migrate the related content of these plugins. <a target="_blank" href="manage" > Click here</a>  to install these plugins.');

}

/*
CREATE TABLE IF NOT EXISTS `phpfox_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `type_id` varchar(75) NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `owner_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `time_stamp` int(10) unsigned NOT NULL,
  `update_time` int(10) unsigned NOT NULL DEFAULT '0',
  `update_user` varchar(100) DEFAULT NULL,
  `rating` varchar(10) DEFAULT NULL,
  `ip_address` varchar(15) NOT NULL,
  `author` varchar(255) DEFAULT NULL,
  `author_email` varchar(100) DEFAULT NULL,
  `author_url` varchar(255) DEFAULT NULL,
  `view_id` tinyint(1) NOT NULL DEFAULT '0',
  `child_total` smallint(4) unsigned NOT NULL DEFAULT '0',
  `total_like` int(10) unsigned NOT NULL DEFAULT '0',
  `total_dislike` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `user_id` (`user_id`,`view_id`),
  KEY `owner_user_id` (`owner_user_id`,`view_id`),
  KEY `type_id` (`type_id`,`item_id`,`view_id`),
  KEY `parent_id` (`parent_id`,`view_id`),
  KEY `parent_id_2` (`parent_id`,`type_id`,`item_id`,`view_id`),
  KEY `view_id` (`view_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

 */

/*
CREATE TABLE IF NOT EXISTS `phpfox_comment_text` (
  `comment_id` int(10) unsigned NOT NULL,
  `text` mediumtext,
  `text_parsed` mediumtext,
  KEY `comment_id` (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
 */

/*
CREATE TABLE IF NOT EXISTS `engine4_core_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `like_count` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_comment_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `resource_type` (`resource_type`,`resource_id`),
  KEY `poster_type` (`poster_type`,`poster_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;
 * 
 */
