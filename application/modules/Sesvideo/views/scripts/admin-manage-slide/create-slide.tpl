<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: create-slide.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/ses-scripts/jscolor/jscolor.js');

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/dismiss_message.tpl';?>
<div class="sesbasic_search_reasult">
	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesvideo', 'controller' => 'manage-slide', 'action' => 'manage','id'=>$this->gallery_id), $this->translate("Back to Manage Videos and Photos") , array('class'=>'sesbasic_icon_back buttonlink')); ?>
</div>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script type="application/javascript">
function sign_button(value){
	if(value == 1)
		scriptJquery('div[id^="signup_button_"]').show();
	else
		scriptJquery('div[id^="signup_button_"]').hide();
	scriptJquery('#signup_button-wrapper').show();
}
function log_button(value){
	if(value == 1)
		scriptJquery('div[id^="login_button_"]').show();
	else
		scriptJquery('div[id^="login_button_"]').hide();
	
	scriptJquery('#login_button-wrapper').show();
}
function register_form(value){
	if(value == 1)
		scriptJquery('#register_position-wrapper').show();
	 else
	 	scriptJquery('#register_position-wrapper').hide();
}
function extra_buton(value){
	if(value == 1)
		scriptJquery('div[id^="extra_button_"]').show();
	else
		scriptJquery('div[id^="extra_button_"]').hide();
	
	scriptJquery('#extra_button_-wrapper').show();
}
sign_button(scriptJquery('#login_button').val());
log_button(scriptJquery('#signup_button').val());
register_form(scriptJquery('#show_register_form').val());
extra_buton(scriptJquery('#extra_button').val());
</script>
