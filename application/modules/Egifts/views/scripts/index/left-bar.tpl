<?php ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<?php 
	$request = Zend_Controller_Front::getInstance()->getRequest();
	$moduleName = $request->getModuleName();
	$actionName = $request->getActionName();
	$controllerName = $request->getControllerName();
?>
<div class="layout_middle egifts_dashboard_main">
	<div class="generic_layout_container">
		<?php echo $this->content()->renderWidget('egifts.browse-menu'); ?>
	</div>

	<div class="generic_layout_container layout_core_content">
		<div class="egifts_dashboard">
			<div class="egifts_dashboard_tabs">
				<ul class="navigation">
					<li <?php if($actionName == 'payment-requests') { ?> class="active" <?php } ?> ><a class="menu_sns_payment_settings sns_payment_paymentreq" href="<?php echo $this->url(array("action" => 'payment-requests'), 'egifts_general', true); ?>"><?php echo $this->translate("Payment Requested"); ?></a></li>
					<li <?php if($actionName == 'account-details') { ?> class="active" <?php } ?> ><a class="menu_sns_payment_settings sns_payment_paymentactdtl" href="<?php echo $this->url(array("action" => 'account-details'), 'egifts_general', true); ?>"><?php echo $this->translate("Account Details"); ?></a></li>
					<li <?php if($actionName == 'payment-transaction') { ?> class="active" <?php } ?>><a class="menu_sns_payment_settings sns_payment_receivedpamt" href="<?php echo $this->url(array("action" => 'payment-transaction'), 'egifts_general', true); ?>"><?php echo $this->translate("Payment Received"); ?></a></li>
				</ul>
			</div>
