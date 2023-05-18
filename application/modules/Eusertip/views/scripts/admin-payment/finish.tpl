<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Eusertip
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: finish.tpl 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

?>
<?php if(empty($this->error)){ ?>
  The payment has been successfully sent to the paid content owner. <?php echo $this->htmlLink($this->url(array('route' => 'default', 'module' => 'eusertip', 'controller' => 'payment','action'=>'index')), $this->translate("Back to Payment Requests")); ?>
<?php }else{ ?>
	The payment has been failed or cancelled. <?php echo $this->htmlLink($this->url(array('module' => 'eusertip', 'controller' => 'payment','action'=>'index'), 'admin_default', true), $this->translate("Back to Payment Requests")); ?>
<?php } ?>
