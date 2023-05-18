<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Everification_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {

    $db = $this->getDb();
    // Manage Documents Verification page
    $pageId = $db->select()
        ->from('engine4_core_pages', 'page_id')
        ->where('name = ?', 'everification_settings_manage')
        ->limit(1)
        ->query()
        ->fetchColumn();

    if( !$pageId ) {

        // Insert page
        $db->insert('engine4_core_pages', array(
            'name' => 'everification_settings_manage',
            'displayname' => 'SNS - Verified Badge - Verified Badge Settings Page',
            'title' => 'Verified Badge Settings Page',
            'description' => 'This page is the user document verification settings page.',
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
        'name' => 'user.settings-menu',
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

    parent::onInstall();
  }
}
