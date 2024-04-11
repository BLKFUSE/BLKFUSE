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

class Sesnews_Widget_GutterPhotoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->photoviewtype = $this->_getParam('photoviewtype', "circle");
    $this->view->title = $this->_getParam('title', "About Me");
    $this->getElement()->removeDecorator('Title');
    // Only sesnews or user as subject
    if( Engine_Api::_()->core()->hasSubject('sesnews_news') ) {
      $this->view->user_description_limit = $this->_getParam('user_description_limit', 150);
      $this->view->sesnews = $sesnews = Engine_Api::_()->core()->getSubject('sesnews_news');
      $this->view->owner = $owner = $sesnews->getOwner();
    } else if( Engine_Api::_()->core()->hasSubject('user') ) {
      $this->view->sesnews = null;
      $this->view->owner = $owner = Engine_Api::_()->core()->getSubject('user');
    } else {
      return $this->setNoRender();
    }
  }
}
