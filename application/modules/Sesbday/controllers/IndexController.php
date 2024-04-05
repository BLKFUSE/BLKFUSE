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

class Sesbday_IndexController extends Core_Controller_Action_Standard {

  public function indexAction() {
  
    $wishingMessage = $this->_getParam('wishingMessage');
    $userIdentity = $this->_getParam('userIdentity');
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if(!$wishingMessage || !$userIdentity || !$viewer_id){
      echo 0;die;
    }
    
    try {
      $subject = Engine_Api::_()->getItem('user',$userIdentity);
      $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
      $action = $actionTable->addActivity($viewer, $subject, 'post', $wishingMessage, array(
        'count' => 0,
      ));

      $actionLink = '<a href="' . $action->getHref() . '">' . "Happy Birthday ". '</a>';
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($subject,$viewer, $viewer, 'sesbday_birthday' , array("actionLink" => $actionLink));
      
      $birthday_subject = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbday.birthday.subject', '');
      $description = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbday.birthday.content', '');
      $search = array(
          '/\>[^\S ]+/s', // strip whitespaces after tags, except space
          '/[^\S ]+\</s', // strip whitespaces before tags, except space
          '/(\s)+/s'       // shorten multiple whitespace sequences
      );
      $replace = array(
          '>',
          '<',
          '\\1'
      );
      //check uploaded content images
      $doc = new DOMDocument();
      @$doc->loadHTML($description);
      $tags = $doc->getElementsByTagName('img');
      foreach ($tags as $tag) {
        $src = $tag->getAttribute('src');
        if (strpos($src, 'http://') === FALSE && strpos($src, 'https://') === FALSE) {
          $imageGetFullURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" . $_SERVER['HTTP_HOST'] . $src : "http://" . $_SERVER['HTTP_HOST'] . $src;
          $tag->setAttribute('src', $imageGetFullURL);
        }
      }
      $description = $doc->saveHTML();
      //get all background url tags
      $description = $this->getBackgroundImages($description);
      $description = preg_replace($search, $replace, $description);
      
      Engine_Api::_()->getApi('mail', 'core')->sendSystem($subject->email, 'sesbday_birthday_email', array('host' => $_SERVER['HTTP_HOST'], 'birthday_content' => $description, 'birthday_subject' => $birthday_subject, 'queue' => false, 'recipient_title' => $subject->getTitle()));

      $wishesTable = Engine_Api::_()->getDbtable('wishes', 'sesbday');
      $wishe = $wishesTable->createRow();
      $wishe->user_id = $viewer_id;
      $wishe->subject_id = $userIdentity;
      $wishe->creation_date = date('Y-m-d H:i:s');
      $wishe->save();
      echo 1;die;
    } catch(Exception $e) {
      echo 0;die;
    }
  }
  
  public function getBackgroundImages($content = '') {
    $matches = array();
    preg_match_all('~\bbackground(-image)?\s*:(.*?)\(\s*(\'|")?(?<image>.*?)\3?\s*\)~i', $content, $matches, PREG_SET_ORDER);
    foreach ($matches as $match) {
      if (strpos($match['image'], 'http://') === FALSE && strpos($match['image'], 'https://') === FALSE) {
        $imageGetFullURL = (!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" . $_SERVER['HTTP_HOST'] . $match['image'] : "http://" . $_SERVER['HTTP_HOST'] . $match['image'];
        $content = str_replace($match['image'], $imageGetFullURL, $content);
      }
    }
    return $content;
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
