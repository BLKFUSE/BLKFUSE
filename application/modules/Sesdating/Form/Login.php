<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Login.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Login extends Engine_Form_Email
{
  protected $_mode;
  
  public function setMode($mode)
  {
    $this->_mode = $mode;
    return $this;
  }
  
  public function getMode()
  {
    if( null === $this->_mode ) {
      $this->_mode = 'page';
    }
    return $this->_mode;
  }
  
  public function init()
  {
    $tabindex = 1;
    $this->_emailAntispamEnabled = (Engine_Api::_()->getApi('settings', 'core')
          ->getSetting('core.spam.email.antispam.login', 1) == 1);
    
    // Used to redirect users to the correct page after login with Facebook
    $_SESSION['redirectURL'] = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
    $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null;
    $description = Zend_Registry::get('Zend_Translate')->_("If you already have an account, please enter your details below. If you don't have one yet, please <a href='%s'>sign up</a> first.");
    $description= sprintf($description, Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_signup', true));
    if($sesdating_landingpage) {
    // Init form
    $this->setTitle('Member Sign In');
    $this->setDescription($description);
    $this->setAttrib('id', 'sesdating_form_login');
    $this->loadDefaultDecorators();
    $this->getDecorator('Description')->setOption('escape', false);

    $email = Zend_Registry::get('Zend_Translate')->_('Email Address');
    // Init email
    $this->addEmailElement(array(
      'label' => $email,
      'required' => true,
      'allowEmpty' => false,
      'filters' => array(
        'StringTrim',
      ),
      'validators' => array(
        'EmailAddress'
      ),
      
      // Fancy stuff
      'tabindex' => $tabindex++,
      'autofocus' => 'autofocus',
      'inputType' => 'email',
      'class' => 'text',
			'placeholder' => 'Email',
    ));
    }
    $password = Zend_Registry::get('Zend_Translate')->_('Password');
    // Init password
    $this->addElement('Password', 'password', array(
      'label' => $password,
			'placeholder' => 'Password',
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'filters' => array(
        'StringTrim',
      ),
    ));

    $this->addElement('Hidden', 'return_url', array(
      'value'=> Zend_Controller_Front::getInstance()->getRouter()->assemble(array()),
    ));

    $settings = Engine_Api::_()->getApi('settings', 'core');
    if( $settings->core_spam_login ) {
      $this->addElement('captcha', 'captcha', Engine_Api::_()->core()->getCaptchaOptions(array(
        'tabindex' => $tabindex++,
        'size' => ($this->getMode() == 'column') ? 'normal' : 'normal',
      )));
    }
    	//SES Work For Show and Hide Password
    $this->addElement('dummy', 'showhidepassword', array(
      'decorators' => array(array('ViewScript', array(
        'viewScript' => 'application/modules/Sesdating/views/scripts/showhidepassword.tpl',
      ))),
			'tabindex' => $tabindex++,			
    ));
		//SES Work For Show and Hide Password
    
    // Init remember me
//     $this->addElement('Checkbox', 'remember', array(
//       'label' => 'Remember Me',
//       'tabindex' => $tabindex++,
//     ));
		
    $content = Zend_Registry::get('Zend_Translate')->_("<span><a href='%s'>Forgot Password?</a></span>");
    $content= sprintf($content, Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'user', 'controller' => 'auth', 'action' => 'forgot'), 'default', true));


    // Init forgot password link
    $this->addElement('Dummy', 'forgot', array(
      'content' => $content,
    ));

    $this->addDisplayGroup(array(
      //'remember',
			'forgot'
    ), 'buttons');

    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Sign In',
      'type' => 'submit',
      'ignore' => true,
      'tabindex' => $tabindex++,
    ));

    // Init facebook login link
//    if( 'none' != $settings->getSetting('core_facebook_enable', 'none')
//        && $settings->core_facebook_secret ) {
//      $this->addElement('Dummy', 'facebook', array(
//        'content' => User_Model_DbTable_Facebook::loginButton(),
//      ));
//    }

    // Init twitter login link
//    if( 'none' != $settings->getSetting('core_twitter_enable', 'none')
//        && $settings->core_twitter_secret ) {
//      $this->addElement('Dummy', 'twitter', array(
//        'content' => User_Model_DbTable_Twitter::loginButton(),
//      ));
//    }
    
    // Init janrain login link
//    if( 'none' != $settings->getSetting('core_janrain_enable', 'none')
//        && $settings->core_janrain_key ) {
//      $mode = $this->getMode();
//      $this->addElement('Dummy', 'janrain', array(
//        'content' => User_Model_DbTable_Janrain::loginButton($mode),
//      ));
//    }

    // Set default action
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login'));
  }
}
