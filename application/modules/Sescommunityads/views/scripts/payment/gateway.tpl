<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: gateway.tpl  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 ?>
 <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/scripts/core.js'); ?>
 <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescommunityads/externals/styles/styles.css'); ?>
 <div class="layout_middle">
  <div class="generic_layout_container">
    <div class="headline">
      <h2>
        <?php echo $this->translate('Ads');?>
      </h2>
      <?php if(is_countable($this->navigation) && engine_count($this->navigation) > 0 ): ?>
        <div class="tabs">
          <?php
            // Render the menu
            echo $this->navigation()
              ->menu()
              ->setContainer($this->navigation)
              ->render();
          ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="generic_layout_container layout_core_content">
    <?php $currentCurrency = Engine_Api::_()->payment()->getCurrentCurrency(); ?>
    <div class="sescmads_payment_process">
      <form method="get" action="<?php echo $this->escape($this->url(array('action' => 'process'),'sescomminityads_payment',false)) ?>" enctype="application/x-www-form-urlencoded">
        <h3>
          <?php echo $this->translate('Make Payment to subscribe to ').$this->package->title; ?>
        </h3>
        <?php if( $this->package->recurrence ): ?>
        <p class="form-description">
          <?php echo $this->translate('') ?>
        </p>
        <?php endif; ?>
        <p class="_des">
          <?php if( $this->package->recurrence ): ?>
            <?php echo $this->translate('Choose the gateway below to continue to make the payment of:') ?>
          <?php else: ?>
            <?php echo $this->translate('Please pay a one-time fee to continue:') ?>
          <?php endif; ?>
          <?php echo $this->package->getPackageDescription(); ?>
        </p>
        <div class="form-elements">
          <div id="buttons-wrapper" class="form-wrapper">
              <?php foreach( $this->gateways as $gatewayInfo ):
                $gateway = $gatewayInfo['gateway'];
                if($gateway->plugin == "Payment_Plugin_Gateway_Stripe") continue;
                $plugin = $gatewayInfo['plugin'];
                $first = ( !isset($first) ? true : false );
                $gatewayObject = $gateway->getGateway();
                $supportedCurrencies = $gatewayObject->getSupportedCurrencies();
                if(!engine_in_array($currentCurrency,$supportedCurrencies))
                  continue;
                ?>
                <?php if( !$first ): ?>
                  <span><?php echo $this->translate('or') ?></span>
                <?php endif; ?>
                <button type="submit" name="execute" onclick="scriptJquery('#gateway_id').attr('value', '<?php echo $gateway->gateway_id ?>')">
                  <?php echo $this->translate('Pay with %1$s', $this->translate($gateway->title)) ?>
                </button>
              <?php endforeach; ?>
          </div>
        </div>
        <input type="hidden" name="gateway_id" id="gateway_id" value="" />
      </form>
      <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')): ?>
        <?php  echo $this->partial('have_coupon.tpl','ecoupon',array('id'=>$this->package->package_id,'params'=>json_encode(array('resource_type'=>$this->item->getType(),'resource_id'=>$this->item->sescommunityad_id,'is_package'=>1,'package_type'=>$this->package->getType(),'package_id'=>$this->package->package_id)))); ?> 
      <?php endif; ?>
      <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit')) { ?>
        <div class="sescmads_payment_process_credit">
          <?php  echo $this->partial('apply_credit.tpl','sescredit',array('id'=>$this->package->package_id,'moduleName'=>'sescommunityads','item_price'=>$this->itemPrice,'item_id'=>$this->item->sescommunityad_id)); ?>
        </div> 
      <?php } ?>
    </div>
  </div>
</div>
<script type="application/javascript">
    var itemPrice<?php echo $this->package->package_id; ?> = '<?php echo $this->itemPrice; ?>';
</script>
