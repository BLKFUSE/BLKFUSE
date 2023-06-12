<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Emotionfiles.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedcomment_Model_DbTable_Emotionfiles extends Engine_Db_Table
{
  protected $_rowClass = 'Sesadvancedcomment_Model_Files';
  public function getPaginator($params = array())
  {
    return Zend_Paginator::factory($this->getFiles($params));
  }
  public function getFiles($params = array()){
     $select = ($this->select());
     if(!empty($params['limit'])){
       $select->limit($params['limit']);
     }
		 $select->where('gallery_id =?',$params['gallery_id']);
    if(!empty($params['fetchAll'])){
      return $this->fetchAll($select);  
    }
    return $select;
  }
}