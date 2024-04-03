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

class Sescontest_Widget_ContestEntryViewController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);

    $show_criterias = $params['criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $getparam = Zend_Controller_Front::getInstance()->getRequest();
    $entryId = $getparam->getParam('id', 0);
    $contest_id = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestId($getparam->getParam('contest_id'));
    $this->view->contest = $contest = Engine_Api::_()->getItem('contest', $contest_id);
    $this->view->entry = $entry = Engine_Api::_()->getItem('participant', $entryId);
    $this->view->canComment = Engine_Api::_()->authorization()->isAllowed('participant', $this->view->viewer(), 'comment');
    if (!empty($contest->category_id))
      $this->view->category = Engine_Api::_()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();
    $this->view->entryTags = $entry->tags()->getTagMaps();
  }

}
