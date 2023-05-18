<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: add.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<div class='sesvideo_playlist_popup'>
  <?php if( isset($this->success) ): ?>
  <div class="global_form_popup_message">
    <?php if( $this->success ): ?>
    <p><?php echo $this->message ?></p>
    <br />

    <?php elseif( !empty($this->error) ): ?>
    <pre style="text-align:left"><?php echo $this->error ?></pre>
    <?php else: ?>
    <p><?php echo $this->translate('There was an error processing your request.  Please try again later.') ?></p>
    <?php endif; ?>
  </div>
  <?php return; endif; ?>
  <?php echo $this->form->render($this) ?>
</div>
<script type="text/javascript">
  function updateTextFields() {
    if (document.getElementById('playlist_id').value == 0) {
      document.getElementById('title-wrapper').show();
      document.getElementById('description-wrapper').show();
      document.getElementById('mainphoto-wrapper').show();
			document.getElementById('is_private-wrapper').show();
    } else {
      document.getElementById('title-wrapper').hide();
			document.getElementById('is_private-wrapper').hide();
      document.getElementById('description-wrapper').hide();
      document.getElementById('mainphoto-wrapper').hide();
    }
    parent.Smoothbox.instance.doAutoResize();
  }
  en4.core.runonce.add(updateTextFields);
</script>
