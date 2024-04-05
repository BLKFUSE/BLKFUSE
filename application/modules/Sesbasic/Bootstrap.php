<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbasic_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  protected function _initFrontController() {
    Zend_Controller_Front::getInstance()->registerPlugin(new Sesbasic_Plugin_Core);
  }
  
  public function __construct($application) {
  
    parent::__construct($application);
    
    $this->initViewHelperPath();
    $baseUrl = Zend_Registry::get('StaticBaseUrl');
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $headScript = new Zend_View_Helper_HeadScript();
    
    if (strpos($_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'], '/admin/') === FALSE) {
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/core.js');
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/sessmoothbox/sessmoothbox.js');
			
			$headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/tooltip/jquery.tooltipster.js');
			$view->headLink()->appendStylesheet($baseUrl . "application/modules/Sesbasic/externals/styles/tooltip/tooltipster.css");

			//pass comma seprated string of modules
      $pluginNames = array('sesalbum', 'sesvideo', 'sesevent', 'sesblog', 'sesgroupalbum', 'sesmember',"sesadvancedactivity", "emultlist");
      if (Engine_Api::_()->sesbasic()->isModuleEnable($pluginNames)) {
        $headScript->appendFile($baseUrl . 'application/modules/Sesbasic/externals/scripts/jquery.flex-images.js');
      }
    }
    
	  if(!Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesmultiplecurrency')){
			$session = new Zend_Session_Namespace('ses_multiple_currency');
      $session->multipleCurrencyPluginActivated = 0;
    }
    
		//Clear warning log into production mode.
		if (APPLICATION_ENV == 'production') {
			file_put_contents(APPLICATION_PATH . '/temporary/log/warnings.log', '');
		}
		
    //Tweet plugin work
    $script = '';
    $script .= "var sestweet_text = '';";
    $view->headScript()->appendScript($script);

		$this->initViewHelperPath();
		
		$layout = Zend_Layout::getMvcInstance();
		$layout->getView()->addFilterPath(APPLICATION_PATH . "/application/modules/Sesbasic/View/Filter", 'Sesbasic_View_Filter_')->addFilter('Shortcode');
  }
}
