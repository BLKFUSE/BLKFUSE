<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Details.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Model_DbTable_Details extends Engine_Db_Table
{
  protected $_rowClass = 'Sesadvancedactivity_Model_Detail';
  
  public function isRowExists($action_id) {

    $detail_id = $this->select()
            ->from($this->info('name'), 'detail_id')
            ->where('action_id =?', $action_id)
            ->query()
            ->fetchColumn();
    return $detail_id;
  
  }
}