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
class Sescontest_Widget_ContestPhotoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();
    if ($subject->getType() == 'participant' && $subject->media == 2 && (!$params['show_photo']))
      $canShow = 0;
    else
      $canShow = 1;
    if (!$canShow)
      return $this->setNoRender();
  }

}
