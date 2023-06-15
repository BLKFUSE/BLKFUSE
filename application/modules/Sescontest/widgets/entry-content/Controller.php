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

class Sescontest_Widget_EntryContentController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->next = $this->_getParam('next', 1);
    $this->view->previous = $this->_getParam('previous', 1);
    $getparam = Zend_Controller_Front::getInstance()->getRequest();
    $entryId = $getparam->getParam('id', 0);
    $contest_id = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestId($getparam->getParam('contest_id'));
    $this->view->contest = $contest = Engine_Api::_()->getItem('contest', $contest_id);
    $this->view->entry = $entry = Engine_Api::_()->getItem('participant', $entryId);
  }

}
