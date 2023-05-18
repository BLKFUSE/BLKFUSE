<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Userpayments.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sescomadbanr_Model_DbTable_Userpayments extends Engine_Db_Table {

  protected $_rowClass = "Sescomadbanr_Model_Userpayment";

  public function getUserpayments($param = array()) {

    $tableName = $this->info('name');
    $select = $this->select()
            ->from($tableName)->order('userpayment_id DESC');
    if (isset($param['fetchAll'])) {
        $select->where('enabled =?', 1);
        return $this->fetchAll($select);
    }
    return Zend_Paginator::factory($select);
  }
}
