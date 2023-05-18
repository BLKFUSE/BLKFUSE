<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_ReviewProfileOptionsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1))
      return $this->setNoRender();
    $this->view->viewType = $this->_getParam('viewType', 'vertical');
    $coreMenuApi = Engine_Api::_()->getApi('menus', 'core');

    if (!Engine_Api::_()->core()->hasSubject('sesmember_review'))
      return $this->setNoRender();

    $review = Engine_Api::_()->core()->getSubject('sesmember_review');
    $this->view->content_item = $event = Engine_Api::_()->getItem('user', $review->user_id);
    $this->view->navigation = $coreMenuApi->getNavigation('sesmember_reviewprofile');
  }

}
