<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php include APPLICATION_PATH .  '/application/modules/Sesadvancedactivity/views/scripts/dismiss_message.tpl';
?>

<div class='sesbasic-form'>
  <div>
    <?php if( engine_count($this->subnavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subnavigation)->render();?>
      </div>
    <?php endif; ?>
    <div class='sesbasic-form-cont'>
	    <div class='clear'>
			  <div class='settings sesbasic_admin_form'>
			    <?php echo $this->form->render($this); ?>
			  </div>
			</div>
		</div>
  </div>
</div>



<script type="application/javascript">


function enablestickers(value){
  if(value == 1){
    document.getElementById('sesadvancedcomment_stickertitle-wrapper').style.display = 'block';
    document.getElementById('sesadvancedcomment_stickerdescription-wrapper').style.display = 'block';
    document.getElementById('sesadvancedcomment_backgroundimage-wrapper').style.display = 'block';
  }else{
    document.getElementById('sesadvancedcomment_stickertitle-wrapper').style.display = 'none';
    document.getElementById('sesadvancedcomment_stickerdescription-wrapper').style.display = 'none';
    document.getElementById('sesadvancedcomment_backgroundimage-wrapper').style.display = 'none';
  }
}

function showLanguage(value){
  if(value == 1){
    document.getElementById('sesadvancedcomment_language-wrapper').style.display = 'block';		
  }else{
    document.getElementById('sesadvancedcomment_language-wrapper').style.display = 'none';		
  }
}
enablestickers(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.enablestickers', 1); ?>);
showLanguage(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedcomment.translate', 0); ?>);
</script>
