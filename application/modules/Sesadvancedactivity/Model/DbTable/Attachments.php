<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Attachments.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Model_DbTable_Attachments extends Engine_Db_Table {

  protected $_name = 'activity_attachments';
  
  public function getAllEvents($event_id) {
  
    $attachmentsTableName = $this->info('name');
    $select = $this->select()
                  ->where('type =?', 'sesadvancedactivity_event')
                  ->where('id =?', $event_id);
    return $this->fetchAll($select);
  
  }
}