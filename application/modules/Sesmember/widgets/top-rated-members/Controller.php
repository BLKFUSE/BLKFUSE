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
class Sesmember_Widget_TopRatedMembersController extends Engine_Content_Widget_Abstract {

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
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    $value['text'] = isset($searchArray['search_text']) ? $searchArray['search_text'] : (!empty($params['search_text']) ? $params['search_text'] : (isset($_GET['search_text']) && ($_GET['search_text'] != '') ? $_GET['search_text'] : ''));
    $value['location'] = isset($searchArray['location']) ? $searchArray['location'] : (isset($_GET['location']) ? $_GET['location'] : (isset($params['location']) ? $params['location'] : ''));
    $value['show'] = isset($searchArray['show']) ? $searchArray['show'] : (isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ? $params['show'] : ''));
    $value['miles'] = isset($searchArray['miles']) ? $searchArray['miles'] : (isset($_GET['miles']) ? $_GET['miles'] : (isset($params['miles']) ? $params['miles'] : ''));
    $value['view'] = isset($searchArray['view']) ? $searchArray['view'] : (isset($_GET['view']) ? $_GET['view'] : (isset($params['view']) ? $params['view'] : ''));
    $value['country'] = isset($searchArray['country']) ? $searchArray['country'] : (isset($_GET['country']) ? $_GET['country'] : (isset($params['country']) ? $params['country'] : ''));
    $value['state'] = isset($searchArray['state']) ? $searchArray['state'] : (isset($_GET['state']) ? $_GET['state'] : (isset($params['state']) ? $params['state'] : ''));
    $value['city'] = isset($searchArray['city']) ? $searchArray['city'] : (isset($_GET['city']) ? $_GET['city'] : (isset($params['city']) ? $params['city'] : ''));
    $value['zip'] = isset($searchArray['zip']) ? $searchArray['zip'] : (isset($_GET['zip']) ? $_GET['zip'] : (isset($params['zip']) ? $params['zip'] : ''));
    $value['alphabet'] = isset($searchArray['alphabet']) ? $searchArray['alphabet'] : (isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : ''));
    $value['lat'] = isset($searchArray['lat']) ? $searchArray['lat'] : (isset($_GET['lat']) ? $_GET['lat'] : (isset($params['lat']) ? $params['lat'] : '26.9110600'));
    $value['lng'] = isset($searchArray['lng']) ? $searchArray['lng'] : (isset($_GET['lng']) ? $_GET['lng'] : (isset($params['lng']) ? $params['lng'] : '75.7373560'));

    $value['is_vip'] = isset($searchArray['is_vip']) ? $searchArray['is_vip'] : (isset($_GET['is_vip']) ? $_GET['is_vip'] : (isset($params['is_vip']) ? $params['is_vip'] : ''));
    $value['has_photo'] = isset($searchArray['has_photo']) ? $searchArray['has_photo'] : (isset($_GET['has_photo']) ? $_GET['has_photo'] : (isset($params['has_photo']) ? $params['has_photo'] : ''));
    $value['is_online'] = isset($searchArray['is_online']) ? $searchArray['is_online'] : (isset($_GET['is_online']) ? $_GET['is_online'] : (isset($params['is_online']) ? $params['is_online'] : ''));
    $value['profile_type'] = isset($searchArray['profile_type']) ? $searchArray['profile_type'] : (isset($_GET['profile_type']) ? $_GET['profile_type'] : (isset($params['profile_type']) ? $params['profile_type'] : ''));
    $sesmember_topmembers = Zend_Registry::isRegistered('sesmember_topmembers') ? Zend_Registry::get('sesmember_topmembers') : null;
    if (empty($sesmember_topmembers))
      return $this->setNoRender();
    $this->view->title_truncation_list = $title_truncation_list = isset($params['list_title_truncation']) ? $params['list_title_truncation'] : $this->_getParam('list_title_truncation', '45');
    $this->view->rating_graph = $defaultRatingGraph = isset($params['rating_graph']) ? $params['rating_graph'] : $this->_getParam('rating_graph', '1');
    $this->view->height = $defaultHeight = isset($params['height']) ? $params['height'] : $this->_getParam('height', '350');
    
    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', '2');
    
    $this->view->width = $defaultWidth = isset($params['width']) ? $params['width'] : $this->_getParam('width', '220');
    $value['network'] = isset($searchArray['network']) ? $searchArray['network'] : (isset($_GET['network']) ? $_GET['network'] : (isset($params['network']) ? $params['network'] : ''));
    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'follow', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'verifiedLabel', 'likeButton', 'friendButton', 'likemainButton', 'message', 'followButton', 'friendCount', 'profileType', 'mutualFriendCount', 'email', 'location', 'age', 'recommendation'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $this->view->page = $page = $this->_getParam('page', 1);
    $this->view->limit = $limit = isset($_POST['limit']) ? $_POST['limit'] : $this->_getParam('limit_data', 10);

    $value['order'] = isset($params['order']) ? $params['order'] : $this->_getParam('order', '');
    $value['info'] = isset($params['info']) ? $params['info'] : $this->_getParam('search_type', 'most_rated');
    $value['info'] = str_replace('SP', '_', $value['info']);

    $this->view->params = array('list_title_truncation' => $title_truncation_list, 'show_criterias' => $show_criterias, 'rating_graph' => $defaultRatingGraph, 'height' => $defaultHeight, 'order' => $value['order'], 'location' => $value['location'], 'lat' => $value['lat'], 'lng' => $value['lng'], 'miles' => $value['miles'], 'country' => $value['country'], 'state' => $value['state'], 'city' => $value['city'], 'zip' => $value['zip'], 'alphabet' => $value['alphabet'], 'width' => $defaultWidth, 'info' => $value['info'], 'network' => $value['network'], 'is_vip' => $value['is_vip'], 'has_photo' => $value['has_photo'], 'is_online' => $value['is_online'], 'profile_type' => $value['profile_type'], 'socialshare_enable_plusicon' => $socialshare_enable_plusicon, 'socialshare_icon_limit' => $socialshare_icon_limit);

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $identity = $view->identity;
    $this->view->widgetId = isset($_POST['widgetId']) ? $_POST['widgetId'] : $identity;
    $this->view->loadOptionData = isset($_POST['loadOptionData']) ? $_POST['loadOptionData'] : $this->_getParam('pagging', 'auto_load');
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('members', 'sesmember')->getMemberPaginator($value, array('is_vip' => $value['is_vip'], 'has_photo' => $value['has_photo'], 'is_online' => $value['is_online']));
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);
    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');
  }

}
