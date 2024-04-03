<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: VoteController.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_VoteController extends Core_Controller_Action_Standard {

  public function voteallAction() {
    $value = $this->_getParam('values', 0);
    $viewer = $this->view->viewer()->getIdentity();
    $is_ajax = $this->_getParam('is_ajax', 0);
    $values = explode(' ', $value);
    if (!$value || !$is_ajax || !engine_count($values))
      return;
    try {
      foreach ($values as $item) {
        $participant = Engine_Api::_()->getItem('participant', $item);
        if (!$participant)
          continue;
        $contest_id = $participant->contest_id;
        $this->voting($contest_id, $participant->getIdentity(), true);
      }
    } catch (Exception $e) {
      echo "0";
      die;
    }
    echo "1";
    die;
  }

  public function voteAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    if (!Engine_Api::_()->authorization()->getPermission(5, 'participant', 'allow_entry_vote') && !$viewerId) {
      if (!$this->_helper->requireUser->isValid()) {
        echo "";
        die;
      }
    }
    $contest_id = $this->_getparam('contest_id');
    $entry_id = $this->_getparam('id');
    $this->voting($contest_id, $entry_id);
  }

  protected function voting($contest_id, $entry_id, $type = false) {

    $entry = Engine_Api::_()->getItem('participant', $entry_id);
    $owner = $entry->getOwner();
    $viewer = $this->view->viewer();
    $viewerId = $viewer->getIdentity();
    $hasVoted = Engine_Api::_()->getDbTable('votes', 'sescontest')->hasVoted($viewerId, $contest_id, $entry_id);

    if ($hasVoted) {
      echo "";
      die;
    }
    $votingTable = Engine_Api::_()->getDbTable('votes', 'sescontest');

    $db = $votingTable->getAdapter();
    $db->beginTransaction();
    try {
      $vote = $votingTable->createRow();
      $vote->contest_id = $contest_id;
      $vote->participant_id = $entry_id;
      $vote->owner_id = $viewerId;
      $vote->ip_address = $_SERVER["REMOTE_ADDR"];
      $vote->creation_date = date('Y-m-d h:i:s');
      $vote->save();
      $levelId = $viewer->getIdentity() ? $viewer->level_id : Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember')) {
        $isViewerJury = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->isJuryMember(array('user_id' => $viewerId, 'contest_id' => $contest_id));
        if ($isViewerJury) {
          $voteCount = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'juryVoteWeight');
        } else {
          $voteCount = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'votecount_weight');
        }
      } else {
        $voteCount = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'votecount_weight');
      }
      if(!$voteCount)
        $voteCount = 1;
      $vote->jury_vote_count = $voteCount;
      $vote->save();
      Engine_Api::_()->getDbTable('participants', 'sescontest')->update(array('vote_date' => date('Y-m-d h:i:s'), 'vote_count' => new Zend_Db_Expr("vote_count + $voteCount")), array('participant_id= ?' => $entry_id));
      $liked = 0;
      if ($this->_getParam('integration') && $viewerId) {
        $contest = Engine_Api::_()->getItem('contest', $contest_id);
        if ($contest->authorization()->isAllowed($viewer, 'comment')) {
          $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
          $tableMainLike = $tableLike->info('name');
          $select = $tableLike->select()
                  ->from($tableMainLike)
                  ->where('resource_type = ?', 'participant')
                  ->where('poster_id = ?', $viewerId)
                  ->where('poster_type = ?', 'user')
                  ->where('resource_id = ?', $entry_id);
          $result = $tableLike->fetchRow($select);
          if (engine_count($result) == 0) {
            $like = $tableLike->createRow();
            $like->poster_id = $viewerId;
            $like->resource_type = 'participant';
            $like->resource_id = $entry_id;
            $like->poster_type = 'user';
            $like->save();
            Engine_Api::_()->getDbTable('participants', 'sescontest')->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array('participant_id = ?' => $entry_id));
            $liked = 1;
            if ($viewerId && $owner->getType() == 'user' && $owner->getIdentity() != $viewerId) {
              $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
              Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sescontest_like_contest_entry', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $entry->getType(), "object_id = ?" => $entry->getIdentity()));
              Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $entry, 'sescontest_like_contest_entry');
              $result = $activityTable->fetchRow(array('type =?' => 'sescontest_like_contest_entry', "subject_id =?" => $viewerId, "object_type =? " => $entry->getType(), "object_id = ?" => $entry->getIdentity()));
              if (!$result) {
                $action = $activityTable->addActivity($viewer, $entry, 'sescontest_like_contest_entry');
                if ($action)
                  $activityTable->attachActivity($action, $entry);
              }
            }
          }
        }
      }
      //Commit
      $db->commit();
      //Start Voting Activity Feed Work
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewerId) {
        $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
        if ($viewerId) {
          Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'sescontest_vote_contest_entry', "subject_id =?" => $viewerId, "object_type =? " => 'participant', "object_id = ?" => $entry->getIdentity()));
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $entry, 'sescontest_vote_contest_entry');
          $result = $activityTable->fetchRow(array('type =?' => 'sescontest_vote_contest_entry', "subject_id =?" => $viewerId, "object_type =? " => 'participant', "object_id = ?" => $entry->getIdentity()));
          if (!$result) {
            $action = $activityTable->addActivity($viewer, $entry, 'sescontest_vote_contest_entry');
            if ($action)
              $activityTable->attachActivity($action, $entry);
          }
          $senderTitle = $viewer->getTitle();
        }
        else {
          $senderTitle = $this->view->translate('Anonymous User');
        }
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($entry->getOwner(), 'sescontest_vote_contest_entry', array('member_name' => $senderTitle, 'entry_title' => $entry->getTitle(), 'object_link' => $entry->getHref(), 'host' => $_SERVER['HTTP_HOST'], 'queue' => false));
      }
      if (!$type) {
        //End Voting Activity Feed Work
        echo json_encode(array('status' => 'true', 'like_status' => $liked));
        die;
      }
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

}
