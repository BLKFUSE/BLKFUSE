<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeelingactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesfeelingactivity_Form_Admin_Settings_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this
            ->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

		$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;

    if ($settings->getSetting('sesfeelingactivity.pluginactivated')) {

      $this->addElement('Radio', 'sesfeelingactivity_enablefeeling', array(
        'label' => 'Enable Feeling/Activity',
        'description' => 'Do you want to enable your members to share their feeling/activity in their status update posts on your website?',
        'multiOptions' => array(
            '1' => 'Yes',
            '0' => 'No',
        ),
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesfeelingactivity.enablefeeling', 1),
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
