<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Widget_ShareContentController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->title = $settings->getSetting('sescontest.share.title',1);
    $this->view->contestType = $settings->getSetting('sescontest.share.contesttype',1);
    $this->view->contestDate = $settings->getSetting('sescontest.share.date',1);
    $this->view->description = $settings->getSetting('sescontest.share.description',1);
    $this->view->logo = $settings->getSetting('sescontest.share.logo',1);
    if($this->view->logo){
        $this->view->logoimage = $settings->getSetting('sescontest.share.logoimage','application/modules/Sescontest/externals/images/share_content/comany_logo.png');
        if(!$this->view->logoimage)
          $this->view->logo = 0;
    }
    $this->view->sponsored = $settings->getSetting('sescontest.share.sponsored',0);
    $this->view->contestTemplate = $settings->getSetting('sescontest.share.contestTemplate',1);
    $this->view->entryTemplate = $settings->getSetting('sescontest.share.entryTemplate',1);
    $isPopup = $this->_getParam('isPopup',0);
    $contest = false;
    $entry = false;
    $winner = false;
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $moduleName = $request->getModuleName();
    $actionName = $request->getActionName();
    $controllerName = $request->getControllerName();
    
    if($moduleName == "sescontest" && $controllerName == "index" && $actionName == "create")
      $contest = true;
    else if($moduleName == "sescontest" && $controllerName == "index" && $actionName == "create")
      $entry = true;
    else if($this->_getParam('winner',0))
      $winner = true;
    else if($isPopup)
      $contest = true;
    else
      return $this->setNoRender();
    
   // echo $moduleName.' || '.$controllerName.' || '.$actionName;die;
    
    $this->view->contest = $contest;
    $this->view->entry  = $entry;
    $this->view->winner = $winner;
    $type = 3;
    if($type == 1)
      $this->view->type = "Text";
    else if($type == 2)
      $this->view->type = "Photo";
    else if($type == 3)
      $this->view->type = "Video";
    else if($type == 5)
      $this->view->type = "Audio";
    
  }

}
