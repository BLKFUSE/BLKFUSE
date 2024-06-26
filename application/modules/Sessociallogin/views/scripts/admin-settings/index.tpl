<?php



/**

 * SocialEngineSolutions

 *

 * @category   Application_Sessociallogin

 * @package    Sessociallogin

 * @copyright  Copyright 2015-2016 SocialEngineSolutions

 * @license    http://www.socialenginesolutions.com/license/

 * @version    $Id: index.tpl 2017-07-04 00:00:00 SocialEngineSolutions $

 * @author     SocialEngineSolutions

 */



?>

<?php include APPLICATION_PATH .  '/application/modules/Sessociallogin/views/scripts/dismiss_message.tpl'; ?>



<div class="settings sesbasic_admin_form sesact_global_setting">

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

<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sessociallogin.pluginactivated',0)){ ?>

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