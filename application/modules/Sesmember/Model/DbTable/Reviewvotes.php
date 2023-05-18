<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Reviewvotes.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Model_DbTable_Reviewvotes extends Engine_Db_Table {

  protected $_rowClass = 'Sesmember_Model_Reviewvote';

  public function isReviewVote($params = array()) {
    $select = $this->select();
    if (isset($params['user_id']))
      $select->where('user_id =?', $params['user_id']);

    if (isset($params['review_id']))
      $select->where('review_id =?', $params['review_id']);

    if (isset($params['type']))
      $select->where('type =?', $params['type']);

    return $select->limit(1)->query()
                    ->fetchColumn();
  }
}
