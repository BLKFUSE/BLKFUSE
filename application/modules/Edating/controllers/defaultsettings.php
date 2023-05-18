<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: defaultsettings.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();

//Default Privacy Set Work
$permissionsTable = Engine_Api::_()->getDbTable('permissions', 'authorization');
foreach (Engine_Api::_()->getDbTable('levels', 'authorization')->fetchAll() as $level) {
  $form = new Edating_Form_Admin_Settings_Level(array(
      'public' => ( engine_in_array($level->type, array('public')) ),
      'moderator' => (engine_in_array($level->type, array('admin', 'moderator'))),
  ));
  $values = $form->getValues();
  $valuesForm = $permissionsTable->getAllowed('edating_dating', $level->level_id, array_keys($form->getValues()));

  $form->populate($valuesForm);
  if ($form->defattribut)
    $form->defattribut->setValue(0);
  $db = $permissionsTable->getAdapter();
  $db->beginTransaction();
  try {
    $nonBooleanSettings = $form->nonBooleanFields();
    $permissionsTable->setAllowed('edating_dating', $level->level_id, $values, '', $nonBooleanSettings);
    // Commit
    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
    throw $e;
  }
}


$pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'edating_index_already-viewed')
            ->limit(1)
            ->query()
            ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
    'name' => 'edating_index_already-viewed',
    'displayname' => 'SNS - Already Viewed Members Page',
    'title' => 'Already Viewed Members',
    'description' => 'This page show all member which viewer viewed.',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $pageId,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $pageId,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_right_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  //widgets
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $top_middle_id,
    'order' => 1,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.already-viewed',
    'page_id' => $pageId,
    'parent_content_id' => $main_middle_id,
    'order' => 2,
  ));
}

$pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'edating_index_mutual-likes')
            ->limit(1)
            ->query()
            ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
    'name' => 'edating_index_mutual-likes',
    'displayname' => 'SNS - Mutual Likes Page',
    'title' => 'Mutual Likes',
    'description' => 'This page show all mutual likes.',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $pageId,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $pageId,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_right_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  //widgets
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $top_middle_id,
    'order' => 1,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.mutual-likes',
    'page_id' => $pageId,
    'parent_content_id' => $main_middle_id,
    'order' => 2,
  ));
}

$pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'edating_index_who-like-me')
            ->limit(1)
            ->query()
            ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
    'name' => 'edating_index_who-like-me',
    'displayname' => 'SNS - Who Like Me Page',
    'title' => 'Who Like Me',
    'description' => 'This page show all members who like me.',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $pageId,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $pageId,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_right_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  //widgets
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $top_middle_id,
    'order' => 1,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.who-like-me',
    'page_id' => $pageId,
    'parent_content_id' => $main_middle_id,
    'order' => 2,
  ));
}


$pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'edating_index_my-likes')
            ->limit(1)
            ->query()
            ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
    'name' => 'edating_index_my-likes',
    'displayname' => 'SNS - My Likes Page',
    'title' => 'My Likes',
    'description' => 'This page show all my like members.',
    'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'top',
    'page_id' => $pageId,
    'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $pageId,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_right_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  //widgets
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $top_middle_id,
    'order' => 1,
  ));
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.my-likes',
    'page_id' => $pageId,
    'parent_content_id' => $main_middle_id,
    'order' => 2,
  ));
}


$pageId = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'edating_index_browse')
              ->limit(1)
              ->query()
              ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
    'name' => 'edating_index_browse',
    'displayname' => 'SNS - Dating Browse Page',
    'title' => 'Dating Browse',
    'description' => 'This page lists all members.',
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
  $top_id = $db->lastInsertId();

  // Insert main
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'main',
    'page_id' => $pageId,
    'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  // Insert top-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  // Insert main-right
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'right',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 1,
  ));
  $main_right_id = $db->lastInsertId();

  // Insert main-middle
  $db->insert('engine4_core_content', array(
    'type' => 'container',
    'name' => 'middle',
    'page_id' => $pageId,
    'parent_content_id' => $main_id,
    'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  //widgets
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-menu',
    'page_id' => $pageId,
    'parent_content_id' => $top_middle_id,
    'order' => 1,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-users',
    'page_id' => $pageId,
    'parent_content_id' => $main_middle_id,
    'order' => 2,
    'params' => '{"showinfo":"1","cancelbutton":"1","limit_data":"2","title":"","nomobile":"0","name":"edating.browse-users"}',
  ));
  
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'edating.browse-search',
    'page_id' => $pageId,
    'parent_content_id' => $main_right_id,
    'order' => 3,
    'params' => '{"viewType":"vertical","title":"","nomobile":"0","name":"edating.browse-search"}',
  ));
}


$pageId = $db->select()
              ->from('engine4_core_pages', 'page_id')
              ->where('name = ?', 'edating_index_settings')
              ->limit(1)
              ->query()
              ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
      'name' => 'edating_index_settings',
      'displayname' => 'SNS - Dating Settings Page',
      'title' => 'Dating Settings',
      'description' => 'This page for user dating settings.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $pageId,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'right',
      'page_id' => $pageId,
      'parent_content_id' => $main_id,
      'order' => 1,
  ));
  $main_right_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $main_id,
      'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'edating.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $top_middle_id,
      'order' => 1,
  ));

  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'core.content',
    'page_id' => $pageId,
    'parent_content_id' => $main_middle_id,
    'order' => 2,
  ));

}


$pageId = $db->select()
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'edating_index_photos')
            ->limit(1)
            ->query()
            ->fetchColumn();
if( !$pageId ) {
  $db->insert('engine4_core_pages', array(
      'name' => 'edating_index_photos',
      'displayname' => 'SNS - Dating Photos Page',
      'title' => 'Dating Photos',
      'description' => 'This page lists dating photos.',
      'custom' => 0,
  ));
  $pageId = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'top',
      'page_id' => $pageId,
      'order' => 1,
  ));
  $top_id = $db->lastInsertId();
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'main',
      'page_id' => $pageId,
      'order' => 2,
  ));
  $main_id = $db->lastInsertId();

  // Insert top-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $top_id,
  ));
  $top_middle_id = $db->lastInsertId();

  // Insert main-middle
  $db->insert('engine4_core_content', array(
      'type' => 'container',
      'name' => 'middle',
      'page_id' => $pageId,
      'parent_content_id' => $main_id,
      'order' => 3,
  ));
  $main_middle_id = $db->lastInsertId();

  //widgets
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'edating.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $top_middle_id,
      'order' => 1,
  ));

  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'core.content',
      'page_id' => $pageId,
      'parent_content_id' => $main_middle_id,
      'order' => 2,
  ));
}
