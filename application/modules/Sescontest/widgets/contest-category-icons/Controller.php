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

class Sescontest_Widget_ContestCategoryIconsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $params = Engine_Api::_()->sescontest()->getWidgetParams($this->view->identity);
		$this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '2');

    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->$show_criteria = $show_criteria;

    if ((is_array($show_criterias) && engine_in_array('countContests', $show_criterias)) || $params['criteria'] == 'most_contest')
      $params['countContests'] = true;
    $this->view->params = $params;

    // Get contests category
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('categories', 'sescontest')->getCategory(array('countContests' => true, 'limit' => $params['limit_data'], 'fetchAll' => true));

    if (is_countable($paginator) && engine_count($paginator) == 0)
      return;
  }

}
