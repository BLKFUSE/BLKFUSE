<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Global.php 9747 2012-07-26 02:08:08Z john $
 * @author     John Boehr <j@webligo.com>
 */

/**
 * @category   Application_Core
 * @package    Payment
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Payment_Form_Admin_Settings_Global extends Engine_Form
{
  public function init()
  {

    $description = $this->getTranslator()->translate(
          'These settings affect all members in your community. <br>');

    $settings = Engine_Api::_()->getApi('settings', 'core');

    if( $settings->getSetting('user.support.links', 0) == 1 ) {
      $moreinfo = $this->getTranslator()->translate(
          'More Info: <a href="%1$s" target="_blank"> KB Article</a>');
    } else {
      $moreinfo = $this->getTranslator()->translate(
          '');
    }

    $description = vsprintf($description.$moreinfo, array(
      'https://community.socialengine.com/blogs/597/75/billing-settings',
    ));

    // Decorators
    $this->loadDefaultDecorators();
    $this->getDecorator('Description')->setOption('escape', false);

    $this
      ->setTitle('Global Settings')
      ->setDescription($description);

    $gateways = '';
    $gatewaysTable = Engine_Api::_()->getDbtable('gateways', 'payment');
    foreach( $gatewaysTable->fetchAll() as $gateway ) {
      $gateways .= $gateway->title . ', ';
    }
    $gateways = trim($gateways);
    $gateways = rtrim($gateways, ',');

    $currency_description = 'Supported Gateways: '.$gateways;

    // Element: currency
    $this->addElement('Select', 'currency', array(
      'label' => 'Currency',
      'value' => 'USD',
      'description' => $currency_description,
      'value' => $settings->getSetting('payment.currency'),
    ));
    $this->getElement('currency')->getDecorator('Description')->setOption('placement', 'APPEND');
    
    $this->addElement('Select', 'autoupdate', array(
      'label' => 'Automatically Update Currency Exchange Rates',
      'multiOptions' => array('1'=>'Yes','0'=>'No'),
      'value' => $settings->getSetting("payment.autoupdate",0),
      'onchange' => "autoUpdateCurrency(this.value);",
    ));

    //currency api key
    $url = '<a href="https://free.currencyconverterapi.com/free-api-key" target="_blank">Click here</a>';
    $description = sprintf('Enter the currency converter API key. %s to create a free license key.',$url);
    $this->addElement('Text', "currencyapikey", array(
      'label' => 'Enter Currency Converter API Key',
      'description' => $description,
      'allowEmpty' => true,
      'required' => false,
      'value' => $settings->getSetting('payment.currencyapikey'),
    ));
    $this->getElement('currencyapikey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    $this->addElement('Button', 'execute', array(
      'label' => 'Save Changes',
      'type' => 'submit',
    ));
  }
}
