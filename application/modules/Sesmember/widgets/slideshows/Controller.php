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
class Sesmember_Widget_SlideshowsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->socialshare_enable_plusicon = $this->_getParam('socialshare_enable_plusicon', 1);
    $this->view->socialshare_icon_limit = $this->_getParam('socialshare_icon_limit', 2);
    
    $this->view->height = $this->_getParam('height', '180');
    $this->view->slideheight = $this->_getParam('slideheight', '180');
    $this->view->width = $this->_getParam('width', '180');
    $this->view->title_truncation_list = $this->_getParam('list_title_truncation', '45');
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_id = $viewer->getIdentity();
    $show_criterias = isset($value['show_criterias']) ? $value['show_criterias'] : $this->_getParam('show_criteria', array('like', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'verifiedLabel', 'likeButton', 'friendButton', 'likemainButton', 'message', 'followButton', 'rating', 'friendCount', 'profileType', 'mutualFriendCount', 'email', 'location', 'age', 'profileButton'));
    $this->view->profileFieldCount = $this->_getParam('profileFieldCount', '5');
    $sesmember_tabbed = Zend_Registry::isRegistered('sesmember_tabbed') ? Zend_Registry::get('sesmember_tabbed') : null;
    if (empty($sesmember_tabbed))
      return $this->setNoRender();
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $value['limit'] = $this->_getParam('limit_data', 5);
    $value['criteria'] = $this->_getParam('criteria', 5);
    $value['info'] = $this->_getParam('info', 'recently_created');
    $value['order'] = $this->_getParam('order', '');
    $value['fetchAll'] = true;
    $this->view->paginator = Engine_Api::_()->getDbTable('members', 'sesmember')->getMemberSelect($value, array());
    if (is_countable($this->view->paginator) && engine_count($this->view->paginator) <= 0)
      return $this->setNoRender();
  }

}
