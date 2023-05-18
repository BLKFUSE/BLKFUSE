<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sespage
 * @package    Sespage
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: field-create.tpl  2018-04-23 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php if( $this->form ): ?>

  <div id="create-field">
    <?php echo $this->form->render($this) ?>
  </div>

  <?php if( !empty($this->formAlt) ): ?>
    <div id="map-field" style="display: none;">
      <?php echo $this->formAlt->render($this) ?>
    </div>
  <?php endif; ?>
<?php else: ?>
  <div class="global_form_popup_message">
    <?php echo $this->translate("Your changes have been saved.") ?>
  </div>
  <script type="text/javascript">
    parent.onFieldCreate(
      <?php echo Zend_Json::encode($this->field) ?>,
      <?php echo Zend_Json::encode($this->htmlArr) ?>
    );
    setTimeout(function() { parent.Smoothbox.close(); },1000);
  </script>
<?php endif; ?>
