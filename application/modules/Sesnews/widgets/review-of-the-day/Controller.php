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

class Sesnews_Widget_ReviewOfTheDayController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);

    $this->view->height = $this->_getParam('height', '315');
    $this->view->title_truncation = $this->_getParam('title_truncation', '45');
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    $show_criterias = isset($value['show_criterias']) ? $value['show_criterias'] : $this->_getParam('show_criteria', array('title', 'like', 'view', 'featuredLabel', 'verifiedLabel', 'socialSharing', 'rating', 'by'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $this->view->results = Engine_Api::_()->getDbTable('reviews', 'sesnews')->getNewsReviewSelect(array('widgetName' => 'oftheday', 'fetchAll' => true), array());
    if (engine_count($this->view->results) <= 0)
      return $this->setNoRender();
  }

}
