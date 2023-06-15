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

<div class="sescontest_award_container sesbasic_clearfix sesbasic_bxs">	
	<div class="sescontest_award_entries sesbasic_clearfix">
  	<ul class="sescontest_award_entries_list sesbasic_clearfix">
      <?php if(isset($this->firstAwardActive) && !empty($this->contest->award)):?>
        <li class="sesbasic_clearfix">
          <div class="_icon sescontest_award_icon sescontest_award_1st"><p><span>1</span><span><?php echo $this->translate('st');?></span></p></div>
          <div class="sescontest_award_entries_content sesbasic_html_block">
            <?php echo $this->contest->award;?>
          </div>
        </li>
      <?php endif;?>
      <?php if(isset($this->secondAwardActive) && !empty($this->contest->award2)):?>
        <li class="sesbasic_clearfix">
          <div class="_icon sescontest_award_icon sescontest_award_2nd"><p><span>2</span><span><?php echo $this->translate('nd');?></span></p></div>
          <div class="sescontest_award_entries_content sesbasic_html_block">
            <?php echo $this->contest->award2;?>
          </div>
        </li>
      <?php endif;?>
      <?php if(isset($this->thirdAwardActive) && !empty($this->contest->award3)):?>
        <li class="sesbasic_clearfix">
          <div class="_icon sescontest_award_icon sescontest_award_3rd"><p><span>3</span><span><?php echo $this->translate('rd');?></span></p></div>
          <div class="sescontest_award_entries_content sesbasic_html_block">
            <?php echo $this->contest->award3;?>
          </div>
        </li>
      <?php endif;?>
      <?php if(isset($this->fourthAwardActive) && !empty($this->contest->award4)):?>
        <li class="sesbasic_clearfix">
          <div class="_icon sescontest_award_icon sescontest_award_4th"><p><span>4</span><span><?php echo $this->translate('th');?></span></p></div>
          <div class="sescontest_award_entries_content sesbasic_html_block">
            <?php echo $this->contest->award4;?>
          </div>
        </li>
      <?php endif;?>
      <?php if(isset($this->fifthAwardActive) && !empty($this->contest->award5)):?>
        <li class="sesbasic_clearfix">
          <div class="_icon sescontest_award_icon sescontest_award_5th"><p><span>5</span><span><?php echo $this->translate('th');?></span></p></div>
          <div class="sescontest_award_entries_content sesbasic_html_block">
						<?php echo $this->contest->award5;?>
          </div>
        </li>
      <?php endif;?>
    </ul>
  </div>
</div>