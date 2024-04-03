<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: _dataBar.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php if($viewer->getIdentity()):?>
  <?php $oldTz = date_default_timezone_get();?>
<?php endif;?>
<?php $endtime = strtotime($contest->endtime);?>
<?php if($viewer->getIdentity()):?>
  <?php date_default_timezone_set($viewer->timezone);?>
<?php endif;?>
<?php $endtime = strtotime(date('Y-m-d H:i:s',$endtime));?>
<?php $currentTime = time();?>
<?php $diff=($endtime-$currentTime);?>
<?php $temp = $diff/86400;?>
<?php $dd = floor($temp); $temp = 24*($temp-$dd);?>
<?php $hh = floor($temp); $temp = 60*($temp-$hh);?>
<?php $mm = floor($temp); $temp = 60*($temp-$mm); ?>
<?php $ss = floor($temp);?>
<?php if($viewer->getIdentity()):?>
  <?php date_default_timezone_set($oldTz);?>
<?php endif;?>
<?php $currentTime = strtotime(date('Y-m-d H:i:s'));?>
<?php $participate = Engine_Api::_()->getDbTable('participants', 'sescontest')->hasParticipate($viewer->getIdentity(), $contest->contest_id);?>
<?php $countEntries = Engine_Api::_()->getDbTable('participants', 'sescontest')->getContestEntries($contest->contest_id);?>
<?php if(isset($this->entryCountActive)):?>
  <div class="_ent">
    <span class="_count"><?php echo $countEntries;?></span>
    <span class="_text"><?php if($countEntries == 1):?><?php echo $this->translate("Entry");?><?php else:?><?php echo $this->translate("Entries");?><?php endif;?></span>
  </div>
<?php endif;?>
<?php if(strtotime($contest->endtime) > time()):?>
  <?php if($this->statusActive):?>
    <?php if($dd > 0):?>
      <div class="_dl">
        <span class="_count"><?php echo $dd ?></span>
        <span class="_text"><?php echo $this->translate('Days left');?></span>
      </div>
    <?php else:?>
      <div class="_countdown sescontest_countdown_view">
          <span class="_count">
          <div class="finish-message" style="display: none;"><?php echo $this->translate('ENDED');?></div>
          <div class="countdown-contest">
            <div style="display: none;"><?php echo str_replace('timestamp','timestamp sescontest-timestamp-update ',$this->timestamp($contest->endtime)); ?></div>
            <?php if($dd > 0):?>
              <div>
                <p>
                  <span class='day'><?php echo $dd;?></span><span><?php echo $this->translate("d")?></span>
                </p>
              </div>
            <?php endif;?>
            <div>
              <p>
                <span class='hour'><?php echo $hh;?></span><span><?php echo $this->translate("h")?></span>
              </p> 
            </div>
            <div>
              <p>
                <span class='minute'><?php echo $mm;?></span><span><?php echo $this->translate("m")?></span>
              </p>
            </div>
            <div>
              <p>
                <span class='second'><?php echo $ss;?></span><span><?php echo $this->translate("s")?></span>
              </p>
            </div>
          </div>
        </span>
      </div>
    <?php endif;?>
  <?php endif;?>
  <?php if(isset($this->joinButtonActive)):?>
    <?php if(isset($participate['can_join']) && isset($participate['show_button'])){?>
      <div class="_join"> 
        <a href="<?php echo $this->url(array('action' => 'create', 'contest_id' => $contest->contest_id),'sescontest_join_contest','true');?>" class="sescontest_list_join_btn join sesbasic_animation"><?php echo $this->translate('JOIN NOW');?></a>
      </div>
    <?php } elseif(isset($participate['show_button'])) {?>
      <div>
        <a href="javascript:;" class="sescontest_list_join_btn joined sesbasic_animation"><?php echo $this->translate('JOINED');?></a>
      </div>
    <?php };?>
  <?php endif;?>
<?php else:?>
  <?php if($this->statusActive):?>
    <div class="sescontest_list_ended_msg"><?php echo $this->translate("ENDED");?></div>
  <?php endif;?>
  <?php if($this->voteCountActive):?>
    <div> 
      <?php $totalVote = Engine_Api::_()->getDbTable('votes', 'sescontest')->getTotalVotes($contest->contest_id);?>
      <span class="_count"><?php echo $totalVote;?></span>
      <span class="_text"><?php if($totalVote == 1):?><?php echo $this->translate("Vote");?><?php else:?><?php echo $this->translate("Votes");?><?php endif;?></span>
    </div>
  <?php endif;?>
<?php endif;?>

