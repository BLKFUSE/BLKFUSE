<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Twitter.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Model_Dbtable_Twitter extends Engine_Db_Table
{
  protected $_api;

  protected $_oauth;
  protected $_name = 'user_twitter';
  public function getApi()
  {
    if( null === $this->_api ) {
      $this->_initializeApi();
    }

    return $this->_api;
  }

  public function getOauth()
  {
    if( null === $this->_oauth ) {
      $this->_initializeApi();
    }
    
    return $this->_oauth;
  }

  public function clearApi()
  {
    $this->_api = null;
    $this->_oauth = null;
    return $this;
  }

  public function isConnected()
  {
    // @todo make sure that info is validated
    return ( !empty($_SESSION['twitter_token2']) && !empty($_SESSION['twitter_secret2']) );
  }
  public function isEnabled(){
    // Load settings
    $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.twitter');
 
    if( empty($settings['key']) ||
        empty($settings['secret']) ||
        empty($settings['enable']) ||
        $settings['enable'] == 'none' ) {

      return false;
    }  
    
     return true;
  }
  protected function _initializeApi()
  {
    // Load classes
    include_once 'Services/Twitter.php';
    include_once 'HTTP/OAuth/Consumer.php';

    if( !class_exists('Services_Twitter', false) ||
        !class_exists('HTTP_OAuth_Consumer', false) ) {
      throw new Core_Model_Exception('Unable to load twitter API classes');
    }

    // Load settings
    $settings = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.twitter');
    if( empty($settings['key']) ||
        empty($settings['secret']) ||
        empty($settings['enable']) ||
        $settings['enable'] == 'none' ) {

      $this->_api = null;
      Zend_Registry::set('Twitter_Api', $this->_api);
    }

    // Try to log viewer in?
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !isset($_SESSION['twitter_uid']) ||
        @$_SESSION['twitter_lock'] !== $viewer->getIdentity() ) {
      $_SESSION['twitter_lock'] = $viewer->getIdentity();
      if( $viewer && $viewer->getIdentity() ) {
        // Try to get from db
        $info = $this->select()
            ->from($this)
            ->where('user_id = ?', $viewer->getIdentity())
            ->query()
            ->fetch();
        if( is_array($info) &&
            !empty($info['twitter_secret']) &&
            !empty($info['twitter_token']) ) {
          $_SESSION['twitter_uid'] = $info['twitter_uid'];
          $_SESSION['twitter_secret2'] = $info['twitter_secret'];
          $_SESSION['twitter_token2'] = $info['twitter_token'];
        } else {
          $_SESSION['twitter_uid'] = false; // @todo make sure this gets cleared properly
        }
      } else {
        // Could not get
        //$_SESSION['twitter_uid'] = false;
      }
    }
    
    $this->_api = new Services_Twitter();

    // Get oauth
    if( isset($_SESSION['twitter_token2'], $_SESSION['twitter_secret2']) ) {
      $this->_oauth = new HTTP_OAuth_Consumer($settings['key'], $settings['secret'],
          $_SESSION['twitter_token2'], $_SESSION['twitter_secret2']);
    } else if( isset($_SESSION['twitter_token'], $_SESSION['twitter_secret']) ) {
      $this->_oauth = new HTTP_OAuth_Consumer($settings['key'], $settings['secret'],
          $_SESSION['twitter_token'], $_SESSION['twitter_secret']);
    } else {
      $this->_oauth = new HTTP_OAuth_Consumer($settings['key'], $settings['secret']);
    }
    $this->_api->setOAuth($this->_oauth);
  }

  /**
   * Generates the button used for Twitter Connect
   */
  public static function loginButton($connect_text = 'Sign-in with Twitter')
  {
    return Zend_Controller_Front::getInstance()->getRouter()
        ->assemble(array('module' => 'sesadvancedactivity', 'controller' => 'auth',
          'action' => 'twitter'), 'default', true);
   
  }
}
