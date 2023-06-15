<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Jobs.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Plugin_Task_Jobs extends Core_Plugin_Task_Abstract {
  public function execute() {
    $contestTable = Engine_Api::_()->getDbTable('contests', 'sescontest');
    $contestTableName = $contestTable->info('name');
    $participantTable = Engine_Api::_()->getDbTable('participants', 'sescontest');
    $participantTableName = $participantTable->info('name');
    $select = $contestTable->select()
            ->from($contestTableName, array('*'))
            ->where('(' . $contestTableName . ".resulttime = '' and " . $contestTableName . ".endtime <= '" . date('Y-m-d H:i:s') . "') or (" . $contestTableName . ".resulttime != '' and " . $contestTableName . ".resulttime <= '" . date('Y-m-d H:i:s') . "')")
            ->where($contestTableName . '.process != ?', 1);
    $contests = $contestTable->fetchAll($select);
    foreach ($contests as $contest) {
      $select = $participantTable->select()
              ->from($participantTableName, array('vote_count', 'participant_id', 'owner_id', 'title'))
              ->where('contest_id =?', $contest->contest_id)
              ->where('vote_count >?', 0)
              ->order('vote_count DESC')
              ->order('vote_date ASC')
              ->limit($contest->award_count);
      $participants = $participantTable->fetchAll($select);
      $count = 1;
      foreach ($participants as $participant) {
          $owner = $participant->getOwner();
          if(!$owner->getIdentity())
              continue;
        $participantTable->update(array('winner_date' => date('Y-m-d h:i:s'), 'rank' => $count), array('participant_id =?' => $participant->participant_id));
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $contest->getOwner(), $contest, 'sescontest_winner_contest_entry', array("rank" => $count));
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner, 'sescontest_winner_contest_entry', array('contest_title' => $contest->getTitle(),'winner_rank'=>Engine_Api::_()->sescontest()->ordinal($count),'object_link' => $contest->getHref(), 'host' => $_SERVER['HTTP_HOST']));
        $count++;
      }
      $participantsSelect = $participantTable->select()
              ->from($participantTableName, array('vote_count', 'participant_id', 'owner_id', 'title'))
              ->where('contest_id =?', $contest->contest_id);
      $participants = $participantTable->fetchAll($participantsSelect);
      foreach ($participants as $participant) {
          $owner = $participant->getOwner();
          if(!$owner->getIdentity())
              continue;
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($owner, 'sescontest_result_announced', array('contest_title' => $contest->getTitle(), 'object_link' => $contest->getHref(), 'host' => $_SERVER['HTTP_HOST']));
      }
      $contestTable->update(array('process' => 1), array('contest_id =?' => $contest->contest_id));
    }
    //Start Voting Start and End Notification,Email Work
    $participants = $participantTable->getParticipants('start');
    if (engine_count($participants) > 0) {
      foreach ($participants as $participant) {
          $owner = $participant->getOwner();
          if(!$owner->getIdentity())
              continue;
        $contest = Engine_Api::_()->getItem('contest', $participant->contest_id);
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($participant->getOwner(), $contest->getOwner(), $contest, 'sescontest_vote_start_entry');
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($participant->getOwner(), 'sescontest_vote_start_entry', array('contest_title' => $contest->getTitle(), 'object_link' => $participant->getHref(), 'host' => $_SERVER['HTTP_HOST']));
        $participant->start = 1;
        $participant->save();
      }
    }
    $participants = $participantTable->getParticipants('end');
    if (engine_count($participants) > 0) {
      foreach ($participants as $participant) {
          $owner = $participant->getOwner();
          if(!$owner->getIdentity())
              continue;
        $contest = Engine_Api::_()->getItem('contest', $participant->contest_id);
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($participant->getOwner(), $contest->getOwner(), $contest, 'sescontest_vote_end_entry');
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($participant->getOwner(), 'sescontest_vote_end_entry', array('contest_title' => $contest->getTitle(), 'object_link' => $participant->getHref(), 'host' => $_SERVER['HTTP_HOST']));
        $participant->end = 1;
        $participant->save();
      }
    }
    //End Work Here
    return true;
  }
}
