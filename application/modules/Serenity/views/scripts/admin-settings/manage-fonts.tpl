<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Serenity
 * @copyright  Copyright 2006-2022 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: manage-fonts.tpl 2022-06-20
 */

?>

<h2><?php echo $this->translate('Serenity Theme') ?></h2>

<?php if( engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
  </div>
<?php endif; ?>

<div class='clear'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>

<script>
  scriptJquery(document).ready(function() {
    usegooglefont('<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('serenity.googlefonts', 1);?>');
  });
  
  function usegooglefont(value) {
    if(value == 1) {
      if(document.getElementById('serenity_bodygrp'))
        document.getElementById('serenity_bodygrp').style.display = 'none';
      if(document.getElementById('serenity_headinggrp'))
        document.getElementById('serenity_headinggrp').style.display = 'none';
      if(document.getElementById('serenity_mainmenugrp'))
        document.getElementById('serenity_mainmenugrp').style.display = 'none';
      if(document.getElementById('serenity_tabgrp'))
        document.getElementById('serenity_tabgrp').style.display = 'none';
      if(document.getElementById('serenity_googlebodygrp'))
        document.getElementById('serenity_googlebodygrp').style.display = 'block';
      if(document.getElementById('serenity_googleheadinggrp'))
        document.getElementById('serenity_googleheadinggrp').style.display = 'block';
      if(document.getElementById('serenity_googlemainmenugrp'))
        document.getElementById('serenity_googlemainmenugrp').style.display = 'block';
      if(document.getElementById('serenity_googletabgrp'))
        document.getElementById('serenity_googletabgrp').style.display = 'block';
    } else {
      if(document.getElementById('serenity_bodygrp'))
        document.getElementById('serenity_bodygrp').style.display = 'block';
      if(document.getElementById('serenity_headinggrp'))
        document.getElementById('serenity_headinggrp').style.display = 'block';
      if(document.getElementById('serenity_mainmenugrp'))
        document.getElementById('serenity_mainmenugrp').style.display = 'block';
      if(document.getElementById('serenity_tabgrp'))
        document.getElementById('serenity_tabgrp').style.display = 'block';
      if(document.getElementById('serenity_googlebodygrp'))
        document.getElementById('serenity_googlebodygrp').style.display = 'none';
      if(document.getElementById('serenity_googleheadinggrp'))
        document.getElementById('serenity_googleheadinggrp').style.display = 'none';
      if(document.getElementById('serenity_googlemainmenugrp'))
        document.getElementById('serenity_googlemainmenugrp').style.display = 'none';
      if(document.getElementById('serenity_googletabgrp'))
        document.getElementById('serenity_googletabgrp').style.display = 'none';
    }
  }
</script>
