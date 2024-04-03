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

class Sescontest_Widget_VoteButtonController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
			
    $this->view->viewer_id = $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (empty($viewerId))
      return $this->setNoRender();
    if (!Engine_Api::_()->core()->hasSubject('participant'))
      return $this->setNoRender();
    $this->view->subject = $entry = Engine_Api::_()->core()->getSubject('participant');
    if ($entry->owner_id == $viewerId)
      return $this->setNoRender();
  }

}
