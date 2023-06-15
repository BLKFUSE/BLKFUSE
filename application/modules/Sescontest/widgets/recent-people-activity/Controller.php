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
class Sescontest_Widget_RecentPeopleActivityController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (!Engine_Api::_()->core()->hasSubject('contest'))
      return $this->setNoRender();
    $this->view->contest = $contest = Engine_Api::_()->core()->getSubject();
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $show_criterias = $params['criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $likeMembers = $favMembers = $followMembers = 0;
    if (isset($this->view->likeActive))
      $this->view->likeMembers = $likeMembers = Engine_Api::_()->sescontest()->getMemberByLike($contest->contest_id, ($params['view_more_like'] + 1));
    if (isset($this->view->favouriteActive))
      $this->view->favMembers = $favMembers = Engine_Api::_()->sescontest()->getMemberFavourite($contest->contest_id, ($params['view_more_favourite'] + 1));
    if (isset($this->view->followActive))
      $this->view->followMembers = $followMembers = Engine_Api::_()->sescontest()->getMemberFollow($contest->contest_id, ($params['view_more_follow'] + 1));
    if (is_countable($likeMembers) && engine_count($likeMembers) == 0 && is_countable($favMembers) &&  engine_count($favMembers) == 0 && is_countable($followMembers) &&  engine_count($followMembers) == 0) {
      return $this->setNoRender();
    }
  }

}
