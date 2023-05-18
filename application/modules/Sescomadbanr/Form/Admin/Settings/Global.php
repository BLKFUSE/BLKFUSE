<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescomadbanr
 * @package    Sescomadbanr
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2019-03-08 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescomadbanr_Form_Admin_Settings_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')->setDescription('These settings affect all members in your community.');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);
    
    if ($settings->getSetting('sescomadbanr.pluginactivated')) {

        $this->addElement('Text', "sescomadbanr_paymentemail", array(
            'label' => 'Email of Your Paypal',
            'description' => "Enter email of your paypal account where you want to get payment from users.",
            'allowEmpty' => false,
            'required' => true,
            'value' => $settings->getSetting('sescomadbanr.paymentemail', ''),
        ));

        $this->addElement('Text', "sescomadbanr_itemname", array(
            'label' => 'Item Name',
            'description' => "Enter item name which you want to show when send payment link to users.",
            'allowEmpty' => false,
            'required' => true,
            'value' => $settings->getSetting('sescomadbanr.itemname', 'Ads'),
        ));

        $this->addElement('Text', "sescomadbanr_sponsored", array(
            'label' => 'Sponsored Text',
            'description' => "Enter text for Sponsored that you want to change.",
            'allowEmpty' => false,
            'required' => true,
            'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sescomadbanr.sponsored', 'Advertisement'),
        ));

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
    } else {
      //Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Activate This Plugin',
          'type' => 'submit',
          'ignore' => true
      ));
    }
  }
}
