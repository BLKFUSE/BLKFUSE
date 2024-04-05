<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesthought/views/scripts/dismiss_message.tpl';?>

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
<script>
  scriptJquery(document).ready(function() {
    showCat('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.enablecategory', 1);?>');
  });
  function showCat(value) {
    if(value == 1) {
      if(document.getElementById('sesthought_categoryrequried-wrapper'))
        document.getElementById('sesthought_categoryrequried-wrapper').style.display = 'flex';
    } else {
      if(document.getElementById('sesthought_categoryrequried-wrapper'))
        document.getElementById('sesthought_categoryrequried-wrapper').style.display = 'none';
    }
  }
</script>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesthought.pluginactivated',0)) { ?>
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