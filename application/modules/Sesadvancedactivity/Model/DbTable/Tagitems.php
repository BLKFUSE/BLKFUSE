<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Tagitems.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_DbTable_Tagitems extends Engine_Db_Table
{
  public function getActionItems($action_id = ''){
    if(!$action_id)
      return array();
    $select = $this->select()->where('action_id =?',$action_id);
    if(!empty($params['paginator']))
      return $select;
    return $this->fetchAll($select);  
  }
}