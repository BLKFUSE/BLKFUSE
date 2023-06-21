<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Egifts
 * @package    Egifts
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Egifts/views/scripts/dismiss_message.tpl';?>

<script type="text/javascript">
  var fetchLevelSettings =function(level_id){
    window.location.href= en4.core.baseUrl+'admin/egifts/level/index/id/'+level_id;
  }
  
  scriptJquery(document).ready(function() {
    hideShow('<?php echo $this->egifts_cashgifts; ?>');
	});
  
  function hideShow(value) {
		if(value == 1) {
			scriptJquery('#egifts_admcosn-wrapper').show();
			scriptJquery('#egifts_commival-wrapper').show();
			scriptJquery('#egifts_threamt-wrapper').show();
		} else {
			scriptJquery('#egifts_admcosn-wrapper').hide();
			scriptJquery('#egifts_commival-wrapper').hide();
			scriptJquery('#egifts_threamt-wrapper').hide();
		}
  }	
</script>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
