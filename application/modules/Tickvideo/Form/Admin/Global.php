<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Tickvideo
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2020-11-03  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Tickvideo_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Global Settings')
        ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "tickvideo_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('tickvideo.licensekey'),
    ));
    $this->getElement('tickvideo_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    if ($settings->getSetting('tickvideo.pluginactivated')) {

      $this->addElement('Radio', 'tickvideo_allow_video', array(
        'label' => 'Do you want to allow to show tickvideo inside video module.?',
        // 'description' => 'Do you want to make a unauthorized videos searchable? (If set to no, videos that are not authorized for the current user will not be displayed in the video search results and widgets.)',
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('tickvideo.allow.video', 0),
        'multiOptions' => array(
          '1' => 'Yes',
          '0' => 'No',
        ),
      ));

      // Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Save Changes',
          'type' => 'submit',
          'ignore' => true
      ));
    } else {
      $enabledSesbasic = Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesbasic');
      $fields = array(
          'label' => 'Activate This Plugin',
          'type' => 'submit',
          'ignore' => true
      );
      if(!$enabledSesbasic){
        $fields['disable'] = true;
        $fields['title'] = 'To Activate this plugin, please first install all dependent plugins as show in the tips above.';
      }
      //Add submit button
      $this->addElement('Button', 'submit',$fields);
    }

  }
}
