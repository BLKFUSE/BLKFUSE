<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Likes.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Model_DbTable_Likes extends Engine_Db_Table{

	protected $_rowClass = 'Edating_Model_Like';
	
	public function checkmutual($owner_id,$user_id) {

		$select = $this->select()
                  ->from($this->info('name'))
                  ->where("user_id = ?", $owner_id)
                  ->where("owner_id = ?", $user_id)
                  ->limit(1);
		return $this->fetchAll($select);
	}

	public function getLikesPaginator($params) {
	
		$paginator = Zend_Paginator::factory($this->getLikesSelect($params));
		
		if( !empty($params['page']))
		  $paginator->setCurrentPageNumber($params['page']);
		  
		if( !empty($params['limit']))
		  $paginator->setItemCountPerPage($params['limit']);
		  
		return $paginator;
	}
	
	public function getLikesSelect($params = array()) {

		$userTable = Engine_Api::_()->getDbTable('users', 'user');
		$userTableName = $userTable->info('name');
		
		$likesTable = Engine_Api::_()->getDbTable('likes', 'edating');
		$likesTablename = $likesTable->info('name');
		
		$actionTable = Engine_Api::_()->getDbTable('actions', 'edating');
		$actionTablename = $actionTable->info('name');
		
		$viewer = Engine_Api::_()->user()->getViewer();
		
		$select = $userTable->select()
                      ->setIntegritycheck(false)
                      ->from($userTableName);

		if ($params["widgetname"] == "already-viewed")
      $select->joinLeft($actionTablename, "`{$actionTablename}`.`user_id` = `{$userTableName}`.`user_id`");
		else
      $select->joinLeft($likesTablename, "`{$likesTablename}`.`user_id` = `{$userTableName}`.`user_id`");
		
		if(isset($params["widgetname"])) {
      if($params["widgetname"] == 'my-likes') {
        $select->where("owner_id = ?", $viewer->getIdentity())
              ->where("is_own = ?", 1)
              ->where("mutual = ?", 0);
      } else if($params["widgetname"] == 'who-like-me') {
        $select->where("owner_id = ?", $viewer->getIdentity())
              ->where("is_own = ?", 0)
              ->where("mutual = ?", 0);
      } else if($params["widgetname"] == 'mutual-likes') {
        $select->where("owner_id = ?", $viewer->getIdentity())
              ->where("mutual = ?", 1);
      } else if($params["widgetname"] == 'already-viewed') {
        $select->where("owner_id = ?", $viewer->getIdentity())
              ->where("action = ?", 'visit');
      }
		}
		$select->order('time_stamp DESC');
		
		return $select;
  }
  
  public function getAlreadyLikedViewer($params = array()) {
  
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $select = $this->select()
        ->from($this, array('user_id'))
        ->where('owner_id = ?', $viewer_id)
        ->where('is_viewed = ?', 1)
        ->where('is_own = ?', 1);
    $users = array();
    foreach( $select->query()->fetchAll() as $user ) {
      $users[] = $user['user_id'];
    }
    return $users;
  }
	
	public function makeview() {
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->update(array('is_viewed' => 1), array('owner_id = ?' => $viewer->getIdentity(), 'mutual = ?' => 0, 'is_own = ?' => 0));
	}
	
	public function makeviewmutual() {
		$viewer = Engine_Api::_()->user()->getViewer();
		$this->update(array('is_viewed' => 1), array('owner_id = ?' => $viewer->getIdentity(),'mutual = ?' => 1));
	}
}
