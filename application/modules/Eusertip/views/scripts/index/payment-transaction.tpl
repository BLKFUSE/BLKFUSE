<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: payment-transaction.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eusertip/externals/styles/style.css'); ?>

<div class="eusertip_dashboard_content sesbm sesbasic_clearfix">
  <?php $defaultCurrency = Engine_Api::_()->eusertip()->defaultCurrency(); ?>
  <div class="sesbasic_dashboard_content_header sesbasic_clearfix">	
    <h3><?php echo $this->translate("Payment Transaction of Received Payments"); ?></h3>
    <br />
    <p><?php echo $this->translate('Here, you are viewing the details of payments received from the website.') ?></p>
    <br />
  </div>
  <?php if( isset($this->paymentRequests) && engine_count($this->paymentRequests) > 0): ?>
  <div class="sesbasic_dashboard_table sesbasic_bxs">
    <form method="post" >
      <table class="eusertip_manage_table">
        <thead>
          <tr>
            <th><?php echo $this->translate("Requested Amount") ?></th>
            <th><?php echo $this->translate("Released Amount") ?></th>
            <th><?php echo $this->translate("Released Date") ?></th>
            <th><?php echo $this->translate("Response Message") ?></th>
            <th><?php echo $this->translate("Status") ?></th>
            <th><?php echo $this->translate("Options") ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($this->paymentRequests as $item): ?>
            <tr>
              <td data-label=" <?php echo $this->translate("Requested Amount") ?>" class='eusertip_manage_table_price'><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($item->requested_amount,$defaultCurrency); ?></td>
              <td data-label=" <?php echo $this->translate("Released Amount") ?> " class="eusertip_manage_table_bold payment_found"><?php echo Engine_Api::_()->eusertip()->getCurrencyPrice($item->release_amount	,$defaultCurrency); ?></td>
              <td data-label=" <?php echo $this->translate("Released Date") ?> " ><?php echo $item->release_date != "0000-00-00 00:00:00" ? Engine_Api::_()->eusertip()->dateFormat($item->release_date) :  '-'; ?></td> 
              <td data-label=" <?php echo $this->translate("Response Message") ?> "><?php echo $this->string()->truncate(empty($item->admin_message	) ? '-' : $item->admin_message, 30) ?></td>
              <td data-label=" <?php echo $this->translate("Status") ?> " class="eusertip_manage_table_bold"><?php echo ucfirst($item->state); ?></td>
              <td data-label=" " class="table_options">
                <?php //echo $this->htmlLink($this->url(array('action' => 'detail-payment', 'id' => $item->userpayrequest_id, 'page_id' => $this->page->custom_url), 'eusertip_dashboard', true), $this->translate(""), array('title' => $this->translate("View Details"), 'class' => 'sessmoothbox fa fa-eye')); ?>
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
      <?php echo $this->translate("No transactions have been made yet.") ?>
    </span>
  </div>
  <?php endif; ?>
</div>
