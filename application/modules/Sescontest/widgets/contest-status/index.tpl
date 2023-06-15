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

<?php $dateArray = Engine_Api::_()->getDbTable('participants', 'sescontest')->checkContestTime($this->viewer_id, $this->contest->contest_id);?>
<div class="sesbasci_bxs sesbasic_clearfix">
  <?php if($this->params['current_status']):?>
    <?php if(strtotime(date('Y-m-d H:i:s')) > strtotime($this->contest->endtime)):?>
      <div class="sescontest_contest_status close" style="font-size:<?php echo is_numeric($this->params['status_font_size'])?$this->params['status_font_size'].'px':$this->params['status_font_size'] ?>;">
      	<i class="fa fa-trophy"></i>
        <span class="sescontest_contest_status_txt"><?php echo $this->translate("Ended");?></span>
    	</div>
    <?php elseif(strtotime(date('Y-m-d H:i:s')) < strtotime($this->contest->starttime)):?>
      <div class="sescontest_contest_status pending" style="font-size:<?php echo is_numeric($this->params['status_font_size'])?$this->params['status_font_size'].'px':$this->params['status_font_size'] ?>;">
      	<i class="fa fa-trophy"></i>
        <span class="sescontest_contest_status_txt"><?php echo $this->translate("Coming Soon");?></span>
      </div>
    <?php elseif(strtotime(date('Y-m-d H:i:s')) >= strtotime($this->contest->starttime)):?>
      <div class="sescontest_contest_status open" style="font-size:<?php echo is_numeric($this->params['status_font_size'])?$this->params['status_font_size'].'px':$this->params['status_font_size'] ?>;">
      	<i class="fa fa-trophy"></i>
        <span class="sescontest_contest_status_txt"><?php echo $this->translate("Active");?></span>
      </div>
    <?php endif;?>
  <?php endif;?>
  <?php if(isset($dateArray['join_start_date']) && $this->params['show_js_date']):?>
    <div class="sescontest_contest_status open" style="font-size:<?php echo is_numeric($this->params['entry_font_size'])?$this->params['entry_font_size'].'px':$this->params['entry_font_size'] ?>;">
    	<i class="fa fa-upload"></i>
      <span class="sescontest_contest_status_txt"><?php echo $this->translate("Entries Submission Started");?></span>
  	</div>
  <?php elseif(isset($dateArray['join_end_date']) && $this->params['show_je_date'] && strtotime(date('Y-m-d H:i:s')) < strtotime($this->contest->endtime)):?>
    <div class="sescontest_contest_status close" style="font-size:<?php echo is_numeric($this->params['entry_font_size'])?$this->params['entry_font_size'].'px':$this->params['entry_font_size'] ?>;">
    	<i class="fa fa-upload"></i>
      <span class="sescontest_contest_status_txt"><?php echo $this->translate("Entries Submission Ended");?></span>
  	</div>
  <?php endif;?>
  <?php if(isset($dateArray['voting_start_date']) && $this->params['show_vs_date']):?>
    <div class="sescontest_contest_status open" style="font-size:<?php echo is_numeric($this->params['entry_font_size'])?$this->params['entry_font_size'].'px':$this->params['entry_font_size'] ?>;">
    	<i class="far fa-hand-point-up"></i>
    	<span class="sescontest_contest_status_txt"><?php echo $this->translate("Voting Started");?></span>
  	</div>
  <?php elseif(isset($dateArray['voting_end_date']) && $this->params['show_ve_date'] && strtotime(date('Y-m-d H:i:s')) < strtotime($this->contest->endtime)):?>
    <div class="sescontest_contest_status close" style="font-size:<?php echo is_numeric($this->params['entry_font_size'])?$this->params['entry_font_size'].'px':$this->params['entry_font_size'] ?>;">
    	<i class="far fa-hand-point-up"></i>
      <span class="sescontest_contest_status_txt"><?php echo $this->translate("Voting Ended");?></span>
    </div>
  <?php endif;?>
</div>