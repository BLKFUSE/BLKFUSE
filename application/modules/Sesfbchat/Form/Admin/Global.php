<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesfbchat
 * @package    Sesfbchat
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2019-01-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesfbchat_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')
        ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesfbchat_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesfbchat.licensekey'),
    ));
    $this->getElement('sesfbchat_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('sesfbchat.pluginactivated')) {
    
      $this->addElement('Radio', "sesfbchat_enable_messenger", array(
          'label' => 'Enable FB Customer Chat',
          'description' => 'Do you want to enable the FB Messenger Customer Live Chat on your website?',
          'allowEmpty' => false,
          'required' => true,
          'multiOptions'=>array(
            '1'=>'Yes',
            '0'=>'No',
          ),
          'value' => $settings->getSetting('sesfbchat_enable_messenger','1'),
      ));

      $required = false;
      $allowEmpty = true;

      if((!empty($_POST["sesfbchat_enable_messenger"]) && $_POST["sesfbchat_enable_messenger"] == 1)) {
          $required = true;
          $allowEmpty = false;
      }


			$this->addElement('Text', "sesfbchat_app_id", array(
          'label' => 'App Id',
          'description' => '',

          'value' => $settings->getSetting('sesfbchat_app_id',''),
      ));
      $this->getElement('sesfbchat_app_id')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $this->addElement('Text', "sesfbchat_page_id", array(
          'label' => 'Facebook Page Id',
          'description' => 'Enter the id of the Facebook page on with which you want to enable the live chat messaging.',
          'allowEmpty' => $allowEmpty,
          'required' => $required,
          'value' => $settings->getSetting('sesfbchat_page_id',''),
      ));
      $this->getElement('sesfbchat_page_id')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $this->addElement('Radio', "sesfbchat_enable_timing", array(
          'label' => 'Custom Start & End Display Time',
          'description' => 'Do you want to display the live chat box for custom duration? If you choose Yes, then you can choose the Start time and End time of the display of the box. But, if you choose No, then the live chat box will be displayed 24x7 on your site.',
          'allowEmpty' => false,
          'required' => true,
          'multiOptions'=>array(
            '1'=>'Yes',
            '0'=>'No',
          ),
          'value' => $settings->getSetting('sesfbchat_enable_timing','0'),
      ));
      // Start time
      $required = false;
      $allowEmpty = true;
      if((!empty($_POST["sesfbchat_enable_timing"]) && $_POST["sesfbchat_enable_timing"] == 1)){
      $required = true;
          $allowEmpty = false;
      }

      $start = new Engine_Form_Element_CalendarDateTime('sesfbchat_starttime');
      $start->setLabel("Start Time");
      $start->setAllowEmpty($allowEmpty);
      $start->setRequired($required);
      $start->setValue(date('Y-m-d H:i:s',strtotime($settings->getSetting('sesfbchat_starttime',date('Y-m-d')))));
      $this->addElement($start);
      $this->getElement('sesfbchat_starttime')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
  // End time
      $end = new Engine_Form_Element_CalendarDateTime('sesfbchat_endtime');
      $end->setLabel("End Time");
      $end->setAllowEmpty($allowEmpty);
      $end->setRequired($required);
      $end->setValue(date('Y-m-d H:i:s',strtotime($settings->getSetting('sesfbchat_endtime',date('Y-m-d')))));

    $this->addElement($end);
      $this->getElement('sesfbchat_endtime')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));


      $this->addElement('Radio', "sesfbchat_messenger_icon", array(
          'label' => 'FB Messenger Icon Visibility',
          'description' => 'Choose from below to who all users you want to show FB Messenger Icon.',
          'allowEmpty' => false,
          'required' => true,
          'multiOptions'=>array(
            '0'=>'Only logged-in members',
            '1'=>'Only logged-out users',
            '2'=>'Both logged-in and logged-out users.',
          ),
          'value' => $settings->getSetting('sesfbchat_messenger_icon','0'),
      ));
      $this->getElement('sesfbchat_messenger_icon')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

  
      $this->addElement('Text', "sesfbchat_login_text", array(
          'label' => 'Greeting Text for Logged-in Users',
          'description' => 'Enter the greeting text which will be shown when users are logged-in into their FB accounts in the same browser.',
          'allowEmpty' => false,
          'required' => true,
          'value' => $settings->getSetting('sesfbchat_login_text','Hi! How can we help you?'),
      ));
      $this->getElement('sesfbchat_login_text')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $this->addElement('Text', "sesfbchat_logout_text", array(
          'label' => 'Greeting Text for Logged-out Users',
          'description' => 'Enter the greeting text which will be shown when users are logged-out from their FB accounts in the same browser.',
          'allowEmpty' => false,
          'required' => true,
          'value' => $settings->getSetting('sesfbchat_logout_text','Hi! How can we help you?'),
      ));
      $this->getElement('sesfbchat_logout_text')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

  
      $this->addElement('Text', "sesfbchat_theme_color", array(
          'label' => 'Messenger Theme Color',
    'description' => 'Choose from below the color of the FB Messenger Theme color.',
          'class' =>'SESColor',
          'required' =>true,
          'allowEmpty'=>false,
          'value' => $settings->getSetting('sesfbchat_theme_color',''),
      ));
      $this->getElement('sesfbchat_theme_color')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

      $this->addElement('Radio', "sesfbchat_devices", array(
          'label' => 'Display Based on Devices',
          'description' => 'Choose from below the devices on which the FB Messenger icon will be displayed no your website.',
          'allowEmpty' => false,
          'required' => true,
          'multiOptions'=>array(
            '0'=>'Both Mobile and Desktop',
            '1'=>'Only Desktop',
            '2'=>'Only Mobile',
          ),
          'value' => $settings->getSetting('sesfbchat_devices','1'),
      ));
      $this->getElement('sesfbchat_devices')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

//       $this->addElement('Radio', "sesfbchat_position", array(
//           'label' => 'Position for FB Messenger Icon',
//           'description' => 'Choose the placement position of the FB Messenger icon on your website.',
//           'allowEmpty' => false,
//           'required' => true,
//           'multiOptions'=>array(
//             '0'=>'Bottom Right',
//             '1'=>'Bottom Left',
//           ),
//           'value' => $settings->getSetting('sesfbchat_position','0'),
//       ));
//       $this->getElement('sesfbchat_position')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
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
