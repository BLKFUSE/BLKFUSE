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
<?php

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
<div class="settings sesbasic_admin_form">
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="application/javascript">
scriptJquery('#dummy-label').remove();
document.getElementById('dummy-element').style.fontSize = '14px';
document.getElementById('dummy-element').style.fontWeight = 'bold';
</script>

<script>
scriptJquery(document).ready(function() {
enablesessocialshare(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.enablesessocialshare', 1); ?>);
});
function enableShare(value) {

  if(value == 1) {
    var enableShareval = scriptJquery('input[name=sesalbum_enablesocialshare]:checked').val();
    var enablesessocialshareval = scriptJquery('input[name=sesalbum_enablesessocialshare]:checked').val();
    scriptJquery('input[name="sesalbum_enablesessocialshare"]').prop('checked',true);
  }
}

function enablesessocialshare(value) {

if(value == 1) {
  var enableShareval = scriptJquery('input[name=sesalbum_enablesocialshare]:checked').val();
  var enablesessocialshareval = scriptJquery('input[name=sesalbum_enablesessocialshare]:checked').val();
  scriptJquery('input[name="sesalbum_enablesocialshare"]').prop('checked',true);
  document.getElementById('sesalbum_enableplusicon-wrapper').style.display = 'block';
  document.getElementById('sesalbum_iconlimit-wrapper').style.display = 'block';
} else {
  document.getElementById('sesalbum_enableplusicon-wrapper').style.display = 'none';
  document.getElementById('sesalbum_iconlimit-wrapper').style.display = 'none';
}

}

</script>
