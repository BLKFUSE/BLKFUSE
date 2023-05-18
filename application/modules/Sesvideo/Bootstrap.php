<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
class Sesvideo_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  public function __construct($application) {
  
    parent::__construct($application);
    if (strpos(str_replace('/', '', $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']), str_replace('/', '', $_SERVER['SERVER_NAME'] . 'admin')) === FALSE) {
      $baseURL = Zend_Registry::get('StaticBaseUrl');
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      $this->initViewHelperPath();
    }
  }
	
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sesvideo/controllers/Checklicense.php';
  }
  
  protected function _initRouter() {
  
    $router = Zend_Controller_Front::getInstance()->getRouter();
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.pluginactivated')) {
      $integrateothermodulesTable = Engine_Api::_()->getDbTable('integrateothermodules', 'sesvideo');
      $select = $integrateothermodulesTable->select();
      $results = $integrateothermodulesTable->fetchAll($select);
      if(engine_count($results) > 0) {
        foreach ($results as $result) {
          $router->addRoute('sesvideo_browsevideo_' . $result->getIdentity(), new Zend_Controller_Router_Route($result->content_url . '/browse-videos', array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'browse-videos', 'resource_type' => $result->content_type ,'integrateothermodule_id' => $result->integrateothermodule_id)));
        }
        return $router;
      }
    }
  }
}
