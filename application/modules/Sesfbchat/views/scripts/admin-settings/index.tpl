<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesfbchat/views/scripts/dismiss_message.tpl';
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/ses-scripts/jscolor/jscolor.js');
?>

<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesfbchat.pluginactivated',0)) { ?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php } ?>

<script type="application/javascript">

scriptJquery(document).on('change','input[type=radio][name=sesfbchat_enable_messenger]',function(){
    var value = scriptJquery(this).val();
    if(value == 1){
        scriptJquery('#sesfbchat_login_text-wrapper').show();
        scriptJquery('#sesfbchat_logout_text-wrapper').show();    
        scriptJquery('#sesfbchat_enable_timing-wrapper').show();
        scriptJquery('#sesfbchat_messenger_icon-wrapper').show();
        scriptJquery('#sesfbchat_starttime-wrapper').show();
        scriptJquery('#sesfbchat_endtime-wrapper').show();        
        scriptJquery('#sesfbchat_theme_color-wrapper').show();
        scriptJquery('#sesfbchat_devices-wrapper').show();
        scriptJquery('#sesfbchat_position-wrapper').show();
        scriptJquery('#sesfbchat_button_size-wrapper').show();
        scriptJquery('#sesfbchat_app_id-wrapper').show();
        scriptJquery('#sesfbchat_page_id-wrapper').show();
    }else{
        scriptJquery('#sesfbchat_login_text-wrapper').hide();
        scriptJquery('#sesfbchat_logout_text-wrapper').hide();    
        scriptJquery('#sesfbchat_enable_timing-wrapper').hide();
        scriptJquery('#sesfbchat_messenger_icon-wrapper').hide();
        scriptJquery('#sesfbchat_starttime-wrapper').hide();
        scriptJquery('#sesfbchat_endtime-wrapper').hide();        
        scriptJquery('#sesfbchat_theme_color-wrapper').hide();
        scriptJquery('#sesfbchat_devices-wrapper').hide();
        scriptJquery('#sesfbchat_position-wrapper').hide();
        scriptJquery('#sesfbchat_button_size-wrapper').hide();
        scriptJquery('#sesfbchat_app_id-wrapper').hide();
        scriptJquery('#sesfbchat_page_id-wrapper').hide();
     }
});

  scriptJquery(document).ready(function(e){
  scriptJquery('.event_calendar_container').hide();
    scriptJquery('input[type=radio][name=sesfbchat_enable_messenger]:checked').trigger('change');    
  });

  scriptJquery(document).on('change','input[type=radio][name=sesfbchat_enable_timing]',function(){
      var value = scriptJquery(this).val();
      if(value == 1){
        
          scriptJquery('#sesfbchat_starttime-wrapper').show();
          scriptJquery('#sesfbchat_endtime-wrapper').show();
      }else{
      
          scriptJquery('#sesfbchat_starttime-wrapper').hide();
          scriptJquery('#sesfbchat_endtime-wrapper').hide();
      }
  });
  scriptJquery(document).ready(function(e){
    scriptJquery('input[type=radio][name=sesfbchat_enable_timing]:checked').trigger('change');    
  });
</script>
