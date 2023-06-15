
<?php if(!$this->is_ajax){ 
echo $this->partial('dashboard/left-bar.tpl', 'sescontest', array(
	'contest' => $this->contest,
      ));	
?>
	<div class="sesbasic_dashboard_content sesbm sesbasic_clearfix">
<?php } ?>
<div class="sesbasic_dashboard_form sesevent_dashboard_account_details">
<ul class="sescontest_dashboard_sub_tabs">
    <li class="<?php echo $this->gateway_type == 'paypal' ? '_active' : ''; ?>"><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'account-details'), 'sescontest_dashboard', true).'?gateway_type=paypal'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-paypal"></i><span><?php echo $this->translate('Paypal Details'); ?></span></a></li>
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvpmnt') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvpmnt.enable.package', 1)){ ?>
        <li class="<?php echo $this->gateway_type == 'stripe' ? '_active' : ''; ?>"><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'account-details'), 'sescontest_dashboard', true).'?gateway_type=stripe'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-stripe"></i><span><?php echo $this->translate('Stripe Details'); ?></span></a></li>
    <?php } ?>
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('epaytm')){ ?>
        <li class="<?php echo $this->gateway_type == 'paytm' ? '_active' : ''; ?>"><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'account-details'), 'sescontest_dashboard', true).'?gateway_type=paytm'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-paytm"></i><span><?php echo $this->translate('Paytm Details'); ?></span></a></li>
    <?php } ?>
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecashfree')){ ?>
        <li class="<?php echo $this->gateway_type == 'cashfree' ? '_active' : ''; ?>"><a href="<?php echo $this->url(array('contest_id' => $this->contest->custom_url,'action'=>'account-details'), 'sescontest_dashboard', true).'?gateway_type=cashfree'; ?>" class="sesbasic_dashboard_nopropagate_content"><i class="fab fa-cc-cashfree"></i><span><?php echo $this->translate('Cashfree Details'); ?></span></a></li>
    <?php } ?>
</ul>
<?php echo $this->form->render() ?>
</div>
<?php if(!$this->is_ajax){ ?>
    </div>
	</div>
</div>
<?php  } ?>
<?php if($this->is_ajax) die; ?>
