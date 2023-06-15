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

<?php if($this->editOverview){ ?>
	<?php 
   if($this->subject->overview){
   	$overviewicon = "sesbasic_icon_edit";
   	$overviewtext = $this->translate("Change Overview");
   }else{
    $overviewicon = "sesbasic_icon_add";
   	$overviewtext = $this->translate("Add Overview");
   } ?>
  <div class="sesbasic_profile_tabs_top sesbasic_clearfix">
    <a href="<?php echo $this->url(array('contest_id' => $this->subject->custom_url, 'action'=>'overview'), 'sescontest_dashboard', true); ?>" class="sesbasic_button <?php echo $overviewicon; ?>">
      <?php echo $overviewtext; ?>
    </a>
  </div>
<?php } ?>
<div class="sesbasic_html_block">
  <?php if($this->subject->overview):?>
    <?php echo $this->subject->overview;?>
  <?php else: ?>
    <div class="tip">
      <span><?php echo $this->translate("There is currently no overview.");?></span>
    </div>     
  <?php endif; ?>
</div>