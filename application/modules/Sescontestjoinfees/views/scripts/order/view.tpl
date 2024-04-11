<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: view.tpl  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjoinfees/externals/styles/styles.css'); ?>
<?php if($this->format == 'smoothbox' && empty($_GET['order'])){ ?>
<link href="<?php $this->layout()->staticBaseUrl ?>application/modules/Sescontestjoinfees/externals/styles/print.css" rel="stylesheet" media="print" type="text/css" />
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjoinfees/externals/styles/print.css'); ?>
<?php } ?>
<?php if($this->format == 'smoothbox' && !empty($_GET['order'])){ ?>
<a href="javascript:;" onclick= "javascript:parent.Smoothbox.close();" class="fa fa-times sescontest_orderview_popup_close"></a>
<?php } ?>
<div class="layout_middle">
  <div class="sescontest_ticket_order_view_page generic_layout_container layout_core_content"> 
  <?php if($this->format != 'smoothbox'){ ?>
    <div class="clear sescontest_order_view_top">
      <a href="<?php echo $this->url(array('action'=>'view'), 'sescontestjoinfees_user_order', true); ?>" class="buttonlink sesbasic_icon_back"><?php echo $this->translate("Back To My Orders"); ?></a>
    </div>
    <?php } ?>
    <div class="sescontest_order_container sescontest_invoice_container sesbasic_bxs sesbasic_clearfix">
      <div class="sescontest_invoice_header sesbasic_clearfix">
        <div class="floatL">
         <?php echo $this->translate("Order Id:#%s",$this->order->order_id); ?>
        </div>
        <div class="floatR">
          <?php $totalAmount = $this->order->total_amount; ?>
          [<?php echo $this->translate('Total:'); ?><?php echo $totalAmount <= 0 ? $this->translate("FREE") : Engine_Api::_()->payment()->getCurrencyPrice($totalAmount,$this->order->currency_symbol,$this->order->change_rate); ?>]
        </div>
      </div>
      <div class="sescontest_invoice_content_wrap sesbm sesbasic_clearfix clear">
        <div class="sescontest_invoice_content_left sesbm">
          <div class="sescontest_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Ordered For"); ?></b>
            <div class="sescontest_invoice_content_detail">
              <span><?php echo $this->htmlLink($this->contest->getHref(),$this->contest->getTitle()); ?></span>
              <span> 
        
	      </span>
            </div>
          </div>
          <div class="sescontest_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Ordered By"); ?></b>
            <div class="sescontest_invoice_content_detail">
              <span><?php echo $this->htmlLink($this->order->getOwner()->getHref(), $this->order->getOwner()->getTitle()) ?></span>
              <span><?php echo $this->order->getOwner()->email; ?></span>
            </div>
          </div>
          <div class="sescontest_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Payment Information"); ?></b>
            <div class="sescontest_invoice_content_detail">
              <span><?php echo $this->translate("Payment method: %s",$this->order->gateway_type); ?></span>
            </div>
          </div>
        </div>
        <div class="sescontest_invoice_content_right">
          <div class="sescontest_invoice_content_box sesbm">
            <b class="bold"><?php echo $this->translate("Order Information"); ?></b>
            <div class="sescontest_invoice_content_detail">	
              <span><?php echo $this->translate("Ordered Date :"); ?> <?php echo Engine_Api::_()->sescontestjoinfees()->dateFormat($this->order->creation_date); ?></span>
             
            </div>
          </div>
          
          </div>
        </div>
      <div class="sescontest_invoice_header"><b class="bold"><?php echo $this->translate("Order Details"); ?></b></div>
      <div class="sescontest_table sescontest_invoice_order_table">
         <table>
          <tr>
            <th><?php echo $this->translate("Contest Name"); ?></th>
            <th class="rightT"><?php echo $this->translate("Price"); ?></th>
            <th class="rightT"><?php echo $this->translate("Sub Total"); ?></th>
          </tr>
          <tr>
            <td><?php echo $this->contest->getTitle(); ?></td>
            <td class="rightT">
                <?php echo $this->order->total_amount <= 0 ? $this->translate("FREE") : Engine_Api::_()->payment()->getCurrencyPrice($this->order->total_amount,$this->order->currency_symbol,$this->order->change_rate); ?><br />
            </td>
            <td class="rightT">
              <?php $price= $this->order->total_amount; ?>
              <?php echo $price <= 0 ? $this->translate("FREE") : Engine_Api::_()->payment()->getCurrencyPrice(round($price,2),$this->order->currency_symbol,$this->order->change_rate); ?><br />
            </td>
           </tr>
        </table>
        <div class="sescontest_invoice_total_price_box sesbm">
          <div>
            <span><?php echo $this->translate("Subtotal:"); ?></span>
            <span><?php echo $this->order->total_amount <= 0 ? $this->translate("FREE") : Engine_Api::_()->payment()->getCurrencyPrice($this->order->total_amount,$this->order->currency_symbol,$this->order->change_rate); ?></span>
          </div>
         
          <div class="sescontest_invoice_total_price_box_total">
            <span><?php echo $this->translate("Grand Total :"); ?></span>
            <span><?php echo $totalAmount <= 0  ? $this->translate("FREE") : Engine_Api::_()->payment()->getCurrencyPrice($totalAmount,$this->order->currency_symbol,$this->order->change_rate); ?></span>
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
