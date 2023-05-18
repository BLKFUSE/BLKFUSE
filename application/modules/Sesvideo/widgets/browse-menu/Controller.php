<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesvideo_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Get navigation
	
	$this->view->createButton = $this->_getParam('createButton', 1);

    $this->view->navigation = Engine_Api::_()
            ->getApi('menus', 'core')
            ->getNavigation('sesvideo_main', array());
    if (is_countable($this->view->navigation) && engine_count($this->view->navigation) == 1) {
      $this->view->navigation = null;
    }
    $this->view->max = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.taboptions', 6);
    $sesvideo_browsemenu = Zend_Registry::isRegistered('sesvideo_browsemenu') ? Zend_Registry::get('sesvideo_browsemenu') : null;
	
	$this->view->createPrivacy = Engine_Api::_()->authorization()->isAllowed('video', Engine_Api::_()->user()->getViewer(), 'create');
	
    if(empty($sesvideo_browsemenu)) {
      return $this->setNoRender();
    }
  }
}
