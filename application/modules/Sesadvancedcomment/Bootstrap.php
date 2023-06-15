<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
class Sesadvancedcomment_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  public function __construct($application) {

    parent::__construct($application);
    
    $this->initViewHelperPath();
    $baseUrl = Zend_Registry::get('StaticBaseUrl');
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $view->headTranslate(array('Write a comment...'));
    
		$headScript = new Zend_View_Helper_HeadScript();
		if (strpos($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], '/admin/') === FALSE) {
			$headScript->appendFile($baseUrl . 'application/modules/Sesadvancedcomment/externals/scripts/core.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/member/membership.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.tooltip.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/tooltip.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.min.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/hashtag/autosize.min.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/mention/underscore-min.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/mention/jquery.mentionsInput.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
			$headScript->appendFile($baseUrl .  'application/modules/Sesbasic/externals/scripts/hashtag/hashtags.js');
			$view->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesadvancedcomment/externals/styles/styles.css');
			$view->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesadvancedactivity/externals/styles/styles.css');
			$view->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesbasic/externals/styles/mention/jquery.mentionsInput.css');
			$view->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesbasic/externals/styles/emoji.css');
			$view->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css');  
		}
  }

  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sesadvancedcomment/controllers/Checklicense.php';
  }
}
