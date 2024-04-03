<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: install.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Tickvideo_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {
  
		$db = $this->getDb();
		
		// Check if it's already been placed
		$select = new Zend_Db_Select($db);
		$pageId = $select->from('engine4_core_pages', 'page_id')
							->where('name = ?', 'eticktokclone_profile_index')
							->limit(1)
							->query()
							->fetchColumn();
		if( empty($pageId) ) {
			$db->insert('engine4_core_pages', array(
				'name' => 'eticktokclone_profile_index',
				'displayname' => 'SNS - TikTok Clone - Tiktok Clone Member Profile Page',
				'title' => 'eticktokclone Profile',
				'description' => 'This is the profile for an eticktokclone.',
				'custom' => 0,
				'provides' => 'subject=eticktokclone_user',
			));
			$pageId = $db->lastInsertId('engine4_core_pages');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'main',
				'parent_content_id' => null,
				'order' => 1,
				'params' => '',
			));
			$containerId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'middle',
				'parent_content_id' => $containerId,
				'order' => 3,
				'params' => '',
			));
			$middleId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'left',
				'parent_content_id' => $containerId,
				'order' => 1,
				'params' => '',
			));
			$leftId = $db->lastInsertId('engine4_core_content');
			
			
			// middle column
			$db->insert('engine4_core_content', array(
					'page_id' => $pageId,
					'type' => 'widget',
					'name' => 'eticktokclone.member-profile-user-info',
					'parent_content_id' => $middleId,
					'order' => 1,
			));

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'widget',
				'name' => 'core.container-tabs',
				'parent_content_id' => $middleId,
				'order' => 2,
				'params' => '{"max":"6"}',
			));
			$tabId = $db->lastInsertId('engine4_core_content');

			// tabs
			$db->insert('engine4_core_content', array(
					'page_id' => $pageId,
					'type' => 'widget',
					'name' => 'eticktokclone.profile-videos',
					'parent_content_id' => $tabId,
					'order' => 1,
					'params' => '{"title":"Videos"}',
			));
			$db->insert('engine4_core_content', array(
					'page_id' => $pageId,
					'type' => 'widget',
					'name' => 'eticktokclone.profile-like-videos',
					'parent_content_id' => $tabId,
					'order' => 2,
					'params' => '{"title":"Liked","name":"eticktokclone.SETWIDGETNAME-like-videos"}',
			));
			$db->insert('engine4_core_content', array(
					'page_id' => $pageId,
					'type' => 'widget',
					'name' => 'eticktokclone.browse-members',
					'parent_content_id' => $tabId,
					'order' => 3,
					'params' => '{"type":"followers","title":"Followers","nomobile":"0","name":"eticktokclone.browse-members"}',
			));
			$db->insert('engine4_core_content', array(
					'page_id' => $pageId,
					'type' => 'widget',
					'name' => 'eticktokclone.browse-members',
					'parent_content_id' => $tabId,
					'order' => 4,
					'params' => '{"type":"followings","title":"Following","nomobile":"0","name":"eticktokclone.browse-members"}',
			));
		}

		// Check if it's already been placed
		$select = new Zend_Db_Select($db);
		$pageId = $select
				->from('engine4_core_pages', 'page_id')
				->where('name = ?', 'eticktokclone_index_tagged')
				->limit(1)
				->query()
				->fetchColumn();
		if( empty($pageId) ) {
			$db->insert('engine4_core_pages', array(
				'name' => 'eticktokclone_index_tagged',
				'displayname' => 'SNS - TikTok Clone - Tiktok Clone Tagged Videos Page',
				'title' => 'eticktokclone Profile',
				'description' => 'This is the profile for an eticktokclone.',
				'custom' => 0,
			));
			$pageId = $db->lastInsertId('engine4_core_pages');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'main',
				'parent_content_id' => null,
				'order' => 1,
				'params' => '',
			));
			$containerId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'middle',
				'parent_content_id' => $containerId,
				'order' => 3,
				'params' => '',
			));
			$middleId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'left',
				'parent_content_id' => $containerId,
				'order' => 1,
				'params' => '',
			));
			$leftId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'widget',
				'name' => 'eticktokclone.tag-view-info',
				'parent_content_id' => $middleId,
				'order' => 1,
			));
			
			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'widget',
				'name' => 'eticktokclone.tagged-videos',
				'parent_content_id' => $middleId,
				'order' => 2,
			));
		}

		// Check if it's already been placed
		$select = new Zend_Db_Select($db);
		$pageId = $select
				->from('engine4_core_pages', 'page_id')
				->where('name = ?', 'eticktokclone_index_explore')
				->limit(1)
				->query()
				->fetchColumn();
		$widgetOrder = 1;
		if( empty($pageId) ) {
			$db->insert('engine4_core_pages', array(
				'name' => 'eticktokclone_index_explore',
				'displayname' => 'SNS - TikTok Clone - Explore Page',
				'title' => 'Explore Page',
				'description' => '',
				'custom' => 0,
			));
			$pageId = $db->lastInsertId('engine4_core_pages');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'main',
				'parent_content_id' => null,
				'order' => $widgetOrder++,
				'params' => '',
			));
			$containerId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'left',
				'parent_content_id' => $containerId,
				'order' => $widgetOrder++,
				'params' => '',
			));
			$mainLeftId = $db->lastInsertId('engine4_core_content');
			
			$db->insert('engine4_core_content', array(
				'page_id' => $pageId,
				'type' => 'container',
				'name' => 'middle',
				'parent_content_id' => $containerId,
				'order' => $widgetOrder++,
				'params' => '',
			));
			$mainMiddleId = $db->lastInsertId('engine4_core_content');

			$db->insert('engine4_core_content', array(
				'type' => 'widget',
				'name' => 'eticktokclone.sidebar-links',
				'page_id' => $pageId,
				'parent_content_id' => $mainLeftId,
				'order' => $widgetOrder++,
			));
			$db->insert('engine4_core_content', array(
				'type' => 'widget',
				'name' => 'eticktokclone.suggested-members',
				'page_id' => $pageId,
				'parent_content_id' => $mainLeftId,
				'order' => $widgetOrder++,
				'params' => '{"limit":"5","title":"Suggested Members","nomobile":"0","name":"eticktokclone.suggested-members"}',
			));
			$db->insert('engine4_core_content', array(
				'type' => 'widget',
				'name' => 'eticktokclone.popular-tags',
				'page_id' => $pageId,
				'parent_content_id' => $mainLeftId,
				'order' => $widgetOrder++,
				'params' => '{"title":"Trending","name":"eticktokclone.popular-tags"}',
			));

			$db->insert('engine4_core_content', array(
				'type' => 'widget',
				'name' => 'eticktokclone.sidebar-links',
				'page_id' => $pageId,
				'parent_content_id' => $mainMiddleId,
				'order' => $widgetOrder++,
			));
			$db->insert('engine4_core_content', array(
				'type' => 'widget',
				'name' => 'eticktokclone.video-feed',
				'page_id' => $pageId,
				'parent_content_id' => $mainMiddleId,
				'order' => $widgetOrder++,
			));
			$db->insert('engine4_core_content', array(
				'type' => 'widget',
				'name' => 'eticktokclone.browse-members',
				'page_id' => $pageId,
				'parent_content_id' => $mainMiddleId,
				'order' => $widgetOrder++,
			));
		}
    parent::onInstall();
  }
}
