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
class Sescontest_Widget_TopMembersController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->widgetId = $widgetId = (isset($_POST['widget_id']) ? $_POST['widget_id'] : $this->view->identity);
    $this->view->params = $params = Engine_Api::_()->sescontest()->getWidgetParams($widgetId);
    $this->view->loadJs = true;
    $options = array('tabbed' => true, 'paggindData' => true);
    $this->view->optionsListGrid = $options;
    $this->view->is_ajax = $is_ajax = isset($_POST['is_ajax']) ? true : false;

    $defaultOpenTab = array();
    $defaultOptions = $arrayOptions = array();
    $defaultOptionsArray = $params['criteria'];
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
          if ($defaultValue == 'topParticipant')
            $valueLabel = 'Top Participant';
          else if ($defaultValue == 'topCreator')
            $valueLabel = 'Top Contest Creators';
          else if ($defaultValue == 'topVoter')
            $valueLabel = 'Top Voters';
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

    $this->view->defaultOptions = $defaultOptions;
    $this->view->openTab = $contentType = $this->_getParam('openTab', null) ? $this->_getParam('openTab', null) : current(array_keys($defaultOptions));
    $show_criterias = $params['show_criteria'];
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria . 'Active'} = $show_criteria;
    $limit_data = isset($params['limit_data']) ? $params['limit_data'] : 3;

    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');
    $participateTable = Engine_Api::_()->getDbTable('participants', 'sescontest');
    $participantTableName = $participateTable->info('name');
    $voteTable = Engine_Api::_()->getDbTable('votes', 'sescontest');
    $voteTableName = $voteTable->info('name');
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');

    if ($contentType == 'topParticipant') {
      $subContest = $contestTable->select()->from($contestTableName, array("total_contest" => new Zend_Db_Expr("COUNT($contestTableName.user_id)"), "user_id"))->group($contestTableName . ".user_id");

      $subVote = $voteTable->select()->from($voteTableName, array("total_vote" => new Zend_Db_Expr("COUNT($voteTableName.owner_id)"), "owner_id"))->group($voteTableName . ".owner_id");

      $select = $userTable->select()
              ->setIntegrityCheck(false)
              ->from($participantTableName, null);
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesusercoverphoto')) {
        $select->join($userTableName, $userTableName . '.user_id=' . $participantTableName . '.owner_id', array('user_id', 'displayname', 'photo_id', 'coverphoto', "COUNT($participantTableName.owner_id) as total_count"));
      } else {
        $select->join($userTableName, $userTableName . '.user_id=' . $participantTableName . '.owner_id', array('user_id', 'displayname', 'photo_id', "COUNT($participantTableName.owner_id) as total_count"));
      }
      $select->joinLeft(array('cn' => new Zend_Db_Expr('(' . $subContest . ')')), $userTableName . '.user_id= cn.user_id', array(("total_contest")))
              ->joinLeft(array('cn1' => new Zend_Db_Expr('(' . $subVote . ')')), $userTableName . '.user_id= cn1.owner_id', array(("total_vote")))
              ->order("COUNT($participantTableName.owner_id) DESC")
              ->group($participantTableName . '.owner_id');
    } elseif ($contentType == 'topCreator') {
      $subContest = $participateTable->select()->from($participantTableName, array("total_count" => new Zend_Db_Expr("COUNT($participantTableName.owner_id)"), "owner_id"))->group($participantTableName . ".owner_id");

      $subVote = $voteTable->select()->from($voteTableName, array("total_vote" => new Zend_Db_Expr("COUNT($voteTableName.owner_id)"), "owner_id"))->group($voteTableName . ".owner_id");

      $select = $userTable->select()
              ->setIntegrityCheck(false)
              ->from($contestTableName, null);
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesusercoverphoto')) {
        $select->join($userTableName, $userTableName . '.user_id=' . $contestTableName . '.user_id', array('user_id', 'displayname', 'photo_id', 'coverphoto', "COUNT($contestTableName.user_id) as total_contest"));
      } else {
        $select->join($userTableName, $userTableName . '.user_id=' . $contestTableName . '.user_id', array('user_id', 'displayname', 'photo_id', "COUNT($contestTableName.user_id) as total_contest"));
      }
      $select->joinLeft(array('cn' => new Zend_Db_Expr('(' . $subContest . ')')), $userTableName . '.user_id= cn.owner_id', array(("total_count")))
              ->joinLeft(array('cn1' => new Zend_Db_Expr('(' . $subVote . ')')), $userTableName . '.user_id= cn1.owner_id', array(("total_vote")))
              ->order("COUNT($contestTableName.user_id) DESC")
              ->group($contestTableName . '.user_id');
    } elseif ($contentType == 'topVoter') {
      $subContest = $participateTable->select()->from($participantTableName, array("total_count" => new Zend_Db_Expr("COUNT($participantTableName.owner_id)"), "owner_id"))->group($participantTableName . ".owner_id");

      $subVote = $contestTable->select()->from($contestTableName, array("total_contest" => new Zend_Db_Expr("COUNT($contestTableName.user_id)"), "user_id"))->group($contestTableName . ".user_id");

      $select = $userTable->select()
              ->setIntegrityCheck(false)
              ->from($voteTableName, null);
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesusercoverphoto')) {
        $select->join($userTableName, $userTableName . '.user_id=' . $voteTableName . '.owner_id', array('user_id', 'displayname', 'photo_id', 'coverphoto', "COUNT($voteTableName.owner_id) as total_vote"));
      } else {
        $select->join($userTableName, $userTableName . '.user_id=' . $voteTableName . '.owner_id', array('user_id', 'displayname', 'photo_id', "COUNT($voteTableName.owner_id) as total_vote"));
      }
      $select->joinLeft(array('cn' => new Zend_Db_Expr('(' . $subContest . ')')), $userTableName . '.user_id= cn.owner_id', array(("total_count")))
              ->joinLeft(array('cn1' => new Zend_Db_Expr('(' . $subVote . ')')), $userTableName . '.user_id= cn1.user_id', array(("total_contest")))
              ->order("COUNT($voteTableName.owner_id) DESC")
              ->group($voteTableName . '.owner_id');
    }

    $this->view->widgetName = 'top-members';
    $this->view->viewmore = isset($_POST['viewmore']) ? $_POST['viewmore'] : '';
    $this->view->page = $page = $this->_getParam('page', 1);
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($limit_data);
    $paginator->setCurrentPageNumber($page);
    if ($is_ajax) {
      $this->getElement()->removeDecorator('Container');
    }
  }

}
