<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Global.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Global Settings')->setDescription('These settings affect all members in your community.');
    
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);

    $this->addElement('Text', "sesmember_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesmember.licensekey'),
    ));
    $this->getElement('sesmember_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));
    
    if ($settings->getSetting('sesmember.pluginactivated')) {
      $this->addElement('Select', 'sesmember_enable_location', array(
          'label' => 'Enable Location',
          'description' => 'Do you want to enable location for members on your website?',
          'multiOptions' => array(
              '1' => 'Yes,enable location',
              '0' => 'No, do not enable location',
          ),
          'value' => $settings->getSetting('sesmember.enable.location', 1),
      ));
      $this->addElement('Select', 'sesmember_showsignup_location', array(
          'label' => 'Show Location Field On Singup Page',
          'description' => 'Do you want to show location field on signup page?',
          'multiOptions' => array(
              '1' => 'Yes,show location',
              '0' => 'No, do not show location',
          ),
          'value' => $settings->getSetting('sesmember.showsignup.location', 1),
      ));
      $this->addElement('Select', 'sesmember_user_approved', array(
          'label' => 'Member Auto Verified',
          'description' => 'Do you want to allow users to auto-verfied on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'onchange' => 'show_type(this.value)',
          'value' => $settings->getSetting('sesmember.user.approved', 1),
      ));

      $this->addElement('Select', 'sesmember_approve_criteria', array(
          'label' => 'Auto Verified Type',
          'description' => 'On which based user will be auto verfied?',
          'multiOptions' => array(
              1 => 'Member Based on "Like"',
              0 => 'Member Based on "Profile View"'
          ),
          'onchange' => 'showCriteria(this.value)',
          'value' => $settings->getSetting('sesmember.approve.criteria', 1),
      ));

      $this->addElement('Text', "sesmember_like_count", array(
          'label' => 'Enter Like Count',
          'description' => "Enter the like count, after which member will be automatic verfied.",
          'allowEmpty' => false,
          'required' => true,
          'value' => $settings->getSetting('sesmember.like.count', 10),
      ));

      $this->addElement('Text', "sesmember_view_count", array(
          'label' => 'Enter View Count',
          'description' => "Enter the view count, after which member will be automatic verfied.",
          'allowEmpty' => false,
          'required' => true,
          'value' => $settings->getSetting('sesmember.view.count', 10),
      ));

      /* Follow Functionality Setting */
      $this->addElement('Select', 'sesmember_follow_active', array(
          'label' => 'Enable Follow Functionality',
          'description' => 'Do you want to enable follow functionality on your website?',
          'onchange' => 'showfollowtext(this.value)',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesmember.follow.active', 1),
      ));

        $this->addElement('Select', 'sesmember_autofollow', array(
          'label' => 'Auto Follow / Approvals',
          'description' => 'Do you want to enable auto follow / approvals functionality on your website?',
          'multiOptions' => array(
              1 => 'Yes',
              0 => 'No'
          ),
          'value' => $settings->getSetting('sesmember.autofollow', 1),
        ));

      $this->addElement('Text', "sesmember_follow_followtext", array(
          'label' => 'Follow Button Text',
          'description' => "Enter the text for Follow Button.",
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sesmember.follow.followtext', 'Follow'),
      ));
      $this->addElement('Text', "sesmember_follow_unfollowtext", array(
          'label' => 'Unfollow Button Text',
          'description' => "Enter the text Unfollow Button.",
          'allowEmpty' => true,
          'required' => false,
          'value' => $settings->getSetting('sesmember.follow.unfollowtext', 'Unfollow'),
      ));
      //New File System Code
        $default_photos_main = array();
        $files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
        foreach( $files as $file ) {
          $default_photos_main[$file->storage_path] = $file->name;
        }
        $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
        $fileLink = $view->baseUrl() . '/admin/files/';

        if (engine_count($default_photos_main) > 0) {
                $default_photos = array_merge(array('application/modules/Sesmember/externals/images/vip-label.png'=>''),$default_photos_main);
        $this->addElement('Select', 'member_vip_image', array(
            'label' => 'Default Photo for VIP Label',
            'description' => 'Choose a default photo for VIP Label on your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a target="_blank" href="' . $fileLink . '">File & Media Manager</a>. Leave the field blank if you do not want to change this default photo.]',
            'multiOptions' => $default_photos,
            'value' => $settings->getSetting('member_vip_image'),
        ));
        } else {
        $description = "<div class='tip'><span>" . Zend_Registry::get('Zend_Translate')->_('There are currently no photo for  default on your website. Photo to be chosen for default on your website should be first uploaded from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section. => There are currently no photo in the File & Media Manager for the  default on your website. Please upload the Photo to be chosen for default on your website from the "Layout" >> "<a target="_blank" href="' . $fileLink . '">File & Media Manager</a>" section.') . "</span></div>";
        //Add Element: Dummy
        $this->addElement('Dummy', 'member_vip_image', array(
            'label' => 'Default Photo for VIP Label',
            'description' => $description,
        ));
        }
        $this->member_vip_image->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Text', "sesmember_nearest_distance", array(
          'label' => 'Default distance for Nearest member page',
          'description' => 'Enter the default distance for nearest member for Nearest member page.',
          'allowEmpty' => false,
          'required' => true,
          'value' => $settings->getSetting('sesmember_nearest_distance', '100'),
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
