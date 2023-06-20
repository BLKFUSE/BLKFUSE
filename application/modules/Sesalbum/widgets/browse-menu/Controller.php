<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesalbum_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
	  
	 $this->view->createButton = $this->_getParam('createButton', 1);
    
	// Get navigation menu
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sesalbum_main');
    if (is_countable($this->view->navigation) && engine_count($this->view->navigation) == 1) {
      $this->view->navigation = null;
    }
    $this->view->max = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.taboptions', 5);
    $sesalbum_browsemenu = Zend_Registry::isRegistered('sesalbum_browsemenu') ? Zend_Registry::get('sesalbum_browsemenu') : null;
	
	$this->view->createPrivacy = Engine_Api::_()->authorization()->isAllowed('album', Engine_Api::_()->user()->getViewer(), 'create');
	
    if(empty($sesalbum_browsemenu)) {
      return $this->setNoRender();
    }
  }
}
