<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: view.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/style.css'); ?>
<?php if($this->format == 'smoothbox' && empty($_GET['order'])){ ?>
  <link href="<?php $this->layout()->staticBaseUrl ?>application/modules/Egifts/externals/styles/print.css" rel="stylesheet" media="print" type="text/css" />
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/print.css'); ?>
<?php } ?>
<?php if($this->format == 'smoothbox' && !empty($_GET['order'])){ ?>
<a href="javascript:;" onclick= "javascript:parent.Smoothbox.close();" class="fa fa-times egifts_orderview_popup_close"></a>
<?php } ?>
<div class="egifts_manage_order_poup">
  <div class="egifts_ticket_order_view_page"> 
    <div class="egifts_order_container egifts_invoice_container sesbasic_bxs sesbasic_clearfix">
      <div class="egifts_invoice_header sesbasic_clearfix">
        <div class="floatL">
         <?php echo $this->translate("Order Id:#%s",$this->order->giftpurchase_id); ?>
        </div>
        <div class="floatR">
          <?php $totalAmount = $this->order->total_amount; ?>
          [<?php //echo $this->translate('Total:'); ?><?php //echo $totalAmount <= 0 ? $this->translate("FREE") : Engine_Api::_()->sesbasic()->getCurrencyPrice($totalAmount,$this->order->currency_symbol,$this->order->change_rate); ?>]
        </div>
      </div>
      <div class="egifts_invoice_content_wrap sesbm sesbasic_clearfix clear">
        <div class="egifts_invoice_content_left sesbm">
          <div class="egifts_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Ordered For"); ?></b>
            <div class="egifts_invoice_content_detail">
              <span><?php echo $this->gift->getTitle(); ?></span>
            </div>
          </div>
          <div class="egifts_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Ordered By"); ?></b>
            <div class="egifts_invoice_content_detail">
              <span><?php echo $this->htmlLink($this->order->getOwner()->getHref(), $this->order->getOwner()->getTitle()) ?></span>
              <span><?php echo $this->order->getOwner()->email; ?></span>
            </div>
          </div>
        </div>
        <div class="egifts_invoice_content_right">
          <div class="egifts_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Order Information"); ?></b>
            <div class="egifts_invoice_content_detail">	
              <span><?php echo $this->translate("Ordered Date :"); ?> <?php echo Engine_Api::_()->sesbasic()->dateFormat($this->order->creation_date); ?></span>
            </div>
          </div>
          <?php if($this->order->billing_info){ 
            $billing = unserialize($this->order->billing_info);
            $billing = (Object) $billing;
            ?>
          <div class="egifts_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Name & Billing Address"); ?></b>
            <div class="egifts_invoice_content_detail">	
              <ul class="_billingdetails">
                <li style="display: block;"> <?php  echo $billing->first_name.' '.$billing->last_name; ?></li>
                <li><?php echo $billing->address; ?></li>
                  <?php if(isset($billing->country)) { ?>
                    <?php $billingCountry =   Engine_Api::_()->getItem('estore_country', $billing->country);?>
                    <li><?php echo $billingCountry->name; ?></li>
                    <li><?php echo $billingCountry->phonecode; ?></li>
                 <?php } ?>
                <?php if(isset($billing->state)) { ?>
                    <?php $billingState =   Engine_Api::_()->getItem('estore_state', $billing->state);?>
                    <li><?php echo $billingState->name; ?></li>
                <?php } ?>
                <li><?php echo $billing->city; ?></li>
                <?php if($billing->phone_number){ ?>
                <li>Ph. <?php echo $billing->phone_number; ?></li>
                <?php } ?>
                <li>Zip Code. <?php echo $billing->zip_code; ?></li>
                <li><?php echo $billing->email; ?></li>
              </ul>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="egifts_invoice_header"><b class="bold"><?php echo $this->translate("Order Details"); ?></b></div>
      <div class="egifts_table egifts_invoice_order_table">
         <table class="egifts_manage_order_poup_table">
          <tr>
            <th><?php echo $this->translate("Joining Fees Plan Name"); ?></th>
            <th class="rightT"><?php echo $this->translate("Price"); ?></th>
            <th class="rightT"><?php echo $this->translate("Sub Total"); ?></th>
          </tr>
          <tr>
            <td><?php echo $this->joinfees->getTitle(); ?></td>
            <td class="rightT">
              <?php echo $this->order->total_amount <= 0 ? $this->translate("FREE") : Engine_Api::_()->sesbasic()->getCurrencyPrice($this->order->total_amount - $this->order->total_admintax_cost,$this->order->currency_symbol,$this->order->change_rate); ?><br />
            </td>
            <td class="rightT">
              <?php $price= $this->order->total_amount  - $this->order->total_admintax_cost; ?>
              <?php echo $price <= 0 ? $this->translate("FREE") : Engine_Api::_()->sesbasic()->getCurrencyPrice(round($price,2),$this->order->currency_symbol,$this->order->change_rate); ?><br />
            </td>
           </tr>
        </table>
        <div class="egifts_invoice_total_price_box sesbm">
          <div>
            <span><?php echo $this->translate("Subtotal:"); ?></span>
            <span>  <b> <?php echo $this->order->total_amount <= 0 ? $this->translate("FREE") : Engine_Api::_()->sesbasic()->getCurrencyPrice($this->order->total_amount - $this->order->total_admintax_cost,$this->order->currency_symbol,$this->order->change_rate); ?>  </b> </span>
          </div>

          <?php if(!empty($this->order->admin_taxes)){ ?>
            <?php $taxes = unserialize($this->order->admin_taxes);
              foreach($taxes as $tax){
            ?>
          <div>
            <span><?php echo $tax['title'].' '.$this->translate(":"); ?></span>
            <span>  <b> <?php echo $tax['price'] <= 0 ? $this->translate("0") : Engine_Api::_()->sesbasic()->getCurrencyPrice($tax['price'],$this->order->currency_symbol,$this->order->change_rate); ?>  </b> </span>
          </div>
            <?php } ?>

          <?php } ?>
          <div class="egifts_invoice_total_price_box_total">
            <span><?php echo $this->translate("Grand Total :"); ?></span>
            <span> <b> <?php echo $totalAmount <= 0  ? $this->translate("FREE") : Engine_Api::_()->sesbasic()->getCurrencyPrice($totalAmount,$this->order->currency_symbol,$this->order->change_rate); ?> </b> </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if($this->format == 'smoothbox' && empty($_GET['order'])){ ?>
<style type="text/css" media="print">
  @page { size: landscape; }
</style>
<script type="application/javascript">
scriptJquery(document).ready(function(e){
		window.print();
});
</script>
<?php } ?>
