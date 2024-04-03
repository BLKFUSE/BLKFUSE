<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Usergateway.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjoinfees_Model_Usergateway extends Core_Model_Item_Abstract
{
  protected $_searchTriggers = false;

  protected $_modifiedTriggers = false;
  
  /**
   * @var Engine_Payment_Plugin_Abstract
   */
  protected $_plugin;
  
  /**
   * Get the payment plugin
   *
   * @return Engine_Payment_Plugin_Abstract
   */
  public function getPlugin()
  {
    if( null === $this->_plugin ) {
			$settings = Engine_Api::_()->getApi('settings', 'core');
   	  $userGatewayEnable = $settings->getSetting('sescontest.userGateway', 'paypal');
			if($this->plugin == "Sescontestjoinfees_Plugin_Gateway_Event_Stripe"){
          $class = 'Sescontestjoinfees_Plugin_Gateway_Event_Stripe';
          Engine_Loader::loadClass('Sescontestjoinfees_Plugin_Gateway_Event_Stripe');
			} elseif($this->plugin == "Sescontestjoinfees_Plugin_Gateway_Event_Paytm"){
          $class = 'Sescontestjoinfees_Plugin_Gateway_Event_Paytm';
          Engine_Loader::loadClass('Sescontestjoinfees_Plugin_Gateway_Event_Paytm');
			} elseif($this->plugin == "Sescontestjoinfees_Plugin_Gateway_PayPal") {
          $class = 'Sescontestjoinfees_Plugin_Gateway_PayPal';
          Engine_Loader::loadClass('Sescontestjoinfees_Plugin_Gateway_Event_PayPal');
			} else {
          $class = $this->plugin;
      }
      $plugin = new $class($this);
      if( !($plugin instanceof Engine_Payment_Plugin_Abstract) ) {
        throw new Engine_Exception(sprintf('Payment plugin "%1$s" must ' .
            'implement Engine_Payment_Plugin_Abstract', $class));
      }
      $this->_plugin = $plugin;
    }
    return $this->_plugin;
  }

  /**
   * Get the payment gateway
   * 
   * @return Engine_Payment_Gateway
   */
  public function getGateway()
  {
    return $this->getPlugin()->getGateway();
  }

  /**
   * Get the payment service api
   * 
   * @return Zend_Service_Abstract
   */
  public function getService()
  {
    return $this->getPlugin()->getService();
  }
}
