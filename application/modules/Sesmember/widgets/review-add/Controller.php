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
class Sesmember_Widget_ReviewAddController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    //check review widget place on profile page
    if (!Engine_Api::_()->sesbasic()->getIdentityWidget('sesmember.member-reviews', 'widget', 'user_profile_index'))
      return $this->setNoRender();

    if (!Engine_Api::_()->core()->hasSubject('user'))
      return $this->setNoRender();

    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    if ($viewer->getIdentity() == 0)
      return $this->setNoRender();

    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1))
      return $this->setNoRender();

    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.owner', 1)) {
      $allowedCreate = true;
    } else {
      if ($subject->user_id == $viewer->getIdentity())
        return $this->setNoRender();
    }

    if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view'))
      return $this->setNoRender();

    $reviewTable = Engine_Api::_()->getDbtable('reviews', 'sesmember');
    $this->view->isReview = $hasReview = $reviewTable->isReview(array('user_id' => $subject->getIdentity(), 'column_name' => 'review_id'));

    if ($hasReview && !Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'edit'))
      return $this->setNoRender();

    if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'create'))
      return $this->setNoRender();
  }

}
