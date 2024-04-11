<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesbasic/views/scripts/dismiss_message.tpl';?>
<h2 class="page_heading"><?php echo $this->translate('SocialNetworking.Solutions (SNS) Basic Required Plugin'); ?></h2>
<?php if (engine_count($this->navigation)): ?>
  <div class='tabs'>
    <?php
    // Render the menu
    echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>

<script type="text/javascript">
  var fetchLevelSettings = function(level_id) {
    window.location.href = en4.core.baseUrl + 'admin/sesbasic/photolightbox/index/id/' + level_id;
    //alert(level_id);
  }
</script>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
		<?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class='settings sesbasic-form-cont sesbasic_admin_form'>
      <?php echo $this->form->render($this) ?>
    </div>
	</div>
</div>
<script type="application/javascript">

scriptJquery('h4').hide();
scriptJquery('<p class="form-description"></p>').insertBefore('h4');
scriptJquery('.form-description').html('These settings are applied on a per member level basis. Start by selecting the member level you want to modify, then adjust the settings for that level below.');

function setVideoType(value){
	if(value == 1) {
   document.getElementById('video_approve_type-wrapper').style.display = 'block';		
	}else{
		 document.getElementById('video_approve_type-wrapper').style.display = 'none';	
	}
}
if(document.getElementById('video_approve').value == 1) {
   document.getElementById('video_approve_type-wrapper').style.display = 'block';		
}else{
	 document.getElementById('video_approve_type-wrapper').style.display = 'none';	
}
</script>
