<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: RequireUser.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesapi_Controller_Action_Helper_RequireUser extends
  Core_Controller_Action_Helper_RequireAbstract
{
  protected $_errorAction = array('requireuser', 'error', 'core');
  
  public function checkRequire()
  {
    try
    {
      $viewer = Engine_Api::_()->user()->getViewer();
    }
    catch( Exception $e )
    {
      $viewer = null;
    }

    $ret = false;
    if( $viewer instanceof Core_Model_Item_Abstract && $viewer->getIdentity() )
    {
      $ret = true;
    }

    if( !$ret && APPLICATION_ENV == 'development' && Zend_Registry::isRegistered('Zend_Log') && ($log = Zend_Registry::get('Zend_Log')) instanceof Zend_Log )
    {
      $target = $this->getRequest()->getModuleName() . '.' .
              $this->getRequest()->getControllerName() . '.' .
              $this->getRequest()->getActionName();
      $log->log('Require class '.get_class($this).' failed check for: '.$target, Zend_Log::DEBUG);
    }

    return $ret;
  }
}