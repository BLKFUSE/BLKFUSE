
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sescontestjoinfees/externals/styles/styles.css'); ?>
 
<div class="sescontest_view_detail_popup">
<?php $defaultCurrency = Engine_Api::_()->sescontestjoinfees()->defaultCurrency(); ?>
  <h3> <?php echo $this->translate("Payment Details"); ?> </h3>
  <table class="sesbm">   
  	<tr>
      <td><?php echo $this->translate('Contest Name') ?>:</td>
      <td><a href="<?php echo $this->event->getHref(); ?>" target="_blank"><?php echo $this->event->title; ?></a>
     </td>
    </tr>
    <tr>
    	<?php $user = Engine_Api::_()->getItem('user', $this->item->owner_id); ?>
      <td><?php echo $this->translate('Owner') ?>:</td>
      <td><?php echo $this->htmlLink($user->getHref(), $user->getTitle(), array('target'=>'_blank')); ?></td>
    </tr>
     <tr>
      <td><?php echo $this->translate('Request ID') ?>:</td>
      <td><?php echo $this->item->userpayrequest_id ; ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Requested Amount'); ?>:</td>
      <td><?php echo Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($this->item->requested_amount,$defaultCurrency) ; ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Payment Request Date') ?>:</td>
      <td> <?php echo Engine_Api::_()->sescontestjoinfees()->dateFormat($this->item->creation_date	); ?></td>
    </tr>
   <tr>
      <td><?php echo $this->translate('Requested Message') ?>:</td>
      <td> <?php echo $this->item->user_message ? $this->viewMore($this->item->user_message) : '-'; ?></td>
    </tr>
    <!--<tr>
      <td><?php echo $this->translate('Released Amount') ?>:</td>
      <td><?php echo Engine_Api::_()->sescontestjoinfees()->getCurrencyPrice($this->item->release_amount,$defaultCurrency); ?></td>
    </tr>
    <tr>
      <td><?php echo $this->translate('Released Date') ?>:</td>
      <td> <?php echo $this->item->release_date != "0000-00-00 00:00:00" && (bool)strtotime($this->item->release_date) ? Engine_Api::_()->sescontestjoinfees()->dateFormat($this->item->release_date) : '-'; ?></td>
    </tr>
   <tr>
      <td><?php echo $this->translate('Response Message') ?>:</td>
      <td> <?php echo $this->item->admin_message ? $this->viewMore($this->item->admin_message) : '-'; ?></td>
    </tr>-->
    <tr>
      <td><?php echo $this->translate('Status') ?>:</td>
      <td><?php echo ucfirst($this->item->state); ?></td>
     </td>
    </tr>
  </table>
  <br />
  <button onclick='javascript:parent.Smoothbox.close()'>
    <?php echo $this->translate("Close") ?>
  </button>
</div>
