<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _voteData.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $contest = Engine_Api::_()->getItem('contest', $entry->contest_id);?>
<?php if(!$this->viewer()->getIdentity()):?><?php $levelId = 5;?><?php else:?><?php $levelId = $this->viewer()->level_id;?><?php endif;?>
<?php if ($levelId != 5 && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember') && $contest->audience_type == 0):?>
  <?php $isViewerJury = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->isJuryMember(array('user_id' => $this->viewer()->getIdentity(), 'contest_id' => $contest->contest_id));?>
  <?php if(!$isViewerJury):?>
    <?php return;?>
  <?php endif;?>
<?php endif;?>
<?php $voteType = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'allow_entry_vote');?>
<?php if ($voteType != 0 && (($voteType == 1 && $entry->owner_id != $this->viewer()->getIdentity()) || $voteType == 2)):?>
  <?php $contest = Engine_Api::_()->getItem('contest', $entry->contest_id);?>
  <?php $contest_id = $contest->contest_id;?>
  <?php if(strtotime($contest->votingstarttime) <= time() && strtotime($contest->votingendtime) > time() && strtotime($contest->endtime) > time()):?>
    <?php $hasVoted = Engine_Api::_()->getDbTable('votes', 'sescontest')->hasVoted($this->viewer()->getIdentity(), $contest_id, $entry->participant_id);?>
    <?php if($hasVoted):?>
      <?php $class = 'disable' ;?>
      <?php $text = $this->translate("Voted");?>
    <?php else:?>
      <?php $class = '';?>
      <?php $text = "Vote";?>
    <?php endif;?> 
    <?php 
        if(Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'canEntryMultvote') && $class == "disable" ){ 
        $canMultipleVote = 1;
        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestjurymember') && Engine_Api::_()->user()->getViewer()->getIdentity() > 0) {
          $isViewerJury = Engine_Api::_()->getDbTable('members', 'sescontestjurymember')->isJuryMember(array('user_id' => Engine_Api::_()->user()->getViewer()->getIdentity(), 'contest_id' => $contest_id));
          if($isViewerJury)
            $canMultipleVote = 0;
         }
         if($canMultipleVote) {
          $interval = Engine_Api::_()->authorization()->getPermission($levelId, 'participant', 'voteInterval');
          $time = strtotime($hasVoted->creation_date);
          $interval = "+$interval minutes";
          $voteTime1 = strtotime($interval, $time);
          if (time() < strtotime(date("Y-m-d H:i:s", $voteTime1))){
            $seconds =  strtotime(date("Y-m-d H:i:s", $voteTime1)) - time() ; 
            $time = "";
            $days    = floor($seconds / 86400);
            if($days > 0 )
              $time .= $days == 1 ? $days.' Day ' : $days.' Days ';
            $hours   = floor(($seconds - ($days * 86400)) / 3600);
            if($hours > 0 )
              $time .= $hours == 1 ? $hours.' Hour ' : $hours.' Hours ';
            $minutes = floor(($seconds - ($days * 86400) - ($hours * 3600))/60);
            if($minutes > 0 )
              $time .= $minutes == 1 ? $minutes.' Minute ' : $minutes.' Minutes ';
            $seconds = floor(($seconds - ($days * 86400) - ($hours * 3600) - ($minutes*60)));
            if($seconds > 0 )
              $time .= $seconds == 1 ? $seconds.' Second ' : $seconds.' Seconds ';
           ?>
           <?php if(!empty($showTextAlreadyVoted)){ ?>
              <p><?php echo $this->translate("As you have already voted, you will be able to vote again after ") ."<b>".$time; ?></b></p>            
              <p><?php echo $this->translate("Please, Refresh/Reload/Reopen this page to vote again."); ?></p>
            <?php $showTextAlreadyVoted = ""; ?>
           <?php }else{ ?>
              Vote again in<br /> <b><?php echo $time; ?></b>
           <?php } ?>
        <?php } ?>
        <?php  }else{ ?>
      <a href='javascript:;' id="sescontest_vote_button_<?php echo $entry->participant_id;?>" onclick="doVote('<?php echo $entry->participant_id;?>', '<?php echo $contest_id;?>', this);" class="sesbasic_animation contest_join_btn <?php echo $class; ?>"><i class="far fa-hand-point-up"></i><span><?php echo $text; ?></span></a>
    <?php } ?>
    <?php  }else{ ?>
      <a href='javascript:;' id="sescontest_vote_button_<?php echo $entry->participant_id;?>" onclick="doVote('<?php echo $entry->participant_id;?>', '<?php echo $contest_id;?>', this);" class="sesbasic_animation contest_join_btn <?php echo $class; ?>"><i class="far fa-hand-point-up"></i><span><?php echo $text; ?></span></a>
    <?php } ?>
  <?php endif;?>
  <?php $request = Zend_Controller_Front::getInstance()->getRequest();?>
  <?php $controllerName = $request->getControllerName();?>
  <?php $actionName = $request->getActionName();?>
  <?php $canIntegrate =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.vote.integrate', 0);?>
  <script type='text/javascript'>
    function doVote(participantId, contestId, obj) {
      if('<?php echo $controllerName ;?>' == 'join' && '<?php echo $actionName ;?>' == 'view') {
        if(scriptJquery('.contest_join_btn').hasClass('disable'))
          return;
        scriptJquery('.contest_join_btn').html('<i class="far fa-hand-point-up"></i><span>'+en4.core.language.translate('Voted')+'</span>');
        scriptJquery('.contest_join_btn').addClass('disable');
      }
      else {
        if(scriptJquery(obj).hasClass('disable'))
          return;
        scriptJquery(obj).html('<i class="far fa-hand-point-up"></i><span>'+en4.core.language.translate('Voted')+'</span>');
        scriptJquery(obj).addClass('disable'); 
      }
      //Send request
      var request = scriptJquery.ajax({
        url: en4.core.baseUrl + 'sescontest/vote/vote/contest_id/'+contestId+'/id/'+participantId+'/integration/<?php echo $canIntegrate;?>',
        'method' : 'POST',
        success : function(responseJSON) {
          if(responseJSON.like_status) {
            scriptJquery('.sescontest_entry_like_'+participantId).addClass('button_active')
            scriptJquery('.sescontest_entry_like_'+participantId).find('span').html(parseInt(scriptJquery('.sescontest_entry_like_'+participantId).find('span').html())+1);
          }
        }
      });
      
    }
  </script>
<?php endif;?>
