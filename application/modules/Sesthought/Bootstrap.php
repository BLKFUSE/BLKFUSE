<?php

class Sesthought_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  public function __construct($application) {
  
    parent::__construct($application);
    
    if (strpos(str_replace('/', '', $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']), str_replace('/', '', $_SERVER['SERVER_NAME'] . 'admin')) === FALSE) {
      $baseURL = Zend_Registry::get('StaticBaseUrl');
      $headScript = new Zend_View_Helper_HeadScript();
      $headScript->appendFile($baseURL . 'application/modules/Sesthought/externals/scripts/core.js');
    }
  }
	
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sesthought/controllers/Checklicense.php';
  }
}