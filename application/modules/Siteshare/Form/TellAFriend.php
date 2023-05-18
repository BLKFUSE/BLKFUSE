<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2012-2017 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: TellAFriend.php SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Form_TellAFriend extends Engine_Form
{

  public $_error = array();

  public function init()
  {

    $this
      ->setAttrib('name', 'siteshare_send-email');
    $this->setAttrib('class', 'global_form_box siteshare_sendemail_form');
    $this->addElement('Text', 'sender_name', array(
      'label' => 'Your Name',
      'allowEmpty' => false,
      'placeholder' => 'Your Name',
      'required' => true,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
    )));

    $this->addElement('Text', 'sender_email', array(
      'label' => 'Your Email',
      'placeholder' => 'yourname@email.com',
      'allowEmpty' => false,
      'required' => true,
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
        new Engine_Filter_StringLength(array('max' => '63')),
    )));

    $this->addElement('Text', 'reciver_emails', array(
      'label' => 'To',
      'allowEmpty' => false,
      'required' => true,
      'placeholder' => 'name@email.com, another@email.com',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    $this->reciver_emails->getDecorator("Description")->setOption("placement", "append");

    $this->addElement('textarea', 'message', array(
      'label' => 'Message',
      'required' => true,
      'allowEmpty' => false,
      'attribs' => array('rows' => 24, 'cols' => 150, 'style' => 'height:120px;'),
      'value' => Zend_Registry::get('Zend_Translate')->_('Thought you would be interested in this.'),
      'filters' => array(
        'StripTags',
        new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_EnableLinks(),
        new Engine_Filter_Censor(),
      ),
    ));
    $this->message->getDecorator("Description")->setOption("placement", "append");

    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    if( empty($viewer_id) ) {
      $this->addElement('captcha', 'captcha', Engine_Api::_()->core()->getCaptchaOptions(array(
          'tabindex' => 100
      )));
      $this->captcha->getDecorator("Description")->setOption("placement", "append");
    }

    $this->addElement('Button', 'send', array(
      'label' => 'Tell a Friend',
      'type' => 'submit',
    ));
  }

}
