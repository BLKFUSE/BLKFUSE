<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2018-11-05 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_Form_Admin_Settings_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->setTitle('Global Settings')
        ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

//     $this->addElement('Text', "sesstories_licensekey", array(
//         'label' => 'Enter License key',
//         'description' => $descriptionLicense,
//         'allowEmpty' => false,
//         'required' => true,
//         'value' => $settings->getSetting('sesstories.licensekey'),
//     ));
//     $this->getElement('sesstories_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    //if ($settings->getSetting('sesstories.pluginactivated')) {
    
        $this->addElement('Text', "sesstories_videouplimit", array(
            'label' => 'Video Size Limit',
            'description' => "Enter the size of video you want your users to upload on your website in MB.",
            'allowEmpty' => false,
            'required' => true,
            'value' => $settings->getSetting('sesstories.videouplimit', '10'),
        ));
        $this->addElement('Text', "sesstories_dayslimit", array(
          'label' => 'Stories Duration',
          'description' => "Enter the duration (in days) for which Stories uploaded by the members will be visible on your website.",
          'allowEmpty' => false,
          'required' => true,
          'validators' => array(
            array('Int', true),
            array('GreaterThan', true, array(0)),
          ),
          'value' => $settings->getSetting('sesstories.dayslimit', '1'),
      ));

        $this->addElement('Text', "sesstories_storyviewtime", array(
            'label' => 'Delay Time for Photos in Slideshow',
            'description' => "Enter the delay time (in seconds) for photos to be shown in the story slideshow. (Note: Videos will play according to the video length, so this setting will not be applicable on videos.",
            'allowEmpty' => false,
            'required' => true,
            'value' => $settings->getSetting('sesstories.storyviewtime', '5'),
        ));
        
        
        $this->addElement('Text', 'video_ffmpeg_path', array(
          'label' => 'Path to FFMPEG',
          'description' => 'Please enter the full path to your FFMPEG installation. (Environment variables are not present)',
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ffmpeg.path', ''),
        ));

        // Add submit button
        $this->addElement('Button', 'submit', array(
            'label' => 'Save Changes',
            'type' => 'submit',
            'ignore' => true
        ));
//     } else {
// 
//         //Add submit button
//         $this->addElement('Button', 'submit', array(
//             'label' => 'Activate your plugin',
//             'type' => 'submit',
//             'ignore' => true
//         ));
//     }
  }
}
