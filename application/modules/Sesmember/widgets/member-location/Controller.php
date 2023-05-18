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
class Sesmember_Widget_MemberLocationController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1))
      return setNoRender();
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
    if (isset($_POST['searchParams']) && $_POST['searchParams'])
      parse_str($_POST['searchParams'], $searchArray);
    if (!$is_ajax)
      $value['locationWidget'] = true;
    
    $searchParams = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    $form = new Sesmember_Form_Filter_Browse(array('friendType' => 'yes', 'searchType' => 'yes', 'locationSearch' => 'yes', 'kilometerMiles' => 'yes', 'browseBy' => 'yes', 'searchTitle' => 'yes', 'FriendsSearch' => 'yes', 'citySearch' => 'yes', 'stateSearch' => 'yes', 'zipSearch' => 'yes', 'countrySearch' => 'yes', 'alphabetSearch' => 'yes', 'memberType' => 'yes', 'hasPhoto' => 'yes', 'isOnline' => 'yes', 'isVip' => 'yes', 'type' => 'user', 'networkGet' => 'yes'));
    if($is_ajax){
     $form->populate($searchArray);
     $formValues = $form->getValues();
    }else{
      $formValues = array();  
    }
    $page = $this->_getParam('page', 1);
    $value['sort'] = isset($searchArray['order']) ? $searchArray['order'] : (isset($_GET['order']) ? $_GET['order'] : (isset($params['order']) ? $params['order'] : $this->_getParam('sort', 'mostSPliked')));
    $value['search'] = 1;
    $this->view->location = $value['location'] = isset($searchArray['location']) ? $searchArray['location'] : (isset($_GET['location']) ? $_GET['location'] : (isset($params['location']) ? $params['location'] : $this->_getParam('location')));
    $this->view->lat = $value['lat'] = isset($searchArray['lat']) ? $searchArray['lat'] : (isset($_GET['lat']) ? $_GET['lat'] : (isset($params['lat']) ? $params['lat'] : $this->_getParam('lat', '26.9110600')));
    $value['show'] = isset($searchArray['show']) ? $searchArray['show'] : (isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ? $params['show'] : ''));
    $this->view->lng = $value['lng'] = isset($searchArray['lng']) ? $searchArray['lng'] : (isset($_GET['lng']) ? $_GET['lng'] : (isset($params['lng']) ? $params['lng'] : $this->_getParam('lng', '75.7373560')));
    $value['miles'] = isset($searchArray['miles']) ? $searchArray['miles'] : (isset($_GET['miles']) ? $_GET['miles'] : (isset($params['miles']) ? $params['miles'] : $this->_getParam('miles', '1000')));
    $value['text'] = $text = isset($searchArray['search_text']) ? $searchArray['search_text'] : (!empty($params['search_text']) ? $params['search_text'] : (isset($_GET['search_text']) && ($_GET['search_text'] != '') ? $_GET['search_text'] : ''));
    $this->view->show_criterias = $show_criterias = isset($_POST['show_criterias']) ? json_decode($_POST['show_criterias'], true) : $this->_getParam('show_criteria', array('like', 'title', 'socialSharing', 'view', 'location', 'age', 'friendButton', 'likemainButton', 'message', 'followButton', 'sponsoredLabel', 'featuredLabel', 'verifiedLabel', 'profileType', 'rating'));
    
    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', '2');
    
    $limit = $value['limit'] = isset($searchArray['limit']) ? $searchArray['limit'] : (isset($_GET['limit']) ? $_GET['limit'] : (isset($params['limit']) ? $params['limit'] : $this->_getParam('limit', 10)));
    $value['alphbetorder'] = isset($searchArray['alphabet']) ? $searchArray['alphabet'] : (isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : $this->_getParam('alphabet', '')));
    $value['network'] = isset($searchArray['network']) ? $searchArray['network'] : (isset($_GET['network']) ? $_GET['network'] : (isset($params['network']) ? $params['network'] : $this->_getParam('network', '')));
    
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $defaultOrder = $value['sort'];
    if (!empty($defaultOrder)) {
      $orderKey = str_replace(array('SP', ''), array(' ', ' '), $defaultOrder);
      $defaultOrder = Engine_Api::_()->sesbasic()->getColumnName($orderKey);
    }
    else
      $defaultOrder = 'like_count DESC';
    $value['order'] = $defaultOrder;
    $value['view'] = isset($searchArray['view']) ? $searchArray['view'] : (isset($_GET['view']) ? $_GET['view'] : (isset($params['view']) ? $params['view'] : ''));
    $this->view->page = $page;
    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('members', 'sesmember')
            ->getMemberPaginator(array_merge($value, array('search' => 1)),$formValues);
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($page);
    $this->view->widgetName = 'member-location';
  }

}
