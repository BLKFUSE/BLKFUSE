<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: dismiss_message.tpl 2020-06-13 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<h2><?php echo $this->translate("SNS - Virtual Gifts Plugin") ?></h2>
<?php $egifts_adminmenu = Zend_Registry::isRegistered('egifts_adminmenu') ? Zend_Registry::get('egifts_adminmenu') : null; ?>
<?php if(!empty($egifts_adminmenu)) { ?>
  <div class="sesbasic_nav_btns">
    <a href="<?php echo $this->url(array('module' => 'egifts', 'controller' => 'settings', 'action' => 'support'),'admin_default',true); ?>" class="help-btn">Help & Support</a>
  </div>
  <?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
    <div class='tabs'>
      <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
    </div>
  <?php endif; ?>
<?php } ?>
