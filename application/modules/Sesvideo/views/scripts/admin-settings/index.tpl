<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sesvideo/views/scripts/dismiss_message.tpl';?>
<div class='clear sesbasic_admin_form'>
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
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.pluginactivated',0)){ 
 ?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php }else{ ?>
<script type="application/javascript">
  scriptJquery(document).ready(function() {
      rating_video("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('video.video.rating', 1); ?>");
      checkChange("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.chanel', 0); ?>");
			//rating_chanel("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('video.chanel.rating', 1); ?>");
  });
  
  
	function rating_video(value){
		if(value == 1){
      if(document.getElementById('video_ratevideo_own-wrapper'))
      	document.getElementById('video_ratevideo_own-wrapper').style.display = 'flex';
      if(document.getElementById('video_ratevideo_again-wrapper'))
        document.getElementById('video_ratevideo_again-wrapper').style.display = 'flex';
      if(document.getElementById('video_ratevideo_show-wrapper'))
        document.getElementById('video_ratevideo_show-wrapper').style.display = 'none';	
		} else{
      if(document.getElementById('video_ratevideo_show-wrapper'))
        document.getElementById('video_ratevideo_show-wrapper').style.display = 'flex';
      if(document.getElementById('video_ratevideo_own-wrapper'))
        document.getElementById('video_ratevideo_own-wrapper').style.display = 'none';
      if(document.getElementById('video_ratevideo_again-wrapper'))
        document.getElementById('video_ratevideo_again-wrapper').style.display = 'none';
		}
	} 
  
	function rating_chanel(value){
		if(value == 1){
			document.getElementById('video_chanel_rating-wrapper').style.display = 'flex';		
			document.getElementById('video_ratechanel_own-wrapper').style.display = 'flex';		
			document.getElementById('video_ratechanel_again-wrapper').style.display = 'flex';
			document.getElementById('video_ratechanel_show-wrapper').style.display = 'none';	
		} else{
			document.getElementById('video_chanel_rating-wrapper').style.display = 'flex';
			document.getElementById('video_ratechanel_show-wrapper').style.display = 'flex';
			document.getElementById('video_ratechanel_own-wrapper').style.display = 'none';
			document.getElementById('video_ratechanel_again-wrapper').style.display = 'none';
		}
	} 

	function checkChange(value){
		if(value == 1){
			document.getElementById('video_enable_chaneloption-wrapper').style.display = 'flex';
			document.getElementById('video_chanels_manifest-wrapper').style.display = 'flex';	
			document.getElementById('video_chanel_manifest-wrapper').style.display = 'flex';	
			document.getElementById('videochanel_category_enable-wrapper').style.display = 'flex';	
			document.getElementById('video_enable_chaneloption-wrapper').style.display = 'flex';	
			document.getElementById('video_enable_subscription-wrapper').style.display = 'flex';
		} else{
			document.getElementById('video_enable_chaneloption-wrapper').style.display = 'none';
			document.getElementById('video_chanels_manifest-wrapper').style.display = 'none';	
			document.getElementById('video_chanel_manifest-wrapper').style.display = 'none';	
			document.getElementById('videochanel_category_enable-wrapper').style.display = 'none';	
			document.getElementById('video_enable_chaneloption-wrapper').style.display = 'none';	
			document.getElementById('video_enable_subscription-wrapper').style.display = 'none';
		}
	}

  function rating_artist(value) {
    if (value == 1) {
      //document.getElementById('sesvideo_rateartist_own-wrapper').style.display = 'flex';
      document.getElementById('sesvideo_rateartist_again-wrapper').style.display = 'flex';
      document.getElementById('sesvideo_rateartist_show-wrapper').style.display = 'none';
    } else {
      document.getElementById('sesvideo_rateartist_show-wrapper').style.display = 'flex';
      //document.getElementById('sesvideo_rateartist_own-wrapper').style.display = 'none';
      document.getElementById('sesvideo_rateartist_again-wrapper').style.display = 'none';
    }
  }
</script>
<?php  } ?>
<style> 
	button[disabled] { 
	  background:#bdbdbd; 
	  border-color:#bdbdbd; 
	  cursor:not-allowed; 
  }
</style>