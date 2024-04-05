<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating	
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: dismiss_message.tpl  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
 ?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenuItemName' => 'core_admin_main_plugins', 'childMenuItemName' => 'core_admin_main_plugins_sesdating')); ?>
<h2 class="page_heading"><?php echo $this->translate("Responsive Dating Theme") ?></h2>
<?php if(!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic')) { ?>
  	<div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other features to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>
<?php $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null; ?>
<?php if(!empty($sesdating_landingpage)) { ?>
  <?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
    <div class='tabs'>
      <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
    </div>
  <?php endif; ?>
<?php } ?>
