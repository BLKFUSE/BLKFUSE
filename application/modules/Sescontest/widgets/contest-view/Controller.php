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

class Sescontest_Widget_ContestViewController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('contest_id', null);
    $contest_id = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestId($id);
    if (!Engine_Api::_()->core()->hasSubject())
      $this->view->contest = $contest = Engine_Api::_()->getItem('contest', $contest_id);
    else
      $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $sescontest_widget = Zend_Registry::isRegistered('sescontest_widget') ? Zend_Registry::get('sescontest_widget') : null;
    if(empty($sescontest_widget)) {
      return $this->setNoRender();
    }
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    // Get category
    if (!empty($contest->category_id))
      $this->view->category = Engine_Api::_()->getDbtable('categories', 'sescontest')->find($contest->category_id)->current();
    $this->view->contestTags = $contest->tags()->getTagMaps();
    $this->view->canComment = $contest->authorization()->isAllowed($viewer, 'comment');
  }

}
