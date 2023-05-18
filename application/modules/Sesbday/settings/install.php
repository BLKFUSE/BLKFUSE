<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesbday_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {
    $db = $this->getDb();
	$this->_birthdayBrowsePage();
	$this->_addBirthdayTipMemberHome();
    $this->_addBirthdayCalanderMemberHome();

    parent::onInstall();
  }
    protected function _addBirthdayCalanderMemberHome()
    {
        $db = $this->getDb();
        $select = new Zend_Db_Select($db);

        // Get page id
        $pageId = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'user_index_home')
            ->limit(1)
            ->query()
            ->fetchColumn(0)
        ;

        // Check if it's already been placed
        $select = new Zend_Db_Select($db);
        $hasWidget = $select
            ->from('engine4_core_content', new Zend_Db_Expr('TRUE'))
            ->where('page_id = ?', $pageId)
            ->where('type = ?', 'widget')
            ->where('name = ?', 'sesbday.calendar')
            ->query()
            ->fetchColumn()
        ;

        // Add it
        if( !$hasWidget ) {

            // container_id (will always be there)
            $select = new Zend_Db_Select($db);
            $containerId = $select
                ->from('engine4_core_content', 'content_id')
                ->where('page_id = ?', $pageId)
                ->where('type = ?', 'container')
                ->limit(1)
                ->query()
                ->fetchColumn()
            ;

            // middle_id (will always be there)
            $select = new Zend_Db_Select($db);
            $rightId = $select
                ->from('engine4_core_content', 'content_id')
                ->where('parent_content_id = ?', $containerId)
                ->where('type = ?', 'container')
                ->where('name = ?', 'right')
                ->limit(1)
                ->query()
                ->fetchColumn()
            ;

            // insert
            if( $rightId ) {
                $db->insert('engine4_core_content', array(
                    'page_id' => $pageId,
                    'type'    => 'widget',
                    'name'    => 'sesbday.calendar',
                    'parent_content_id' => $rightId,
                    'order'   => 3,
                    //'params'  => '{}',
                ));
            }
        }
    }
    protected function _addBirthdayTipMemberHome()
    {
        $db = $this->getDb();
        $select = new Zend_Db_Select($db);

        // Get page id
        $pageId = $select
            ->from('engine4_core_pages', 'page_id')
            ->where('name = ?', 'user_index_home')
            ->limit(1)
            ->query()
            ->fetchColumn(0)
        ;

        // event.home-upcoming

        // Check if it's already been placed
        $select = new Zend_Db_Select($db);
        $hasWidget = $select
            ->from('engine4_core_content', new Zend_Db_Expr('TRUE'))
            ->where('page_id = ?', $pageId)
            ->where('type = ?', 'widget')
            ->where('name = ?', 'sesbday.today-birthdays-tip')
            ->query()
            ->fetchColumn()
        ;

        // Add it
        if( !$hasWidget ) {

            // container_id (will always be there)
            $select = new Zend_Db_Select($db);
            $containerId = $select
                ->from('engine4_core_content', 'content_id')
                ->where('page_id = ?', $pageId)
                ->where('type = ?', 'container')
                ->limit(1)
                ->query()
                ->fetchColumn()
            ;

            // middle_id (will always be there)
            $select = new Zend_Db_Select($db);
            $rightId = $select
                ->from('engine4_core_content', 'content_id')
                ->where('parent_content_id = ?', $containerId)
                ->where('type = ?', 'container')
                ->where('name = ?', 'right')
                ->limit(1)
                ->query()
                ->fetchColumn()
            ;

            // insert
            if( $rightId ) {
                $db->insert('engine4_core_content', array(
                    'page_id' => $pageId,
                    'type'    => 'widget',
                    'name'    => 'sesbday.today-birthdays-tip',
                    'parent_content_id' => $rightId,
                    'order'   => 1,
                    //'params'  => '{}',
                ));
            }
        }
    }
  protected function _birthdayBrowsePage()
  {

    $db = $this->getDb();

    // profile page
    $pageId = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'sesbday_index_browse')
      ->limit(1)
      ->query()
      ->fetchColumn();


    // insert if it doesn't exist yet
    if( !$pageId ) {
      // Insert page
      $db->insert('engine4_core_pages', array(
        'name' => 'sesbday_index_browse',
        'displayname' => 'Birthday Browse Page',
        'title' => 'Birthday Browse',
        'description' => 'This page lists users birthday.',
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

  
      // Insert 
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesbday.birthday-listing',
        'page_id' => $pageId,
        'parent_content_id' => $mainMiddleId,
        'order' => 1,
      ));
	  
	  // Insert 
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesbday.calendar',
        'page_id' => $pageId,
        'parent_content_id' => $mainMiddleId,
        'order' => 2,
      ));
	  
	   // Insert 
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesbday.today-birthdays-tip',
        'page_id' => $pageId,
        'parent_content_id' => $mainMiddleId,
        'order' => 3,
      ));
    }

    return $this;
  }
}
