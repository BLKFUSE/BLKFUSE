<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Reactions.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedcomment_Model_DbTable_Reactions extends Engine_Db_Table {

  protected $_rowClass = 'Sesadvancedcomment_Model_Reaction';

  public function getPaginator($params = array()) {

    return Zend_Paginator::factory($this->getReactions($params));
  }

  public function getReactions($params = array()){

    $select = ($this->select());

    if(@$params['userside']) {
      $select = $select->where('enabled =?', 1);
    }

    if(!empty($params['fetchAll'])) {
      return $this->fetchAll($select);
    }
    return $select;
  }
}
