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
class Sesmember_Widget_BrowseMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $searchArray = array();
    // Prepare
    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);

    if (isset($_POST['searchParams']) && $_POST['searchParams'])
      parse_str($_POST['searchParams'], $searchArray);
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
		$this->view->gridblock = $gridblock = isset($params['gridblock']) ? $params['gridblock'] : $this->_getParam('gridblock', '4');
    $is_search = isset($_POST['is_search']) ? true : false;
    $searchParams = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    $form = new Sesmember_Form_Filter_Browse(array('friendType' => 'yes', 'searchType' => 'yes', 'locationSearch' => 'yes', 'kilometerMiles' => 'yes', 'browseBy' => 'yes', 'searchTitle' => 'yes', 'FriendsSearch' => 'yes', 'citySearch' => 'yes', 'stateSearch' => 'yes', 'zipSearch' => 'yes', 'countrySearch' => 'yes', 'alphabetSearch' => 'yes', 'memberType' => 'yes', 'hasPhoto' => 'yes', 'isOnline' => 'yes', 'isVip' => 'yes', 'type' => 'user', 'networkGet' => 'yes'));
    if(!empty($searchArray)){
      $params = array_merge($params,$searchArray);
    }
    if ($is_search)
      $form->populate($searchArray);
    elseif ($is_ajax)
      $form->populate($params);
    else
      $form->populate($searchParams);

    //cookie location work for page load
    if (!$is_ajax) {
      //check location detect
      $identity = Engine_Api::_()->sesbasic()->getIdentityWidget('sesmember.browse-members', 'widget', 'sesmember_index_browse');
      if ($identity) {
        //get cookie data
        $cookiedata = Engine_Api::_()->sesbasic()->getUserLocationBasedCookieData();
        if (!empty($cookiedata['location']) && $cookiedata['location'] != 'undefined') {
          $params['location'] = $cookiedata['location'];
          $params['lat'] = $cookiedata['lat'];
          $params['lng'] = $cookiedata['lng'];
          $params['miles'] = 1000;
        }
      }
    }

    $page = $this->_getParam('page', 1);
    $this->view->show_item_count = $show_item_count = isset($params['show_item_count']) ? $params['show_item_count'] : $this->_getParam('show_item_count', 0);

    $text = isset($searchArray['search_text']) ? $searchArray['search_text'] : (!empty($params['search_text']) ? $params['search_text'] : (isset($_GET['search_text']) && ($_GET['search_text'] != '') ? $_GET['search_text'] : ''));
    $limit_data = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', '10');

    //List View
    $this->view->socialshare_enable_plusiconlistview = $socialshare_enable_plusiconlistview = isset($params['socialshare_enable_plusiconlistview']) ? $params['socialshare_enable_plusiconlistview'] : $this->_getParam('socialshare_enable_plusiconlistview', 1);
    $this->view->socialshare_icon_limitlistview = $socialshare_icon_limitlistview = isset($params['socialshare_icon_limitlistview']) ? $params['socialshare_icon_limitlistview'] : $this->_getParam('socialshare_icon_limitlistview', '2');

    //Advanced List View
    $this->view->socialshare_enable_plusiconadvlistview = $socialshare_enable_plusiconadvlistview = isset($params['socialshare_enable_plusiconadvlistview']) ? $params['socialshare_enable_plusiconadvlistview'] : $this->_getParam('socialshare_enable_plusiconadvlistview', 1);
    $this->view->socialshare_icon_limitadvlistview = $socialshare_icon_limitadvlistview = isset($params['socialshare_icon_limitadvlistview']) ? $params['socialshare_icon_limitadvlistview'] : $this->_getParam('socialshare_icon_limitadvlistview', '2');

    //Grid View
    $this->view->socialshare_enable_plusicongridview = $socialshare_enable_plusicongridview = isset($params['socialshare_enable_plusicongridview']) ? $params['socialshare_enable_plusicongridview'] : $this->_getParam('socialshare_enable_plusicongridview', 1);
    $this->view->socialshare_icon_limitgridview = $socialshare_icon_limitgridview = isset($params['socialshare_icon_limitgridview']) ? $params['socialshare_icon_limitgridview'] : $this->_getParam('socialshare_icon_limitgridview', '2');


    //Advanced Grid View
    $this->view->socialshare_enable_plusiconadvgridview = $socialshare_enable_plusiconadvgridview = isset($params['socialshare_enable_plusiconadvgridview']) ? $params['socialshare_enable_plusiconadvgridview'] : $this->_getParam('socialshare_enable_plusiconadvgridview', 1);
    $this->view->socialshare_icon_limitadvgridview = $socialshare_icon_limitadvgridview = isset($params['socialshare_icon_limitadvgridview']) ? $params['socialshare_icon_limitadvgridview'] : $this->_getParam('socialshare_icon_limitadvgridview', '2');

    //Pinboard View
    $this->view->socialshare_enable_plusiconpinview = $socialshare_enable_plusiconpinview = isset($params['socialshare_enable_plusiconpinview']) ? $params['socialshare_enable_plusiconpinview'] : $this->_getParam('socialshare_enable_plusiconpinview', 1);
    $this->view->socialshare_icon_limitpinview = $socialshare_icon_limitpinview = isset($params['socialshare_icon_limitpinview']) ? $params['socialshare_icon_limitpinview'] : $this->_getParam('socialshare_icon_limitpinview', '2');

    //Map View
    $this->view->socialshare_enable_plusiconmapview = $socialshare_enable_plusiconmapview = isset($params['socialshare_enable_plusiconmapview']) ? $params['socialshare_enable_plusiconmapview'] : $this->_getParam('socialshare_enable_plusiconmapview', 1);
    $this->view->socialshare_icon_limitmapview = $socialshare_icon_limitmapview = isset($params['socialshare_icon_limitmapview']) ? $params['socialshare_icon_limitmapview'] : $this->_getParam('socialshare_icon_limitmapview', '2');


    $this->view->profileFieldCount = $profileFieldCount = isset($params['profileFieldCount']) ? $params['profileFieldCount'] : $this->_getParam('profileFieldCount', '5');
    $this->view->list_title_truncation = $list_title_truncation = isset($params['list_title_truncation']) ? $params['list_title_truncation'] : $this->_getParam('list_title_truncation', '100');
    $this->view->grid_title_truncation = $grid_title_truncation = isset($params['grid_title_truncation']) ? $params['grid_title_truncation'] : $this->_getParam('grid_title_truncation', '100');
    $this->view->pinboard_title_truncation = $pinboard_title_truncation = isset($params['pinboard_title_truncation']) ? $params['pinboard_title_truncation'] : $this->_getParam('pinboard_title_truncation', '100');
    $this->view->advgrid_title_truncation = $advgrid_title_truncation = isset($params['advgrid_title_truncation']) ? $params['advgrid_title_truncation'] : $this->_getParam('advgrid_title_truncation', '100');
    $this->view->advgrid_height = $advgrid_height = isset($params['advgrid_height']) ? $params['advgrid_height'] : $this->_getParam('advgrid_height', '222');
    $this->view->advgrid_width = $advgrid_width = isset($params['advgrid_width']) ? $params['advgrid_width'] : $this->_getParam('advgrid_width', '322');

    $this->view->Overlay_color_grid = $Overlay_color_grid = isset($params['Overlay_color_grid']) ? $params['Overlay_color_grid'] : $this->_getParam('Overlay_color_grid', 'white');
    $this->view->enable_cover_photo_adv_grid = $enable_cover_photo_adv_grid = isset($params['enable_cover_photo_adv_grid']) ? $params['enable_cover_photo_adv_grid'] : $this->_getParam('enable_cover_photo_adv_grid', 'white');
    //search data
    $defaultOrderOrg = $this->_getParam('order');
    if (!empty($defaultOrderOrg)) {
      $orderKey = str_replace(array('SP', ''), array(' ', ' '), $defaultOrderOrg);
      $defaultOrder = Engine_Api::_()->sesbasic()->getColumnName($orderKey);
    }
    else
      $defaultOrder = 'like_count DESC';

    $value['order'] = isset($searchArray['order']) ? $searchArray['order'] : (isset($_GET['order']) ? $_GET['order'] : (isset($params['order']) ? $params['order'] : $defaultOrder));

    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'follow', 'rating', 'title', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'likeButton', 'socialSharing', 'view', 'friendCount', 'profileType', 'age', 'email', 'message', 'verifiedLabel', 'likeButton', 'mutualFriendCount', 'friendButton','viewProfileButton', 'likemainButton', 'viewDetailsLink'));
    $value['location'] = isset($searchArray['location']) ? $searchArray['location'] : (isset($_GET['location']) ? $_GET['location'] : (isset($params['location']) ? $params['location'] : ''));
    $value['show'] = isset($searchArray['show']) ? $searchArray['show'] : (isset($_GET['show']) ? $_GET['show'] : (isset($params['show']) ? $params['show'] : ''));
    $value['miles'] = isset($searchArray['miles']) ? $searchArray['miles'] : (isset($_GET['miles']) ? $_GET['miles'] : (isset($params['miles']) ? $params['miles'] : ''));
    $value['view'] = isset($searchArray['view']) ? $searchArray['view'] : (isset($_GET['view']) ? $_GET['view'] : (isset($params['view']) ? $params['view'] : ''));
    $value['country'] = isset($searchArray['country']) ? $searchArray['country'] : (isset($_GET['country']) ? $_GET['country'] : (isset($params['country']) ? $params['country'] : ''));
    $value['state'] = isset($searchArray['state']) ? $searchArray['state'] : (isset($_GET['state']) ? $_GET['state'] : (isset($params['state']) ? $params['state'] : ''));
    $value['city'] = isset($searchArray['city']) ? $searchArray['city'] : (isset($_GET['city']) ? $_GET['city'] : (isset($params['city']) ? $params['city'] : ''));
    $value['zip'] = isset($searchArray['zip']) ? $searchArray['zip'] : (isset($_GET['zip']) ? $_GET['zip'] : (isset($params['zip']) ? $params['zip'] : ''));

    $value['network'] = isset($searchArray['network']) ? $searchArray['network'] : (isset($_GET['network']) ? $_GET['network'] : (isset($params['network']) ? $params['network'] : ''));

    $sesmember_browsemembers = Zend_Registry::isRegistered('sesmember_browsemembers') ? Zend_Registry::get('sesmember_browsemembers') : null;
    if (empty($sesmember_browsemembers))
      return $this->setNoRender();
    $value['alphabet'] = isset($searchArray['alphabet']) ? $searchArray['alphabet'] : (isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : ''));

    $this->view->filter = $filter = $value['filter'] = isset($searchArray['filter']) ? $searchArray['filter'] : (isset($_GET['filter']) ? $_GET['filter'] : (isset($params['filter']) ? $params['filter'] : Zend_Controller_Front::getInstance()->getRequest()->getParam('filter', null)));

    $this->view->identityForWidget = isset($_POST['identity']) ? $_POST['identity'] : '';
    $this->view->loadOptionData = $loadOptionData = isset($params['pagging']) ? $params['pagging'] : $this->_getParam('pagging', 'auto_load');

    // check to see if request is for specific user's listings
    if (($user_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('user'))) {
      $values['user_id'] = $user_id;
    }
    if(is_array($show_criterias)){
       foreach ($show_criterias as $show_criteria)
         $this->view->{$show_criteria . 'Active'} = $show_criteria;
     }
    if (!$is_ajax) {
      $this->view->optionsEnable = $optionsEnable = $this->_getParam('enableTabs', array('list', 'advlist', 'advgrid', 'grid', 'pinboard', 'map'));
      if (!engine_count($optionsEnable) || empty($optionsEnable))
        $this->setNoRender();
      $view_type = $this->_getParam('openViewType', 'list');
      if (!engine_in_array($view_type, $optionsEnable)) {
        $view_type = $optionsEnable[0];
      }
      if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1) && $view_type == 'map') {
        $view_type = $optionsEnable[0];
      }
      if (!empty($optionsEnable)) {
        $this->view->bothViewEnable = true;
      }
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

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $getModuleName = $request->getModuleName();
    $getControllerName = $request->getControllerName();
    $getActionName = $request->getActionName();
    if($getActionName == 'editormembers') {
      $value['actioname'] = 'editormembers';
    }
    //Blog Work
    $blog_contributors = '';
    if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog')) {

      if ($getModuleName == 'sesblog' && $getControllerName == 'index' && $getActionName == 'contributors') {
        $value['blog_contributors'] = $blog_contributors = 'blog-contributors';
      } elseif(isset($params['blog_contributors'])) {
        $blog_contributors = $value['blog_contributors'] = isset($params['blog_contributors']) ? $params['blog_contributors'] : 'blog_contributors';
      }
    }

    //Browse Memebers based on Profile Type Page Work
    $value['homepage_id'] = $homepage_id = isset($params['homepage_id']) ? $params['homepage_id'] : Zend_Controller_Front::getInstance()->getRequest()->getParam('homepage_id', 0);

    $value['profile_type'] = $profile_type = 0;

    if($homepage_id) {
      $homepagesTable = Engine_Api::_()->getDbTable('homepages', 'sesmember');
      $select = $homepagesTable->select()->where('type = ?', 'browse')->where('homepage_id =?', $homepage_id);
      $homePages = $homepagesTable->fetchRow($select);
      if($homePages) {
        $member_levels = json_decode($homePages->member_levels);
        if($member_levels) {
          $value['profile_type'] = $profile_type = isset($params['profile_type']) ? $params['profile_type'] : $member_levels[0];
        }
      }
    }
    //Browse Memebers based on Profile Type Page Work


    //member level exclude
    $this->view->memberlevels = $value['memberlevels'] = $memberlevels = isset($params['memberlevels']) ? $params['memberlevels'] : $this->_getParam('memberlevels', array());
    //member level exclude

    $params = array('gridblock' => $gridblock, 'pagging' => $loadOptionData, 'limit_data' => $limit_data, 'profileFieldCount' => $profileFieldCount, 'list_title_truncation' => $list_title_truncation, 'grid_title_truncation' => $grid_title_truncation, 'pinboard_title_truncation' => $pinboard_title_truncation, 'show_criterias' => $show_criterias, 'view_type' => $view_type, 'height' => $defaultHeight, 'list_container_height' => $listContainerHeight, 'list_container_width' => $listContainerWidth, 'photo_height' => $defaultPhotoHeight, 'photo_width' => $defaultPhotoWidth, 'info_height' => $defaultInfoHeight, 'pinboard_width' => $defaultPinboardWidth, 'order' => $value['order'], 'location' => $value['location'], 'lat' => $value['lat'], 'lng' => $value['lng'], 'miles' => $value['miles'], 'width' => $defaultWidth, 'country' => $value['country'], 'state' => $value['state'], 'city' => $value['city'], 'zip' => $value['zip'], 'advgrid_title_truncation' => $advgrid_title_truncation, 'advgrid_height' => $advgrid_height, 'advgrid_width' => $advgrid_width, 'show_item_count' => $show_item_count, 'alphabet' => $value['alphabet'], 'network' => $value['network'], 'blog_contributors' => $blog_contributors, 'socialshare_enable_plusiconlistview' => $socialshare_enable_plusiconlistview, 'socialshare_icon_limitlistview' => $socialshare_icon_limitlistview, 'socialshare_enable_plusiconadvlistview' => $socialshare_enable_plusiconadvlistview, 'socialshare_icon_limitadvlistview' => $socialshare_icon_limitadvlistview, 'socialshare_enable_plusicongridview' => $socialshare_enable_plusicongridview, 'socialshare_icon_limitgridview' => $socialshare_icon_limitgridview, 'socialshare_enable_plusiconadvgridview' => $socialshare_enable_plusiconadvgridview, 'socialshare_icon_limitadvgridview' => $socialshare_icon_limitadvgridview, 'socialshare_enable_plusiconpinview' => $socialshare_enable_plusiconpinview, 'socialshare_icon_limitpinview' => $socialshare_icon_limitpinview, 'socialshare_enable_plusiconmapview' => $socialshare_enable_plusiconmapview, 'socialshare_icon_limitmapview' => $socialshare_icon_limitmapview, 'profile_type' => $profile_type, 'homepage_id' => $homepage_id, 'memberlevels' => $memberlevels, 'gridblock' => $gridblock);

    $value['alphabet'] = isset($searchArray['alphabet']) ? $searchArray['alphabet'] : (isset($_GET['alphabet']) ? $_GET['alphabet'] : (isset($params['alphabet']) ? $params['alphabet'] : ''));
    $this->view->text = $value['text'] = $text;
    $this->view->widgetName = 'browse-members';
    $this->view->page = $page;

    $this->view->params = array_merge($params, $form->getValues(), $value);
    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');
    // Get paginator

    $this->view->paginator = $paginator = Engine_Api::_()->getDbTable('members', 'sesmember')
            ->getMemberPaginator(array_merge($value, array('search' => 1,'fromBrowse'=>1)), $form->getValues());
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);

    $advancedSettingBtn = $this->_getParam('show_advanced_search', '1');
    if (!$advancedSettingBtn) {
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $formFilter->removeElement("advanced_options_search_" . $view->identity);
    }
  }
}
