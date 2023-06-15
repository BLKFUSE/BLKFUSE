<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Widget_BrowseMenuController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Get navigation
	
	$this->view->createButton = $this->_getParam('createButton', 1);

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')
      ->getNavigation('sesnews_main');
    if( engine_count($this->view->navigation) == 1 ) {
      $this->view->navigation = null;
    }
    $sesnews_browsenews = Zend_Registry::isRegistered('sesnews_browsenews') ? Zend_Registry::get('sesnews_browsenews') : null;
	
	$this->view->createPrivacy = Engine_Api::_()->authorization()->isAllowed('sesnews_news', Engine_Api::_()->user()->getViewer(), 'create');
	
    if (empty($sesnews_browsenews))
      return $this->setNoRender();
    $this->view->max = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.taboptions', 6);
    if (is_countable($this->view->navigation) && engine_count($this->view->navigation) == 1) {
      $this->view->navigation = null;
    }
  }
}
