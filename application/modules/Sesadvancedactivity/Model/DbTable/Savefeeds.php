<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Savefeeds.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_DbTable_Savefeeds extends Engine_Db_Table
{
  protected $_rowClass = 'Sesadvancedactivity_Model_Savefeed';
  public function isSaved($params = array()){
    $select = $this->select()->where('action_id =?',$params['action_id'])->where('user_id =?',$params['user_id'])->limit(1);
    return $this->fetchRow($select);  
  }
  
}