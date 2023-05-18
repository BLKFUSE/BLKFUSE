<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Parameters.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Model_DbTable_Parameters extends Engine_Db_Table {

  protected $_rowClass = 'Sesmember_Model_Parameter';
  protected $_name = 'sesmember_parameters';

  function getParameterResult($params = array()) {
    if (isset($params['column_name']))
      $columnName = $params['column_name'];
    else
      $columnName = '*';
    $select = $this->select()->from($this->info('name'), $columnName)->where('profile_type =?', $params['profile_type']);
    return $select->query()->fetchAll();
  }

}