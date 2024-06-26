<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Facebook.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_DbTable_Facebook extends Engine_Db_Table
{
  protected $_api;

  public static function getFBInstance()
  {
    return Engine_Api::_()->getDbtable('facebook', 'user')->getApi();
  }

  public function getApi()
  {
    // Already initialized
    if( null !== $this->_api ) {
      return $this->_api;
    }

    // Need to initialize
    $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.facebook');
    if( empty($settings['secret']) ||
        empty($settings['appid']) ||
        empty($settings['enable']) ||
        $settings['enable'] == 'none' ) {
      $this->_api = null;
      Zend_Registry::set('Facebook_Api', $this->_api);
      return false;
    }

    $this->_api = new Facebook_Api(array(
      'appId'  => $settings['appid'],
      'secret' => $settings['secret'],
      'cookie' => false, // @todo make sure this works
      'baseDomain' => $_SERVER['HTTP_HOST'],
    ));
    Zend_Registry::set('Facebook_Api', $this->_api);

    // Try to log viewer in?
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !isset($_SESSION['facebook_uid']) ||
        @$_SESSION['facebook_lock'] !== $viewer->getIdentity() ) {
      $_SESSION['facebook_lock'] = $viewer->getIdentity();
      if( $this->_api->getUser() ) {
        $_SESSION['facebook_uid'] = $this->_api->getUser();
      } else if( $viewer && $viewer->getIdentity() ) {
        // Try to get from db
        $table =  Engine_Api::_()->getDbtable('facebook', 'user');
        $info = $table->select()
            ->from($table->info('name'))
            ->where('user_id = ?', $viewer->getIdentity())
            ->query()
            ->fetch();
        if( is_array($info) && !empty($info['facebook_uid']) &&
            !empty($info['access_token']) && !empty($info['code']) ) {
          $_SESSION['facebook_uid'] = $info['facebook_uid'];
          $this->_api->setPersistentData('code', $info['code']);
          $this->_api->setPersistentData('access_token', $info['access_token']);
        } else {
          // Could not get
          $_SESSION['facebook_uid'] = false;
        }
      } else {
        // Could not get
        //$_SESSION['facebook_uid'] = false;
      }
    }
    
    return $this->_api;
  }

  public function isConnected()
  {
    if( ($api = $this->getApi()) ) {
      return (bool) $api->getUser();
    } else {
      return false;
    }
  }

  public function checkConnection(User_Model_User $user = null)
  {
    if( null === $user ) {
      $user = Engine_Api::_()->user()->getViewer();
    }
    try {
			if(!$this->getApi())
			 return false;
      $this->getApi()->api('/me');
      $fb_uid = Engine_Api::_()->getDbtable('facebook', 'user')
          ->fetchRow(array('user_id = ?' => $user->getIdentity()));
    } catch( Exception $e ) {
      return false;
    }
    
    if( !$fb_uid || !$fb_uid->facebook_uid || $fb_uid->facebook_uid != $this->getApi()->getUser() ) {
      return false;
    } else {
      return true;
    }
  }
  
  /**
   * Generates the button used for Facebook Connect
   *
   * @param mixed $fb_params A string or array of Facebook parameters for login
   * @param string $connect_with_facebook The string to display inside the button
   * @return String Generates HTML code for facebook login button
   */
  public static function loginButton($connect_text = 'Connect with Facebook')
  {
     return Zend_Controller_Front::getInstance()->getRouter()
        ->assemble(array('module' => 'sesadvancedactivity', 'controller' => 'auth',
          'action' => 'facebook'), 'default', true); 
  }
}
