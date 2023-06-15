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
class Sescontest_Widget_ContestsSlideshowController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);

    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $value['criteria'] = $params['criteria'];
    $value['info'] = $params['info'];
    $value['order'] = $params['order'];
    $value['limit'] = 3;
    $value['left'] = 1;

    $selectLeft = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect($value);

    $limit = $params['limit_data'];
    $valueRight['criteria'] = $params['criteria_right'];
    $valueRight['info'] = $params['info_right'];
    $valueRight['order'] = $params['order'];
    $valueRight['right'] = 1;
    if ($params['enableSlideshow']) {
      $valueRight['limit'] = $limit;
    } else {
      $valueRight['limit'] = 1;
    }
    $selectRight = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect($valueRight);
    $Select = "SELECT t.* FROM (($selectLeft) UNION  ($selectRight)) as t";
    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
    $this->view->contests = $db->query($Select)->fetchAll();
    if (engine_count($this->view->contests) < 4)
      return $this->setNoRender();
  }

}
