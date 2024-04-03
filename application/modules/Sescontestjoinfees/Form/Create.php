<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjoinfees
 * @package    Sescontestjoinfees
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Create.php  2017-12-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontestjoinfees_Form_Create extends Engine_Form
{
  public function init()
  {
    parent::init();
    $this->setTitle('Entry Fees');
    
    $descri = "There seems to be some issue with the payment gateway details of this website. Please contact our site administrators to  proceed with entering the fees for your contest.";
    
    //check gateway enable
    $gatewayTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    $gatewaySelect = $gatewayTable->select()
      ->where('enabled = ?', 1);
    $gateways = $gatewayTable->fetchAll($gatewaySelect);
    
    $enable = false;
    foreach($gateways as $gateway){
      if($gateway->enabled){
        $enable = true;
        break;  
      }
    }
    
    if(!$enable)
      $description = $this->getTranslator()->translate($descri);
    else
      $description = $this->getTranslator()->translate("Below, enter the Contest Joining Fees or Entry Submission Fees, which you want to charge from members who wants to participate in your contest.");
    $this->setDescription($description);
    // Decorators
    $this->loadDefaultDecorators();
    $this->getDecorator('Description')->setOption('escape', false);
    if(!$enable)
      return;
    // Elements
    $defaultCurrency = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmultiplecurrency.defaultcurrency',Engine_Api::_()->getApi('settings', 'core')->getSetting('payment.currency', 'USD'));
		//Event Currency
		$this->addElement('Select', 'currency', array(
		 		'label'=>'Currency',
        'multiOptions' => array($defaultCurrency=>$defaultCurrency),
    ));
    $this->addElement('Text', 'entry_fees', array(
      'label' => 'Entry Fees',
      'filters' => array(
       new Zend_Filter_StringTrim(),
      ),
      'value'=>0,
      'validators' => array(
            array('Int', true),
        )
    ));
    
		// Element: execute
    $this->addElement('Button', 'execute', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'decorators' => array('ViewHelper'),
      'order' => 10001,
      'ignore' => true,
    ));
  }
}
