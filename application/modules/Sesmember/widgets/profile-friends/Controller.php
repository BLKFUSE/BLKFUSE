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
class Sesmember_Widget_ProfileFriendsController extends Engine_Content_Widget_Abstract {

  protected $_childCount;

  public function indexAction() {

    //General Friend settings
    $this->view->make_list = Engine_Api::_()->getApi('settings', 'core')->user_friends_lists;

		if (isset($_POST['widgetParams']))
		$widgetParams = json_decode($_POST['widgetParams'], true);
		$this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;
		$page = $this->_getParam('page', 1);
		$viewer = Engine_Api::_()->user()->getViewer();

		if (!$is_ajax) {
			// Don't render this if not authorized
			if (!Engine_Api::_()->core()->hasSubject()) {
				return $this->setNoRender();
			}
			// Don't render this if friendships are disabled
			if (!Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible) {
				return $this->setNoRender();
			}
			// Get subject and check auth
			$this->view->subject  = $subject = Engine_Api::_()->core()->getSubject('user');
			if (!$subject->authorization()->isAllowed($viewer, 'view')) {
				return $this->setNoRender();
			}
    }
    else {
      $subject_id = $widgetParams['subject_id'];
      $this->view->subject = $subject = Engine_Api::_()->getItem('user', $subject_id);
    }
    $show_criterias = isset($widgetParams['show_criterias']) ? $widgetParams['show_criterias'] : $this->_getParam('show_criteria', array('like', 'title', 'socialSharing', 'view', 'featuredLabel', 'sponsoredLabel', 'vipLabel', 'verifiedLabel', 'likeButton', 'friendButton', 'likemainButton', 'message', 'followButton', 'follow' , 'rating', 'friendCount', 'profileType', 'mutualFriendCount'));

    $limit_data = isset($widgetParams['limit_data']) ? $widgetParams['limit_data'] : $this->_getParam('limit_data', '10');
    $this->view->loadOptionData = $loadOptionData = isset($widgetParams['pagging']) ? $widgetParams['pagging'] : $this->_getParam('pagging', 'auto_load');
    $this->view->widgetParams = array('limit_data' => $limit_data, 'show_criterias' => $show_criterias, 'subject_id' => $subject->getIdentity(), 'pagging' => $loadOptionData);

    foreach ($show_criterias as $show_criteria)
    $this->view->{$show_criteria . 'Active'} = $show_criteria;

    // Multiple friend mode
    $select = $subject->membership()->getMembersOfSelect();
    $this->view->paginator = $friends = $paginator = Zend_Paginator::factory($select);

    // Set item count per page and current page number
    $paginator->setItemCountPerPage($limit_data);
    $this->view->page = $page;
    $paginator->setCurrentPageNumber($page);

		if ($is_ajax)
		$this->getElement()->removeDecorator('Container');

    // Get stuff
    $ids = array();
    foreach ($friends as $friend) {
      $ids[] = $friend->resource_id;
    }
    $this->view->friendIds = $ids;

    // Get the items
    $friendUsers = array();
    foreach (Engine_Api::_()->getItemTable('user')->find($ids) as $friendUser) {
      $friendUsers[$friendUser->getIdentity()] = $friendUser;
    }
    $this->view->friendUsers = $friendUsers;

    // Get lists if viewing own profile
    if ($viewer->isSelf($subject)) {
      // Get lists
      $listTable = Engine_Api::_()->getItemTable('user_list');
      $this->view->lists = $lists = $listTable->fetchAll($listTable->select()->where('owner_id = ?', $viewer->getIdentity()));

      $listIds = array();
      foreach ($lists as $list) {
        $listIds[] = $list->list_id;
      }

      // Build lists by user
      $listItems = array();
      $listsByUser = array();
      if (!empty($listIds)) {
        $listItemTable = Engine_Api::_()->getItemTable('user_list_item');
        $listItemSelect = $listItemTable->select()
                ->where('list_id IN(?)', $listIds)
                ->where('child_id IN(?)', $ids);
        $listItems = $listItemTable->fetchAll($listItemSelect);
        foreach ($listItems as $listItem) {
          //$list = $lists->getRowMatching('list_id', $listItem->list_id);
          //$listsByUser[$listItem->child_id][] = $list;
          $listsByUser[$listItem->child_id][] = $listItem->list_id;
        }
      }
      $this->view->listItems = $listItems;
      $this->view->listsByUser = $listsByUser;
    }

    // Do not render if nothing to show
    if ($paginator->getTotalItemCount() <= 0) {
      return $this->setNoRender();
    }

    // Add count to title if configured
    if ($paginator->getTotalItemCount() > 0) {
      $this->_childCount = $paginator->getTotalItemCount();
    }
  }

  public function getChildCount() {
    return $this->_childCount;
  }

}
