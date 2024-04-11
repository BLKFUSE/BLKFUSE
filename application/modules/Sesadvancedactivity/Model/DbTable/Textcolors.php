<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Textcolors.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Model_DbTable_Textcolors extends Engine_Db_Table {

  protected $_rowClass = 'Sesadvancedactivity_Model_Textcolor';
  
  public function getAllTextColors() {
    
    $tableName = $this->info('name');
    $select = $this->select()
                  ->from($tableName)
                  ->where('active =?', 1);
    return $this->fetchAll($select);

  }
}