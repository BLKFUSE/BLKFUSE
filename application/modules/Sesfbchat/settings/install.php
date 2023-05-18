<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: install.php  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesfbchat_Installer extends Engine_Package_Installer_Module {


  public function onInstall() {
    $this->_addChatMemberProfile();
    parent::onInstall();
  }
  protected function _addChatMemberProfile()
  {
    $db = $this->getDb();
    $select = new Zend_Db_Select($db);

    // Get page id
    $pageId = $select
      ->from('engine4_core_pages', 'page_id')
      ->where('name = ?', 'footer')
      ->limit(1)
      ->query()
      ->fetchColumn(0);

    // Check if it's already been placed
    $select = new Zend_Db_Select($db);
    $hasWidget = $select
      ->from('engine4_core_content', new Zend_Db_Expr('TRUE'))
      ->where('page_id = ?', $pageId)
      ->where('type = ?', 'widget')
      ->where('name = ?', 'sesfbchat.chat')
      ->query()
      ->fetchColumn()
      ;

    // Add it
    if( !$hasWidget ) {
        $select = new Zend_Db_Select($db);
        $select
            ->from('engine4_core_content', 'content_id')
            ->where('page_id = ?', $pageId)
            ->where('name = ?', 'main')
            ->limit(1);
        $info = $select->query()->fetch();
        if ($info) {
            $db->insert('engine4_core_content', array(
                'page_id' => $pageId,
                'type' => 'widget',
                'name' => 'sesfbchat.chat',
                'parent_content_id' => $info['content_id'],
                'order' => 999
            ));
        }
    }
  }
}
