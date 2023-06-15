<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/scripts/core.js'); ?>

<?php if($this->placementType == 'sidebar'):?>

<div class="sescontest_countdown_mini sesbasic_clearfix sesbasic_bxs">
  <?php if($this->headingTitle):?>
  <p><?php echo $this->headingTitle;?></p>
  <?php endif;?>
  <div class="finish-message" style="display: none;"><?php echo $this->finishMessage;?></div>
  <div class="countdown-contest sescontest_countdown_mini_box sescontest_countdown_mini_box_<?php echo $this->identity; ?>">
    <?php  $diff=strtotime($this->countDownTime)-time();?>
    <?php $temp = $diff/86400;?>
    <?php $dd = floor($temp); $temp = 24*($temp-$dd);?>
    <?php $hh = floor($temp); $temp = 60*($temp-$hh); ?>
    <?php $mm = floor($temp); $temp = 60*($temp-$mm); ?>
    <?php $ss = floor($temp);?>
    <div style="display: none;"><?php echo str_replace('timestamp','timestamp sescontest-timestamp-update ',$this->timestamp($this->countDownTime)); ?></div>
    <?php if($dd > 0):?>
    <div>
      <p> <span class='day'><?php echo $dd;?></span> <span><?php echo $this->translate("DD")?></span> </p>
    </div>
    <?php endif;?>
    <?php if($hh > 0):?>
    <div>
      <p> <span class='hour'><?php echo $hh;?></span> <span><?php echo $this->translate("HH")?></span> </p>
    </div>
    <?php endif;?>
    <?php if($mm > 0):?>
    <div>
      <p> <span class='minute'><?php echo $mm;?></span> <span><?php echo $this->translate("MM")?></span> </p>
    </div>
    <div>
      <p> <span class='second'><?php echo $ss;?></span> <span><?php echo $this->translate("SS")?></span> </p>
    </div>
    <?php endif;?>
  </div>
</div>
<style type="text/css">
		.sescontest_countdown_mini_box_<?php echo $this->identity; ?> > div p{border-radius:<?php echo is_numeric($this->radious) ? $this->radious.'px' : $this->radious;?>;}
	</style>
<?php else:?>
<div class="sescontest_countdown_full sesbasic_bxs sesbasic_clearfix">
  <?php if($this->headingTitle):?>
  <p><?php echo $this->headingTitle;?></p>
  <?php endif;?>
  
  <div id="sescontestCountdown_<?php echo $this->identity; ?>" class="sescontest_countdown_box">
    <div style="display: none;"><?php echo str_replace('timestamp','timestamp sescontest-timestamp-middle',$this->timestamp($this->countDownTime)); ?></div>
    <div class="time_circles">
      <div class="textDiv_Days">
        <h4>Days</h4>
        <span>0</span></div>
      <div class="textDiv_Hours">
        <h4>Hours</h4>
        <span>0</span></div>
      <div class="textDiv_Minutes">
        <h4>Minutes</h4>
        <span>0</span></div>
      <div class="textDiv_Seconds">
        <h4>Seconds</h4>
        <span>0</span></div>
    </div>
  </div>
</div>
<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl .'application/modules/Sescontest/externals/scripts/countdown.js');?>
<script>
   // scriptJquery("#sescontestCountdown_<?php echo $this->identity; ?>").TimeCircles();
    // alert(scriptJquery("#sescontestCountdown").TimeCircles().getTime());
  // scriptJquery("#sescontestCountdown_<?php echo $this->identity; ?>").TimeCircles({count_past_zero: true});
  </script>
<?php endif;?>
