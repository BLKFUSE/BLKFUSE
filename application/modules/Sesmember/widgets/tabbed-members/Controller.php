<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Widget_TabbedMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    // Prepare
    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);
		if (isset($_POST['searchParams']) && $_POST['searchParams']) {
			if(engine_in_array($_POST['searchParams']))
				$searchArray = $_POST['searchParams'];
			elseif(is_string($_POST['searchParams']))
				parse_str($_POST['searchParams'], $searchArray);
		}
    $viewer = Engine_Api::_()->user()->getViewer();
		$this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '3');
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $value['nearest'] = false;
    if (!$is_ajax) {
      $p = Zend_Controller_Front::getInstance()->getRequest()->getParams();
      $nearest = false;
      if ($p['action'] == 'nearest-member' && $p['module'] == 'sesmember')
        $nearest = true;
      $value['nearest'] = $nearest;
    }
    $value['nearest'] = isset($searchArray['nearest']) ? $searchArray['nearest'] : (isset($_GET['nearest']) ? $_GET['nearest'] : (isset($params['nearest']) ? $params['nearest'] : $value['nearest']));
    ;
    $page = $this->_getParam('page', 1);
    $defaultOptionsArray = $this->_getParam('search_type', array('week', 'month', 'recentlySPcreated', 'mostSPviewed', 'mostSPliked', 'mostSPrated', 'featured', 'sponsored', 'verified', 'vip'));
    if (!$is_ajax && is_array($defaultOptionsArray)) {
      $this->view->tab_option = $this->_getParam('tabOption', 'advance');
      $defaultOptions = $arrayOptions = array();
      foreach ($defaultOptionsArray as $key => $defaultValue) {
        if ($this->_getParam($defaultValue . '_order'))
          $order = $this->_getParam($defaultValue . '_order');
        else
          $order = (777 + $key);
        if ($this->_getParam($defaultValue . '_label'))
          $valueLabel = $this->_getParam($defaultValue . '_label') . '||' . $defaultValue;
        else {
          if ($defaultValue == 'week')
            $valueLabel = 'This Week' . '||' . $defaultValue;
          else if ($defaultValue == 'month')
            $valueLabel = 'This Month' . '||' . $defaultValue;
          else if ($defaultValue == 'recentlySPcreated')
            $valueLabel = 'Recently Created' . '||' . $defaultValue;
          else if ($defaultValue == 'mostSPviewed')
            $valueLabel = 'Most Viewed' . '||' . $defaultValue;
          else if ($defaultValue == 'mostSPliked')
            $valueLabel = 'Most Liked' . '||' . $defaultValue;
          else if ($defaultValue == 'mostSPrated')
            $valueLabel = 'Most Rated' . '||' . $defaultValue;
          else if ($defaultValue == 'featured')
            $valueLabel = 'Featured' . '||' . $defaultValue;
          else if ($defaultValue == 'sponsored')
            $valueLabel = 'Sponsored' . '||' . $defaultValue;
          else if ($defaultValue == 'verified')
            $valueLabel = 'Verified' . '||' . $defaultValue;
          else if ($defaultValue == 'vip')
            $valueLabel = 'Vip' . '||' . $defaultValue;
        }
        $arrayOptions[$order] = $valueLabel;
      }
      ksort($arrayOptions);
      $counter = 0;
      foreach ($arrayOptions as $key => $valueOption) {
        $key = explode('||', $valueOption);
        if ($counter == 0)
          $this->view->defaultOpenTab = $defaultOpenTab = $key[1];
        $defaultOptions[$key[1]] = $key[0];
        $counter++;
      }
      $this->view->defaultOptions = $defaultOptions;
    }

    if (isset($_GET['openTab']) || $is_ajax) {
      $this->view->defaultOpenTab = $defaultOpenTab = ($this->_getParam('openTab', false) ? $this->_getParam('openTab') : (isset($params['order']) ? $params['order'] : ''));
    }
    $sesmember_tabbed = Zend_Registry::isRegistered('sesmember_tabbed') ? Zend_Registry::get('sesmember_tabbed') : null;
    if (empty($sesmember_tabbed))
      return $this->setNoRender();
    $this->view->show_item_count = $show_item_count = isset($params['show_item_count']) ? $params['show_item_count'] : $this->_getParam('show_item_count', 0);
    $this->view->show_limited_data = $show_limited_data = isset($params['show_limited_data']) ? $params['show_limited_data'] : $this->_getParam('show_limited_data', 0);
    $text = isset($searchArray['search_text']) ? $searchArray['search_text'] : (!empty($params['search_text']) ? $params['search_text'] : (isset($_GET['search_text']) && ($_GET['search_text'] != '') ? $_GET['search_text'] : ''));
    $limit_data = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', '10');
    
    
    $this->view->socialshare_enable_plusiconlistview = $socialshare_enable_plusiconlistview = isset($params['socialshare_enable_plusiconlistview']) ? $params['socialshare_enable_plusiconlistview'] : $this->_getParam('socialshare_enable_plusiconlistview', 1);
    $this->view->socialshare_icon_limitlistview = $socialshare_icon_limitlistview = isset($params['socialshare_icon_limitlistview']) ? $params['socialshare_icon_limitlistview'] : $this->_getParam('socialshare_icon_limitlistview', '2');

    $this->view->socialshare_enable_plusiconadvlistview = $socialshare_enable_plusiconadvlistview = isset($params['socialshare_enable_plusiconadvlistview']) ? $params['socialshare_enable_plusiconadvlistview'] : $this->_getParam('socialshare_enable_plusiconadvlistview', 1);
    $this->view->socialshare_icon_limitadvlistview = $socialshare_icon_limitadvlistview = isset($params['socialshare_icon_limitadvlistview']) ? $params['socialshare_icon_limitadvlistview'] : $this->_getParam('socialshare_icon_limitadvlistview', '2');

    $this->view->socialshare_enable_plusicongridview = $socialshare_enable_plusicongridview = isset($params['socialshare_enable_plusicongridview']) ? $params['socialshare_enable_plusicongridview'] : $this->_getParam('socialshare_enable_plusicongridview', 1);
    $this->view->socialshare_icon_limitgridview = $socialshare_icon_limitgridview = isset($params['socialshare_icon_limitgridview']) ? $params['socialshare_icon_limitgridview'] : $this->_getParam('socialshare_icon_limitgridview', '2');

    $this->view->socialshare_enable_plusiconpinview = $socialshare_enable_plusiconpinview = isset($params['socialshare_enable_plusiconpinview']) ? $params['socialshare_enable_plusiconpinview'] : $this->_getParam('socialshare_enable_plusiconpinview', 1);
    $this->view->socialshare_icon_limitpinview = $socialshare_icon_limitpinview = isset($params['socialshare_icon_limitpinview']) ? $params['socialshare_icon_limitpinview'] : $this->_getParam('socialshare_icon_limitpinview', '2');

    $this->view->socialshare_enable_plusiconmapview = $socialshare_enable_plusiconmapview = isset($params['socialshare_enable_plusiconmapview']) ? $params['socialshare_enable_plusiconmapview'] : $this->_getParam('socialshare_enable_plusiconmapview', 1);
    $this->view->socialshare_icon_limitmapview = $socialshare_icon_limitmapview = isset($params['socialshare_icon_limitmapview']) ? $params['socialshare_icon_limitmapview'] : $this->_getParam('socialshare_icon_limitmapview', '2');

    $this->view->socialshare_enable_plusiconadvgridview = $socialshare_enable_plusiconadvgridview = isset($params['socialshare_enable_plusiconadvgridview']) ? $params['socialshare_enable_plusiconadvgridview'] : $this->_getParam('socialshare_enable_plusiconadvgridview', 1);
    $this->view->socialshare_icon_limitadvgridview = $socialshare_icon_limitadvgridview = isset($params['socialshare_icon_limitadvgridview']) ? $params['socialshare_icon_limitadvgridview'] : $this->_getParam('socialshare_icon_limitadvgridview', '2');
    
    $this->view->list_title_truncation = $list_title_truncation = isset($params['list_title_truncation']) ? $params['list_title_truncation'] : $this->_getParam('list_title_truncation', '100');
    $this->view->grid_title_truncation = $grid_title_truncation = isset($params['grid_title_truncation']) ? $params['grid_title_truncation'] : $this->_getParam('grid_title_truncation', '100');
    $this->view->pinboard_title_truncation = $pinboard_title_truncation = isset($params['pinboard_title_truncation']) ? $params['pinboard_title_truncation'] : $this->_getParam('pinboard_title_truncation', '100');
    $this->view->profileFieldCount = $profileFieldCount = isset($params['profileFieldCount']) ? $params['profileFieldCount'] : $this->_getParam('profileFieldCount', '5');
    $value['location'] = isset($searchArray['location']) ? $searchArray['location'] : (isset($_GET['location']) ? $_GET['location'] : (isset($params['location']) ? $params['location'] : ''));
    $value['show'] = isset($searchArray['show']) ? $searchArray['show'] : (isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ? $params['show'] : ''));
    $value['miles'] = isset($searchArray['miles']) ? $searchArray['miles'] : (isset($_GET['miles']) ? $_GET['miles'] : (isset($params['miles']) ? $params['miles'] : ''));
    $value['view'] = isset($searchArray['view']) ? $searchArray['view'] : (isset($_GET['view']) ? $_GET['view'] : (isset($params['view']) ? $params['view'] : ''));
    $value['country'] = isset($searchArray['country']) ? $searchArray['country'] : (isset($_GET['country']) ? $_GET['country'] : (isset($params['country']) ? $params['country'] : ''));
    $value['state'] = isset($searchArray['state']) ? $searchArray['state'] : (isset($_GET['state']) ? $_GET['state'] : (isset($params['state']) ? $params['state'] : ''));
    $value['city'] = isset($searchArray['city']) ? $searchArray['city'] : (isset($_GET['city']) ? $_GET['city'] : (isset($params['city']) ? $params['city'] : ''));
    $value['zip'] = isset($searchArray['zip']) ? $searchArray['zip'] : (isset($_GET['zip']) ? $_GET['zip'] : (isset($params['zip']) ? $params['zip'] : ''));
    $value['alphabet'] = isset($searchArray['alphabet']) ? $searchArray['alphabet'] : (isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : ''));



    $this->view->advgrid_title_truncation = $advgrid_title_truncation = isset($params['advgrid_title_truncation']) ? $params['advgrid_title_truncation'] : $this->_getParam('advgrid_title_truncation', '100');
    $this->view->advgrid_height = $advgrid_height = isset($params['advgrid_height']) ? $params['advgrid_height'] : $this->_getParam('advgrid_height', '222');
    $this->view->advgrid_width = $advgrid_width = isset($params['advgrid_width']) ? $params['advgrid_width'] : $this->_getParam('advgrid_width', '322');

    //search data
    $orderKey = str_replace(array('SP', ''), array(' ', ' '), $defaultOpenTab);
    $defaultOrder = Engine_Api::_()->sesbasic()->getColumnName($orderKey);
    $value['order'] = $defaultOpenTab;
    $value['info'] = str_replace('SP', '_', $defaultOpenTab);
    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'rating', 'title', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'likeButton', 'socialSharing', 'view', 'friendCount', 'profileType', 'age', 'message', 'profileField', 'heading', 'labelBold', 'verifiedLabel', 'likeButton', 'mutualFriendCount', 'friendButton', 'likemainButton'));

    $this->view->identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : '';
    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 'auto_load');

    if ($viewer->getIdentity() && @$value['view'] == 1) {
      $value['users'] = array();
      foreach ($viewer->membership()->getMembersInfo(true) as $memberinfo) {
        $value['users'][] = $memberinfo->user_id;
      }
    }

    // check to see if request is for specific user's listings
    if (($user_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('user'))) {
      $values['user_id'] = $user_id;
    }

    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    if (!$is_ajax) {
      $this->view->optionsEnable = $optionsEnable = $this->_getParam('enableTabs', array('list', 'advlist', 'advgrid', 'grid', 'pinboard', 'map'));
      if (!engine_count($optionsEnable))
        $this->setNoRender();
      $view_type = $this->_getParam('openViewType', 'list');
      if (!engine_in_array($view_type, $optionsEnable))
        $view_type = $optionsEnable[0];
      if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1) && $view_type == 'map')
        $view_type = $optionsEnable[0];
      
        $this->view->bothViewEnable = true;
    }
    $this->view->view_type = $view_type = (isset($_POST['type']) ? $_POST['type'] : (isset($params['view_type']) ? $params['view_type'] : $view_type));
    $this->view->height = $defaultHeight = isset($params['height']) ? $params['height'] : $this->_getParam('height', '200px');
    $this->view->width = $defaultWidth = isset($params['width']) ? $params['width'] : $this->_getParam('width', '200px');
    $this->view->list_container_height = $listContainerHeight = isset($params['list_container_height']) ? $params['list_container_height'] : $this->_getParam('main_height', '200px');
    $this->view->list_container_width = $listContainerWidth = isset($params['list_container_width']) ? $params['list_container_width'] : $this->_getParam('main_width', '200px');
    $this->view->photo_height = $defaultPhotoHeight = isset($params['photo_height']) ? $params['photo_height'] : $this->_getParam('photo_height', '200px');
    $this->view->photo_width = $defaultPhotoWidth = isset($params['photo_width']) ? $params['photo_width'] : $this->_getParam('photo_width', '200px');
    $this->view->info_height = $defaultInfoHeight = isset($params['info_height']) ? $params['info_height'] : $this->_getParam('info_height', '200px');
    $this->view->pinboard_width = $defaultPinboardWidth = isset($params['pinboard_width']) ? $params['pinboard_width'] : $this->_getParam('pinboard_width', '200px');
    $value['lat'] = isset($searchArray['lat']) ? $searchArray['lat'] : (isset($_GET['lat']) ? $_GET['lat'] : (isset($params['lat']) ? $params['lat'] : '26.9110600'));
    $value['lng'] = isset($searchArray['lng']) ? $searchArray['lng'] : (isset($_GET['lng']) ? $_GET['lng'] : (isset($params['lng']) ? $params['lng'] : '75.7373560'));
    if (!$is_ajax && $nearest) {
      $viewer = Engine_Api::_()->user()->getViewer();
      $getLoggedInuserLocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData($viewer->getType(), $viewer->getIdentity());
      if ($getLoggedInuserLocation) {
        $value['location'] = $viewer->location;
        $value['lat'] = $getLoggedInuserLocation->lat;
        $value['lng'] = $getLoggedInuserLocation->lng;
        $value['miles'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_nearest_distance', 1000);
      }
    }
    $params = array('gridblock' => $gridblock, 'pagging' => $loadOptionData, 'limit_data' => $limit_data, 'profileFieldCount' => $profileFieldCount, 'list_title_truncation' => $list_title_truncation, 'grid_title_truncation' => $grid_title_truncation, 'pinboard_title_truncation' => $pinboard_title_truncation, 'show_criterias' => $show_criterias, 'view_type' => $view_type, 'height' => $defaultHeight, 'list_container_height' => $listContainerHeight, 'list_container_width' => $listContainerWidth, 'photo_height' => $defaultPhotoHeight, 'photo_width' => $defaultPhotoWidth, 'info_height' => $defaultInfoHeight, 'pinboard_width' => $defaultPinboardWidth, 'order' => $value['order'], 'location' => $value['location'], 'lat' => $value['lat'], 'lng' => $value['lng'], 'miles' => $value['miles'], 'country' => $value['country'], 'state' => $value['state'], 'city' => $value['city'], 'zip' => $value['zip'], 'alphabet' => $value['alphabet'], 'width' => $defaultWidth, 'show_limited_data' => $show_limited_data, 'advgrid_title_truncation' => $advgrid_title_truncation, 'advgrid_height' => $advgrid_height, 'advgrid_width' => $advgrid_width, 'show_item_count' => $show_item_count, 'socialshare_enable_plusicon' => $socialshare_enable_plusicon, 'socialshare_icon_limit' => $socialshare_icon_limit);

    $this->view->widgetName = 'tabbed-members';
    $this->view->page = $page;

    $this->view->params = array_merge($params, $value);
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }

    // Get paginator
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('members', 'sesmember')
            ->getMemberPaginator(array_merge($value, array('search' => 1)));
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
  }

}
