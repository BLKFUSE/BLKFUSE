<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Gateway.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Epaidcontent_Model_Gateway extends Core_Model_Item_Abstract
{
  protected $_searchTriggers = false;

  protected $_modifiedTriggers = false;
  protected $_type = 'payment_gateway';
  /**
   * @var Engine_Payment_Plugin_Abstract
   */
  protected $_plugin;
  
  /**
   * Get the payment plugin
   *
   * @return Engine_Payment_Plugin_Abstract
   */
  public function getPlugin($type = 'ticket')
  {
      if( null === $this->_plugin ) {
        $class = $this->plugin;
        if($this->plugin == "Sesadvpmnt_Plugin_Gateway_Stripe"):
          $class = str_replace('Sesadvpmnt','Epaidcontent',$class);
        elseif($this->plugin == "Epaytm_Plugin_Gateway_Paytm"):
          $class = str_replace('Epaytm','Epaidcontent',$class);
        else:
          $class = str_replace('Payment','Epaidcontent',$class);
        endif;
				Engine_Loader::loadClass($class);
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
  public function getGateway($type = 'ticket')
  {
		return $this->getPlugin($type)->getGateway();
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
