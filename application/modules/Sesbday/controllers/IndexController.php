<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesbday_IndexController extends Core_Controller_Action_Standard
{
  public function indexAction()
  {
    $wishingMessage = $this->_getParam('wishingMessage');
	$userIdentity = $this->_getParam('userIdentity');
	$viewer = Engine_Api::_()->user()->getViewer();
	$viewer_id = $viewer->getIdentity();
	if(!$wishingMessage || !$userIdentity || !$viewer_id){
		echo 0;die;
	}
	try{
	$subject = Engine_Api::_()->getItem('user',$userIdentity);
	$actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
	$action = $actionTable->addActivity($viewer, $subject, 'post', $wishingMessage, array(
		'count' => 0,
	));

          $actionLink = '<a href="' . $action->getHref() . '">' . "Happy Birthday ". '</a>';

        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($subject,$viewer, $viewer, 'sesbday_birthday' , array("actionLink" => $actionLink));

	 $wishesTable = Engine_Api::_()->getDbtable('wishes', 'sesbday');
	 $wishe = $wishesTable->createRow();
	 $wishe->user_id = $viewer_id;
	 $wishe->subject_id = $userIdentity;
	 $wishe->creation_date = date('Y-m-d H:i:s');
	 $wishe->save();
		echo 1;die;
	}catch(Exception $e){
		echo 0;die;
	}

  }
  function browseAction(){
	  // Render
    $this->_helper->content
        //->setNoRender()
        ->setEnabled()
        ;
  }
  function popupAction(){

  }
  public function getUsersAction()
  {
	    $params = $this->_getParam('params');
		$yearMonth = $this->_getParam('params',false);
		if($yearMonth){
			list($year,$month,$day) = explode('-',$yearMonth);
		}
		$this->view->viewmore = $this->_getParam('viewmore',0);
		$this->view->viewmoreT = $this->_getParam('viewmoreT',0);
		$this->view->currentDay = $yearMonth;
		$page = $this->_getParam('page', 1);
		$users = Engine_Api::_()->sesbday()->getFriendBirthday($params,1,true);
		$this->view->paginator = $paginator = $users["data"];
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($page);
		$this->view->paginator = $paginator;
  }
}
