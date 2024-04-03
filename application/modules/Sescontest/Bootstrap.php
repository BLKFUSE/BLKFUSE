<?php
/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Bootstrap.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Bootstrap extends Engine_Application_Bootstrap_Abstract {

  public function __construct($application) {
    parent::__construct($application);
    $this->initViewHelperPath();
    if (!class_exists('Core_Model_Like', false))
      include_once APPLICATION_PATH . '/application/modules/Core/Model/Like.php';
    Engine_Api::_()->getDbTable('likes', 'core')->setRowClass('Sescontest_Model_Like');
  }
  protected function _initFrontController() {
    include APPLICATION_PATH . '/application/modules/Sescontest/controllers/Checklicense.php';
  }
  protected function _initRouter() {
  
    $router = Zend_Controller_Front::getInstance()->getRouter();
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontest.pluginactivated')) {
      $integrateothermodulesTable = Engine_Api::_()->getDbTable('integrateothermodules', 'sescontest');
      $select = $integrateothermodulesTable->select();
      $results = $integrateothermodulesTable->fetchAll($select);
      if(engine_count($results) > 0) {
        foreach ($results as $result) {
          $router->addRoute('sescontest_browsecontest_' . $result->getIdentity(), new Zend_Controller_Router_Route($result->content_url . '/browse-contests', array('module' => 'sescontest', 'controller' => 'index', 'action' => 'browse-contests', 'resource_type' => $result->content_type ,'integrateothermodule_id' => $result->integrateothermodule_id)));
        }
        return $router;
      }
    }
  }
}
