<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: install.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {

    $db = $this->getDb();

    //Alphabetically Members Search Page
    $page_id = $db->select()
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'sesmember_index_alphabetic-members-search')
      ->limit(1)
      ->query()
      ->fetchColumn();
    if( !$page_id ) {
      $widgetOrder = 1;
      $db->insert('engine4_core_pages', array(
        'name' => 'sesmember_index_alphabetic-members-search',
        'displayname' => 'SNS - Ultimate Members - Alphabetically Members Search Page',
        'title' => 'Alphabetically Members Search',
        'description' => ' This page show all members alphabetically of your website.',
        'custom' => 0,
      ));
      $page_id = $db->lastInsertId();
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'top',
        'page_id' => $page_id,
        'order' => 1,
      ));
      $top_id = $db->lastInsertId();
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'main',
        'page_id' => $page_id,
        'order' => 2,
      ));
      $main_id = $db->lastInsertId();
      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $page_id,
        'parent_content_id' => $top_id,
      ));
      $top_middle_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
        'type' => 'container',
        'name' => 'middle',
        'page_id' => $page_id,
        'parent_content_id' => $main_id,
        'order' => 2,
      ));
      $main_middle_id = $db->lastInsertId();

      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesmember.browse-menu',
        'page_id' => $page_id,
        'parent_content_id' => $top_middle_id,
        'order' => $widgetOrder++,
      ));

      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesmember.members-listing',
        'page_id' => $page_id,
        'parent_content_id' => $main_middle_id,
        'order' => $widgetOrder++,
      ));
    }
    parent::onInstall();
  }
}
