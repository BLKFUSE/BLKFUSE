<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Targetpost.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Model_DbTable_Targetpost extends Engine_Db_Table
{
  protected $_name = 'sesadvancedactivity_targetpost';
  protected $_rowClass = 'Sesadvancedactivity_Model_Targetpost';
  public function getTargetPost($action_id = ''){
    if(!$action_id)
      return array();
    $select = $this->select()->where('action_id =?',$action_id);
    return $this->fetchRow($select);  
  }
}