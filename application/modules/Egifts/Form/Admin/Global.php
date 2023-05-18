<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egifts_Form_Admin_Global extends Engine_Form {

  public function init() {
    
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $egifts_user = Zend_Registry::isRegistered('egifts_user') ? Zend_Registry::get('egifts_user') : null;
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "egifts_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('egifts.licensekey'),
    ));
    $this->getElement('egifts_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
     
    if ($settings->getSetting('egifts.pluginactivated')) {
        $this->addElement('Radio', "egifts_enable_price", array(
            'label' => 'Display Gift Price to Receiver',
            'description' => " Do you want to display gift price to the receivers which they get from other members on your site? If you select No below, then price will not get displayed at My Gifts page for received gifts & at Member profile Page.",
            'multiOptions' => array('1' => 'Yes','0' => 'No'),
            'value' => $settings->getSetting('egifts.enable.price', 1),
        ));
        if($egifts_user) {
          $this->addElement('Text', 'egifts_plural_manifest', array(
              'label' => 'Plural Text for "gifts" in URL',
              'description' => 'Enter the text which you want to show in place of "gifts" in the URLs of this plugin.',
              'allowEmpty' => false,
              'required' => true,
              'value' => $settings->getSetting('egifts.plural.manifest', 'gifts'),
          ));
          $this->addElement('Text', 'egifts_singular_manifest', array(
              'label' => 'Singular Text for "gift" in URL',
              'description' => 'Enter the text which you want to show in place of "gift" in the URLs of this plugin.',
              'allowEmpty' => false,
              'required' => true,
              'value' => $settings->getSetting('egifts.singular.manifest', 'gift'),
          ));
          $this->addElement('Text', 'egifts_text_plural', array(
              'label' => 'Plural Text for "Gifts"',
              'description' => 'Enter the text which you want to show in place of "Gifts" at various places in this plugin like search form, navigation menu, etc.',
              'allowEmpty' => false,
              'required' => true,
              'value' => $settings->getSetting('egifts.text.plural', 'gifts'),
          ));
          $this->addElement('Text', 'egifts_text_singular', array(
                'label' => 'Singular Text for "Gift"',
                'description' => 'Enter the text which you want to show in place of "Gift" at various places in this plugin.',
                'allowEmpty' => false,
                'required' => true,
                'value' => $settings->getSetting('egifts.text.singular', 'gift'),
          ));
        }
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
