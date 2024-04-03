<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: finish.tpl  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php if(empty($this->error)){ ?>
		The payment has been successfully sent to the contest owner. <?php echo $this->htmlLink($this->url(array('route' => 'default', 'module' => 'sescontestjoinfees', 'controller' => 'payment','action'=>'index')), $this->translate("Back to Payment Requests")); ?>
<?php }else{ ?>
	The payment has been failed or cancelled. <?php echo $this->htmlLink($this->url(array('route' => 'default', 'module' => 'sescontestjoinfees', 'controller' => 'payment','action'=>'index')), $this->translate("Back to Payment Requests")); ?>
<?php } ?>
 