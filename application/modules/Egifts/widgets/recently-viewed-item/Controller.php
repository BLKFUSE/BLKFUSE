<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Egifts_Widget_RecentlyViewedItemController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    if ($this->view->viewer()->getIdentity() == 0)
      return $this->setNoRender();
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $params = Engine_Api::_()->egifts()->getWidgetParams($widgetId);

    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $value = array();
    $value['criteria'] = $type;
    $value['limit'] = $params['limit_data'];
    $value['type'] = 'egifts_gift';
    $value['paginator'] = 1;
    $this->view->page = $page;
    $this->view->params = $params;
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('recentlyviewitems', 'egifts')->getitem($value);
    $this->view->getitem = true;
    if (is_countable($paginator) && engine_count($paginator) == 0)
      return $this->setNoRender();
  }

}
