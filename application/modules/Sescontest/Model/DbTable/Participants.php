<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Participants.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Participants extends Engine_Db_Table {

  protected $_rowClass = "Sescontest_Model_Participant";

  public function getParticipantPaginator($params = array()) {
    return Zend_Paginator::factory($this->getParticipantSelect($params));
  }

  
  public function getParticipantSelect($params = array()) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $currentTime = date('Y-m-d H:i:s');
    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');
    $participantTableName = $this->info('name');
    
		$usersTable = Engine_Api::_()->getDbTable('users', 'user');
    $usersTableName = $usersTable->info('name');
    
    $select = $this->select()
            ->from($participantTableName, array('*'))
            ->setIntegrityCheck(false)
            ->join($usersTableName, $usersTableName . ".user_id = " . $participantTableName . ".owner_id  ", null);

    if ((isset($params['text']) && !empty($params['text'])) || (isset($params['media_type']) && !empty($params['media_type']))) {
      $select->join($contestTableName, $participantTableName . '.contest_id =' . $contestTableName . '.contest_id', array('contest_type'));
    }
    if (isset($params['contest_id']))
      $select->where('contest_id =?', $params['contest_id']);

    if (isset($params['text']) && !empty($params['text']))
      $select->where($contestTableName . ".title LIKE  ?", '%' . $params['text'] . '%');

    if (!empty($params['entry_text']))
      $select->where($participantTableName . ".title LIKE ? OR " . $participantTableName . ".description LIKE ?", '%' . $params['entry_text'] . '%');

    if (isset($params['sort']) && !empty($params['sort'])) {
      if ($params['sort'] == 'old')
        $select = $select->order($participantTableName . '.creation_date ASC');
	  else if($params['sort'] == 'high')
		   $select = $select->order($participantTableName . '.rank DESC');
	  else if($params['sort'] == 'low')
		   $select = $select->order($participantTableName . '.rank ASC');
      else
        $select = $select->order($participantTableName . '.' . $params['sort'] . ' DESC');
    }

    if (!empty($params['own_entry'])) {
      $select->where('owner_id !=?', $viewerId);
    }

    if (isset($params['user_id'])) {
      $select->where('owner_id =?', $params['user_id']);
    }

    if (isset($params['show']) && !empty($params['show'])) {
      if ($params['show'] == 1 && $viewerId) {
        $users = $viewer->membership()->getMembershipsOfIds();
        if ($users)
          $select->where($participantTableName . '.owner_id IN (?)', $users);
        else
          $select->where($participantTableName . '.owner_id IN (?)', 0);
      }
      elseif ($params['show'] == 2) {
        $networkMembershipTable = Engine_Api::_()->getDbTable('membership', 'network');
        $membershipNetworkUserIds = $networkMembershipTable->getMembershipsOfIds($viewer);
        $networkMembershipTableName = $networkMembershipTable->info('name');
        if(engine_count($membershipNetworkUserIds)){
          
          $select->join($networkMembershipTableName, $participantTableName . ".owner_id = " . $networkMembershipTableName . ".user_id  ", null)
                  ->where($networkMembershipTableName . ".resource_id  IN (?) ", $membershipNetworkUserIds);
        }else{
          
           $select->join($networkMembershipTableName, $participantTableName . ".owner_id = " . $networkMembershipTableName . ".user_id  ", null)
                  ->where($networkMembershipTableName . ".resource_id  = ?", "");
        }
      } elseif ($params['show'] == 3) {
        $select->where($participantTableName . '.owner_id=?', $viewerId);
      } elseif ($params['show'] == 'week') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
        $select->where("DATE(" . $participantTableName . ".winner_date) between ('$endTime') and ('$currentTime')");
      } elseif ($params['show'] == 'month') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(" . $participantTableName . ".creation_date) between ('$endTime') and ('$currentTime')");
      }
    }
    if (isset($params['media_type']) && !empty($params['media_type']))
      $select = $select->where($contestTableName . '.contest_type =?', $params['media_type']);
    return $select;
  }

  public function getWinnerPaginator($params = array()) {
    return Zend_Paginator::factory($this->getWinnerSelect($params));
  }

  public function getWinnerSelect($params = array()) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $currentTime = date('Y-m-d H:i:s');
    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');
    $participantTableName = $this->info('name');
    
    $usersTable = Engine_Api::_()->getDbTable('users', 'user');
    $usersTableName = $usersTable->info('name');
    
    $select = $this->select()
            ->from($contestTableName, array('contest_id', 'resulttime'))
            ->setIntegrityCheck(false)
            ->join($participantTableName, $contestTableName . '.contest_id = ' . $participantTableName . '.contest_id', array('*'))
            ->join($usersTableName, $usersTableName . ".user_id = " . $participantTableName . ".owner_id", null)
            ->where($participantTableName . ".rank != ?", 0);
    //   ->order($participantTableName . '.rank ASC');
    if (!empty($params['text']))
      $select->where($participantTableName . ".title LIKE ? OR " . $participantTableName . ".description LIKE ?", '%' . $params['text'] . '%');

    if (!empty($params['entry_text']))
      $select->where($participantTableName . ".title LIKE ? OR " . $participantTableName . ".description LIKE ?", '%' . $params['entry_text'] . '%');

    if (!empty($params['contest_text']))
      $select->where($contestTableName . ".title LIKE  ?", '%' . $params['contest_text'] . '%');

    if (isset($params['sort']) && !empty($params['sort'])) {
      if ($params['sort'] == 'vote_count DESC' || $params['sort'] == 'vote_count ASC')
        $select = $select->order($participantTableName . '.' . $params['sort']);
      elseif ($params['sort'] == 'high')
        $select = $select->order($participantTableName . '.rank DESC');
      elseif ($params['sort'] == 'low')
        $select = $select->order($participantTableName . '.rank ASC');
      else
        $select = $select->order($participantTableName . '.' . $params['sort'] . ' DESC');
    }
    if (isset($params['show']) && !empty($params['show'])) {
      if ($params['show'] == 1 && $viewerId) {
        $users = $viewer->membership()->getMembershipsOfIds();
        if ($users)
          $select->where($participantTableName . '.owner_id IN (?)', $users);
        else
          $select->where($participantTableName . '.owner_id IN (?)', 0);
      }
      elseif ($params['show'] == 2) {
        $networkMembershipTable = Engine_Api::_()->getDbTable('membership', 'network');
        $membershipNetworkUserIds = $networkMembershipTable->getMembershipsOfIds($viewer);
        $networkMembershipTableName = $networkMembershipTable->info('name');
        $select->join($networkMembershipTableName, $participantTableName . ".owner_id = " . $networkMembershipTableName . ".user_id  ", null)
                ->where($networkMembershipTableName . ".resource_id  IN (?) ", $membershipNetworkUserIds);
      } elseif ($params['show'] == 3) {
        $select->where($participantTableName . '.owner_id=?', $viewerId);
      } elseif ($params['show'] == 'week') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 week"));
        $select->where("DATE(" . $participantTableName . ".winner_date) between ('$endTime') and ('$currentTime')");
      } elseif ($params['show'] == 'month') {
        $endTime = date('Y-m-d H:i:s', strtotime("-1 month"));
        $select->where("DATE(" . $participantTableName . ".creation_date) between ('$endTime') and ('$currentTime')");
      }
    }
	
    if (isset($params['media_type']) && !empty($params['media_type']))
      $select = $select->where($contestTableName . '.contest_type =?', $params['media_type']);

    if (isset($params['rank']) && !empty($params['rank']))
      $select = $select->where($participantTableName . '.rank =?', $params['rank']);

    if (isset($params['contest_id']) && $params['contest_id'])
      $select->where($participantTableName . '.contest_id =?', $params['contest_id']);
	
    if (isset($params['fetchAll']))
      return $this->fetchAll($select);

    return $select;
  }

  public function hasParticipate($viewerId, $contestId) {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    $show = 0;
    $message = array();
    $contest = Engine_Api::_()->getItem('contest', $contestId);
    $joinEndTime = $contest->joinendtime;
    $joinStartTime = $contest->joinstarttime;
    $participate = $this->select()->from($this->info('name'), 'participant_id')
            ->where('contest_id =?', $contestId)
            ->where('owner_id =?', $viewerId)
            ->query()
            ->fetchColumn();
    if (!$participate)
      $message['can_join'] = 1;
    if (time() < strtotime($joinEndTime) && time() >= strtotime($joinStartTime) && time() < strtotime($contest->endtime) && Engine_Api::_()->authorization()->isAllowed('participant', $viewer, 'auth_participant'))
      $message['show_button'] = 1;
    return $message;
  }

  public function checkContestTime($viewerId, $contestId) {
    $dateArray = array();
    $contest = Engine_Api::_()->getItem('contest', $contestId);
    $joinEndTime = $contest->joinendtime;
    $joinStartTime = $contest->joinstarttime;
    $votingEndTime = $contest->votingendtime;
    $votingStartTime = $contest->votingstarttime;
    if (strtotime(date('Y-m-d H:i:s')) < strtotime($joinEndTime) && strtotime(date('Y-m-d H:i:s')) >= strtotime($joinStartTime))
      $dateArray['join_start_date'] = 1;
    if (strtotime(date('Y-m-d H:i:s')) > strtotime($joinEndTime))
      $dateArray['join_end_date'] = 1;
    if (strtotime(date('Y-m-d H:i:s')) < strtotime($votingEndTime) && strtotime(date('Y-m-d H:i:s')) >= strtotime($votingStartTime))
      $dateArray['voting_start_date'] = 1;
    if (strtotime(date('Y-m-d H:i:s')) > strtotime($votingEndTime))
      $dateArray['voting_end_date'] = 1;
    return $dateArray;
  }

  public function getContestEntries($contestId = null, $userId = null) {
    $select = $this->select()->from($this->info('name'), "COUNT(participant_id) as entry_count");
    if ($userId)
      $select->where('owner_id =?', $userId);
    elseif ($contestId)
      $select->where('contest_id =?', $contestId);
    $count = $select->query()->fetchColumn();
    if ($count)
      return $count;
    else
      return 0;
  }

  public function getVoteRank($contest_id, $vote_count) {
    return $this->select()
                    ->from($this->info('name'), "COUNT(participant_id)")
                    ->where('contest_id =?', $contest_id)
                    ->where('vote_count >= ?', $vote_count)
                    ->order('vote_count DESC')
                    ->group('vote_count')
                    ->query()
                    ->fetchColumn();
  }

  public function getOfTheDayResults() {

    $select = $this->select()
            ->from($this->info('name'), array('*'))
            ->where('offtheday =?', 1)
            ->where('startdate <= DATE(NOW())')
            ->where('enddate >= DATE(NOW())')
            ->order('RAND()');
    return $this->fetchRow($select);
  }

  public function getNextEntryId($entryId, $contest_id) {
    return $this->select()->from($this->info('name'), 'participant_id')
                    ->where('contest_id = ?', $contest_id)
                    ->where('participant_id > ?', $entryId)
                    ->order('participant_id ASC')
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }

  public function getPreviousEntryId($entryId, $contest_id) {
    return $this->select()->from($this->info('name'), 'participant_id')
                    ->where('contest_id = ?', $contest_id)
                    ->where('participant_id < ?', $entryId)
                    ->order('participant_id DESC')
                    ->limit(1)
                    ->query()
                    ->fetchColumn();
  }

  public function getContestMembers($contestId, $type) {
    $userTable = Engine_Api::_()->getItemTable('user');
    $userTableName = $userTable->info('name');
    $participantTableName = $this->info('name');
    $select = $this->select()->setIntegrityCheck(false)->from($participantTableName, array('participant_id', 'email'))
            ->join($userTableName, $participantTableName . '.owner_id =' . $userTableName . '.user_id', array('user_id', 'displayname'))
            ->where($participantTableName . '.contest_id =?', $contestId)
            ->where($participantTableName . '.owner_id !=?', Engine_Api::_()->user()->getViewer()->getIdentity());
    if ($type == 'winner')
      $select->where($participantTableName . '.rank !=?', 0);
    return $this->fetchAll($select);
  }

  public function getParticipants($param = null) {
    $select = $this->select()->from($this->info('name'), array('participant_id', 'contest_id', 'votingstarttime', 'votingendtime', 'start', 'end', 'owner_id', 'title'));
    if ($param == 'start') {
      $select->where('votingstarttime <= ?', date('Y-m-d H:i:s'))->where('start != ?', 1);
    } else {
      $select->where('votingendtime <= ?', date('Y-m-d H:i:s'))->where('end != ?', 1);
    }
    return $this->fetchAll($select);
  }

  public function getContestWinners() {
    $select = $this->select()->from($this->info('name'), "COUNT(participant_id)")
            ->where('rank !=?', 0);
    return $select->query()->fetchColumn();
  }

}
