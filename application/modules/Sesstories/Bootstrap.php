<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  public function __construct($application) {
      parent::__construct($application);
      if(strpos(str_replace('/','',$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']),str_replace('/','',$_SERVER['SERVER_NAME'].'admin'))=== FALSE){
        $this->initViewHelperPath();
          $baseURL = Zend_Registry::get('StaticBaseUrl');
        $headScript = new Zend_View_Helper_HeadScript();
        $headScript->appendFile($baseURL .'application/modules/Sesstories/externals/scripts/core.js');
      }
      
      $front = Zend_Controller_Front::getInstance();
      $front->registerPlugin(new Sesstories_Plugin_Core);
  }
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sesstories/controllers/Checklicense.php';
  }
}
