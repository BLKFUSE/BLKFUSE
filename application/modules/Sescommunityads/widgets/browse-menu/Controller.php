<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
	  
	 $this->view->createButton = $this->_getParam('createButton', 1);
	
	 $this->view->createPrivacy = Engine_Api::_()->authorization()->isAllowed('sescommunityads', Engine_Api::_()->user()->getViewer(), 'create');
    // Get navigation menu
    $this->view->title = $this->_getParam('title','Advertisements');
    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
            ->getNavigation('sescommunityads_main');
    if (is_countable($this->view->navigation) && engine_count($this->view->navigation) == 1) {
      $this->view->navigation = null;
    }
    
  }
}
