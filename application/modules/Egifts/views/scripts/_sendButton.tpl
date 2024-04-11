<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _sendButton.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<a href="<?php echo $this->url(array('action' => 'send-gift','gift_id'=>$item->getIdentity()), 'egifts_general', true); ?>" class="sessmoothbox"><button><?php echo $this->translate("Send");?></button></a>
            	