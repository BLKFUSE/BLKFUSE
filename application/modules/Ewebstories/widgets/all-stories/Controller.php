<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Ewebstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2020-03-20 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Ewebstories_Widget_AllStoriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    $this->view->isAjax = $this->_getParam('is_ajax',0);
    //$this->view->identity = $this->_getParam('identity',0);
    $this->view->viewer = $viewer = $this->view->viewer();
    if(!$viewer->getIdentity()){
      return $this->setNoRender();
    }
    
    $this->view->title = $this->_getParam('title');
    
    //get all stories
    $user_id = $viewer->getIdentity();
    $userarchivedstories = $this->_getParam('userarchivedstories', null);
    $ewebstories_browse = Zend_Registry::isRegistered('ewebstories_browse') ? Zend_Registry::get('ewebstories_browse') : null;
    if(empty($ewebstories_browse))
      return $this->setNoRender();
    $highlight = $this->_getParam('highlight', null);
    if(!empty($_SESSION['story_id'])){
        $item = Engine_Api::_()->getItem('sesstories_story',$_SESSION['story_id']);
        if($item) {
            $this->view->user_id = $item->owner_id;
            $this->view->story_id = $_SESSION['story_id'];
        }
        unset($_SESSION['story_id']);
    }
    $this->view->story = Engine_Api::_()->sesstories()->userData($highlight,$userarchivedstories,$user_id,$this->view);
  }
}
