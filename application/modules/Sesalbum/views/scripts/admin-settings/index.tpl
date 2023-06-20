<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesalbum
 * @package    Sesalbum
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-06-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<h2>
  <?php echo $this->translate("Advanced Photos & Albums Plugin") ?>
</h2>
<div class="sesbasic_nav_btns">
    <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'help'),'admin_default',true); ?>" class="request-btn">Help</a>
</div>
<?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic'))
	{
		include APPLICATION_PATH .  '/application/modules/Sesbasic/views/scripts/_mapKeyTip.tpl'; 
	} else { ?>
		 <div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other featrures to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
	<?php } ?>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<?php if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.pluginactivated')) { ?>
<?php $flushData = Engine_Api::_()->sesalbum()->getFlushPhotoData();
   if($flushData >0){ ?>
  <div class="sesalbum_warning">
      You have <?php echo $flushData; ?> unmapped photos <a href="<?php echo $this->url(array('module' => 'sesalbum', 'controller' => 'settings', 'action' => 'flush-photo'),'admin_default',true); ?>">click here</a> to remove them.
  </div>
<?php  } } ?>
<div class="settings sesbasic_admin_form">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="text/javascript">
  function confirmChangeLandingPage(value) {
    if(value == 1 && !confirm('Are you sure want to set the default Welcome page of this plugin as the Landing page of your website. for old landing page you will have to manually make changes in the Landing page from Layout Editor. Back up page of your current landing page will get created with the name “LP backup from SNS Pages”.')){
      scriptJquery('#sespage_changelanding-0').prop('checked',true);
    }else if(value == 0){
        //silence
    }else{
      scriptJquery('#sespage_changelanding-0').removeAttr('checked');
      scriptJquery('#sespage_changelanding-0').prop('checked',false);	
    }
  }
</script>
<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.pluginactivated',0)){ 
 ?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php } ?>


<script type="text/javascript">
function rating_album(value){
		if(value == 1){
			document.getElementById('sesalbum_ratealbum_own-wrapper').style.display = 'block';		
			document.getElementById('sesalbum_ratealbum_again-wrapper').style.display = 'block';
			document.getElementById('sesalbum_ratealbum_show-wrapper').style.display = 'none';	
		}else{
			document.getElementById('sesalbum_ratealbum_show-wrapper').style.display = 'block';
			document.getElementById('sesalbum_ratealbum_own-wrapper').style.display = 'none';
			document.getElementById('sesalbum_ratealbum_again-wrapper').style.display = 'none';
		}
}
function show_position(value){
	if(value == 1){
			document.getElementById('sesalbum_position_watermark-wrapper').style.display = 'block';
	}else{
			document.getElementById('sesalbum_position_watermark-wrapper').style.display = 'none';		
	}
}
// if(document.querySelector('[name="sesalbum_watermark_enable"]:checked').value == 0){
// 	document.getElementById('sesalbum_position_watermark-wrapper').style.display = 'none';	
// }else{
// 		document.getElementById('sesalbum_position_watermark-wrapper').style.display = 'block';
// }
function rating_photo(value){
		if(value == 1){
			document.getElementById('sesalbum_ratephoto_show-wrapper').style.display = 'none';
			document.getElementById('sesalbum_ratephoto_own-wrapper').style.display = 'block';
			document.getElementById('sesalbum_ratephoto_again-wrapper').style.display = 'block';			
		}else{
			document.getElementById('sesalbum_ratephoto_show-wrapper').style.display = 'block';
			document.getElementById('sesalbum_ratephoto_own-wrapper').style.display = 'none';
			document.getElementById('sesalbum_ratephoto_again-wrapper').style.display = 'none';	
		}
}
// if(document.querySelector('[name="sesalbum_album_rating"]:checked').value == 0){
// 	document.getElementById('sesalbum_ratealbum_own-wrapper').style.display = 'none';		
// 	document.getElementById('sesalbum_ratealbum_again-wrapper').style.display = 'none';
// 	document.getElementById('sesalbum_ratealbum_show-wrapper').style.display = 'block';
// }else{
// 	document.getElementById('sesalbum_ratealbum_show-wrapper').style.display = 'none';
// }
// if(document.querySelector('[name="sesalbum_photo_rating"]:checked').value == 0){
// 			document.getElementById('sesalbum_ratephoto_own-wrapper').style.display = 'none';	
// 			document.getElementById('sesalbum_ratephoto_again-wrapper').style.display = 'none';	
// 			document.getElementById('sesalbum_ratephoto_show-wrapper').style.display = 'block';	
// }else{
// 			document.getElementById('sesalbum_ratephoto_show-wrapper').style.display = 'none';	
// }
scriptJquery(document).ready(function() {
    if(!<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enable.location', '1') ;?>)
    document.getElementById('sesalbum_search_type-wrapper').style.display = 'none';
  });
function enableLocationAlbumPhoto(value) {
	if(value == 1)
	  document.getElementById('sesalbum_search_type-wrapper').style.display = 'block';
	else 
	  document.getElementById('sesalbum_search_type-wrapper').style.display = 'none';
	}


</script>
