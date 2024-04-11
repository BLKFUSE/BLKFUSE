<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: header-settings.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>
<div class='tabs'>
  <ul class="navigation">
    <li class="active">
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'header-settings'), $this->translate('Header Settings')) ?>
    </li>
    <li>
      <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesdating', 'controller' => 'manage', 'action' => 'index'), $this->translate('Main Menu Icons')) ?>
    </li>
  </ul>
</div>
<div class='clear sesbasic_admin_form dating_header_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script>

scriptJquery(document).ready(function() {
  showSocialShare("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.show.socialshare', 1); ?>");
  showHidePanel("<?php echo Engine_Api::_()->sesdating()->getContantValueXML('sesdating_sidepanel_effect'); ?>");
  showHeaderDesigns("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.header.design', 1); ?>");
});

function showHidePanel(value) {
  if(value == 1) {
    if(document.getElementById('sesdating_sidepanel_showhide-wrapper'))
      document.getElementById('sesdating_sidepanel_showhide-wrapper').style.display = 'none';
  } else if(value == 2) {
    if(document.getElementById('sesdating_sidepanel_showhide-wrapper'))
      document.getElementById('sesdating_sidepanel_showhide-wrapper').style.display = 'flex';
  }
}

function showHeaderDesigns(value) {

  if(value == 1) {
    if(document.getElementById('sesdating_menu_img-wrapper'))
      document.getElementById('sesdating_menu_img-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_sidepanel_effect-wrapper'))
      document.getElementById('sesdating_sidepanel_effect-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_menuinformation_img-wrapper'))
      document.getElementById('sesdating_menuinformation_img-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_sidepanel_showhide-wrapper'))
      document.getElementById('sesdating_sidepanel_showhide-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_limit-wrapper'))
      document.getElementById('sesdating_limit-wrapper').style.display = 'flex';
    if(document.getElementById('sesdating_moretext-wrapper'))
      document.getElementById('sesdating_moretext-wrapper').style.display = 'flex';
  } else {
    if(document.getElementById('sesdating_menu_img-wrapper'))
      document.getElementById('sesdating_menu_img-wrapper').style.display = 'flex';
    if(document.getElementById('sesdating_sidepanel_effect-wrapper'))
      document.getElementById('sesdating_sidepanel_effect-wrapper').style.display = 'flex';   
    if(document.getElementById('sesdating_menuinformation_img-wrapper'))
      document.getElementById('sesdating_menuinformation_img-wrapper').style.display = 'flex';
    if(document.getElementById('sesdating_sidepanel_effect').value == 1){
      if(document.getElementById('sesdating_sidepanel_showhide-wrapper'))
        document.getElementById('sesdating_sidepanel_showhide-wrapper').style.display = 'none';
    } else {
      if(document.getElementById('sesdating_sidepanel_showhide-wrapper'))
        document.getElementById('sesdating_sidepanel_showhide-wrapper').style.display = 'flex';
    }
    if(document.getElementById('sesdating_limit-wrapper'))
      document.getElementById('sesdating_limit-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_moretext-wrapper'))
      document.getElementById('sesdating_moretext-wrapper').style.display = 'none';
  }
}

function showSocialShare(value) {

  if(value == 1) {
    if(document.getElementById('sesdating_facebookurl-wrapper'))
      document.getElementById('sesdating_facebookurl-wrapper').style.display = 'flex';
    if(document.getElementById('sesdating_googleplusurl-wrapper'))
      document.getElementById('sesdating_googleplusurl-wrapper').style.display = 'flex';
    if(document.getElementById('sesdating_twitterurl-wrapper'))
      document.getElementById('sesdating_twitterurl-wrapper').style.display = 'flex';
    if(document.getElementById('sesdating_pinteresturl-wrapper'))
      document.getElementById('sesdating_pinteresturl-wrapper').style.display = 'flex';
  } else {
    if(document.getElementById('sesdating_facebookurl-wrapper'))
      document.getElementById('sesdating_facebookurl-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_googleplusurl-wrapper'))
      document.getElementById('sesdating_googleplusurl-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_twitterurl-wrapper'))
      document.getElementById('sesdating_twitterurl-wrapper').style.display = 'none';
    if(document.getElementById('sesdating_pinteresturl-wrapper'))
      document.getElementById('sesdating_pinteresturl-wrapper').style.display = 'none';
  }
}
</script>
