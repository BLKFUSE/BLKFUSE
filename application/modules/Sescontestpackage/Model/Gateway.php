<?php
class Sescontestpackage_Model_Gateway extends Core_Model_Item_Abstract
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
      $class = $this->plugin;
      if($this->plugin == "Sesadvpmnt_Plugin_Gateway_Stripe"):
        $class = str_replace('Sesadvpmnt','Sescontestpackage',$class);
      elseif($this->plugin == "Epaytm_Plugin_Gateway_Paytm"):
        $class = str_replace('Epaytm','Sescontestpackage',$class);
      elseif($this->plugin == "Ecashfree_Plugin_Gateway_Cashfree"):
        $class = str_replace('Ecashfree','Sescontestpackage',$class);
      else:
        $class = str_replace('Payment','Sescontestpackage',$class);
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
