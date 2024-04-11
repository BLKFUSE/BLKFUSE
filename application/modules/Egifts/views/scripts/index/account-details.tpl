<?php ?>
	<?php echo $this->partial('index/left-bar.tpl', 'egifts', array("navigation" => $this->navigation)); ?>
		<div class="egifts_dashboard_content">
			<div class="egifts_dashboard_form egifts_dashboard_account_details">
				<ul class="egifts_dashboard_sub_tabs">
					<li class="<?php echo $this->gateway_type == 'paypal' ? '_active' : ''; ?>"><a href="<?php echo $this->url(array('gateway_type'=>"paypal",'action'=>'account-details'), 'egifts_general', true); ?>" class="sesbasic_dashboard_nopropagate_content"><span><?php echo $this->translate('Paypal Details'); ?></span></a></li>
				</ul>
				<?php echo $this->form->render() ?>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
