<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: edit-sharetype.tpl 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */

?>
<h2>
  <?php echo $this->translate('Advanced Share Plugin') ?>
</h2>

<?php if (count($this->navigation)): ?>
    <div class='tabs seaocore_admin_tabs clr'>
        <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
    </div>
<?php endif; ?>
<div>
  <img src='<?php echo $this->layout()->staticBaseUrl . "application/modules/Siteshare/externals/images/back.png" ?>' title="Back to previous Page" />
  <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'siteshare', 'controller' => 'manage'),$this->translate("Back to previous Page")); ?>
    
</div>

<div class='clear'>
  <div class='settings siteshare_form'>
    <?php echo $this->form->render($this); ?> 
  </div>
</div>
<script type="text/javascript">
  function setModuleName(module_name){
   window.location.href="<?php echo $this->url(array('module'=>'siteshare','controller'=>'manage', 'action'=>'add-sharetype'),'admin_default',true)?>/module_name/"+module_name;
 }
</script>
