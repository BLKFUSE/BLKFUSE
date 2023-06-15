<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedgif
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Texts.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesfeedgif_Model_DbTable_Texts extends Engine_Db_Table {

  protected $_rowClass = 'Sesfeedgif_Model_Text';
  
  public function getValue($value) {
  
    return $this->select()->from($this->info('name'), 'limit')->where('text =?', $value)->query()->fetchColumn();
  }
}