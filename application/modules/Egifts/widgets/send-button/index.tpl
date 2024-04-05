<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Egifts/externals/styles/styles.css'); ?>
<?php 
	$subject = Engine_Api::_()->core()->getSubject('user');
?>

<div class="egifts_send_button">
	<a href="<?php echo $this->baseUrl() . '/egifts?userid=' . $subject->getIdentity(); ?>" class="sessmoothbox sesbasic_linkinherit"><button><i class="fas fa-gift"></i><span><?php echo $this->translate("Send Gift"); ?></span></button></a>
</div>