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
class Sescontest_Widget_CategoryAssociateContestsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
		
    $this->view->widgetName = 'category-associate-contests';
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $show_criterias = $params['show_criteria'];
    $this->view->gridblock = $params['gridblock'] = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '4');
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $show_category_criterias = $params['show_category_criteria'];
    foreach ($show_category_criterias as $show_category_criteria)
      $this->view->{$show_category_criteria . 'Active'} = $show_category_criteria;

    $this->view->paginatorCategory = $paginatorCategory = Engine_Api::_()->getDbTable('categories', 'sescontest')->getCategory(array('hasContest' => true, 'criteria' => $params['criteria'], 'order' => $params['popularty']), array('paginator' => 'yes'));

    $page = $this->_getParam('page', 1);
    $paginatorCategory->setItemCountPerPage($params['category_limit']);
    $paginatorCategory->setCurrentPageNumber($page);
    if ($paginatorCategory->getTotalItemCount() > 0) {
      foreach ($paginatorCategory as $key => $valuePaginator) {
        $countArray[] = $valuePaginator->total_contest_categories;
        $contestData['contest_data'][$valuePaginator->category_id] = Engine_Api::_()->getDbTable('contests', 'sescontest')->getContestSelect(array('category_id' => $valuePaginator->category_id, 'limit' => $params['contest_limit'], 'order' => $params['popularty'], 'info' => $params['order'], 'fetchAll' => true));
      }
    } else {
      if (!$is_ajax)
        return $this->setNoRender();
    }
    $sescontest_categories = Zend_Registry::isRegistered('sescontest_categories') ? Zend_Registry::get('sescontest_categories') : null;
    if(empty($sescontest_categories)) {
      return $this->setNoRender();
    }
    $this->view->countArray = $countArray;
    $this->view->resultArray = $contestData;

    // Set item count per page and current page number
    $this->view->page = $page;
    $this->view->paginatorCategory = $paginatorCategory;
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }
  }

}
