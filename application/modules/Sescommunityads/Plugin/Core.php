<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Core.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Plugin_Core {
  public function onRenderLayoutDefaultSimple($event){
    $this->onRenderLayoutDefault($event);  
  }
  
  public function onRenderLayoutMobileDefault($event){
    $this->onRenderLayoutDefault($event);  
  }
  
  public function onRenderLayoutMobileDefaultSimple($event){
    $this->onRenderLayoutDefault($event);  
  } 
  
  public function onUserDeleteBefore($event){
    $this->onRenderLayoutDefault($event);  
  }
  
	public function onRenderLayoutDefault($event) {    
    $viewer = Engine_Api::_()->user()->getViewer();		
		$request = Zend_Controller_Front::getInstance()->getRequest();
		$moduleName = $request->getModuleName();
		$actionName = $request->getActionName();
		$controllerName = $request->getControllerName();
    
    //remove key
    $key = Engine_Api::_()->sescommunityads()->getKey(Zend_Controller_Front::getInstance());
    if(!empty($_SESSION[$key] ))
      unset($_SESSION[$key]);
    $_SESSION[$key] = array();
    $_SESSION[$key."_stop"] = false;
    
    $headScript = new Zend_View_Helper_HeadScript();
		$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') .'application/modules/Sescommunityads/externals/scripts/core.js');
		
		if($moduleName == "sescommunityads" && ($actionName == "create" || $actionName == "edit-ad") && $controllerName == "index"){
			$headScript->appendFile(Zend_Registry::get('StaticBaseUrl') .'application/modules/Sescommunityads/externals/scripts/ad_edit_create.js');
		}
		if($viewer->getIdentity()){
				$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
				$script = 'var currentUserTimezone = "'.$viewer->timezone.'";';
				$view->headScript()->appendScript($script);
		}
	}
}
