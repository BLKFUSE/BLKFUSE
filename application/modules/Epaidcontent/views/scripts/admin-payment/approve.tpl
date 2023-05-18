<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: approve.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php if(!$this->disable_gateway){ ?>
<div class='epaidcontent_approve_payment_popup'>
  <div class='settings'>
    <?php echo $this->form->render($this); ?>
  </div>
</div>
<?php }else{?>
	  <div class="tip">
    <span>
      <?php echo $this->translate("No payment gateway enable.") ?>
    </span>
  </div>
<?php } ?>
