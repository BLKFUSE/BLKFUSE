<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeelingactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: dismiss_message.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenuItemName' => 'core_admin_main_plugins', 'childMenuItemName' => 'core_admin_main_plugins_sesadvancedactivity')); ?>
<h2><?php echo $this->translate("SESFEELINGACTIVITY_PLUGIN") ?></h2>
<div class="sesbasic_nav_btns">
  <a href="https://socialnetworking.solutions/contact-us/" target = "_blank" class="request-btn">Feature Request</a>
</div>
<?php 
$sesfeelingactivity_adminmenu = Zend_Registry::isRegistered('sesfeelingactivity_adminmenu') ? Zend_Registry::get('sesfeelingactivity_adminmenu') : null;
if(!empty($sesfeelingactivity_adminmenu)) { ?>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
  </div>
<?php endif; ?>
<?php } ?>
