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
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="text/javascript">
  function confirmChangeLandingPage(value){
      if(value == 1 && !confirm('Are you sure want to set the default Welcome page of this plugin as the Landing page of your website. for old landing page you will have to manually make changes in the Landing page from Layout Editor. Back up page of your current landing page will get created with the name “LP backup from SNS Contests”.')){
          scriptJquery('#sescontest_changelanding-0').prop('checked',true);
      }else if(value == 0){
          //silence
      }else{
          scriptJquery('#sescontest_changelanding-0').removeAttr('checked');
          scriptJquery('#sescontest_changelanding-0').prop('checked',false);	
      }
}
</script>
<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.pluginactivated',0)){ 
 ?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php } ?>
<style> 
	button[disabled] { 
	  background:#bdbdbd; 
	  border-color:#bdbdbd; 
	  cursor:not-allowed; 
  }
</style>