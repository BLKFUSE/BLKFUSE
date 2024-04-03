<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontestjurymember
 * @package    Sescontestjurymember
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2018-02-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescontestjurymember_Form_Admin_Global extends Engine_Form {

  public function init() {
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Voting by Jury Members Global Settings')
            ->setDescription("");
            
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    if ($settings->getSetting('sescontestjurymember.pluginactivated')) {
      //Add Element: Dummy
      $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
      if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sescontestpackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sescontestpackage.enable.package', 0)) {
        $fileLink = $view->baseUrl() . '/admin/sescontestpackage/package';
      } else {
        $fileLink = $view->baseUrl() . '/admin/sescontest/settings/level';
      }
      $this->addElement('Dummy', 'sescontestjurymember_memberlevel_setting', array(
          'label' => 'Allow to Add Jury Members',
          'description' => 'Please <a target="_blank" href="' . $fileLink . '">click here</a>. to choose appropriate value for this setting to enable contest owners to add Jury Members to their contests. If you have enabled Packages on your website, then you can configure this setting while adding or editing a package.',
          'class' => '',
      ));
      $fileLink = $view->baseUrl() . '/admin/sescontest/settings/entrylevel';
      $this->addElement('Dummy', 'sescontestjurymember_memberlevel_votesetting', array(
          'label' => 'Jury Vote Count Weightage',
          'description' => 'Please <a target="_blank" href="' . $fileLink . '">click here</a>. to configure this setting based on the member level of the jury member.',
          'class' => '',
      ));
      $this->sescontestjurymember_memberlevel_setting->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));
      $this->sescontestjurymember_memberlevel_votesetting->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      //Add submit button
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
