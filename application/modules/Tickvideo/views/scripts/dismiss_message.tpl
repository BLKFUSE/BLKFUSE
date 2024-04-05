<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: dismiss_message.tpl 2020-11-03 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php echo $this->partial('_admin_breadcrumb.tpl', 'core', array('parentMenuItemName' => 'core_admin_main_plugins', 'childMenuItemName' => 'core_admin_main_plugins_tickvideo')); ?>
<style>
.tikvideos_nav_btns{
	float:right;
	margin-top:-62px !important;
}
.tikvideos_nav_btns a{
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
.tikvideos_nav_btns a:before{
	font-family:'Font Awesome 5 Free';
	left:10px;
	position:absolute;
	font-size:17px;
	font-weight:normal;
	top:5px;
}
.tikvideos_nav_btns a:hover{
	text-decoration:none;
	opacity:.8;
}
.tikvideos_nav_btns .help-btn:before{
	content:"\f059"
}
</style>

<h2 class="page_heading"><?php echo $this->translate("Short TikTak Videos Plugin for Mobile Apps") ?></h2>
<div class="tikvideos_nav_btns">
    <a href="<?php echo 'admin/tickvideo/settings/support' ?>"  class="help-btn">Help Center</a>
</div>

<?php if(is_countable($this->navigation) && engine_count($this->navigation) ): ?>
<div class='tabs'>
    <?php echo $this->navigation()->menu()->setContainer($this->navigation)->render(); ?>
</div>
<?php endif; ?>
