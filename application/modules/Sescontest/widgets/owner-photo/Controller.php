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

class Sescontest_Widget_OwnerPhotoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->title = $this->_getParam('showTitle', 1);
    if (Engine_Api::_()->core()->hasSubject('contest')) {
      $item = Engine_Api::_()->core()->getSubject();
      $user = Engine_Api::_()->getItem('user', $item->user_id);
    } elseif (Engine_Api::_()->core()->hasSubject('participant')) {
      $item = Engine_Api::_()->core()->getSubject();
      $user = Engine_Api::_()->getItem('user', $item->owner_id);
    }

    $this->view->item = $user;
    if (!$item)
      return $this->setNoRender();
  }

}
