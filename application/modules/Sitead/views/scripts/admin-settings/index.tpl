<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions 
 * @package    Sitead
 * @copyright  Copyright 2009-2011 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2011-02-16 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
?>

<!--<script type="text/javascript">
  window.addEvent('domready', function() {
    $('sitead_ad_type-0').checked = 'checked';
    $('sitead_ad_type-0').disabled = 'disabled';
  });
</script>-->


<h2><?php echo $this->translate("Advertisements, Community Ads & Marketing Campaigns Plugin") ?></h2>

<?php if( count($this->navigation) ): ?>
<div class='sitead_admin_tabs'>
  <?php
  // Render the menu
  //->setUlClass()
  echo $this->navigation()->menu()->setContainer($this->navigation)->render()
  ?>
</div>
<?php endif; ?>
<?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/_upgrade_messages.tpl'; ?>
<?php   $newStyleWidthUpdate = Engine_Api::_()->getApi('settings', 'core')->getSetting('ad.block.widthupdatefile', 1);
 if (empty($newStyleWidthUpdate)): ?>
<div class="tip">
  <span>
    <?php echo $this->translate('Note: If you want to minimize the size of Advertisements / Advertisements, Community Ads & Marketing Campaigns Plugin\'s CSS, then please give write permission (chmod 777) to the file "/application/modules/Sitead/externals/styles/style.css".');?>
  </span>
</div>
<?php endif; ?>
<div class='seaocore_settings_form'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>