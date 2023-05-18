<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_Bootstrap extends Engine_Application_Bootstrap_Abstract { 
  
  protected function _initRouter() {
  
    $router = Zend_Controller_Front::getInstance()->getRouter();
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.pluginactivated')) {
      $integrateothermodulesTable = Engine_Api::_()->getDbTable('integrateothermodules', 'sesmusic');
      $select = $integrateothermodulesTable->select();
      $results = $integrateothermodulesTable->fetchAll($select);
      if(engine_count($results) > 0) {
        foreach ($results as $result) {
          $router->addRoute('sesmusic_browsemusicalbums_' . $result->getIdentity(), new Zend_Controller_Router_Route($result->content_url . '/music/albums', array('module' => 'sesmusic', 'controller' => 'index', 'action' => 'browse-musicalbums', 'resource_type' => $result->content_type ,'integrateothermodule_id' => $result->integrateothermodule_id)));
        }
        return $router;
      }
    }
  }
	
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sesmusic/controllers/Checklicense.php';
  }
}
