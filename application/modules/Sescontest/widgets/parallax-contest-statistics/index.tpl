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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontest/externals/styles/styles.css'); ?>
<div class="contest_statics_box sesbasic_bxs" style="<?php if(!empty($this->params['bg_image'])):?>background-image:url(<?php echo str_replace('//','/',$this->layout()->staticBaseUrl.'/'.$this->params['bg_image']); ?>);<?php endif;?>height:<?php echo $this->params['height'];?>px;">
	<div class="contest_statics_box_row sesbasic_clearfix">
    <div class="contest_statics_title">
      <h3 class="sescontest_weltitle"><span><?php echo $this->translate($this->params['banner_title']);?></span></h3>
      <p class="sescontest_weldes"><?php echo $this->translate($this->params['description']);?></p>
    </div>
    <?php if(isset($this->totalContestActive)):?>
      <div class="contest_statics_box_item active wow">
        <span class="contest_statics_icon"><img src="application/modules/Sescontest/externals/images/contest.png" /></span>
        <?php if($this->params['show_custom_count'] == 'real'):?>
          <h3 class="counter"><?php echo Engine_Api::_()->getDbTable('contests','sescontest')->countContests('');?></h3>
        <?php else:?>
          <h3 class="counter"><?php echo $this->params['custom_contest'];?></h3>
        <?php endif;?>
        <p class="contest_statics_dis"><?php echo $this->translate('AMAZING CONTESTS');?></p>
      </div>
    <?php endif;?>
    <?php if(isset($this->totalEntriesActive)):?>
      <div class="contest_statics_box_item wow">
        <span class="contest_statics_icon"><img src="application/modules/Sescontest/externals/images/entries.png" /></span>
        <?php if($this->params['show_custom_count'] == 'real'):?>
          <h3 class="counter"><?php echo Engine_Api::_()->getDbTable('participants','sescontest')->getContestEntries();?></h3>
        <?php else:?>
          <h3 class="counter"><?php echo $this->params['custom_entry'];?></h3>
        <?php endif;?>
        <p class="contest_statics_dis"><?php echo $this->translate('ENTRIES SUBMITTED');?></p>
      </div>
    <?php endif;?>
    <?php if(isset($this->totalVotesActive)):?>
      <div class="contest_statics_box_item wow">
        <span class="contest_statics_icon"><img src="application/modules/Sescontest/externals/images/votes.png" /></span>
        <?php if($this->params['show_custom_count'] == 'real'):?>
          <h3 class="counter"><?php echo Engine_Api::_()->getDbTable('votes','sescontest')->getTotalVotes();?></h3>
        <?php else:?>
          <h3 class="counter"><?php echo $this->params['custom_vote'];?></h3>
        <?php endif;?>
        <p class="contest_statics_dis"><?php echo $this->translate('VOTERS');?></p>
      </div>
    <?php endif;?>
    <?php if(isset($this->totalRanksActive)):?>
      <div class="contest_statics_box_item wow">
        <span class="contest_statics_icon"><img src="application/modules/Sescontest/externals/images/ranks.png" /></span>
        <?php if($this->params['show_custom_count'] == 'real'):?>
          <h3 class="counter">5</h3>
        <?php else:?>
          <h3 class="counter"><?php echo $this->params['custom_rank'];?></h3>
        <?php endif;?>
        <p class="contest_statics_dis"><?php echo $this->translate('LIVE RANK');?></p>
      </div>
    <?php endif;?>
    <?php if(isset($this->totalWinnersActive)):?>
      <div class="contest_statics_box_item wow">
        <span class="contest_statics_icon"><img src="application/modules/Sescontest/externals/images/winners.png" /></span>
        <?php if($this->params['show_custom_count'] == 'real'):?>
          <h3 class="counter"><?php echo Engine_Api::_()->getDbTable('participants','sescontest')->getContestWinners();?></h3>
        <?php else:?>
          <h3 class="counter"><?php echo $this->params['custom_winners'];?></h3>
        <?php endif;?>
        <p class="contest_statics_dis"><?php echo $this->translate('WINNERS');?></p>
      </div> 
    <?php endif;?>
  </div>
</div>
<script src="application/modules/Sescontest/externals/scripts/jquery.1.11.1.min.js" type="text/javascript"></script>
<!--<script src="application/modules/Sescontest/externals/scripts/wow.min.js" type="text/javascript"></script>
<script src="application/modules/Sescontest/externals/scripts/main.js" type="text/javascript"></script>-->
<script src="application/modules/Sescontest/externals/scripts/waypoints.min.js"></script> 
<script src="application/modules/Sescontest/externals/scripts/jquery.counterup.min.js"></script> 
<script type="text/javascript">
    sesconObject(document).ready(function( seslpObject ) {
        sesconObject('.counter').counterUp({
            delay: 10,
            time: 1000
        });
    });
</script>
