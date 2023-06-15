<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: entrycreate.tpl  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<?php $enableDescription = Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.show.entrydescription', 1);?>
<?php $enablePhoto = Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.text.entryphoto', 1);?>
<?php $enableMusicPhoto = Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.music.entryphoto', 1);?>
<?php $enableVideoPhoto = Engine_Api::_()->getApi('settings','core')->getSetting('sescontest.video.entryphoto', 1);?>
<script type="text/javascript">
  showMandatory('<?php echo $enableDescription;?>');
  function showMandatory(value) {
    if(value == 1)
      scriptJquery('#sescontest_entrydescription_required-wrapper').show();
    else
      scriptJquery('#sescontest_entrydescription_required-wrapper').hide();
  }
  showMainPhoto('<?php echo $enablePhoto;?>');
  function showMainPhoto(value) {
    if(value == 1)
      scriptJquery('#sescontest_text_entryphotorequired-wrapper').show();
    else
      scriptJquery('#sescontest_text_entryphotorequired-wrapper').hide();
  }
  showMusicMainPhoto('<?php echo $enableMusicPhoto;?>');
  function showMusicMainPhoto(value) {
    if(value == 1)
      scriptJquery('#sescontest_music_entryphotorequired-wrapper').show();
    else
      scriptJquery('#sescontest_music_entryphotorequired-wrapper').hide();
  }
  showVideoMainPhoto('<?php echo $enableVideoPhoto;?>');
  function showVideoMainPhoto(value) {
    if(value == 1)
      scriptJquery('#sescontest_video_entryphotorequired-wrapper').show();
    else
      scriptJquery('#sescontest_video_entryphotorequired-wrapper').hide();
  }
</script>

