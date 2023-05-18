<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Elivestreaming
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Elivehosts.php 2019-10-01 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Elivestreaming_Model_DbTable_Elivehosts extends Engine_Db_Table
{
  protected $_rowClass = "Elivestreaming_Model_Elivehost";
  protected $_name = "elivestreaming_hosts";

  public function getHostId($params = array())
  {
    $select = $this->select();
    $select->from($this->info('name'), array('*'));
    if (!empty($params['story_id']))
      $select->where('story_id =?', $params['story_id']);
    if (!empty($params['action_id']))
      $select->where('action_id =?', $params['action_id']);
    if (!empty($params['video_id']))
      $select->where('video_id =?', $params['video_id']);
    return $this->fetchRow($select);
  }

  public function countLiveVideo($userId = null)
  {
    $count = $this->select()->from($this->info('name'), array("COUNT(elivehost_id)"));
    if ($userId)
      $count->where('user_id =?', $userId);
    $count->where('status =?', 'completed');
    return $count->query()->fetchColumn();
  }
}
