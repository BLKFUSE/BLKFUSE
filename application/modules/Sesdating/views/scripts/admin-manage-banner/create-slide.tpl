<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create-slide.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>
<div class="sesbasic_search_reasult">
	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage-banner', 'action' => 'manage','id'=>$this->banner_id), $this->translate("Back to Manage Photo Slides") , array('class'=>'sesbasic_icon_back buttonlink')); ?>
</div>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="application/javascript">

function extra_buton(value){
	if(value == 1)
		scriptJquery('div[id^="extra_button_"]').show();
	else
		scriptJquery('div[id^="extra_button_"]').hide();
	
	scriptJquery('#extra_button_-wrapper').show();
}
extra_buton(scriptJquery('#extra_button').val());
</script>
<style type="text/css">
.settings div.form-label label.required:after{
	content:" *";
	color:#f00;
}
</style>
