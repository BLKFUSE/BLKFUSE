<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Password.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Core_Form_Admin_Settings_Password extends Engine_Form
{
  public function init()
  {

    $description = $this->getTranslator()->translate(
        'Controls settings about access to the admin panel. <br>');

	$settings = Engine_Api::_()->getApi('settings', 'core');

	if( $settings->getSetting('user.support.links', 0) == 1 ) {
	  $moreinfo = $this->getTranslator()->translate(
        'More Info: <a href="%1$s" target="_blank"> KB Article</a>');
	} else {
	  $moreinfo = $this->getTranslator()->translate(
        '');
	}

    $description = vsprintf($description.$moreinfo, array(
      'https://community.socialengine.com/blogs/597/38/admin-password',
    ));

	// Decorators
    $this->loadDefaultDecorators();
	$this->getDecorator('Description')->setOption('escape', false);

    $this->setTitle('Admin Reauthentication')
      ->setDescription($description);
      ;

    // Mode
    $this->addElement('Radio', 'mode', array(
      'label' => "Reauthentication to Access Admin Panel",
      'multiOptions' => array(
        'none' => 'Do not require reauthentication.',
        'user' => 'Require admins to re-enter their password when they try to access the admin panel.',
        'global' => 'Require admins to enter a global password when they try to access the admin panel.',
      ),
    ));

    // Password
    $this->addElement('Password', 'password', array(
      'label' => 'Password',
      'description' => 'The password for "Require admins to enter a global password when they try to access the admin panel." above (otherwise ignore).',
    ));
    
    //Work For Show and Hide Password
    $this->addElement('dummy', 'showhidepassword', array(
      'decorators' => array(array('ViewScript', array(
        'viewScript' => 'application/modules/User/views/scripts/_showhidepassword.tpl',
      ))),
    ));
		$this->addDisplayGroup(array('password', 'showhidepassword'), 'password_settings_group');

    // Password confirm
    $this->addElement('Password', 'password_confirm', array(
      'label' => 'Password Again',
      'description' => 'Confirm password',
    ));
    
    //Work For Show and Hide Password
    $this->addElement('dummy', 'showhideconfirmpassword', array(
      'decorators' => array(array('ViewScript', array(
        'viewScript' => 'application/modules/User/views/scripts/_showhideconfirmpassword.tpl',
      ))),
    ));
    $this->addDisplayGroup(array('password_confirm', 'showhideconfirmpassword'), 'password_confirm_settings_group');
    // timeout

    $this->addElement('Text', 'timeout', array(
      'label' => 'Timeout',
      'description' => 'How long (in seconds) before admins have to reauthenticate?',
      'required' => true,
      'allowEmpty' => false,
      'validators' => array(
        array('NotEmpty', true),
        array('Int', true),
        array('Between', true, array(300, 86400)),
      )
    ));

    // init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'ignore' => true,
    ));
  }
}

