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

class Sescontest_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
	  
		$this->view->createButton = $this->_getParam('createButton', 1);

    // Get navigation
	
		$this->view->createPrivacy = Engine_Api::_()->authorization()->isAllowed('contest', Engine_Api::_()->user()->getViewer(), 'create');
	
    $this->view->navigation = $navigation = Engine_Api::_()
            ->getApi('menus', 'core')
            ->getNavigation('sescontest_main', array());
    $this->view->max = Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.taboptions', 6);
    if (is_countable($this->view->navigation) && engine_count($this->view->navigation) == 1) {
      $this->view->navigation = null;
    }
    $sescontest_browsemenu = Zend_Registry::isRegistered('sescontest_browsemenu') ? Zend_Registry::get('sescontest_browsemenu') : null;
    if(empty($sescontest_browsemenu)) {
      return $this->setNoRender();
    }
	
  }
}
