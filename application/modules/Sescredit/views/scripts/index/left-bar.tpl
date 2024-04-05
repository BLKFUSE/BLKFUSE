<?php ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescredit/externals/styles/styles.css'); ?>
<?php 
	$request = Zend_Controller_Front::getInstance()->getRequest();
	$moduleName = $request->getModuleName();
	$actionName = $request->getActionName();
	$controllerName = $request->getControllerName();
?>
<div class="layout_middle sescredit_dashboard_main">
	<div class="generic_layout_container">
		<?php echo $this->content()->renderWidget('sescredit.browse-menu'); ?>
	</div>

	<div class="generic_layout_container layout_core_content">
		<div class="sescredit_dashboard">
			<div class="sescredit_dashboard_tabs">
				<ul class="navigation">
					<li <?php if($actionName == 'payment-requests') { ?> class="active" <?php } ?> ><a class="menu_sns_payment_settings sns_payment_paymentreq" href="<?php echo $this->url(array("action" => 'payment-requests'), 'sescredit_general', true); ?>"><?php echo $this->translate("Payment Requested"); ?></a></li>
					<li <?php if($actionName == 'account-details') { ?> class="active" <?php } ?> ><a class="menu_sns_payment_settings sns_payment_paymentactdtl" href="<?php echo $this->url(array("action" => 'account-details'), 'sescredit_general', true); ?>"><?php echo $this->translate("Account Details"); ?></a></li>
					<li <?php if($actionName == 'payment-transaction') { ?> class="active" <?php } ?>><a class="menu_sns_payment_settings sns_payment_receivedpamt" href="<?php echo $this->url(array("action" => 'payment-transaction'), 'sescredit_general', true); ?>"><?php echo $this->translate("Payment Received"); ?></a></li>
				</ul>
			</div>
