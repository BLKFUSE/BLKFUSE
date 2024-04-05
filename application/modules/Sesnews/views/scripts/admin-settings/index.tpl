<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesnews/views/scripts/dismiss_message.tpl';?>

<div class='clear'>
  <div class='settings sesbasic_admin_form '>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>

<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.pluginactivated',0)): 
?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php else: ?>

<script type="application/javascript">
  scriptJquery(document).ready(function() {
    showSearchType("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enable.location', 1); ?>");
    enablenewsdesignview("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enablenewsdesignview', 0); ?>");
  });
  function enablenewsdesignview(value) {
    if(value == 1) {
      document.getElementById('sesnews_chooselayout-wrapper').style.display = 'block';
      document.getElementById('sesnews_defaultlayout-wrapper').style.display = 'none';
    } else {
      document.getElementById('sesnews_chooselayout-wrapper').style.display = 'none';
      document.getElementById('sesnews_defaultlayout-wrapper').style.display = 'block';
    }
  }

  function showSearchType(value) {
    if(value == 1){
        document.getElementById('sesnews_search_type-wrapper').style.display = 'block';
    }else{
        document.getElementById('sesnews_search_type-wrapper').style.display = 'none';		
    }
  }
</script>
<?php endif; ?>
<style> 
	button[disabled] { 
	  background:#bdbdbd; 
	  border-color:#bdbdbd; 
	  cursor:not-allowed; 
  }
</style>