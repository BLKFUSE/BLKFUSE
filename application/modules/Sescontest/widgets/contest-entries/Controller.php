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

class Sescontest_Widget_ContestEntriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $searchArray = array();
				if (isset($_POST['searchParams']) && $_POST['searchParams']) {
					if(engine_in_array($_POST['searchParams']))
						$searchArray = $_POST['searchParams'];
					elseif(is_string($_POST['searchParams']))
						parse_str($_POST['searchParams'], $searchArray);
				}

    $contestId = (isset($_POST['contest_id']) ? $_POST['contest_id'] : 0);
    if ($contestId) {
      $subject = Engine_Api::_()->getItem('contest', $contestId);
      $this->view->contest = $subject;
    } else {
      $subject = Engine_Api::_()->core()->getSubject('contest');
      $this->view->contest = $subject;
      if (!$subject) {
        return $this->setNoRender();
      }
    }
    $this->view->contest_id = $subject->contest_id;
		
    $this->view->canComment = Engine_Api::_()->authorization()->isAllowed('participant', $this->view->viewer(), 'comment');
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '4');
    if (!empty($searchArray)) {
      foreach ($searchArray as $key => $search) {
        $params[$key] = $search;
      }
    }
    $limit_data = $params['limit_data'];

    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $this->view->bothViewEnable = false;
    $this->view->optionsEnable = $optionsEnable = $params['enableTabs'];
    $this->view->view_type = isset($_POST['type']) ? $_POST['type'] : $params['openViewType'];
    if (!empty($optionsEnable)) {
      $this->view->bothViewEnable = true;
    }
    $this->view->widgetName = 'contest-entries';
    $page = $this->_getParam('page', 1);
    $this->view->page = $page;
    $params = array_merge($params, array('contest_id' => $subject->contest_id));
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('participants', 'sescontest')
            ->getParticipantPaginator($params);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }
  }

}
