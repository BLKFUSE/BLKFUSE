<?php

class Sescontestjoinfees_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  public function __construct($application) {
    parent::__construct($application);
    
  }
  protected function _initFrontController() {

    $front = Zend_Controller_Front::getInstance();
    $front->registerPlugin(new Sescontestjoinfees_Plugin_Core);
  }
}