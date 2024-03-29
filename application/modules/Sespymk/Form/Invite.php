<?php

class Sespymk_Form_Invite extends Engine_Form {

  public $invalid_emails = array();

  public $already_members = array();
  
  public $emails_sent = 0;

  public function init() {
  
    // Init settings object
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $translate = Zend_Registry::get('Zend_Translate');

    // Init form
//     $this
//       ->setTitle('Invite Your Friends')
//       ->setDescription('_INVITE_FORM_DESCRIPTION')
//       ->setLegend('');
    $this->setAttribs(array('id' => 'sespymk_invite','class' => 'global_form_box'))->setMethod('GET');
    //$this->setAction($view->url(array('module' => 'sesblog', 'controller' => 'index', 'action' => 'browse'), 'default', true));
    
    // Init recipients
    $this->addElement('Textarea', 'recipients', array(
      'label' => 'Recipients',
      'description' => 'Comma-separated list, or one-email-per-line.',
      'required' => true,
      'allowEmpty' => false,
      'validators' => array(
        new Engine_Validate_Callback(array($this, 'validateEmails')),
      ),
    ));
    $this->recipients->getValidator('Engine_Validate_Callback')->setMessage('Please enter only valid email addresses.');
    $this->recipients->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));
    
    // Init custom message
    if( $settings->getSetting('invite.allowCustomMessage', 1) > 0 ) {
      $this->addElement('Textarea', 'message', array(
        'label' => 'Custom Message',
        'required' => false,
        'allowEmpty' => true,
        //'description' => 'Use %invite_url% to add a link to our sign up page.',
        'value' => $translate->_($settings->getSetting('invite.message', '')),
        'filters' => array(
          new Engine_Filter_Censor(),
        )
      ));
      $this->message->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));
    }

    $this->addElement('Checkbox', 'friendship', array(
      'label' => "Send a friend request if the user(s) join(s) the network",
      'decorators' => array(
        'ViewHelper',
        array('Label', array('placement' => 'APPEND')),
      ),
    ));

    // Init captcha
    if( $settings->core_spam_invite ) {
      $this->addElement('captcha', 'captcha', Engine_Api::_()->core()->getCaptchaOptions());
    }

    // Init submit
    $this->addElement('button', 'submit', array(
      'type' => 'submit',
      'label' => 'Send Invites',
    ));
  }
  
  public function validateEmails($value)
  {
    // Not string?
    if( !is_string($value) || empty($value) ) {
      return false;
    }

    // Validate emails
    $validate = new Zend_Validate_EmailAddress();
    $validate->getHostnameValidator()->setValidateTld(false);

    $emails = array_unique(array_filter(array_map('trim', preg_split("/[\s,]+/", $value))));

    if( empty($emails) ) {
      return false;
    }
    
    foreach( $emails as $email ) {
      if( !$validate->isValid($email) ) {
        return false;
      }
    }

    return true;
  }
}
