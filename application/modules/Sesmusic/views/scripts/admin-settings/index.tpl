<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $settings = Engine_Api::_()->getApi('settings', 'core');
$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/ses-scripts/jscolor/jscolor.js');

?>
<script type="text/javascript">

  scriptJquery(document).ready(function() {
    if(document.getElementById('sesmusic_uploadoption-wrapper'))
      document.getElementById('sesmusic_uploadoption-wrapper').style.display = 'none';
    checkUpload("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer'); ?>");
  });
  
  function showPopUp() {
    Smoothbox.open('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'admin-settings', 'action'=>'showpopup', 'format' => 'smoothbox'), 'default' , true)); ?>');
    parent.Smoothbox.close;
  }
  
	
  function confirmChangeLandingPage(value){
      if(value == 1 && !confirm('Are you sure want to set the default Welcome page of this plugin as the Landing page of your website. for old landing page you will have to manually make changes in the Landing page from Layout Editor. Back up page of your current landing page will get created with the name “LP backup from SNS Professional Music”.')){
          scriptJquery('#sesmusic_changelanding-0').prop('checked',true);
      }else if(value == 0){
          //silence
      }else{
          scriptJquery('#sesmusic_changelanding-0').removeAttr('checked');
          scriptJquery('#sesmusic_changelanding-0').prop('checked',false);	
      }
}


	
  function checkUpload(value) {
    if (value == 'both' || value == 'soundCloud') {
      if (document.getElementById('sesmusic_scclientid-wrapper'))
        document.getElementById('sesmusic_scclientid-wrapper').style.display = 'flex';
      if (document.getElementById('sesmusic_scclientscreatid-wrapper'))
        document.getElementById('sesmusic_scclientscreatid-wrapper').style.display = 'flex';
    } else {
      if (document.getElementById('sesmusic_scclientid-wrapper'))
        document.getElementById('sesmusic_scclientid-wrapper').style.display = 'none';
      if (document.getElementById('sesmusic_scclientscreatid-wrapper'))
        document.getElementById('sesmusic_scclientscreatid-wrapper').style.display = 'none';
    }
  }
</script>
<?php include APPLICATION_PATH .  '/application/modules/Sesmusic/views/scripts/dismiss_message.tpl';?>

<div class="sesbasic-form">
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php
        echo $this->navigation()->menu()->setContainer($this->subNavigation)->render()
        ?>
      </div>
    <?php endif; ?>
    <div class='sesbasic-form-cont'>
      <div class='settings sesbasic_admin_form'>
        <?php echo $this->form->render($this); ?>
      </div>
    </div>
  </div>
</div>

<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>

<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.pluginactivated',0)): 
?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php endif; ?>
<style> 
	button[disabled] { 
	  background:#bdbdbd; 
	  border-color:#bdbdbd; 
	  cursor:not-allowed; 
  }
</style>