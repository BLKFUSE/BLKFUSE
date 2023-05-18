<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Epaidcontent/views/scripts/dismiss_message.tpl';?>
<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.pluginactivated',0)) { ?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php } else { ?>
	<script type="application/javascript">
	
		scriptJquery(document).ready(function() {
			hideShowSettings("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow', 1); ?>");
		});
		
		function hideShowSettings(value) {
			if(value == 1) {
				scriptJquery('#commision-wrapper').show();
				scriptJquery('#epaidcontent_sesalbum-wrapper').show();
				scriptJquery('#epaidcontent_sesvideo-wrapper').show();
				scriptJquery('#epaidcontent_sesmusic-wrapper').show();
			} else {
				scriptJquery('#commision-wrapper').hide();
				scriptJquery('#epaidcontent_sesalbum-wrapper').hide();
				scriptJquery('#epaidcontent_sesvideo-wrapper').hide();
				scriptJquery('#epaidcontent_sesmusic-wrapper').hide();
			}
		}
	</script>
<?php } ?>
