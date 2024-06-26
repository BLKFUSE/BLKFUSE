<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: video.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
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
scriptJquery('.form-description').html('Below, you can configure the settings for the Lightbox for Videos on your website. This settings will work for Videos coming from <a href="http://www.socialenginesolutions.com/social-engine/advanced-videos-channels-plugin/" target="_blank">"Advanced Videos & Channels Plugin"</a> and videos from extensions of other plugins from <a href="http://www.socialenginesolutions.com/socialengine-category/plugins/" target="_blank">SocialEngineSolutions</a>.');
scriptJquery('#dummy-label').remove();
document.getElementById('dummy-element').style.fontSize = '14px';
document.getElementById('dummy-element').style.fontWeight = 'bold';
</script>

<script>
scriptJquery(document).ready(function() {
enablesessocialshare(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.enablesessocialshare', 1); ?>);
});
function enableShare(value) {

  if(value == 1) {
    var enableShareval = scriptJquery('input[name=sesbasic_enablesocialshare]:checked').val();
    var enablesessocialshareval = scriptJquery('input[name=sesbasic_enablesessocialshare]:checked').val();
    scriptJquery('input[name="sesbasic_enablesessocialshare"]').prop('checked',true);
  }
}

function enablesessocialshare(value) {

if(value == 1) {
  var enableShareval = scriptJquery('input[name=sesbasic_enablesocialshare]:checked').val();
  var enablesessocialshareval = scriptJquery('input[name=sesbasic_enablesessocialshare]:checked').val();
  scriptJquery('input[name="sesbasic_enablesocialshare"]').prop('checked',true);
  if(document.getElementById('sesbasic_enableplusicon-wrapper'))
  document.getElementById('sesbasic_enableplusicon-wrapper').style.display = 'flex';
  if(document.getElementById('sesbasic_iconlimit-wrapper'))
  document.getElementById('sesbasic_iconlimit-wrapper').style.display = 'flex';
} else {
  if(document.getElementById('sesbasic_enableplusicon-wrapper'))
  document.getElementById('sesbasic_enableplusicon-wrapper').style.display = 'none';
  if(document.getElementById('sesbasic_iconlimit-wrapper'))
  document.getElementById('sesbasic_iconlimit-wrapper').style.display = 'none';
}

}

</script>
