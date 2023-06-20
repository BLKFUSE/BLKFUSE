<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_Installer extends Engine_Package_Installer_Module {

  public function onInstall() {

    $db = $this->getDb();
    
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
        ->where('name = ?', 'sesalbum.browse-albums');
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
      
      // Insert content
      $db->insert('engine4_core_content', array(
        'type' => 'widget',
        'name' => 'sesalbum.browse-albums',
        'page_id' => $pageId,
        'parent_content_id' => ($tabId ? $tabId : $middleId),
        'order' => 7,
        'params' => '{"load_content":"auto_load","sort":"most_liked","view_type":"2","insideOutside":"inside","fixHover":"fix","show_criteria":["like","comment","rating","view","title","by","socialSharing","favouriteCount","downloadCount","photoCount","featured","sponsored","likeButton","favouriteButton"],"title_truncation":"30","limit_data":"21","height":"240","width":"395","title":"","nomobile":"0","name":"sesalbum.browse-albums"}'
      ));
    }
  }
}
