<?php include APPLICATION_PATH .  '/application/modules/Sescontest/views/scripts/dismiss_message.tpl';?>
<div class='sesbasic-form sesbasic-categories-form'>
  <div>
    <?php if(engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php echo $this->navigation()->menu()->setContainer($this->subNavigation)->render();?>
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
<div class="sesbasic_waiting_msg_box" style="display:none;">
	<div class="sesbasic_waiting_msg_box_cont">
    <?php echo $this->translate("Please wait.. It might take some time to activate plugin."); ?>
    <i></i>
  </div>
</div>
<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.pluginactivated',0)) { ?>
  <?php  ?>
	<script type="application/javascript">
  	scriptJquery('.global_form').submit(function(e){
			scriptJquery('.sesbasic_waiting_msg_box').show();
		});
  </script>
<?php } ?>
<script type="application/javascript">
  function enable_package(value){
    if(value == 1){
      document.getElementById('sescontestpackage_package_info-wrapper').style.display = 'flex';	
      document.getElementById('sescontestpackage_payment_mod_enable-wrapper').style.display = 'flex';	
    }else{
      document.getElementById('sescontestpackage_package_info-wrapper').style.display = 'none';	
      document.getElementById('sescontestpackage_payment_mod_enable-wrapper').style.display = 'none';		
    }
  }
  enable_package(document.getElementById('sescontestpackage_enable_package').value);
</script>
