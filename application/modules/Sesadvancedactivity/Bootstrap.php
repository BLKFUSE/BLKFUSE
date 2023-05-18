<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Bootstrap.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Bootstrap extends Engine_Application_Bootstrap_Abstract
{
  public function __construct($application)
  {
    parent::__construct($application);
    $settings = Engine_Api::_()->getApi('settings', 'core');// GitHub Issue #119
    if ($settings->getSetting('sesadvancedactivity.pluginactivated'))
      $this->initViewHelperPath();

      $front = Zend_Controller_Front::getInstance();
      $front->registerPlugin(new Sesadvancedactivity_Plugin_Core);
  }

  protected function _initFrontController() {
  
    $headScript = new Zend_View_Helper_HeadScript();
    //Advanced Notification work based on admin settings
    $advancednotification = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesadvancedactivity.advancednotification', 0);
    if ($advancednotification) {
      $headScript->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesadvancedactivity/externals/scripts/updates_notifications.js');
    }
    include APPLICATION_PATH . '/application/modules/Sesadvancedactivity/controllers/Checklicense.php';
  }
}
