<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $this->_getAllParams();    
    $this->getElement()->removeDecorator('Title');
    
	$this->view->createButton = $this->_getParam('createButton', 1);

    //Get navigation
    $this->view->navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('sesmusic_main', array());

    if (engine_count($this->view->navigation) == 1)
      $this->view->navigation = null;
    $sesmusic_browsemenu = Zend_Registry::isRegistered('sesmusic_browsemenu') ? Zend_Registry::get('sesmusic_browsemenu') : null;
	
	$this->view->createPrivacy = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', Engine_Api::_()->user()->getViewer(), 'create');
	
    if(empty($sesmusic_browsemenu)) {
      return $this->setNoRender();
    }
    $this->view->max = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.taboptions', 6);
    if (is_countable($this->view->navigation) && engine_count($this->view->navigation) == 1) {
      $this->view->navigation = null;
    }

  }

}
