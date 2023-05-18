<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: NotificationsController.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_NotificationsController extends Core_Controller_Action_Standard {

	public function markreadAction() {
	
    $request = Zend_Controller_Front::getInstance()->getRequest();

    $action_id = $request->getParam('actionid', 0);

    $viewer = Engine_Api::_()->user()->getViewer();
    $notificationsTable = Engine_Api::_()->getDbtable('notifications', 'activity');
    $db = $notificationsTable->getAdapter();
    $db->beginTransaction();

    try {
      $notification = Engine_Api::_()->getItem('activity_notification', $action_id);
      if( $notification ) {
        $notification->read = 1;
        $notification->save();
      }
      // Commit
      $db->commit();
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
    
    if ($this->_helper->contextSwitch->getCurrentContext()  != 'json') {
      $this->_helper->viewRenderer->setNoRender();
    }
  }
  
	public function hideAction() {
	
    $viewer = Engine_Api::_()->user()->getViewer();
		$notificationsTable = Engine_Api::_()->getDbtable('notifications', 'activity');
    $where = array('`user_id` = ?' => $viewer->getIdentity(), '`read` = ?' => 0);
    $notificationsTable->update(array('read' => 1), $where);
    echo "true";die;
  }
  
  public function pulldownAction() {

    $page = $this->_getParam('page');
    
    $viewer = Engine_Api::_()->user()->getViewer();
    
    $this->view->notifications = $notifications = Engine_Api::_()->getDbtable('notifications', 'sesadvancedactivity')->getUpdateNotificationsPaginator($viewer);
    
    $notifications->setCurrentPageNumber($page);

    if ($notifications->getCurrentItemCount() <= 0 || $page > $notifications->getCurrentPageNumber()) {
      $this->_helper->viewRenderer->setNoRender(true);
      return;
    }
    Engine_Api::_()->getDbtable('notifications', 'activity')->update(array('read' => 1), array('user_id = ?' => $viewer->getIdentity()));
    $this->_helper->viewRenderer->postDispatch();
    $this->_helper->viewRenderer->setNoRender(true);
  }

}
