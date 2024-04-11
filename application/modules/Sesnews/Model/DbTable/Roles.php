<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Roles.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_Roles extends Engine_Db_Table {
  /**
   * Gets a paginator for sesnews
   *
   * @param Core_Model_Item_Abstract $user The user to get the messages for
   * @return Zend_Paginator
   */
  public function getNewsAdmins($params = array()) {

    $select = $this->select()->where('news_id =?', $params['news_id']);
    return Zend_Paginator::factory($select);
  }

  public function isNewsAdmin($newsId = null, $newsAdminId = null) {
    return $this->select()->from($this->info('name'), 'role_id')
    ->where('user_id =?', $newsAdminId)
    ->where('news_id =?', $newsId)->query()->fetchColumn();
  }
}
