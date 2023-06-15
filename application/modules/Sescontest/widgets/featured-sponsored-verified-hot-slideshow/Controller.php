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

class Sescontest_Widget_FeaturedSponsoredVerifiedHotSlideshowController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $value['criteria'] = $params['criteria'];
    $value['info'] = $params['info'];
    $value['order'] = $params['order'];
    $value['fetchAll'] = true;
    $value['limit'] = $params['limit_data'];
    $this->view->contests = $contests = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect($value);
    if (engine_count($contests) <= 0)
      return $this->setNoRender();
  }

}
