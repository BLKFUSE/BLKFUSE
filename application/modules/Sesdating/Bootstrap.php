<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Bootstrap extends Engine_Application_Bootstrap_Abstract {

	public function __construct($application) {
	
    parent::__construct($application);
    
    $front = Zend_Controller_Front::getInstance();
    $front->registerPlugin(new Sesdating_Plugin_Core);
		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    if(strpos($_SERVER['REQUEST_URI'],'admin/menus') !== FALSE  ){
       $headScript = new Zend_View_Helper_HeadScript();
  	  
      $headScript->appendFile($baseUrl . 'application/modules/Sesdating/externals/scripts/admin.js');
    }
   
  
    $this->initViewHelperPath();    
    $layout = Zend_Layout::getMvcInstance();
    $layout->getView()
            ->addFilterPath(APPLICATION_PATH . "/application/modules/Sesdating/View/Filter", 'Sesdating_View_Filter_')
            ->addFilter('Bodyclass');
    
	}
	
  protected function _initFrontController() {
  
    $this->initActionHelperPath();
    Zend_Controller_Action_HelperBroker::addHelper(new Sesdating_Controller_Action_Helper_LoginError());
    include APPLICATION_PATH . '/application/modules/Sesdating/controllers/Checklicense.php';
  }
}
