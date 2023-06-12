<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesandroidapp
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: create-graphic.tpl 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php
      $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesapi/externals/scripts/jscolor/jscolor.js');
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesandroidapp/views/scripts/dismiss_message.tpl';?>
<h2>
  <?php echo $this->translate("Native Android Mobile App") ?>
</h2>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
<div class="sesandroidapp_search_result">
	<?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'sesandroidapp', 'controller' => 'slideshow', 'action' => 'index'), $this->translate("Back to Manage Photo") , array('class'=>'sesandroidapp_icon_back buttonlink')); ?>
</div>
<div class='clear'>
  <div class='settings sesbasic_admin_form'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<script type="application/javascript">
scriptJquery('#type').change(function(){
  if(scriptJquery(this).val() == 1){
    scriptJquery('#video-wrapper').show(); 
    scriptJquery('#title-wrapper').hide(); 
    scriptJquery('#title_color-wrapper').hide(); 
    scriptJquery('#description-wrapper').hide();  
    scriptJquery('#description_color-wrapper').hide();
  }else{
    scriptJquery('#video-wrapper').hide(); 
    scriptJquery('#title-wrapper').show(); 
    scriptJquery('#title_color-wrapper').show(); 
    scriptJquery('#description-wrapper').show();  
    scriptJquery('#description_color-wrapper').show(); 
  }
});
scriptJquery('#type').trigger('change');
</script>
<style type="text/css">
.settings div.form-label label.required:after{
	content:" *";
	color:#f00;
}
</style>
