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
<?php if($this->edit){ ?>
	<?php 
   if($this->subject->rules){
   	$icon = "fas fa-pencil-alt";
   	$text = $this->translate("Change Rules");
   } else {
   	$icon = "fa fa-plus";
   	$text = $this->translate("Add Rules");
   } ?>
  <div class="sesbasic_profile_tabs_top sesbasic_clearfix">
    <a href="<?php echo $this->url(array('contest_id' =>$this->subject->custom_url,'action'=>'rules'),'sescontest_dashboard'); ?>" class="sesbasic_button">
    	<i class="<?php echo $icon; ?>"></i>
      <span><?php echo $text; ?></span>
    </a>
  </div>
<?php } ?>
<div class="sesbasic_html_block">
  <?php if($this->subject->rules):?>
    <?php echo $this->subject->rules;?>
  <?php else: ?>
    <div class="tip">
      <span><?php echo $this->translate("There are currently no rules.");?></span>
    </div>     
  <?php endif; ?>
</div>