<?php
/**
* SocialEngine
*
* @category   Application_Extensions
* @package    Siteshare
* @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
* @license    http://www.socialengineaddons.com/license/
* @version    $Id: faq.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
* @author     SocialEngineAddOns
*/

?>
<h2>
  <?php echo $this->translate('Advanced Share Plugin') ?>
</h2>

<?php if (count($this->navigation)): ?>
    <div class='tabs seaocore_admin_tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>

<?php include_once APPLICATION_PATH .
        '/application/modules/Siteshare/views/scripts/admin-settings/faq_help.tpl';
?>

 