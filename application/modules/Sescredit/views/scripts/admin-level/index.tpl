<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sescredit/views/scripts/dismiss_message.tpl';?>

<script type="text/javascript">
  
  var fetchLevelSettings =function(level_id){
    window.location.href= en4.core.baseUrl+'admin/sescredit/level/index/id/'+level_id;
  }
  
  scriptJquery(document).ready(function() {
    hideShow('<?php echo $this->sescredit_cashcredit; ?>');
	});
  
  function hideShow(value) {
		if(value == 1) {
			scriptJquery('#sescredit_admcosn-wrapper').show();
			scriptJquery('#sescredit_commival-wrapper').show();
			scriptJquery('#sescredit_threamt-wrapper').show();
		} else {
			scriptJquery('#sescredit_admcosn-wrapper').hide();
			scriptJquery('#sescredit_commival-wrapper').hide();
			scriptJquery('#sescredit_threamt-wrapper').hide();
		}
  }	
</script>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
