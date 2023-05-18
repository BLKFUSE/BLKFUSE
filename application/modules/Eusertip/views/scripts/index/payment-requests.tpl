<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: payment-requests.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<div class="eusertip_dashboard_content sesbm sesbasic_clearfix">
  <?php $defaultCurrency = Engine_Api::_()->eusertip()->defaultCurrency(); ?>
  <div class="eusertip_dashboard_content_header sesbasic_clearfix">	
    <h3><?php echo $this->translate("Make Payment Request"); ?></h3>
    <p>
      <?php echo $this->translate('Here you can see the Total Orders for your tips, Total Amount Received, Total Commission of site admin, and the Total Remaining Amount that you can request from the site admin to release. <br><br> Note : You will be able to "Make Payment Request" only if the "Total Remaining Amount" is greater than or equal to "Threshold Amount."'); ?>
    </p>
    <?php if($this->thresholdAmount > 0){ ?>
      <div class="eusertip_db_dashboard_threshold_amt"><?php echo $this->translate("Threshold Amount:"); ?> <b><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($this->thresholdAmount,$defaultCurrency); ?></b></div>
    <?php } ?>
  </div>
  <?php if(!($this->userGateway)){ ?>
    <div class="tip">
    <span>    
      <?php echo $this->translate('You have not submitted your payment gateway details. %1$sClick Here%2$s to submit the details and proceed with the payment request.', '<a href="'.$this->url(array('action'=>'account-details'), 'eusertip_general', true).'">', '</a>'); ?>
    </span>
  </div>
  <?php } ?>
  <?php $orderDetails = $this->orderDetails; ?>
  <div class="eusertip_db_sale_stats_container sesbasic_bxs sesbasic_clearfix eusertip_db_sale_stats_t">
    <div class="eusertip_db_sale_stats">
      <section>
        <span><?php echo $this->translate("Total Orders"); ?></span>
        <span><?php echo $orderDetails['totalOrder'];?></span>
      </section>
    </div>
    <div class="eusertip_db_sale_stats">
      <section>
        <span><?php echo $this->translate("Total Amount"); ?></span>
        <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($orderDetails['totalAmountSale'],$defaultCurrency); ?></span>
      </section>
    </div>
    <div class="eusertip_db_sale_stats">
      <section>
        <span><?php echo $this->translate("Total Commission Amount"); ?></span>
        <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($orderDetails['commission_amount'],$defaultCurrency); ?></span>
      </section>
    </div>
    <div class="eusertip_db_sale_stats">
      <section>
        <span><?php echo $this->translate("Total Remaining Amount"); ?></span>
        <span><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($this->remainingAmount,$defaultCurrency); ?></span>
      </section>
    </div>
  </div>

  <?php if($this->remainingAmount && $this->remainingAmount >= $this->thresholdAmount && ($this->userGateway) && !engine_count($this->isAlreadyRequests)){ ?>
  <div class="eusertip_db_request_payment_link ">	
    <a href="<?php echo $this->url(array('action'=>'payment-request'), 'eusertip_general', true); ?>" class="openSmoothbox sesbasic_button"><i class=" fa fa-money"></i><span><?php echo $this->translate("Make Request For Payment"); ?></span></a>
  </div>
  <?php } ?>
  <?php if( isset($this->paymentRequests) && engine_count($this->paymentRequests) > 0): ?>
  <div class="sesbasic_dashboard_table sesbasic_bxs">
    <form method="post" >
    <table class='eusertip_manage_table'>
        <thead>
          <tr>
            <th><?php echo $this->translate("Request Id"); ?></th>
            <th><?php echo $this->translate("Amount Requested") ?></th>
            <th><?php echo $this->translate("Requested Date") ?></th>
            <th><?php echo $this->translate("Release Amount") ?></th>
            <th><?php echo $this->translate("Release Date") ?></th>
            <th><?php echo $this->translate("Status") ?></th>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php 
            foreach ($this->paymentRequests as $item): ?>
          <tr>
            <td  data-label="<?php echo $this->translate("Request Id"); ?>"><?php echo $item->userpayrequest_id; ?></td>
            <td data-label="<?php echo $this->translate("Amount Requested"); ?>" class='eusertip_manage_table_price'><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($item->requested_amount,$defaultCurrency); ?></td>
            <td data-label="<?php echo $this->translate("Requested Date"); ?>"><?php echo Engine_Api::_()->sesbasic()->dateFormat($item->creation_date	); ?></td> 
            <td data-label="<?php echo $this->translate("Release Amount"); ?>"><?php echo $item->state != 'pending' ? Engine_Api::_()->eusertip()->getCurrencyPrice($item->release_amount	,$defaultCurrency) :  "-"; ?></td>
            <td data-label="<?php echo $this->translate("Release Date"); ?>"><?php echo $item->release_date && (bool)strtotime($item->release_date) && $item->state != 'pending' ? Engine_Api::_()->eusertip()->dateFormat($item->release_date) :  '-'; ?></td> 
            <td data-label="<?php echo $this->translate("Status"); ?>"><?php echo ucfirst($item->state); ?></td>
            <td data-label="<?php echo $this->translate("Options"); ?>" class='eusertip_manage_table_options'>
              <?php if ($item->state == 'pending'){ ?>
                <?php echo $this->htmlLink($this->url(array('action'=>'payment-request','id'=>$item->userpayrequest_id), 'eusertip_general', true), $this->translate(""), array('class' => 'openSmoothbox fas fa-pencil-alt','title'=>$this->translate("Edit Request"))); ?>
                <?php echo $this->htmlLink($this->url(array('action' => 'delete-payment', 'id' => $item->userpayrequest_id), 'eusertip_general', true), $this->translate(""), array('class' => 'openSmoothbox fa fa-trash','title'=>$this->translate("Delete Request"))); ?>
              <?php } ?>
              <?php echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id), 'eusertip_general', true), $this->translate(""), array('class' => 'openSmoothbox fa fa-eye','title'=>$this->translate("View Details"))); ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </form>
  </div>
  <?php else: ?>
  <div class="tip">
    <span>
      <?php echo $this->translate("You do not have any pending payment request yet.") ?>
    </span>
  </div>
  <?php endif; ?>
</div>
