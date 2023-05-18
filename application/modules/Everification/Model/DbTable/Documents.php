<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Documents.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Everification_Model_DbTable_Documents extends Engine_Db_Table {

  public function getDocumentId($params = array()) {

    return $this->select()
            ->from($this->info('name'), 'document_id')
            ->where('user_id = ?', $params['user_id'])
            ->query()
            ->fetchColumn();
  }

  public function getAllUserDocuments($params = array()) {

    $tableName = $this->info('name');
    $select = $this->select()
            ->from($tableName)
            ->where('user_id =?', $params['user_id'])
            ->order('document_id DESC');
    if(!empty($params['verified'])) {
        $select->where('verified =?', '1');
    }

    if (isset($params['fetchAll'])) {
        return $this->fetchAll($select);
    }

    return Zend_Paginator::factory($select);
  }
}
