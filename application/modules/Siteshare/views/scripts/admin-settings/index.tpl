<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: index.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

?>
<script type="text/javascript">
  window.addEvent('domready', function () {
    enableBookmark(<?php echo $this->enabled; ?>);
  });

  function enableBookmark(option) {
    if(option == 1) {
      $('siteshare_share_socialbutton_layout-wrapper').style.display = 'block';
      $('siteshare_share_public_enabled-wrapper').style.display = 'block';
    } else {
      $('siteshare_share_socialbutton_layout-wrapper').style.display = 'none';
      $('siteshare_share_public_enabled-wrapper').style.display = 'none';
    }
  }
</script>

<h2>
  <?php echo $this->translate('Advanced Share Plugin') ?>
</h2>

<?php if (count($this->navigation)): ?>
    <div class='tabs seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<div class='clear'>
  <div class='settings siteshare_form'>
    <?php echo $this->form->render($this); ?> 
  </div>
</div>
