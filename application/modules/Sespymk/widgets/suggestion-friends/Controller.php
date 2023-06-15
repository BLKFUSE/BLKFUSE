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

class Sespymk_Widget_SuggestionFriendsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->viewer_level_id = $viewer->level_id;
    $this->view->viewer_id = $viewer_id = $viewer->getIdentity();

    $this->view->horiwidth = $this->_getParam('horiwidth', '150');
		$this->view->horiheight = $this->_getParam('horiheight', '150');
    $itemCount = $this->_getParam('itemCount', 5);
    $this->view->showType = $this->_getParam('showType', 1);
    $this->view->showdetails = $this->_getParam('showdetails', array('friends', 'mutualfriends'));
    $onlyphotousers = $this->_getParam('onlyphotousers', 0);

    $this->view->memberEnable = Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmember');

    //People You May Know work
    $userIDS = $viewer->membership()->getMembershipsOfIds();
    $userMembershipTable = Engine_Api::_()->getDbtable('membership', 'user');
    $userMembershipTableName = $userMembershipTable->info('name');
    $select_membership = $userMembershipTable->select()
            ->where('resource_id = ?', $viewer->getIdentity());
    $member_results = $userMembershipTable->fetchAll($select_membership);
    $membershipIDS = array();
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
    $select->where($userTableName.'.user_id NOT IN (SELECT user_id FROM engine4_sespymk_ignores WHERE owner_id = '.$viewer_id.')');

    $select->order($userTableName.'.user_id DESC');
    $this->view->peopleyoumayknow = $peopleyoumayknow = Zend_Paginator::factory($select);
    $peopleyoumayknow->setItemCountPerPage($itemCount);
    $peopleyoumayknow->setCurrentPageNumber($this->_getParam('page'));

    //People You may know work
     if ($peopleyoumayknow->getTotalItemCount() == 0)
       return $this->setNoRender();
  }

}
