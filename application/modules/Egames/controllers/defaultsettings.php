<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: defaultsettings.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

$db = Zend_Db_Table_Abstract::getDefaultAdapter();

//Default Privacy Set Work
$permissionsTable = Engine_Api::_()->getDbTable('permissions', 'authorization');
foreach (Engine_Api::_()->getDbTable('levels', 'authorization')->fetchAll() as $level) {
  $form = new Egames_Form_Admin_Settings_Level(array(
      'public' => ( engine_in_array($level->type, array('public')) ),
      'moderator' => (engine_in_array($level->type, array('admin', 'moderator'))),
  ));
  $values = $form->getValues();
  $valuesForm = $permissionsTable->getAllowed('egames_game', $level->level_id, array_keys($form->getValues()));

  $form->populate($valuesForm);
  if ($form->defattribut)
    $form->defattribut->setValue(0);
  $db = $permissionsTable->getAdapter();
  $db->beginTransaction();
  try {
    if ($level->type != 'public') {
      // Set permissions
      $values['auth_comment'] = (array) $values['auth_comment'];
      $values['auth_view'] = (array) $values['auth_view'];
    }
    $nonBooleanSettings = $form->nonBooleanFields();
    $permissionsTable->setAllowed('egames_game', $level->level_id, $values, '', $nonBooleanSettings);
    // Commit
    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
    throw $e;
  }
}

// profile page
$pageId = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'egames_index_edit')
  ->limit(1)
  ->query()
  ->fetchColumn();

if( !$pageId ) {

  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'egames_index_edit',
      'displayname' => 'SNS - Games - Games Edit Page',
      'title' => 'Game Edit Page',
      'description' => 'This page is the game edit page.',
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

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egames.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => 1,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'core.content',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => 1,
  ));
}

// profile page
$pageId = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'egames_index_create')
  ->limit(1)
  ->query()
  ->fetchColumn();

if( !$pageId ) {

  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'egames_index_create',
      'displayname' => 'SNS - Games - Games Create Page',
      'title' => 'Game Create Page',
      'description' => 'This page is the game create page.',
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

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egames.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => 1,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'core.content',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => 1,
  ));
}

// profile page
$pageId = $db->select()
  ->from('engine4_core_pages', 'page_id')
  ->where('name = ?', 'egames_index_view')
  ->limit(1)
  ->query()
  ->fetchColumn();

if( !$pageId ) {

  // Insert page
  $db->insert('engine4_core_pages', array(
      'name' => 'egames_index_view',
      'displayname' => 'SNS - Games - Games View Page',
      'title' => 'Game View Page',
      'description' => 'This page is the game view page.',
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

  // Insert menu
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egames.browse-menu',
      'page_id' => $pageId,
      'parent_content_id' => $topMiddleId,
      'order' => 1,
  ));

  // Insert content
  $db->insert('engine4_core_content', array(
      'type' => 'widget',
      'name' => 'egames.game-view',
      'page_id' => $pageId,
      'parent_content_id' => $mainMiddleId,
      'order' => 1,
  ));

  // comment content
  $db->insert('engine4_core_content', array(
    'type' => 'widget',
    'name' => 'core.comments',
    'page_id' => $pageId,
    'parent_content_id' => $mainMiddleId,
    'order' => 2,
    'params' => '{"title":"Comments"}',
  ));
}

// profile page
  $pageId = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'egames_index_browse')
      ->limit(1)
      ->query()
      ->fetchColumn();

  // insert if it doesn't exist yet
  if( !$pageId ) {
      // Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'egames_index_browse',
          'displayname' => 'SNS - Games - Games Browse Page',
          'title' => 'Games Browse',
          'description' => 'This page lists games entries.',
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

      // Insert main-right
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $pageId,
          'parent_content_id' => $mainId,
          'order' => 1,
      ));
      $mainRightId = $db->lastInsertId();

      // Insert menu
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'egames.browse-menu',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'order' => 2,
      ));

      // Insert content
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'egames.browse-games',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => 1,
      ));

      // Insert search
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'egames.browse-search',
          'page_id' => $pageId,
          'parent_content_id' => $mainRightId,
          'order' => 1,
      ));
      
  }
  // profile page
  $pageId = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'egames_index_manage')
      ->limit(1)
      ->query()
      ->fetchColumn();

  // insert if it doesn't exist yet
  if( !$pageId ) {
      // Insert page
      $db->insert('engine4_core_pages', array(
          'name' => 'egames_index_manage',
          'displayname' => 'SNS - Games - Games Manage Page',
          'title' => 'Games Manage',
          'description' => 'This page lists games entries.',
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

      // Insert main-right
      $db->insert('engine4_core_content', array(
          'type' => 'container',
          'name' => 'right',
          'page_id' => $pageId,
          'parent_content_id' => $mainId,
          'order' => 1,
      ));
      $mainRightId = $db->lastInsertId();

      // Insert menu
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'egames.browse-menu',
          'page_id' => $pageId,
          'parent_content_id' => $topMiddleId,
          'order' => 2,
      ));

      // Insert content
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'egames.manage-games',
          'page_id' => $pageId,
          'parent_content_id' => $mainMiddleId,
          'order' => 1,
      ));

      // Insert search
      $db->insert('engine4_core_content', array(
          'type' => 'widget',
          'name' => 'egames.browse-search',
          'page_id' => $pageId,
          'parent_content_id' => $mainRightId,
          'order' => 1,
      ));
      
  }
