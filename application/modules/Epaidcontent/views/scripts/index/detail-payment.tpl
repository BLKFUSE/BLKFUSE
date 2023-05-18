<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: detail-payment.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Epaidcontent/externals/styles/style.css'); ?>

<div class="epaidcontent_view_detail_popup">
  <?php $defaultCurrency = Engine_Api::_()->epaidcontent()->defaultCurrency(); ?>
  <h3> <?php echo $this->translate("Payment Details"); ?> </h3>
  <table class="payment_requests_view_table">
    <tr>
    	<?php $user = Engine_Api::_()->getItem('user', $this->item->owner_id); ?>
      <td class="bold"><?php echo $this->translate('Owner') ?>:</td>
      <td><?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('target'=>'_blank')); ?></td>
    </tr>
     <tr>
      <td class="bold"><?php echo $this->translate('Request ID') ?>:</td>
      <td><?php echo $this->item->userpayrequest_id ; ?></td>
    </tr>
    <tr>
      <td class="bold"><?php echo $this->translate('Requested Amount'); ?>:</td>
      <td><?php echo Engine_Api::_()->epaidcontent()->getCurrencyPrice($this->item->requested_amount,$defaultCurrency) ; ?></td>
    </tr>
    <tr>
      <td class="bold"><?php echo $this->translate('Payment Request Date') ?>:</td>
      <td> <?php echo Engine_Api::_()->epaidcontent()->dateFormat($this->item->creation_date	); ?></td>
    </tr>
   <tr>
      <td class="bold"><?php echo $this->translate('Requested Message') ?>:</td>
      <td> <?php echo $this->item->user_message ? $this->viewMore($this->item->user_message) : '-'; ?></td>
    </tr>
    <tr>
      <td class="bold"><?php echo $this->translate('Status') ?>:</td>
      <td><?php echo ucfirst($this->item->state); ?></td>
     </td>
    </tr>
  </table>
  <br />
  <button onclick='javascript:parent.Smoothbox.close()'>
    <?php echo $this->translate("Close") ?>
  </button>
</div>
<?php if( @$this->closeSmoothbox ): ?>
<script type="text/javascript">
  TB_close();
</script>
<?php endif; ?>
