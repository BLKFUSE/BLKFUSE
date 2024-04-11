<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Global.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Admin_Global extends Engine_Form {

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->setTitle('Global Settings')
            ->setDescription('These settings affect all members in your community.');

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $supportTicket = '<a href="https://socialnetworking.solutions/support/create-new-ticket/" target="_blank">Support Ticket</a>';
    $sesSite = '<a href="https://socialnetworking.solutions" target="_blank">SocialNetworking.Solutions website</a>';
    $descriptionLicense = sprintf('Enter your license key that is provided to you when you purchased this plugin. If you do not know your license key, please drop us a line from the %s section on %s. (Key Format: XXXX-XXXX-XXXX-XXXX)',$supportTicket,$sesSite);
    $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null;
    
    $this->addElement('Text', "sesdating_licensekey", array(
        'label' => 'Enter License key',
        'description' => $descriptionLicense,
        'allowEmpty' => false,
        'required' => true,
        'value' => $settings->getSetting('sesdating.licensekey'),
    ));
    $this->getElement('sesdating_licensekey')->getDecorator('Description')->setOptions(array('placement' => 'PREPEND', 'escape' => false));

    if ($settings->getSetting('sesdating.pluginactivated')) {
      if($sesdating_landingpage) {
			//UPLOAD PHOTO URL
			$upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesdating', 'controller' => 'manage', 'action' => "upload-photo"), 'admin_default', true);

			$allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

			$editorOptions = array(
					'uploadUrl' => $upload_url,
					'html' => (bool) $allowed_html,
			);

			if (!empty($upload_url)) {
				$editorOptions['plugins'] = array(
						'table', 'fullscreen', 'media', 'preview', 'paste',
						'code', 'image', 'textcolor', 'jbimages', 'link'
				);

				$editorOptions['toolbar1'] = array(
						'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
						'media', 'image', 'jbimages', 'link', 'fullscreen',
						'preview'
				);
			}
			if (!$settings->getSetting('sesdating.layout.enable', 0)) {
				$this->addElement('Radio', 'sesdating_layout_enable', array(
						'label' => 'Set Responsive Dating Theme Landing Page',
						'description' => 'Do you want to set Responsive Dating Landing page for your site? [Note: If you choose Yes, then your current settings will be overwritten by the Responsive Dating Landing page and changes will not be recoverable.]',
						'multiOptions' => array(
								1 => 'Yes',
								0 => 'No'
						),
						'value' => $settings->getSetting('sesdating.layout.enable', 0),
				));
			}

  		$this->addElement('Select', "sesdating_responsive_layout", array(
					'label' => 'Enable Responsive CSS',
					'description' => 'Do you want to enable the responsive css for your website? If you select Yes, then the website will automatically adopt the device screen size.',
					'allowEmpty' => false,
					'required' => true,
					'multiOptions' => array(
							'1' => 'Yes',
							'2' => "No",
					),
					'value' => Engine_Api::_()->sesdating()->getContantValueXML('sesdating_responsive_layout'),
			));

			//New File System Code
			$banner_options = array('' => '');
			$files = Engine_Api::_()->getDbTable('files', 'core')->getFiles(array('fetchAll' => 1, 'extension' => array('gif', 'jpg', 'jpeg', 'png', 'webp')));
			foreach( $files as $file ) {
			  $banner_options[$file->storage_path] = $file->name;
			}
			$view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
			$fileLink = $view->baseUrl() . '/admin/files/';
			$this->addElement('Select', 'sesdating_body_background_image', array(
					'label' => 'Body Background Image',
					'description' => 'Choose from below the body background image for your website. [Note: You can add a new photo from the "File & Media Manager" section from here: <a href="' . $fileLink . '" target="_blank">File & Media Manager</a>.]',
					'multiOptions' => $banner_options,
					'escape' => false,
					'value' => Engine_Api::_()->sesdating()->getContantValueXML('sesdating_body_background_image'),
			));
			$this->sesdating_body_background_image->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Select', 'sesdating_user_photo_round', array(
          'label' => 'Thumbs Images Shape',
          'description' => 'Choose from below the shape of the thumb icons of members’ profile pictures and content profile photos.',
          'multiOptions' => array(
              1 => 'Yes',
              2 => 'No'
          ),
          'value' => Engine_Api::_()->sesdating()->getContantValueXML('sesdating_user_photo_round'),
      ));

      $this->addElement('Radio', 'sesdating_miniuserphotoround', array(
          'label' => 'Member Avatar Shape in Mini Menu',
          'description' => 'Choose from below the shape of the member avatar which is shown in Mini Navigation menu.',
          'multiOptions' => array(
              1 => 'Circle',
              0 => 'Square'
          ),
          'value' => $settings->getSetting('sesdating.miniuserphotoround',1),
      ));

		 $this->addElement('Text', "sesdating_left_columns_width", array(
						'label' => 'Left Column Width',
						'description' => "Enter the left column width of the website. This will affect all the pages on your website.",
						'allowEmpty' => false,
						'required' => true,
						'value' => Engine_Api::_()->sesdating()->getContantValueXML('sesdating_left_columns_width'),
				));

				$this->addElement('Text', "sesdating_right_columns_width", array(
						'label' => 'Right Column Width',
						'description' => 'Enter the right column width of the website. This will affect all the pages on your website.',
						'allowEmpty' => false,
						'required' => true,
						'value' => Engine_Api::_()->sesdating()->getContantValueXML('sesdating_right_columns_width'),
				));

      $this->addElement('Select', 'sesdating_feed_style', array(
        'label' => 'Activity Feed Style',
        'description' => 'Below, choose the style for displaying activity feeds on your website.',
        'multiOptions'=>array('1'=>'Simple Style','2'=>'Designed Block'),
        'value' => $settings->getSetting('sesdating.feed.style', '2'),
    ));

			if (!$settings->getSetting('sesdating.layout.enable', 0)) {
				$this->addElement('Radio', 'sesdating_layout_enable', array(
						'label' => 'Set Responsive Dating Theme Landing Page',
						'description' => 'Do you want to set Responsive Dating Landing page for your site? [Note: If you choose Yes, then your current settings will be overwritten by the Responsive Dating Landing page and changes will not be recoverable.]',
						'multiOptions' => array(
								1 => 'Yes',
								0 => 'No'
						),
						'value' => $settings->getSetting('sesdating.layout.enable', 0),
				));
			}

      $this->addElement('Select', 'sesdating_landingpage_style', array(
          'label' => 'Show Landing Page in Full Width',
          'description' => 'Do you want to show the Landing Page of your website in Full width?',
          'multiOptions' => array(
              1 => 'Yes, show in Full width',
              0 => 'No, show in Boxed style'
          ),
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesdating.landingpage.style', 1),
      ));
      }
			// Add submit button
			$this->addElement('Button', 'submit', array(
					'label' => 'Save Changes',
					'type' => 'submit',
					'ignore' => true
			));
    } else {

			if (!$settings->getSetting('sesdating.layout.enable', 0)) {
				$this->addElement('Radio', 'sesdating_layout_enable', array(
						'label' => 'Set Responsive Dating Theme Landing Page',
						'description' => 'Do you want to set Responsive Dating Landing page for your site? [Note: If you choose Yes, then your current settings will be overwritten by the Responsive Dating Landing page and changes will not be recoverable.]',
						'multiOptions' => array(
								1 => 'Yes',
								0 => 'No'
						),
						'value' => $settings->getSetting('sesdating.layout.enable', 0),
				));
			}

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
