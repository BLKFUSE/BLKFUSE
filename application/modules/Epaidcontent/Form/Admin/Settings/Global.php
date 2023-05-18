<?php

/**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Epaidcontent
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2022-08-16 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Epaidcontent_Form_Admin_Settings_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')
        ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "epaidcontent_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('epaidcontent.licensekey'),
    ));
    $this->getElement('epaidcontent_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('epaidcontent.pluginactivated')) {
    
      $this->addElement('Select', 'epaidcontent_allow', array(
        'label' => 'Enable Paid Content',
        'description'=>'Do you want to allow paid content functionality for owners to take payment from the members who want to view their content?',
        'multiOptions'=>array('1'=>'Yes','0'=>'No'),
        'value'=>$settings->getSetting('epaidcontent.allow',1),
        'onchange' => 'hideShowSettings(this.value);',
	    ));
	    
      $this->addElement('Select', 'epaidcontent_sesalbum', array(
        'label' => 'Enable in "Advanced Photos & Albums Plugin"',
        'description'=>'Do you want to allow paid content functionality in "Advanced Photos & Albums Plugin" for owners to take payment from the members who want to view their content?',
        'multiOptions'=>array('1'=>'Yes','0'=>'No'),
        'value'=> $settings->getSetting('epaidcontent.sesalbum',1),
	    ));
      $this->addElement('Select', 'epaidcontent_sesvideo', array(
        'label' => 'Enable in "Advanced Videos & Channels Plugin"',
        'description'=>'Do you want to allow paid content functionality in "Advanced Videos & Channels Plugin" for owners to take payment from the members who want to view their content?',
        'multiOptions'=>array('1'=>'Yes','0'=>'No'),
        'value'=>$settings->getSetting('epaidcontent.sesvideo',1),
	    ));
      $this->addElement('Select', 'epaidcontent_sesmusic', array(
        'label' => 'Enable in "Professional Music Plugin"',
        'description'=>'Do you want to allow paid content functionality "Professional Music Plugin" for owners to take payment from the members who want to view their content?',
        'multiOptions'=>array('1'=>'Yes','0'=>'No'),
        'value'=>$settings->getSetting('epaidcontent.sesmusic',1),
	    ));

      $commission = '<a href="admin/epaidcontent/settings/level">Click here</a> to set commission which you will receive when users pay fees for the joining paid pages on your website.';
      $this->addElement('Dummy', 'commision', array(
	        'label' => 'Admin Commission',
          'description' => sprintf('%s',$commission),
	    ));
      $this->getElement('commision')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $this->addElement('Button', 'submit', array(
          'label' => 'Save Changes',
          'type' => 'submit',
          'ignore' => true
      ));
    } else {
      $this->addElement('Button', 'submit', array(
          'label' => 'Activate This Plugin',
          'type' => 'submit',
          'ignore' => true
      ));
    }
  }
}
