<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: footer-settings.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php  ?>
<?php include APPLICATION_PATH .  '/application/modules/Sesdating/views/scripts/dismiss_message.tpl';?>
<div class='clear sesbasic_admin_form dating_header_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script>

scriptJquery(document).ready(function() {
  socialmedialinks("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.socialenable', 1); ?>");
});

function socialmedialinks(value){
  if(value == 1){
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
