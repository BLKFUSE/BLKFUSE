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

class Sescontest_Widget_ContestEntryDayOfTheController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $this->view->viewer = Engine_Api::_()->user()->getViewer();

    $show_criterias = $params['information'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $contentType = $params['contentType'];
    if ($contentType == 'contest') {
      $item = Engine_Api::_()->getDbtable('contests', 'sescontest')->getOfTheDayResults();
      //Get all settings
      $this->view->information = $this->_getParam('information', array('featured', 'sponsored', 'hot', 'likeCount', 'commentCount', 'viewCount', 'followCount', 'songsCount', 'title', 'postedby'));
      $this->view->result = $item;
    } elseif ($contentType == 'entry') {
      $item = Engine_Api::_()->getDbtable('participants', 'sescontest')->getOfTheDayResults();
      //Get all settings
      $this->view->information = $this->_getParam('information', array('likeCount', 'commentCount', 'viewCount', 'favouriteCount', 'title', 'postedby'));
      $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
      $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
      $this->view->result = $item;
    }
    if (empty($item))
      return $this->setNoRender();
  }

}
