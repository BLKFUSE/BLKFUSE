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
class Sescontest_Widget_ManageContestsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $searchArray = array();
				if (isset($_POST['searchParams']) && $_POST['searchParams']) {
					if(engine_in_array($_POST['searchParams']))
						$searchArray = $_POST['searchParams'];
					elseif(is_string($_POST['searchParams']))
						parse_str($_POST['searchParams'], $searchArray);
				}

    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $this->view->is_search = !empty($_POST['is_search']) ? true : false;
    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);

    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);

    $show_criterias = $params['show_criteria'];
    if (is_array($show_criterias)) {
      foreach ($show_criterias as $show_criteria)
        $this->view->{$show_criteria . 'Active'} = $show_criteria;
    }

    $defaultOpenTab = array();
    $defaultOptions = $arrayOptions = array();
    $defaultOptionsArray = $params['search_type'];
    $arrayOptn = array();
    if (!$is_ajax && is_array($defaultOptionsArray)) {
      foreach ($defaultOptionsArray as $key => $defaultValue) {
        if ($this->_getParam($defaultValue . '_order'))
          $order = $this->_getParam($defaultValue . '_order');
        else
          $order = (1000 + $key);
        $arrayOptn[$order] = $defaultValue;
        if ($this->_getParam($defaultValue . '_label'))
          $valueLabel = $this->_getParam($defaultValue . '_label');
        else {
          if ($defaultValue == 'upcoming')
            $valueLabel = 'Coming Soon';
          elseif ($defaultValue == 'recentlySPcreated')
            $valueLabel = 'Recently Created';
          else if ($defaultValue == 'mostSPviewed')
            $valueLabel = 'Most Viewed';
          else if ($defaultValue == 'mostSPliked')
            $valueLabel = 'Most Liked';
          else if ($defaultValue == 'mostSPcommented')
            $valueLabel = 'Most Commented';
          else if ($defaultValue == 'mostSPfollowed')
            $valueLabel = 'Most Followed';
          else if ($defaultValue == 'mostSPjoined')
            $valueLabel = 'Most Joined';
          else if ($defaultValue == 'mostSPfavourite')
            $valueLabel = 'Most Favourited';
          else if ($defaultValue == 'featured')
            $valueLabel = 'Featured';
          else if ($defaultValue == 'sponsored')
            $valueLabel = 'Sponsored';
          else if ($defaultValue == 'verified')
            $valueLabel = 'Verified';
          else if ($defaultValue == 'hot')
            $valueLabel = 'Hot';
        }
        $arrayOptions[$order] = $valueLabel;
      }
      ksort($arrayOptions);
      $counter = 0;
      foreach ($arrayOptions as $key => $valueOption) {
        //$key = explode('||', $key);
        if ($counter == 0)
          $this->view->defaultOpenTab = $defaultOpenTab = $arrayOptn[$key];
        $defaultOptions[$arrayOptn[$key]] = $valueOption;
        $counter++;
      }
    }
    $sescontest_manage = Zend_Registry::isRegistered('sescontest_manage') ? Zend_Registry::get('sescontest_manage') : null;
    if(empty($sescontest_manage)) {
      return $this->setNoRender();
    }
    $this->view->defaultOptions = $defaultOptions;

    if (isset($_GET['openTab']) || $is_ajax) {
      $this->view->defaultOpenTab = $defaultOpenTab = (isset($_GET['openTab']) ? str_replace('_', 'SP', $_GET['openTab']) : ($this->_getParam('openTab') != NULL ? $this->_getParam('openTab') : (isset($params['openTab']) ? $params['openTab'] : '' )));
    }

    switch ($defaultOpenTab) {
      case 'ended':
        $params['sort'] = 'ended';
        break;
      case 'active':
        $params['sort'] = 'ongoing';
        break;
      case 'upcoming':
        $params['sort'] = 'upcoming';
        break;
      case 'recentlySPcreated':
        $params['sort'] = 'creation_date';
        break;
      case 'mostSPviewed':
        $params['sort'] = 'view_count';
        break;
      case 'mostSPliked':
        $params['sort'] = 'like_count';
        break;
      case 'mostSPcommented':
        $params['sort'] = 'comment_count';
        break;
      case 'mostSPfavourite':
        $params['sort'] = 'favourite_count';
        break;
      case 'mostSPfollowed':
        $params['sort'] = 'follow_count';
        break;
      case 'mostSPjoined':
        $params['sort'] = 'join_count';
        break;
      case 'featured':
        $params['sort'] = 'featured';
        break;
      case 'sponsored':
        $params['sort'] = 'sponsored';
        break;
      case 'verified':
        $params['sort'] = 'verified';
        break;
      case 'hot':
        $params['sort'] = 'hot';
        break;
    }

    if (!empty($searchArray)) {
      foreach ($searchArray as $key => $search) {
        $params[$key] = $search;
      }
    }
    $this->view->view_type = $viewType = isset($_POST['type']) ? $_POST['type'] : 'list';
    $limit_data = $params["limit_data_$viewType"];
    $this->view->optionsEnable = $optionsEnable = 1;
    if (!empty($optionsEnable)) {
      $this->view->bothViewEnable = true;
    }
    $this->view->widgetName = 'manage-contests';
    $page = $this->_getParam('page', 1);
    $this->view->page = $page;
    $value = array();
    $value['user_id'] = Engine_Api::_()->user()->getViewer()->getIdentity();
    if (isset($params['search']))
      $params['text'] = addslashes($params['search']);
    $params = array_merge($params, $value);
    $params['fixedData'] = isset($fixedData) ? $fixedData : '';
    $params['widgetManage'] = true;
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('contests', 'sescontest')
            ->getContestPaginator($params);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }
  }

}
