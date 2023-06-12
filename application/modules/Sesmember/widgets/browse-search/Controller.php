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
class Sesmember_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    //Auto Detection location work
    $this->view->cookiedata = $cookiedata = Engine_Api::_()->sesbasic()->getUserLocationBasedCookieData();

    $p = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    $nearest = false;
    // if ($p['action'] == 'nearest-member' && $p['module'] == 'sesmember')
    //   $nearest = true;
    // Create form
    $default_search_type = $this->_getParam('default_search_type', 'like_count DESC');
    $search_type = $this->_getParam('search_type', array('recentlySPcreated' => 'Recently Signuped', 'mostSPviewed' => 'Most Viewed', 'mostSPliked' => 'Most Liked', 'mylike' => 'Members I Liked', 'myfollow' => 'Members I Followed', 'featured' => 'Featured', 'sponsored' => 'Sponsored', 'verified' => 'Verified', 'mostSPrated' => 'Most Rated', 'atoz' => 'A to Z', 'ztoa' => 'Z to A'));
    if (engine_count($search_type))
      $browseBy = $this->_getParam('browse_by', 'yes');
    else
      $browseBy = 'no';

    $arrayView = array('0' => 'All\'s Users', '1' => 'My Friend\'s', 'week' => 'This Week', 'month' => 'This Month', '3' => 'Only My Network\'s');
    $defaultView = array('0', '1', '3', 'week', 'month');
    $friend_type = $this->_getParam('view', $defaultView);
   // if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0)
    //unset($friend_type['1']);
		if (engine_count($friend_type))
		$friendOnly = $this->_getParam('friend_show', 'yes');
		else
		$friendOnly = 'no';

    $this->view->view_type = $this->_getParam('view_type', 'horizontal');
    if ($this->_getParam('location', 'yes') != 'no' && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1))
      $location = $this->_getParam('location', 'yes');
    else
      $location = 'no';
    if ($nearest)
      $location = 'no';

    //Profile Type exclude Work
    $profileTypes = $this->_getParam('profiletypes', array());
    $this->view->defaultprofiletypes = $defaultprofiletypes = $this->_getParam('defaultprofiletypes', null);


    $this->view->form = $formFilter = new Sesmember_Form_Filter_Browse(array('friendType' => $friend_type, 'searchType' => $search_type, 'locationSearch' => $location, 'kilometerMiles' => $this->_getParam('kilometer_miles', 'yes'), 'browseBy' => $browseBy, 'searchTitle' => $this->_getParam('search_title'), 'friendsSearch' => $friendOnly, 'citySearch' => $this->_getParam('city', 'yes'), 'stateSearch' => $this->_getParam('state', 'yes'), 'zipSearch' => $this->_getParam('zip', 'yes'), 'countrySearch' => $this->_getParam('country', 'yes'), 'alphabetSearch' => $this->_getParam('alphabet', 'yes'), 'memberType' => $this->_getParam('member_type', 'yes'), 'hasPhoto' => $this->_getParam('has_photo', 'yes'), 'isOnline' => $this->_getParam('is_online', 'yes'), 'isVip' => $this->_getParam('is_vip', 'yes'), 'type' => 'user', 'networkGet' => $this->_getParam('network', 'yes'), 'nearestLocation' => $nearest, 'profileTypes' => $profileTypes, 'defaultProfiletypes' => $defaultprofiletypes, 'isInterest' => $this->_getParam('showinterest', 'yes'), 'ajaxUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'fields'),'user_general'), 'enableAjaxLoad' => false));

    $urlParams = array();
    foreach (Zend_Controller_Front::getInstance()->getRequest()->getParams() as $urlParamsKey => $urlParamsVal) {
      if ($urlParamsKey == 'module' || $urlParamsKey == 'controller' || $urlParamsKey == 'action' || $urlParamsKey == 'rewrite')
        continue;
      $urlParams[$urlParamsKey] = $urlParamsVal;
    }

    $formFilter->populate($urlParams);

    if ($p['action'] == 'nearest-member' && $p['module'] == 'sesmember') {
      $viewer = Engine_Api::_()->user()->getViewer();
      $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($viewer->getIdentity());
      $getLoggedInuserLocation = Engine_Api::_()->getDbTable('locations', 'sesbasic')->getLocationData($viewer->getType(), $viewer->getIdentity());
      if ($getLoggedInuserLocation) {
        $formFilter->location->setValue($userInfoItem->location);
        $formFilter->lat->setValue($getLoggedInuserLocation->lat);
        $formFilter->lng->setValue($getLoggedInuserLocation->lng);
        $formFilter->miles->setValue(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember_nearest_distance', 1000));
      }
    }

    if (!engine_count($friend_type))
      $formFilter->removeElement('view');
    else if ($formFilter->view) {
      $viewArray = array();
      foreach ($friend_type as $val) {
        $viewArray[$val] = $arrayView[$val];
      }

      if (array_key_exists('3', $arrayView)) {
        $userjoinednetwork = Engine_Api::_()->getDbtable('membership', 'network')->fetchRow(array('user_id = ?' => Engine_Api::_()->user()->getViewer()->getIdentity()));
        if (!$userjoinednetwork)
          unset($viewArray['3']);
      }else if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0)
        unset($viewArray['3']);

      if (engine_count($viewArray))
        $formFilter->view->setMultiOptions($viewArray);
    }
    if (isset($_GET['order'])) {
      if ($formFilter->order)
        $formFilter->order->setValue($_GET['order']);
    }else {
      if ($formFilter->order)
        $formFilter->order->setValue($default_search_type);
    }
    $advancedSettingBtn = $this->_getParam('show_advanced_search', '1');
    if (!$advancedSettingBtn) {
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $formFilter->removeElement("advanced_options_search_" . $view->identity);
    }
  }

}
