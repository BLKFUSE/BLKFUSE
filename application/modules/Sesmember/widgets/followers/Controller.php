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
class Sesmember_Widget_FollowersController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active', '1')){
        return $this->setNoRender();
    }
    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $identity = $view->identity;
    $this->view->widgetIdentity = $this->_getParam('content_id', $identity);
    $this->view->is_ajax = $is_ajax = $this->_getParam('is_ajax', 0);
    if ($this->_getParam('showLimitData', 1))
      $this->view->widgetName = 'followers';


    if (!$is_ajax) {
      if (!Engine_Api::_()->core()->hasSubject('user'))
        return $this->setNoRender();
      else
        $this->view->subject = $subject = Engine_Api::_()->core()->getSubject('user');
    }else {
      $this->view->subject = $subject = Engine_Api::_()->getItem('user', $params['subject_id']);
    }
    
    $this->view->socialshare_enable_plusicon = $socialshare_enable_plusicon = isset($params['socialshare_enable_plusicon']) ? $params['socialshare_enable_plusicon'] : $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $socialshare_icon_limit = isset($params['socialshare_icon_limit']) ? $params['socialshare_icon_limit'] : $this->_getParam('socialshare_icon_limit', '2');
    
    $this->view->height = isset($params['height']) ? $params['height'] : $this->_getParam('height', '350');
    $this->view->width = isset($params['width']) ? $params['width'] : $this->_getParam('width', '220');
    $this->view->photo_height = isset($params['photo_height']) ? $params['photo_height'] : $this->_getParam('photo_height', '200');
    $this->view->photo_width = isset($params['photo_width']) ? $params['photo_width'] : $this->_getParam('photo_width', '200');
    $this->view->title_truncation_list = isset($params['list_title_truncation']) ? $params['list_title_truncation'] : $this->_getParam('list_title_truncation', '45');
    $this->view->title_truncation_grid = isset($params['grid_title_truncation']) ? $params['grid_title_truncation'] : $this->_getParam('grid_title_truncation', '45');
    $this->view->view_type = isset($params['viewType']) ? $params['viewType'] : $this->_getParam('viewType', 'list');
    $this->view->image_type = isset($params['imageType']) ? $params['imageType'] : $this->_getParam('imageType', 'square');
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();

    $show_criterias = isset($params['show_criterias']) ? $params['show_criterias'] : $this->_getParam('show_criteria', array('like', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'verifiedLabel', 'likeButton', 'friendButton', 'likemainButton', 'message', 'followButton', 'rating', 'friendCount', 'profileType', 'mutualFriendCount', 'email', 'age'));
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;

    $page = $this->_getParam('page', 1);
    $this->view->results = $paginator = Engine_Api::_()->getDbTable('members', 'sesmember')->followers(array('user_id' => $subject->getIdentity(), 'paginator' => true));
    $limit = isset($params['limit_data']) ? $params['limit_data'] : $this->_getParam('limit_data', 5);

    $this->view->params = array('height' => $this->view->height, 'width' => $this->view->width, 'photo_height' => $this->view->photo_height, 'photo_width' => $this->view->photo_width, 'list_title_truncation' => $this->view->title_truncation_list, 'grid_title_truncation' => $this->view->title_truncation_grid, 'viewType' => $this->view->view_type, 'imageType' => $this->view->image_type, 'show_criterias' => $show_criterias, 'limit_data' => $limit, 'subject_id' => $subject->getIdentity());

    $paginator->setItemCountPerPage($limit);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);
    if ($paginator->getTotalItemCount() <= 0)
      return $this->setNoRender();
    if ($paginator->getTotalItemCount() > 0) {
      $this->_childCount = $paginator->getTotalItemCount();
    }
    if ($is_ajax)
      $this->getElement()->removeDecorator('Container');
  }

  public function getChildCount() {
    return $this->_childCount;
  }

}
