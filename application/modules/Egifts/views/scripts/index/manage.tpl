<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: manage.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<div class="layout_middle">
  <div class="generic_layout_container layout_core_content">
    <div class="egifts_payment_process_complete_page sesbasic_bxs">

	    <?php if(isset($this->item->state) && $this->item->state=="complete") {
			    $giftsend_user=Engine_Api::_()->getItem('user',$this->item->purchase_user_id);
	        ?>
      <div class="egifts_payment_process_complete">
        <i><img src="application/modules/Egifts/externals/images/success.png" alt=""></i>
        <span class="_text"><?php echo $this->translate('Payment Completed') ?></span>
        <p class="_des"><?php echo $this->translate('Thank you! Your payment has ' . 'completed successfully.') ?></p>
        <div id="buttons-wrapper" class="egifts_payment_process_complete_btn">
          <a  href="<?php echo $giftsend_user->getHref(); ?>" class="sesbasic_button"><i class="fas fa-long-arrow-alt-left"></i> <?php echo $this->translate('Back to user profile'); ?></a>
        </div>
      </div>
      <?php }
      else if(isset($this->item->state) && $this->item->state=="failed") {
	      $giftsend_user=Engine_Api::_()->getItem('user',$this->item->purchase_user_id);
          ?>
      <div class="egifts_payment_process_complete">
        <i><img src="application/modules/Egifts/externals/images/fail.png" alt=""></i>
        <span class="_text"><?php echo $this->translate('Payment Failed') ?></span>
        <p class="_des">
          <?php echo $this->translate('Our payment processor has notified us that your payment could not be completed successfully. We suggest that you try again with another credit card or funding source.') ?>
        </p>
        <div id="buttons-wrapper" class="egifts_payment_process_complete_btn">
          <a href="<?php echo $giftsend_user->getHref(); ?>" class="sesbasic_button"><i class="fas fa-long-arrow-alt-left"></i> <?php echo $this->translate('Back to user profile'); ?></a>
        </div>
      </div>
        <?php  }
        else {
            $txt='';
            if(isset($this->item->purchase_user_id) && !empty($this->item->purchase_user_id)) {
	            $giftsend_user = Engine_Api::_()->getItem('user', $this->item->purchase_user_id);
	            $txt="<a href='".$giftsend_user->getHref()."' class='sesbasic_button'><i class='fas fa-long-arrow-alt-left'></i>".$this->translate('Go to My Credits Page')."</a>";
            }
            ?>
          <div class="egifts_payment_process_complete">
              <i><img src="application/modules/Egifts/externals/images/wait.png" alt=""></i>
              <span class="_text _wait"><?php echo $this->translate('Payment Pending') ?></span>
              <p class="_des">
						    <?php echo $this->translate('Thank you for submitting your payment. Your payment is currently pending. You will receive an email notifying you that the payment has completed.') ?>
              </p>
              <div id="buttons-wrapper" class="egifts_payment_process_complete_btn">
				 				<?php echo $txt; ?>
              </div>
          </div>
	    <?php } ?>



    </div>
  </div>
</div>    