<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: view.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<?php if($this->format == 'smoothbox' && empty($_GET['order'])){ ?>
  <link href="<?php $this->layout()->staticBaseUrl ?>application/modules/Eusertip/externals/styles/print.css" rel="stylesheet" media="print" type="text/css" />
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/print.css'); ?>
<?php } ?>
<?php if($this->format == 'smoothbox' && !empty($_GET['order'])){ ?>
<a href="javascript:;" onclick= "javascript:parent.Smoothbox.close();" class="fa fa-times eusertip_orderview_popup_close"></a>
<?php } ?>
<div class="eusertip_manage_order_poup">
  <div class="eusertip_ticket_order_view_page"> 
    <div class="eusertip_order_container eusertip_invoice_container sesbasic_bxs sesbasic_clearfix">
      <div class="eusertip_invoice_header sesbasic_clearfix">
        <div class="floatL">
         <?php echo $this->translate("Order Id:#%s",$this->order->order_id); ?>
        </div>
        <div class="floatR">
          <?php $totalAmount = $this->order->total_amount; ?>
          [<?php echo $this->translate('Total:'); ?><?php echo $totalAmount <= 0 ? $this->translate("FREE") : Engine_Api::_()->eusertip()->getCurrencyPrice($totalAmount,$this->order->currency_symbol,$this->order->change_rate); ?>]
        </div>
      </div>
      <div class="eusertip_invoice_content_wrap sesbm sesbasic_clearfix clear">
        <div class="eusertip_invoice_content_left sesbm">
          <div class="eusertip_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Ordered For"); ?></b>
            <div class="eusertip_invoice_content_detail">
              <span><?php echo $this->tip->getTitle(); ?></span>
            </div>
          </div>
          <div class="eusertip_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Ordered By"); ?></b>
            <div class="eusertip_invoice_content_detail">
              <span><?php echo $this->htmlLink($this->order->getOwner()->getHref(), $this->order->getOwner()->getTitle()) ?></span>
              <span><?php echo $this->order->getOwner()->email; ?></span>
            </div>
          </div>
          <div class="eusertip_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Payment Information"); ?></b>
            <div class="eusertip_invoice_content_detail">
              <span><?php echo $this->translate("Payment method: %s",$this->order->gateway_type); ?></span>
            </div>
          </div>
        </div>
        <div class="eusertip_invoice_content_right">
          <div class="eusertip_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Order Information"); ?></b>
            <div class="eusertip_invoice_content_detail">	
              <span><?php echo $this->translate("Ordered Date :"); ?> <?php echo Engine_Api::_()->eusertip()->dateFormat($this->order->creation_date); ?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="eusertip_invoice_header"><b class="bold"><?php echo $this->translate("Order Details"); ?></b></div>
      <div class="eusertip_table eusertip_invoice_order_table">
         <table class="eusertip_manage_order_poup_table">
          <tr>
            <th><?php echo $this->translate("Tip Name"); ?></th>
            <th class="rightT"><?php echo $this->translate("Price"); ?></th>
            <th class="rightT"><?php echo $this->translate("Sub Total"); ?></th>
          </tr>
          <tr>
            <td><?php echo $this->tip->getTitle(); ?></td>
            <td class="rightT">
              <?php echo $this->order->total_amount <= 0 ? $this->translate("FREE") : Engine_Api::_()->eusertip()->getCurrencyPrice($this->order->total_amount,$this->order->currency_symbol,$this->order->change_rate); ?><br />
            </td>
            <td class="rightT">
              <?php $price= $this->order->total_amount; ?>
              <?php echo $price <= 0 ? $this->translate("FREE") : Engine_Api::_()->eusertip()->getCurrencyPrice(round($price,2),$this->order->currency_symbol,$this->order->change_rate); ?><br />
            </td>
           </tr>
        </table>
        <div class="eusertip_invoice_total_price_box sesbm">
          <div>
            <span><?php echo $this->translate("Subtotal:"); ?></span>
            <span>  <b> <?php echo $this->order->total_amount <= 0 ? $this->translate("FREE") : Engine_Api::_()->eusertip()->getCurrencyPrice($this->order->total_amount,$this->order->currency_symbol,$this->order->change_rate); ?>  <b> </span>
          </div>
         
          <div class="eusertip_invoice_total_price_box_total">
            <span><?php echo $this->translate("Grand Total :"); ?></span>
            <span> <b> <?php echo $totalAmount <= 0  ? $this->translate("FREE") : Engine_Api::_()->eusertip()->getCurrencyPrice($totalAmount,$this->order->currency_symbol,$this->order->change_rate); ?> <b> </span>
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
