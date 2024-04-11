<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Sescredit
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: finish.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<div class="sescredit_payment_success_message">
  <?php if(empty($this->error)){ ?>
    <i><img src="application/modules/Sescredit/externals/images/success.png" alt=""></i>
    <p>The payment has been successfully sent to the owner. <span><i class="fas fa-arrow-left"></i><?php echo $this->htmlLink($this->url(array('module' => 'sescredit', 'controller' => 'payment','action'=>'index'), 'admin_default', true), $this->translate("Back to Payment Requests")); ?></span></p>
  <?php } else { ?>
    <i><img src="application/modules/Sescredit/externals/images/fail.png" alt=""></i>
    <p>The payment has been failed or cancelled. <span><i class="fas fa-arrow-left"></i><?php echo $this->htmlLink($this->url(array('module' => 'sescredit', 'controller' => 'payment','action'=>'index'), 'admin_default', true), $this->translate("Back to Payment Requests")); ?></span></div>
  <?php } ?>
</div>