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

class Sescontest_Widget_JoinContestController extends Engine_Content_Widget_Abstract {

  public function indexAction() { 
    if (!$this->view->viewer()->getIdentity() || !Engine_Api::_()->core()->hasSubject())
      return $this->setNoRender();
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $participate = Engine_Api::_()->getDbTable('participants', 'sescontest')->hasParticipate($this->view->viewer()->getIdentity(), $contest->contest_id);
    if (!isset($participate['can_join']) || !isset($participate['show_button']))
      return $this->setNoRender();
  }

}
