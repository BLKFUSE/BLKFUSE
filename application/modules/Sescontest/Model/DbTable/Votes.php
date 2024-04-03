<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Votes.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Model_DbTable_Votes extends Engine_Db_Table {

  protected $_rowClass = "Sescontest_Model_Vote";

  public function hasVoted($ownerId, $contestId, $entryId) {
    if ($ownerId)
      $levelId = Engine_Api::_()->user()->getViewer()->level_id;
    else
      $levelId = 5;
    if (Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'canEntryMultvote')) {
      $interval = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'voteInterval');
      $voteTime = $this->select()->from($this->info('name'))
              ->where('contest_id =?', $contestId)
              ->where('participant_id =?', $entryId)
              ->where('ip_address =?', $_SERVER["REMOTE_ADDR"])
              ->order('vote_id DESC')
              ->limit(1);
      $voteTime = $this->fetchRow($voteTime);

      if ($voteTime) {

        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember') && Engine_Api::_()->user()->getViewer()->getIdentity() > 0) {
          $isViewerJury = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->isJuryMember(array('user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'contest_id' => $contestId));
          if ($isViewerJury) {
            return $voteTime;
          }
        }

        $time = strtotime($voteTime->creation_date);
        $interval = "+$interval minutes";
        $voteTime1 = strtotime($interval, $time);
        if (time() >= strtotime(date("Y-m-d H:i:s", $voteTime1)))
          return 0;
        return $voteTime;
      }
      return 0;
    }
    $value = $this->select()->from($this->info('name'), 'vote_id')
            ->where('contest_id =?', $contestId)
            ->where('participant_id =?', $entryId);
    if ($ownerId) {
      //$ownerId = "";
    }
    $value->where('owner_id  = "' . $ownerId . '" && ip_address = "' . $_SERVER["REMOTE_ADDR"] . '"');
    //else
    //$value->where('ip_address =?', $_SERVER["REMOTE_ADDR"]);
    return $value->query()->fetchColumn();
  }

  public function getTotalVotes($contestId = null) {
    $voteCount = $this->select()->from($this->info('name'), "COUNT('vote_id')");
    if ($contestId)
      $voteCount->where('contest_id =?', $contestId);
    return $voteCount->query()->fetchColumn();
  }

  public function isVoted($params = array()) {
    return $this->select()->from($this->info('name'), 'vote_id')
                    ->where('owner_id =?', $params['user_id'])
                    ->where('contest_id =?', $params['contest_id'])
                    ->query()
                    ->fetchColumn();
  }

}
