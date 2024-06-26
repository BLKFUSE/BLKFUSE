<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Delete.php 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Form_Settings_Delete extends Engine_Form
{
  public function init()
  {
    $this
      ->setTitle('Delete Account')
      ->setDescription('Are you sure you want to delete your account? Any content '.
        'you\'ve uploaded in the past will be permanently deleted. You will be '.
        'immediately signed out and will no longer be able to sign in with this account.')
      ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
      ;
    
    $code = Zend_Controller_Front::getInstance()->getRequest()->getParam('code', 0);
    $otpfeatures = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.spam.otpfeatures', 1);
    if(!empty($otpfeatures)) {
      
      if(!empty($code)) {
        $this->addElement('Dummy', 'codesent', array(
        'content' => '<div class="tip"><span>Verification code sent successfully. Please check your email.</span></div>',
        'decorators' => array(
          'ViewHelper',
        ),
        ));
      }
      
      $this->addElement('Button', 'send', array(
        'label' => 'Send Verification Code',
        'decorators' => array(
          'ViewHelper',
        ),
      ));

      $this->addElement('Text', "code", array(
          'label' => 'Enter Verification Code',
          'description' => '',
          'allowEmpty' => false,
          'required' => true,
      ));
    }

    // Element: token
    $this->addElement('Hash', 'token');

    // Element: execute
    $this->addElement('Button', 'execute', array(
      'label' => 'Yes, Delete My Account',
      'type' => 'submit',
      'ignore' => true,
      //'style' => 'color:#D12F19;',
      'decorators' => array(
        'ViewHelper',
      ),
    ));

    // Element: cancel
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper',
      ),
    ));
    
    // DisplayGroup: buttons
    $this->addDisplayGroup(array(
      'execute',
      'cancel',
    ), 'buttons');
    
    return $this;
  }
}
