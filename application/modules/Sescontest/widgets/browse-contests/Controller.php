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
class Sescontest_Widget_BrowseContestsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
     
      
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
		
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->is_search = $is_search = !empty($_POST['is_search']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->loadMoreLink = $this->_getParam('openTab') != NULL ? true : false;

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $this->view->gridblock = $params['gridblock'] = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '3');
    if (!$is_ajax) {
      $defaultSearchParams = Engine_Api::_()->sescontest()->getSearchWidgetParams($widgetId);
      if ($defaultSearchParams) {
        $params['show'] = isset($defaultSearchParams['default_view_search_type'])?$defaultSearchParams['default_view_search_type']:'';
        switch ($defaultSearchParams['default_search_type']) {
          case 'recentlySPcreated':
            $columnValue = 'creation_date';
            break;
          case 'mostSPviewed':
            $columnValue = 'view_count';
            break;
          case 'mostSPliked':
            $columnValue = 'like_count';
            break;
          case 'mostSPcommented':
            $columnValue = 'comment_count';
            break;
          case 'mostSPfavourite':
            $columnValue = 'favourite_count';
            break;
          case 'mostSPfollower':
            $columnValue = 'follow_count';
            break;
          case 'entrymintomax':
            $columnValue = 'join_count ASC';
            break;
          case 'entrymaxtomin':
            $columnValue = 'join_count DESC';
            break;
          case 'featured':
            $columnValue = 'featured';
            break;
          case 'sponsored':
            $columnValue = 'sponsored';
            break;
          case 'verified':
            $columnValue = 'verified';
            break;
          case 'hot':
            $columnValue = 'hot';
            break;
        }
        $params['sort'] = $columnValue;
      }
    }
    $sescontest_browse = Zend_Registry::isRegistered('sescontest_browse') ? Zend_Registry::get('sescontest_browse') : null;
    if(empty($sescontest_browse)) {
      return $this->setNoRender();
    }
    if (isset($get_array)) {
      foreach ($get_array as $key => $getvalue) {
        $params[$key] = $getvalue;
      }
      if (isset($params['tag_id']))
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
    $this->view->view_type = $viewType = isset($_POST['type']) ? $_POST['type'] : (engine_count($params['enableTabs']) > 1 ? $params['openViewType'] : $params['enableTabs'][0]);
    $limit_data = $params["limit_data_$viewType"];
    $this->view->optionsEnable = $optionsEnable = $params['enableTabs'];
    if (!empty($optionsEnable)) {
      $this->view->bothViewEnable = true;
    }
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $this->view->widgetName = 'browse-contests';
    $page = $this->_getParam('page', 1);
    $this->view->page = $page;
    $value = array();
    $value['status'] = 1;
    $value['search'] = 1;
    $value['draft'] = "1";
    
    // Contest based contest browse page work
    $page_id = Engine_Api::_()->sescontest()->getWidgetPageId($widgetId);
    if($page_id) {
      $pageName = Engine_Db_Table::getDefaultAdapter()->select()
              ->from('engine4_core_pages', 'name')
              ->where('page_id = ?', $page_id)
              ->limit(1)
              ->query()
              ->fetchColumn();
      if($pageName) {
        $explode = explode('sescontest_index_', $pageName);
        if(is_numeric($explode[1])) {
          $type = Engine_Db_Table::getDefaultAdapter()->select()
                ->from('engine4_sescontest_integrateothermodules', 'content_type')
                ->where('integrateothermodule_id = ?', $explode[1])
                ->limit(1)
                ->query()
                ->fetchColumn();
          if($type) {
            $params['resource_type'] = $type;
          }
        }
      }
    }
    // Contest based contest browse page work

    if (isset($params['search']))
      $params['text'] = addslashes($params['search']);
      if (isset($_GET['search']))
          $params['text'] = addslashes($_GET['search']);
    $params['tag'] = isset($_GET['tag_id']) ? $_GET['tag_id'] : '';
    $params = array_merge($params, $value);
    
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('contests', 'sescontest')
            ->getContestPaginator($params);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }
  }

}
