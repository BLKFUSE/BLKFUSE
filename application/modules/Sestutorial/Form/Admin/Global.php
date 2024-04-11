<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sestutorial_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sestutorial.licensekey'),
    ));
    $this->getElement('sestutorial_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('sestutorial.pluginactivated')) {

      $this->addElement('Text', 'sestutorial_text_singular', array(
        'label' => 'Singular Text for "Tutorial"',
        'description' => 'Enter the text which you want to show in place of "Tutorial" at various places in this plugin like activity feeds, etc.',
        'value' => $settings->getSetting('sestutorial.text.singular', 'tutorial'),
      ));

      $this->addElement('Text', 'sestutorial_text_plural', array(
        'label' => 'Plural Text for "Tutorials"',
        'description' => 'Enter the text which you want to show in place of "Tutorials" at various places in this plugin like search form, navigation menu, etc.',
        'value' => $settings->getSetting('sestutorial.text.plural', 'tutorials'),
      ));

      $this->addElement('Text', 'sestutorial_tutorials_manifest', array(
          'label' => 'Plural "tutorials" Text in URL',
          'description' => 'Enter the text which you want to show in place of "tutorials" in the URLs of this plugin.',
          'value' => $settings->getSetting('sestutorial.tutorials.manifest', 'tutorials'),
      ));

      $this->addElement('Text', 'sestutorial_tutorial_manifest', array(
          'label' => 'Singular "tutorial" Text in URL',
          'description' => 'Enter the text which you want to show in place of "tutorial" in the URLs of this plugin.',
          'value' => $settings->getSetting('sestutorial.tutorial.manifest', 'tutorial'),
      ));

      $this->addElement('Radio', 'sestutorial_menuredirection', array(
          'label' => '"Tutorials" Main Menu Redirection',
          'description' => 'Choose from below where do you want to redirect users when Tutorials Menu item is clicked in the Main Navigation Menu Bar.',
          'multiOptions' => array(
              3 => 'Tutorials Home Page',
              2 => 'Tutorials Browse Page',
          ),
          'value' => $settings->getSetting('sestutorial.menuredirection', 3),
      ));

      $this->addElement('Radio', 'sestutorial_link', array(
          'label' => 'Display Tutorials Menu Item',
          'description' => 'Choose from below where do you want to display this Tutorials menu item link on your website? [If you want to show this link somewhere else instead of Main, Mini or Footer menus, then choose "None of the above." option.]',
          'multiOptions' => array(
              3 => 'In Main Navigation Menu Bar.',
              2 => 'In Mini Navigation Menu Bar.',
              1 => 'In Footer Menu Bar.',
              0 => 'None of the above.',
          ),
          'value' => $settings->getSetting('sestutorial.link', 3),
      ));

      $this->addElement('Radio', 'sestutorial_allowreport', array(
        'label' => 'Allow to Report Tutorials',
        'description' => 'Do you want to allow users to report Tutorials on your website?',
        'multiOptions' => array(
            1 => 'Yes',
            0 => 'No',
        ),
        'value' => $settings->getSetting('sestutorial.allowreport', 1),
      ));

      $this->addElement('Radio', 'sestutorial_allowshare', array(
        'label' => 'Allow to Share Tutorials',
        'description' => 'Do you want to allow users to share Tutorials on your website?',
        'multiOptions' => array(
          1 => 'Yes',
          0 => 'No',
        ),
        'value' => $settings->getSetting('sestutorial.allowshare', 1),
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
