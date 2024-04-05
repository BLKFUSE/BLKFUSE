<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: dismiss_message.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
 <?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenuItemName' => 'core_admin_main_plugins', 'childMenuItemName' => 'core_admin_main_plugins_sesmusic')); ?>
<style>
.sesmusic_nav_btns{
	float:right;
	margin-top:-62px !important;
}
.sesmusic_nav_btns a{
	background-color:#208ed3;
	border-radius:3px;
	background-position:10px center;
	background-repeat:no-repeat;
	color:#fff !important;
	float:left;
	font-weight:bold;
	padding:7px 15px 7px 30px;
	margin:0 8px;
	position:relative;
}
.sesmusic_nav_btns a:before{
	font-family:'Font Awesome 5 Free';
	left:10px;
	position:absolute;
	font-size:17px;
	font-weight:normal;
	top:5px;
}
.sesmusic_nav_btns a:hover{
	text-decoration:none;
	opacity:.8;
}
.sesmusic_nav_btns .help-btn:before{
	content:"\f059"
}
</style>
<h2 class="page_heading"><?php echo $this->translate("SESMUSIC_PLUGIN") ?></h2>
<div class="sesmusic_nav_btns">
  <a href="<?php echo $this->url(array('module' => 'sesmusic', 'controller' => 'settings', 'action' => 'support'),'admin_default',true); ?>" target = "_blank" class="help-btn">Help</a>
</div>
<?php if(!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic')) { ?>
  	<div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other features to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>
<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.pluginactivated')): ?>
  <?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
    <div class='tabs'>
      <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
    </div>
  <?php endif; ?>
<?php endif; ?>
