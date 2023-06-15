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
class Sescontest_Widget_ViewerFriendsParticipatingController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    if (!Engine_Api::_()->core()->hasSubject())
      return $this->setNoRender();
    $subject = Engine_Api::_()->core()->getSubject();
    $contestId = $subject->contest_id;
    $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);

    $show_criterias = $params['criteria'];
    if (is_array($show_criterias)) {
      foreach ($show_criterias as $show_criteria)
        $this->view->{$show_criteria . 'Active'} = $show_criteria;
    }

    $limit_data = isset($params['limit_data']) ? $params['limit_data'] : 5;
    $this->view->listingType = $this->_getParam('listing_type', 'list');
    $users = $viewer->membership()->getMembershipsOfIds();
    if (engine_count($users) < 1)
      return $this->setNoRender();
    $participateTable = Engine_Api::_()->getDbTable('participants', 'sescontest');
    $participantTableName = $participateTable->info('name');
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');
    $select = $userTable->select()
            ->setIntegrityCheck(false)
            ->from($userTableName, array('user_id', 'displayname', 'photo_id'))
            ->join($participantTableName, $userTableName . '.user_id=' . $participantTableName . '.owner_id', null)
            ->where($participantTableName . '.contest_id =?', $contestId)
            ->where($userTableName . '.user_id IN (?)', $users);
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $page = $this->_getParam('page', 1);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    if ($paginator->getTotalItemCount() < 1)
      return $this->setNoRender();
  }

}
