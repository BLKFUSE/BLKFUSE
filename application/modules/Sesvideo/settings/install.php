<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Installer extends Engine_Package_Installer_Module {


  public function onInstall() {
    $db = $this->getDb();

    $db->query('INSERT IGNORE INTO `engine4_core_menuitems` ( `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES ("mobi_browse_sesvideo", "sesvideo", "Videos","", \'{"route":"sesvideo_general"}\', "mobi_browse", NULL, "1", "0", "8");');

		$table_exist_ratings = $db->query('SHOW TABLES LIKE \'engine4_sesvideo_ratings\'')->fetch();
		if (empty($table_exist_ratings)) {
			$db->query('DROP TABLE IF EXISTS `engine4_sesvideo_ratings`;');
			$db->query('CREATE TABLE IF NOT EXISTS `engine4_sesvideo_ratings` (
				`rating_id`  int(11) unsigned NOT NULL auto_increment,
				`resource_id` int(11) NOT NULL,
				`resource_type` varchar(128) NOT NULL,
				`user_id` int(9) unsigned NOT NULL,
				`rating` tinyint(1) unsigned DEFAULT NULL,
				`creation_date` DATETIME NOT NULL ,
				`video_id` int(11) NOT NULL,
				PRIMARY KEY  (`rating_id`),
				UNIQUE KEY `uniqueKey` (`user_id`,`resource_type`,`resource_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;');

			//SE video plugin check
			$select = new Zend_Db_Select($db);
			$select->from('engine4_core_modules')
						->where('name = ?', 'video');
			$sevideo_enabled = $select->query()->fetchObject();
			if (!empty($sevideo_enabled)) {
				$db->query('INSERT IGNORE INTO engine4_sesvideo_ratings (`resource_id`, `resource_type`, `user_id`, `rating`, `video_id`)  select video_id,"video", user_id,rating, video_id from engine4_video_ratings;');
			}
		}

		$table_exist_video = $db->query('SHOW TABLES LIKE \'engine4_sesvideo_videos\'')->fetch();
		if (!empty($table_exist_video)) {
			$importthumbnail = $db->query('SHOW COLUMNS FROM engine4_sesvideo_videos LIKE \'importthumbnail\'')->fetch();
			if (empty($importthumbnail)) {
				$db->query('ALTER TABLE  `engine4_sesvideo_videos` ADD  `importthumbnail` TINYINT( 1 ) NOT NULL DEFAULT "0";');
			}
		}
    
    $this->_addHashtagSearchContent();
    parent::onInstall();
  }
  
  protected function _addHashtagSearchContent() {

    $db = $this->getDb();
    $select = new Zend_Db_Select($db);

    // hashtag search page
    $select
        ->from('engine4_core_pages')
        ->where('name = ?', 'core_hashtag_index')
        ->limit(1);
    $pageId = $select->query()->fetchObject()->page_id;

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $select
        ->from('engine4_core_content')
        ->where('page_id = ?', $pageId)
        ->where('type = ?', 'widget')
        ->where('name = ?', 'sesvideo.browse-video');
    $info = $select->query()->fetch();

    if( empty($info) ) {

        // container_id (will always be there)
        $select = new Zend_Db_Select($db);
        $select
            ->from('engine4_core_content')
            ->where('page_id = ?', $pageId)
            ->where('type = ?', 'container')
            ->limit(1);
        $containerId = $select->query()->fetchObject()->content_id;

        // middle_id (will always be there)
        $select = new Zend_Db_Select($db);
        $select
            ->from('engine4_core_content')
            ->where('parent_content_id = ?', $containerId)
            ->where('type = ?', 'container')
            ->where('name = ?', 'middle')
            ->limit(1);
        $middleId = $select->query()->fetchObject()->content_id;

        // tab_id (tab container) may not always be there
        $select
            ->reset('where')
            ->where('type = ?', 'widget')
            ->where('name = ?', 'core.container-tabs')
            ->where('page_id = ?', $pageId)
            ->limit(1);
        $tabId = $select->query()->fetchObject();
        if( $tabId && @$tabId->content_id ) {
            $tabId = $tabId->content_id;
        } else {
            $tabId = null;
        }
        $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'sesvideo.browse-video',
          'page_id' => $pageId,
          'parent_content_id' => ($tabId ? $tabId : $middleId),
          'order' => 100,
          'params' => '{"enableTabs":["list","grid","pinboard"],"openViewType":"grid","show_criteria":["watchLater","favouriteButton","playlistAdd","likeButton","socialSharing","like","favourite","comment","rating","view","title","category","by","duration","descriptionlist","descriptionpinboard","enableCommentPinboard"],"sort":"mostSPliked","title_truncation_list":"70","title_truncation_grid":"30","description_truncation_list":"230","description_truncation_grid":"45","description_truncation_pinboard":"60","height_list":"180","width_list":"260","height_grid":"270","width_grid":"305","width_pinboard":"305","limit_data_pinboard":"10","limit_data_grid":"15","limit_data_list":"20","pagging":"pagging","title":"","nomobile":"0","name":"sesvideo.browse-video"}',
        ));
    }
  }
  
	public function onDisable(){
		 $db = $this->getDb();
		$db->query("UPDATE engine4_core_jobtypes SET plugin = 'Video_Plugin_Job_Encode',title = 'Video Encode',module='video' WHERE plugin = 'Sesvideo_Plugin_Job_Encode'");

		$db->query("UPDATE engine4_core_jobtypes SET plugin = 'Video_Plugin_Job_Maintenance_RebuildPrivacy' ,title = 'Rebuild Video Privacy',module='video' WHERE plugin = 'Sesvideo_Plugin_Job_Maintenance_RebuildPrivacy'");
		parent::onDisable();
 }
 public function onEnable(){
	  $db = $this->getDb();
	 $db->query("UPDATE engine4_core_jobtypes SET plugin = 'Sesvideo_Plugin_Job_Encode',title = 'Advanced Videos & Channels Plugin Video Encode',module='sesvideo' WHERE plugin = 'Video_Plugin_Job_Encode'");

		$db->query("UPDATE engine4_core_jobtypes SET plugin = 'Sesvideo_Plugin_Job_Maintenance_RebuildPrivacy',module='sesvideo' ,title = 'Advanced Videos & Channels Plugin Rebuild Video Privacy' WHERE plugin = 'Video_Plugin_Job_Maintenance_RebuildPrivacy'");
		parent::onEnable();
 }
}
