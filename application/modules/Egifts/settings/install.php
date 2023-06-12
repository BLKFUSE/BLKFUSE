<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: install.php 2020-06-08  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {
  
    $db = $this->getDb();
    
		$pageId = $db->select()
								->from('engine4_core_pages', 'page_id')
								->where('name = ?', 'egifts_index_my-orders')
								->limit(1)
								->query()
								->fetchColumn();
		if( !$pageId ) {
			// Insert page
			$db->insert('engine4_core_pages', array(
					'name' => 'egifts_index_my-orders',
					'displayname' => 'SNS - Gifts - My Orders Page',
					'title' => 'My Orders',
					'description' => '',
					'custom' => 0,
			));
			$pageId = $db->lastInsertId();

			// Insert top
			$db->insert('engine4_core_content', array(
					'type' => 'container',
					'name' => 'top',
					'page_id' => $pageId,
					'order' => 1,
			));
			$topId = $db->lastInsertId();

			// Insert main
			$db->insert('engine4_core_content', array(
					'type' => 'container',
					'name' => 'main',
					'page_id' => $pageId,
					'order' => 2,
			));
			$mainId = $db->lastInsertId();

			// Insert top-middle
			$db->insert('engine4_core_content', array(
					'type' => 'container',
					'name' => 'middle',
					'page_id' => $pageId,
					'parent_content_id' => $topId,
			));
			$topMiddleId = $db->lastInsertId();

			// Insert main-middle
			$db->insert('engine4_core_content', array(
					'type' => 'container',
					'name' => 'middle',
					'page_id' => $pageId,
					'parent_content_id' => $mainId,
					'order' => 2,
			));
			$mainMiddleId = $db->lastInsertId();

			// Insert content
			$db->insert('engine4_core_content', array(
					'type' => 'widget',
					'name' => 'egifts.browse-menu',
					'page_id' => $pageId,
					'parent_content_id' => $mainMiddleId,
					'order' => 1,
			));
			
			// Insert content
			$db->insert('engine4_core_content', array(
					'type' => 'widget',
					'name' => 'egifts.my-orders',
					'page_id' => $pageId,
					'parent_content_id' => $mainMiddleId,
					'order' => 1,
			));
		}
    parent::onInstall();
  }
}
