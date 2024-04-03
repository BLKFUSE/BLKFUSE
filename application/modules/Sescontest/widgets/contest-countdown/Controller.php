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
class Sescontest_Widget_ContestCountdownController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $viewr_id = $viewer->getIdentity();
    if (!Engine_Api::_()->core()->hasSubject())
      return $this->setNoRender();
    $contest = Engine_Api::_()->core()->getSubject();

    $this->view->radious = $this->_getParam('radious', '0');
		$this->view->placementType = $this->_getParam('placement_type', 'sidebar');
    $this->view->headingTitle = $this->_getParam('heading');
    $countDownType = $this->_getParam('type', 'endtime');
    if ($countDownType == 'endtime') {
      if (strtotime($contest->endtime) < time())
        return $this->setNoRender();
      else {
        $this->view->countDownTime = $contest->endtime;
        $this->view->finishMessage = $this->view->translate("Contest has Ended.");
      }
    } elseif ($countDownType == 'joiningendtime') {
      if (strtotime($contest->joinendtime) < time())
        return $this->setNoRender();
      else {
        $this->view->countDownTime = $contest->joinendtime;
        $this->view->finishMessage = $this->view->translate("Contest entry submission has Ended.");
      }
    } elseif ($countDownType == 'votingendtime') {
      if (strtotime($contest->votingendtime) < time())
        return $this->setNoRender();
      else {
        $this->view->countDownTime = $contest->votingendtime;
        $this->view->finishMessage = $this->view->translate("Contest voting has Ended.");
      }
    } elseif ($countDownType == 'starttime') {
      if (strtotime($contest->starttime) < time())
        return $this->setNoRender();
      else {
        $this->view->countDownTime = $contest->starttime;
        $this->view->finishMessage = $this->view->translate("Contest has Started.");
      }
    }elseif ($countDownType == 'votingstarttime') {
      if (strtotime($contest->votingstarttime) < time())
        return $this->setNoRender();
      else {
        $this->view->countDownTime = $contest->votingstarttime;
        $this->view->finishMessage = $this->view->translate("Contest Voting has Started.");
      }
    } elseif ($countDownType == 'joinstarttime') {
      if (strtotime($contest->joinstarttime) < time())
        return $this->setNoRender();
      else {
        $this->view->countDownTime = $contest->joinstarttime;
        $this->view->finishMessage = $this->view->translate("Contest Participation has Started.");
      }
    }
  }

}
