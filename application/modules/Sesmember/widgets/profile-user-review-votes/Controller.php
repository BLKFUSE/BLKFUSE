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
class Sesmember_Widget_ProfileUserReviewVotesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (!Engine_Api::_()->core()->hasSubject('user'))
      return $this->setNoRender();
    $this->view->subject = $subject = Engine_Api::_()->core()->getSubject();
    $this->view->userInfoItem = $getUserInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($subject->user_id);
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.review.votes', '1'))
      return $this->setNoRender();
  }

}
