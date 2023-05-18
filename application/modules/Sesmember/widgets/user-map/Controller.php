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

class Sesmember_Widget_UserMapController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1))
      return $this->setNoRender();
      
    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if (!Engine_Api::_()->core()->hasSubject() || !Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)) {
      return $this->setNoRender();
    }

    $subject = $this->view->subject = Engine_Api::_()->core()->getSubject();
    $getUserInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($subject->user_id);
    $this->view->locationLatLng =  $locationLatLng = Engine_Api::_()->getDbtable('locations', 'sesbasic')->getLocationData($subject->getType(),$subject->getIdentity());

    $this->view->height = $this->_getParam('height', '400');

    if ((!$getUserInfoItem->location && is_null($getUserInfoItem->location)) || !$locationLatLng) {
      return $this->setNoRender();
    }
  }
}
