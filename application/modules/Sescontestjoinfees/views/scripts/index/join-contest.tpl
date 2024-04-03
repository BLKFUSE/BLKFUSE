<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: join-contest.tpl  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjoinfees/externals/styles/styles.css'); ?>
<?php $givenSymbol = Engine_Api::_()->sescontest()->getCurrentCurrency(); ?>
<div id="popupcontainer" class="sescontest_fees_process_popup sesbasic_bxs">
	<div class="sescontest_fees_process_popup_content">
  <?php if($this->popupTitle){ ?>
  	<h3><?php echo $this->translate($this->popupTitle);?></h3>
    <?php } ?>
    <?php if($this->popupDescription){ ?>
    <p><?php echo $this->translate($this->popupDescription) ?></p>
    <?php } ?>
  	<div class="_btns sesbasic_clearfix">
      <button type="button" id="makePayment"><?php echo $this->translate('Make Payment'); ?></button>
      <button type="button" id="goBack"><?php echo $this->translate('Go back'); ?></span></button>
  	</div>
  </div>
</div>
<script type="application/javascript">
  scriptJquery(document).on('click','#makePayment',function(){
      scriptJquery('#popupcontainer').hide();
      scriptJquery('#checkoutform').show();
  });
  scriptJquery(document).on('click','#goBack',function(){
    window.history.go(-1);
  });
</script>

<div class="layout_middle">
  <div class="generic_layout_container layout_core_content">
    <div class="checkout_form" id="checkoutform" style="display:none;">
      <div class="sescontest_fees_payment_process">
        <form method="get" action="<?php echo $this->escape($this->url(array('action' => 'process','module'=>'sescontestjoinfees','controller'=>'index','contest_id'=>$this->contest_id),'default',true)) ?>" enctype="application/x-www-form-urlencoded">
          <div class="sescontest_fees_process_step">
            <?php $contest = Engine_Api::_()->getItem('contest',$this->contest_id); ?>
            <h3><?php echo $this->translate('Pay %s to join the contest ',Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($contest->entry_fees)).$contest->getTitle(); ?></h3>
            <div id="buttons-wrapper" class="_btn">
              <?php foreach( $this->gateways as $gatewayInfo ):
                $gateway = $gatewayInfo['gateway'];
                $plugin = $gatewayInfo['plugin'];
                $gatewayObject = $gateway->getGateway();
                $supportedCurrencies = $gatewayObject->getSupportedCurrencies();
                if(!engine_in_array($givenSymbol,$supportedCurrencies))
                  continue;
                ?>
                <button type="submit" name="execute"  onclick="scriptJquery('#gateway_id').attr('value', '<?php echo $gateway->gateway_id ?>')">
                  <?php echo $this->translate('Pay with %1$s', $this->translate($gateway->title)) ?>
                </button>
              <?php endforeach; ?>
            </div>
          </div>
          <input type="hidden" name="gateway_id" id="gateway_id" value="" />
        </form>
        <div class="sesbasic_loading_cont_overlay" style="display:none"></div>
        <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')): ?>
          <?php  echo $this->partial('have_coupon.tpl','ecoupon',array('id'=>$contest->contest_id,'params'=>json_encode(array('resource_type'=>$contest->getType(),'resource_id'=>$contest->contest_id,'is_package'=>0,'item_amount'=>$contest->entry_fees)))); ?> 
        <?php endif; ?>
        
        <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit')) { ?>
          <div class="sescontest_fees_payment_process_credit">
            <?php  echo $this->partial('apply_credit.tpl','sescredit',array('id'=>$contest->contest_id,'moduleName'=>'sescontestjoinfees','item_price'=>$this->itemPrice,'item_id'=>$contest->contest_id)); ?>
          </div> 
        <?php } ?>
      </div>
    </div>
  </div>  
</div>
<script type="application/javascript">
  var itemPrice<?php echo $contest->contest_id; ?> = '<?php echo $this->itemPrice; ?>';
</script>
