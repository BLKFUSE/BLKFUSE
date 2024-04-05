<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _jsFiles.tpl 2017-01-12 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php 
	$staticBaseUrl = $this->layout()->staticBaseUrl;
	//$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesadvancedcomment/externals/scripts/core.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/member/membership.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.tooltip.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/tooltip.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/hashtag/autosize.min.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/mention/underscore-min.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/mention/jquery.mentionsInput.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
	$this->headScript()->appendFile($staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
	$this->headScript()->appendFile($staticBaseUrl .  'application/modules/Sesbasic/externals/scripts/hashtag/hashtags.js');
	$this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesadvancedcomment/externals/styles/styles.css');
	$this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesbasic/externals/styles/mention/jquery.mentionsInput.css');
	$this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesbasic/externals/styles/emoji.css');
	$this->headLink()->appendStylesheet($staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css');  
?>
