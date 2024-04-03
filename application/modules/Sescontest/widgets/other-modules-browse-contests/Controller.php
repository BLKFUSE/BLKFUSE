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

class Sescontest_Widget_OtherModulesBrowseContestsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    if(!$is_ajax ){
      if( !Engine_Api::_()->core()->hasSubject()) {
        return $this->setNoRender();
      }
      $subject = Engine_Api::_()->core()->getSubject();
    }else{
      $subject = Engine_Api::_()->getItem($this->_getParam('resource_type'),$this->_getParam('resource_id'));  
    }
    $sescontest_widget = Zend_Registry::isRegistered('sescontest_widget') ? Zend_Registry::get('sescontest_widget') : null;
    if(empty($sescontest_widget)) {
      return $this->setNoRender();
    }
    $this->view->resource_type = $resource_type = $subject->getType();
    $this->view->resource_id = $resource_id = $subject->getIdentity();
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $canCreate = 0;
    if($viewer->getIdentity() && $subject->authorization()->isAllowed($viewer, 'edit') && Engine_Api::_()->authorization()->isAllowed('contest', null, 'create')) 
      $canCreate = 1;
    
    $this->view->canCreate = $canCreate;    
    
    $getParams = '';
    $searchArray = array();
				if (isset($_POST['searchParams']) && $_POST['searchParams']) {
					if(engine_in_array($_POST['searchParams']))
						$searchArray = $_POST['searchParams'];
					elseif(is_string($_POST['searchParams']))
						parse_str($_POST['searchParams'], $searchArray);
				}
    else {
      $getParams = !empty($_POST['getParams']) ? $_POST['getParams'] : $_SERVER['QUERY_STRING'];
      parse_str($getParams, $get_array);
    }
    
    $this->view->getParams = $getParams;
    
    $this->view->is_search = $is_search = !empty($_POST['is_search']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->loadMoreLink = $this->_getParam('openTab') != NULL ? true : false;
    
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    if (isset($get_array)) {
      foreach ($get_array as $key => $getvalue) {
        $params[$key] = $getvalue;
      }
      if(isset($params['tag_id'])) 
        $params['tag'] = Engine_Api::_()->getItem('core_tag', $params['tag_id'])->text;
    }
    if (isset($_GET['category_id']))
      $params['category_id'] = $_GET['category_id'];

    if (isset($_GET['starttime']) && !empty($_GET['starttime']))
      $params['starttime'] = $_GET['starttime'];
    if (isset($_GET['endtime']) && !empty($_GET['endtime']))
      $params['endtime'] = $_GET['endtime'];

    if (isset($_GET['show']) && !empty($_GET['show']))
      $params['show'] = $_GET['show'];
    if (!empty($searchArray)) {
      foreach ($searchArray as $key => $search) {
        $params[$key] = $search;
      }
    }
    $this->view->view_type = $viewType = isset($_POST['type']) ? $_POST['type'] : (engine_count($params['enableTabs']) > 1?$params['openViewType']:$params['enableTabs'][0]);
    $limit_data = $params["limit_data_$viewType"];
    $this->view->optionsEnable = $optionsEnable = $params['enableTabs'];
    if (!empty($optionsEnable)) {
      $this->view->bothViewEnable = true;
    }
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $this->view->widgetName = 'other-modules-browse-contests';
    $page = $this->_getParam('page', 1);
    $this->view->page = $page;
    $value = array();
    $value['status'] = 1;
    $value['search'] = 1;
    $value['draft'] = 1;
  
    if (isset($params['search']))
      $params['text'] = addslashes($params['search']);
    $params['tag'] = isset($_GET['tag_id']) ? $_GET['tag_id'] : '';
    $params['resource_type'] = $resource_type;
    $params['resource_id'] = $resource_id;
    $params = array_merge($params, $value);
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('contests', 'sescontest')
            ->getContestPaginator($params);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    if(!$canCreate && !$paginator->getTotalItemCount())
      return $this->setNoRender();
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }
  }

}
