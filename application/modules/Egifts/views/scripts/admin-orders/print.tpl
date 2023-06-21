<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: print.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<link href="<?php $this->layout()->staticBaseUrl ?>application/modules/Egifts/externals/styles/print.css" rel="stylesheet" media="print" type="text/css" />
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/print.css'); ?>

<div class="egifts_order_view">
  <div class="egifts_order_view_table">
    <table>
      <thead>
        <tr>
          <th><?php echo $this->translate('Order Id'); ?></th>
          <th><?php echo $this->translate('Gift Name'); ?></th>
          <th><?php echo $this->translate('Price'); ?></th>
        </tr>
      </thead>
      <tbody>
        <?php $savedMoney = 0; ?>
        <?php foreach($this->giftorders as $order): ?>
        <tr>
          <td><?php echo $order->giftorder_id; ?></td>
          <td><?php echo $order->gift_title; ?></td>
          <td><?php echo Engine_Api::_()->egifts()->getCurrencyPrice($order->gift_price); ?></td>
        </tr>
        <?php endforeach; ?>
				<tr>
					<td colspan="2"><strong><?php echo $this->translate('Subtotal'); ?></strong></td>
					<td><strong><?php echo Engine_Api::_()->egifts()->getCurrencyPrice($this->giftpurchase->total_amount); ?></strong></td>
				</tr>
      </tbody>
    </table>
  </div>
</div>
<?php if(empty($_GET['order'])){ ?>
<style type="text/css" media="print">
  @page { size: landscape; }
</style>
<script type="application/javascript">
scriptJquery(document).ready(function(e){
    window.print();
});
</script>
<?php } ?>
<style>
#global_header,#global_footer{display:none}
</style>
