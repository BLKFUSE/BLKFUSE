<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sessociallogin
 * @package    Sessociallogin
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: dismiss_message.tpl 2017-07-04 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenuItemName' => 'core_admin_main_plugins', 'lastMenuItemName' => 'SNS - Social Media Login - 1 Click Social Connect Plugin')); ?>
<style>
.sessociallogin_nav_btns{
	float:right;
	margin-top:-62px !important;
}
.sessociallogin_nav_btns a{
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
.sessociallogin_nav_btns a:before{
	left:10px;
	position:absolute;
	font-size:17px;
	font-weight:normal;
	top:12px;
}
.sessociallogin_nav_btns a:hover{
	text-decoration:none;
	opacity:.8;
}
.sessociallogin_nav_btns .request-btn:before{
	content:"";
  background-image: url(application/modules/Sesbasic/externals/images/request.png);
  background-repeat:no-repeat;
  width:20px;
  height:20px
}
</style>
<h2 class="page_heading">
  <?php echo $this->translate("Social Media Login - 1 Click Social Connect Plugin") ?>
</h2>
<div class="sessociallogin_nav_btns">
  <a href="https://socialnetworking.solutions/contact-us/" target = "_blank" class="request-btn">Feature Request</a>
</div>
<?php if(!Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic')) { ?>
  	<div class="tip"><span><?php echo $this->translate("This plugin requires \"<a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>SocialNetworking.Solutions (SNS) Basic Required Plugin </a>\" to be installed and enabled on your website for Location and various other features to work. Please get the plugin from <a href='https://socialnetworking.solutions/social-engine/socialenginesolutions-basic-required-plugin/' target='_blank'>here</a> to install and enable on your site."); ?></span></div>
  <?php } ?>
<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
  <div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render() ?>
  </div>
<?php endif; ?>
