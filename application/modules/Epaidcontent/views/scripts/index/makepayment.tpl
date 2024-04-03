<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: makepayment.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Epaidcontent/externals/styles/style.css'); ?>

<?php $givenSymbol = Engine_Api::_()->epaidcontent()->getCurrentCurrency(); ?>
<script type="application/javascript">
  scriptJquery(document).on('click','#goBack',function(){
    window.history.go(-1);
  });
</script>

<div class="generic_layout_container layout_main">
  <div class="generic_layout_container layout_middle">
  <div class="generic_layout_container layout_core_content">
  <div class="checkout_form" id="checkoutform">
    <form method="get" action="<?php echo $this->escape($this->url(array('action' => 'process','package_id' => $this->package_id),'epaidcontent_general',true)) ?>" enctype="application/x-www-form-urlencoded">
    	<div class="epaidcontent_fees_process_step">
        <h2><?php echo $this->translate('Subscribe %s content.', $this->user->getTitle()); ?></h2>
        <?php echo $this->translate('Pay <b> %s </b> for <b>%s</b>.', Engine_Api::_()->epaidcontent()->getCurrencyPrice($this->package->price), $this->package->title); ?>
        <p><?php echo $this->package->description; ?></p>
        <div id="buttons-wrapper" class="epaidcontent_fees_process_step_button">
          <?php foreach( $this->gateways as $gatewayInfo ):
            $gateway = $gatewayInfo['gateway'];
            $plugin = $gatewayInfo['plugin'];
            $gatewayObject = $gateway->getGateway();
            $supportedCurrencies = $gatewayObject->getSupportedCurrencies();
            if(!engine_in_array($givenSymbol,$supportedCurrencies))
              continue;
            if(engine_in_array($gateway->gateway_id, array('4', '5', '6', '7', '8')))
              continue;
          ?>
          <button type="submit" name="execute"  onclick="scriptJquery('#gateway_id').attr('value', '<?php echo $gateway->gateway_id ?>')">
            <?php echo $this->translate('Pay with %1$s', $this->translate($gateway->title)) ?>
          </button>
          <?php endforeach; ?>
        </div>
        <a class="epaidcontent_fees_process_step_cancel" type="button" id="goBack">Cancel</a>
      </div>

      <input type="hidden" name="gateway_id" id="gateway_id" value="" />
    </form>
    <div class="sesbasic_loading_cont_overlay" style="display:none"></div>
    
    <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('ecoupon')): ?>
      <?php  echo $this->partial('have_coupon.tpl','ecoupon',array('id'=>$page->page_id,'params'=>json_encode(array('resource_type'=>$page->getType(),'resource_id'=>$page->page_id,'is_package'=>0,'item_amount'=>$page->entry_fees)))); ?> 
    <?php endif; ?>
    
     <?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescredit')) { ?>
		  <?php  echo $this->partial('apply_credit.tpl','sescredit',array('id'=>$page->page_id,'moduleName'=>'epaidcontent','item_price'=>$this->itemPrice,'item_id'=>$page->page_id)); ?> 
    <?php } ?>
  </div>
</div>
</div>
</div>
<script type="application/javascript">
  //var itemPrice<?php //echo $page->page_id; ?> = '<?php //echo $this->itemPrice; ?>';
</script>
