<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sespymk
 * @package    Sespymk
 * @copyright  Copyright 2016-2017 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php 2017-03-03 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sespymk_Widget_SuggestionPageController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_level_id = $viewer->level_id;
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();


    $this->view->onlyphotousers = $onlyphotousers = isset($params['onlyphotousers']) ? $params['onlyphotousers'] : $this->_getParam('onlyphotousers', 0);

    $this->view->viewmore = $this->_getParam('viewmore', 0);
    if ($this->view->viewmore)
      $this->getElement()->removeDecorator('Container');

    $this->view->memberEnable = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember');

    //People You May Know work
    $userIDS = $viewer->membership()->getMembershipsOfIds();
    $userMembershipTable = Engine_Api::_()->getDbtable('membership', 'user');
    $userMembershipTableName = $userMembershipTable->info('name');
    $select_membership = $userMembershipTable->select()
            ->where('resource_id = ?', $viewer->getIdentity());
    $member_results = $userMembershipTable->fetchAll($select_membership);
    foreach($member_results as $member_result) {
      $membershipIDS[] = $member_result->user_id;
    }

    $userTable = Engine_Api::_()->getDbtable('users', 'user');
    $userTableName = $userTable->info('name');
    $select = $userTable->select()
                        ->from($userTableName)
                        ->where($userTableName.'.user_id <> ?', $viewer->getIdentity());
    if($onlyphotousers)
      $select->where($userTableName.'.photo_id <> ?', 0);
    if($membershipIDS) {
      $select->where($userTableName.'.user_id NOT IN (?)', $membershipIDS);
    }

    //Interests plugin integration
    $interestbased = $this->_getParam('interestbased', 0);
    if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesinterest') && !empty($interestbased)) {
        $getUserInterests = Engine_Api::_()->getDbTable('userinterests', 'sesinterest')->getUserInterests(array('user_id' => $viewer->getIdentity()));
        $userInterestsArray = array();
        foreach($getUserInterests as $getUserInterest) {
            $userInterestsArray[] = $getUserInterest->interest_id;
        }
        $select->setIntegrityCheck(false);
        $userinterestsTableName = Engine_Api::_()->getDbTable('userinterests', 'sesinterest')->info('name');
        $select->joinLeft($userinterestsTableName, $userinterestsTableName . '.user_id = ' . $userTableName . '.user_id ', null)->where($userinterestsTableName.'.interest_id IN (?)', $userInterestsArray)->group($userinterestsTableName.'.user_id');
    }

    $this->view->paginationType = $paginationType = isset($params['paginationType']) ? $params['paginationType'] : $this->_getParam('paginationType', 1);

    $this->view->height = $height = isset($params['height']) ? $params['height'] : $this->_getParam('height', 230);
    $this->view->horiwidth = $horiwidth = isset($params['horiwidth']) ? $params['horiwidth'] : $this->_getParam('horiwidth', 150);
		$this->view->horiheight = $horiheight = isset($params['horiheight']) ? $params['horiheight'] : $this->_getParam('horiheight', 150);
    
    $this->view->showType = $showType = isset($params['showType']) ? $params['showType'] : $this->_getParam('showType', 1);
    $this->view->showdetails = $showdetails = isset($params['showdetails']) ? $params['showdetails'] : $this->_getParam('showdetails', array('friends', 'mutualfriends'));

    $itemCount = isset($params['itemCount']) ? $params['itemCount'] : $this->_getParam('itemCount', 5);

    $this->view->all_params = array('height' => $height, 'horiwidth' => $horiwidth,'horiheight' => $horiheight, 'onlyphotousers' => $onlyphotousers, 'showType' => $showType, 'showdetails' => $showdetails, 'itemCount' => $itemCount, 'paginationType' => $paginationType);
    $select->where($userTableName.'.user_id NOT IN (SELECT user_id FROM engine4_sespymk_ignores WHERE owner_id = '.$viewer_id.')');

    //$select->order('rand()');
    $this->view->peopleyoumayknow = $peopleyoumayknow = Zend_Paginator::factory($select);
    $peopleyoumayknow->setItemCountPerPage($itemCount);
    $peopleyoumayknow->setCurrentPageNumber($this->_getParam('page'));

    //People You may know work
//      if ($peopleyoumayknow->getTotalItemCount() == 0)
//        return $this->setNoRender();
  }

}
