<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: dismiss_message.tpl 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Everification/externals/styles/admin/styles.css'); ?> 
 
<h2><?php echo $this->translate("Verified Badge Plugin") ?></h2>
<div class="everification_nav_btns">
  <a href="<?php echo $this->url(array('module' => 'everification', 'controller' => 'settings', 'action' => 'support'),'admin_default',true); ?>"><i class="fa everification_icon_help"></i>Help & Support</a>
</div>
<?php if(!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic')) { ?>
  	<div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other features to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>
<?php $everification_admin = Zend_Registry::isRegistered('everification_admin') ? Zend_Registry::get('everification_admin') : null; ?>
<?php if($everification_admin) { ?>
  <?php if( count($this->navigation) ): ?>
    <div class='tabs'>
      <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
    </div>
  <?php endif; ?>
<?php } ?>
