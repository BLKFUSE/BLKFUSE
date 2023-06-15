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

class Sescontest_Widget_ContestWinnerEntriesController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);

    $contestId = (isset($_POST['contest_id']) ? $_POST['contest_id'] : 0);
    if ($contestId) {
      $subject = Engine_Api::_()->getItem('contest', $contestId);
    } else {
      $subject = Engine_Api::_()->core()->getSubject('contest');
      if (!$subject) {
        return $this->setNoRender();
      }
    }
    $this->view->contest_id = $subject->contest_id;
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $this->view->view_type = $viewType = isset($_POST['type']) ? $_POST['type'] :$params['openViewType'];

    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $this->view->widgetName = 'contest-winner-entries';

    $this->view->paginator = Engine_Api::_()->getDbTable('participants', 'sescontest')->getWinnerSelect(array('fetchAll' => true, 'contest_id' => $subject->contest_id, 'sort' => $viewType));
    if (engine_count($this->view->paginator) < 1)
      return $this->setNoRender();
  }

}
