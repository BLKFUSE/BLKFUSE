<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Form_Login extends Engine_Form
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
    $tabindex = rand(100, 9999);
    //$this->_emailAntispamEnabled = (Engine_Api::_()->getApi('settings', 'core')->getSetting('core.spam.email.antispam.login', 1) == 1);

    // Used to redirect users to the correct page after login with Facebook
    $_SESSION['redirectURL'] = Zend_Controller_Front::getInstance()->getRequest()->getRequestUri();
		
		if(isset($_GET['format']) && $_GET['format'] == 'smoothbox') {
			$description = Zend_Registry::get('Zend_Translate')->_("If you already have an account, please enter your details below. If you don't have one yet, please <a href='%s' target='_blank'>sign up</a> first.");
    } else {
			$description = Zend_Registry::get('Zend_Translate')->_("If you already have an account, please enter your details below. If you don't have one yet, please <a href='%s'>sign up</a> first.");
    }
    $description= sprintf($description, Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_signup', true));

    // Init form
    $this->setTitle('Member Sign In');
    $this->setDescription($description);
    $this->setAttrib('id', 'user_form_login');
    $this->loadDefaultDecorators();
    $this->getDecorator('Description')->setOption('escape', false);
    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.username', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('user.signup.allowloginusername', 0)) { 
      $email = Zend_Registry::get('Zend_Translate')->_('Email Address or Username');
    } else {
      $email = Zend_Registry::get('Zend_Translate')->_('Email Address');
    }
    
    // Init password
    $this->addElement('Text', 'email', array(
      'label' => $email,
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'filters' => array(
        'StringTrim',
      ),
      'autofocus' => 'autofocus',
      'class' => 'text',
    ));

    // Init email
//     $emailElement = $this->addEmailElement(array(
//       'label' => $email,
//       'required' => true,
//       'allowEmpty' => false,
//       'filters' => array(
//         'StringTrim',
//       ),
//       'validators' => array(
//         'EmailAddress'
//       ),
// 
//       // Fancy stuff
//       'tabindex' => $tabindex++,
//       'autofocus' => 'autofocus',
//       'inputType' => 'email',
//       'class' => 'text',
//     ));
// 
//     $emailElement->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);

    $password = Zend_Registry::get('Zend_Translate')->_('Password');
    // Init password
    $this->addElement('Password', 'password', array(
      'label' => $password,
      'required' => true,
      'allowEmpty' => false,
      'tabindex' => $tabindex++,
      'filters' => array(
        'StringTrim',
      ),
    ));

    $this->addElement('Hidden', 'return_url', array(

    ));
		
		//Work For Show and Hide Password
    $this->addElement('dummy', 'showhidepassword', array(
      'decorators' => array(array('ViewScript', array(
        'viewScript' => 'application/modules/User/views/scripts/_showhidepassword.tpl',
      ))),
			'tabindex' => $tabindex++,
    ));
		//Work For Show and Hide Password
		 
    $settings = Engine_Api::_()->getApi('settings', 'core');
    if( $settings->core_spam_login ) {
      $this->addElement('captcha', 'captcha', Engine_Api::_()->core()->getCaptchaOptions(array(
        'tabindex' => $tabindex++,
        'size' => ($this->getMode() == 'column') ? 'compact' : 'normal',
      )));
    }
			
		if(isset($_GET['format']) && $_GET['format'] == 'smoothbox') {
			$content = Zend_Registry::get('Zend_Translate')->_("<span><a href='%s' target='_blank'>Forgot Password?</a></span>");
		} else {
			$content = Zend_Registry::get('Zend_Translate')->_("<span><a href='%s'>Forgot Password?</a></span>");
		}
    $content= sprintf($content, Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'user', 'controller' => 'auth', 'action' => 'forgot'), 'default', true));


    // Init forgot password link
    $this->addElement('Dummy', 'forgot', array(
      'content' => $content,
    ));
		 $this->addDisplayGroup(array(
      'forgot',
      'remember'
    ), 'buttons');

    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Sign In',
      'type' => 'submit',
      'ignore' => true,
      'tabindex' => $tabindex++,
    ));

    // Init facebook login link
    if($settings->core_facebook_enable == 'login' && $settings->core_facebook_appid && $settings->core_facebook_secret) {
      $this->addElement('Dummy', 'facebook', array(
        'content' => User_Model_DbTable_Facebook::loginButton(),
      ));
    }

    // Init twitter login link
    if($settings->core_twitter_enable == 'login' && $settings->core_twitter_key && $settings->core_twitter_secret) {
      $this->addElement('Dummy', 'twitter', array(
        'content' => User_Model_DbTable_Twitter::loginButton(),
      ));
    }
    if(($settings->core_facebook_enable == 'login' && $settings->core_facebook_appid && $settings->core_facebook_secret) || $settings->core_twitter_enable == 'login' && $settings->core_twitter_key && $settings->core_twitter_secret) {
      $this->addDisplayGroup(array('facebook', 'twitter'), 'sociallinks');
    }
    
    // Set default action
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array(), 'user_login'));
  }
}
