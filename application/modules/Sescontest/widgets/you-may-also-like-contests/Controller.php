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

class Sescontest_Widget_YouMayAlsoLikeContestsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '12');
    $show_criterias = $params['information'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $value = array();
    $value['limit'] = $params['limit_data'];
    $value['popularity'] = 'You May Also Like';
    $value['fetchAll'] = 'true';

    $this->view->results = $results = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestSelect($value);

    if (is_countable($results) && engine_count($results) <= 0)
      return $this->setNoRender();
  }

}
