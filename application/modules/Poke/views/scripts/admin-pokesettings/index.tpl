<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package	Poke
 * @copyright  Copyright 2009-2010 BigStep Technologies Pvt. Ltd.
 * @license	http://www.socialengineaddons.com/license/
 * @version	$Id: index.tpl 2010-11-27 9:40:21Z SocialEngineAddOns $
 * @author 	SocialEngineAddOns
 */
?>
<h2><?php echo $this->translate("Pokes Plugin") ?></h2>
<?php if( count($this->navigation) ): ?>
  <div class='tabs'>
    <?php
      // Render the menu
      //->setUlClass()
      echo $this->navigation()->menu()->setContainer($this->navigation)->render()
    ?>
  </div>
<?php endif; ?>
<div class="seaocore_settings_form">
	<div class='settings'>
    <?php if($this->message): ?>
      <ul class="form-notices" >
        <li style="font-size:12px;">
          <?php $base_url = Zend_Controller_Front::getInstance()->getBaseUrl(); ?>
          <?php echo "Your settings have been saved successfully."; ?>
        </li>
      </ul>
    <?php endif; ?>
	  <?php echo $this->form->render($this) ?>
	</div>
</div>