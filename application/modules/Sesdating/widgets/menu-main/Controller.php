<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Widget_MenuMainController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->navigation = $navigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('core_main');

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();

    $this->view->moretext = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.moretext', 'More');

    //Cover Photo work
    //Cover Photo work
    $cover = 0;
    if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('sesusercoverphoto')) && $viewerId) {
      if($viewer->coverphoto) {
        $this->view->menuinformationimg = $cover =	Engine_Api::_()->storage()->get($viewer->coverphoto, '');
        if($cover) {
          $this->view->menuinformationimg = $cover->getPhotoUrl();
        }
      }
    }
		if(empty($cover)) {
      $this->view->menuinformationimg = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.menuinformation.img', '');
		}

    $this->view->backgroundImg = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.menu.img', '');
    $sesdating_header = Zend_Registry::isRegistered('sesdating_header') ? Zend_Registry::get('sesdating_header') : null;
    if(empty($sesdating_header))
      return $this->setNoRender();
    $showMainmenu = $this->_getParam('show_main_menu', 1);
    if ($viewerId == 0 && empty($showMainmenu)) {
      $this->setNoRender();
      return;
    }
    $this->view->submenu = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.submenu', 1);
    $this->view->headerDesign = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 2);
    $require_check = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.general.browse', 1);
    if (!$require_check && !$viewerId) {
      $navigation->removePage($navigation->findOneBy('route', 'user_general'));
    }
    $this->view->max = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.limit', 6);
    $this->view->storage = Engine_Api::_()->storage();

    $this->view->homelinksnavigation = Engine_Api::_()->getApi('menus', 'core')->getNavigation('user_home');
  }

}
