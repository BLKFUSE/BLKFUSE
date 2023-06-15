<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: gateway.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
$creditSession = new Zend_Session_Namespace('sescredit_redeem_purchase');
$creditCheckout = new Zend_Session_Namespace('sescredit_points');
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<?php $settings = Engine_Api::_()->getApi('settings', 'core');?>
<?php $currentCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency'); ?>

<div class="layout_middle sesbasic_bxs">
	<div class="generic_layout_container">
    <div class="egifts_payment_process">
      <form method="get" action="<?php echo $this->escape($this->url(array('action' => 'process'))) ?>" enctype="application/x-www-form-urlencoded">
      	<div class="egifts_payment_process_heading"><?php echo $this->translate("Make payment for gift");?></div>
        <div class="egifts_payment_process_content">
					<input type="hidden" name="gateway_id" id="gateway_id" value="" />
        	<div class="total_price">
          	<span><?php echo $this->translate("Total Price"); ?></span>
            <?php echo Engine_Api::_()->egifts()->getCurrencyPrice(round($this->itemPrice,2),$currentCurrency); ?>
          </div>
          <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit')) { ?>
            <div class="egifts_payment_process_credit">
              <?php  echo $this->partial('apply_credit.tpl','sescredit',array('id'=>$this->item->getIdentity(),'moduleName'=>'egifts','item_price'=>$this->itemPrice,'item_id'=>$this->item->getIdentity())); ?>
            </div>
          <?php } ?>
          <div id="buttons-wrapper" class="egifts_payment_process_buttons">
            <?php foreach($this->gateways as $gatewayInfo ):
              $gateway = $gatewayInfo['gateway'];
              $plugin = $gatewayInfo['plugin'];
              $first = ( !isset($first) ? true : false );
              $gatewayObject = $gateway->getGateway();
              
              $supportedCurrencies = $gatewayObject->getSupportedCurrencies();
              if(!engine_in_array($currentCurrency,$supportedCurrencies))
                continue;
              ?>
              <?php if( !$first ): ?>
                <?php echo $this->translate('or') ?>
              <?php endif; ?>
              <button class="sesbasic_animation" type="submit" name="execute" onclick="scriptJquery('#gateway_id').attr('value', '<?php echo $gateway->gateway_id ?>')">
                <?php echo $this->translate('Pay with %1$s', $this->translate($gateway->title)) ?>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
        
      </form>
    </div>
	</div>    
</div>  
<script type="application/javascript">
  var itemPrice<?php echo $this->item->getIdentity(); ?> = '<?php echo $this->itemPrice; ?>';
</script>
