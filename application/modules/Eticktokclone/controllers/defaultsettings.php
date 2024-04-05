<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: defaultsettings.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();

//Member Profile Page
$page_id = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'user_profile_index')
            ->limit(1)
            ->query()
            ->fetchColumn();
if($page_id) {
  $main_id = $db->select()
            ->from('engine4_core_content', 'content_id')
            ->where('page_id = ?', $page_id)
            ->where('name = ?', 'main')
            ->limit(1)
            ->query()
            ->fetchColumn();

  $left_id = $db->select()
          ->from('engine4_core_content', 'content_id')
          ->where('page_id = ?', $page_id)
          ->where('name = ?', 'left')
          ->limit(1)
          ->query()
          ->fetchColumn();
          
	$right_id = $db->select()
          ->from('engine4_core_content', 'content_id')
          ->where('page_id = ?', $page_id)
          ->where('name = ?', 'right')
          ->limit(1)
          ->query()
          ->fetchColumn();

	if(!empty($left_id)) {
		$db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'eticktokclone.tikttok-profile-link',
      'page_id' => $page_id,
      'parent_content_id' => $left_id,
      'order' => 0,
    ));
	}
	
	if(!empty($right_id)) {
		$db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'eticktokclone.tikttok-profile-link',
      'page_id' => $page_id,
      'parent_content_id' => $right_id,
      'order' => 0,
    ));
	}
}
