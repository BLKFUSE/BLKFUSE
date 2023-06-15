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

class Sescontest_Widget_CategoryViewController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $limit_data = $params['contest_limit'];
    $this->view->gridblock = $params['gridblock'] = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '4');
    $categoryId = (isset($_POST['category_id']) ? $_POST['category_id'] : 0);
    if ($categoryId) {
      $category = Engine_Api::_()->getItem('sescontest_category', $categoryId);
      $this->view->category = $category;
    } else {
      $category = Engine_Api::_()->core()->getSubject('sescontest_category');
      $this->view->category = $category;
      if (!$category) {
        return $this->setNoRender();
      }
    }

    $this->view->category_id = $category->category_id;
    $sescontest_categories = Zend_Registry::isRegistered('sescontest_categories') ? Zend_Registry::get('sescontest_categories') : null;
    if(empty($sescontest_categories)) {
      return $this->setNoRender();
    }
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $show_subcatcriterias = $params['show_subcatcriteria'];
    foreach ($show_subcatcriterias as $show_subcatcriteria)
      $this->view->{$show_subcatcriteria . 'SubcatActive'} = $show_subcatcriteria;

    $innerCatData = array();
    if (!$is_ajax) {
      if ($category->subcat_id == 0 && $category->subsubcat_id == 0) {
        $innerCatData = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubcategory(array('category_id' => $category->category_id, 'column_name' => '*', 'countContests' => true, 'getcategory0' => true));
        $columnCategory = 'category_id';
      } else if ($category->subsubcat_id == 0) {
        $innerCatData = Engine_Api::_()->getDbtable('categories', 'sescontest')->getModuleSubsubcategory(array('countContests' => true, 'category_id' => $category->category_id, 'column_name' => '*', 'countContests' => true, 'getcategory0' => true));
        $columnCategory = 'subcat_id';
      } else
        $columnCategory = 'subsubcat_id';
      $this->view->innerCatData = $innerCatData;
      //breadcum
      $this->view->breadcrumb = Engine_Api::_()->getDbtable('categories', 'sescontest')->getBreadcrumb($category);

        $value['view'] = $params['view'];
        $value['info'] = $params['info'];
        

        if ($params['show_popular_contests']) {
          $value['info'] = str_replace("SP",'_',$params['info']);
        $this->view->paginatorc = Engine_Api::_()->getDbTable('contests', 'sescontest')
                ->getContestPaginator(array_merge($value, array('search' => 1, 'fetchAll' => true, 'limit' => 3, $columnCategory => $category->getIdentity())));
        }
    }

    $this->view->paginator = array();
    $this->view->paginator = $paginator = Engine_Api::_()->getDbtable('contests', 'sescontest')->getContestPaginator(array($columnCategory => $category->category_id,'getcategory0' => true), false);
    $page = $this->_getParam('page', 1);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    $this->view->widgetName = 'category-view';
    // initialize type variable type
    $this->view->page = $page;

    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    } else {
      // Do not render if nothing to show
      if ($paginator->getTotalItemCount() <= 0) {
        
      }
    }
  }

}
