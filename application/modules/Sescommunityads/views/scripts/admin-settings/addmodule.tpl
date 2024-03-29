<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: addmodule.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>

<?php include APPLICATION_PATH .  '/application/modules/Sescommunityads/views/scripts/dismiss_message.tpl';?>

<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php //echo $this->form->render($this); ?>
  </div>
</div>


<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sescommunityads', 'controller' => 'settings', 'action' => 'modules'), $this->translate("Back to Modules"), array('class'=>'sesbasic_icon_back buttonlink')) ?>
<br style="clear:both;" /><br />
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="text/javascript">
 function changemodule(modulename) {
   var type = '<?php echo $this->type ?>';
   window.location.href="<?php echo $this->url(array('module'=>'sescommunityads','controller'=>'settings', 'action'=>'addmodule'),'admin_default',true)?>/module_name/"+modulename;
 }
</script>
<style type="text/css">
.sesbasic_back_icon{
  background-image: url(<?php echo $this->layout()->staticBaseUrl ?>application/modules/Core/externals/images/back.png);
}
</style>