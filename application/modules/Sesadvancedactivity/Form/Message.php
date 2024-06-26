<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Message.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesadvancedactivity_Form_Message extends Engine_Form
{
  public function init()
  {
    $this->setTitle('Compose Message');
    $this->setDescription('')
       ->setAttrib('id', 'sesadvact_messages_compose');        

    // init title
    $this->addElement('Text', 'title', array(
      'label' => 'Subject',
      'order' => 1,
      'required' => true,
      'allowEmpty' => false,
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_HtmlSpecialChars(),
      ),
    ));
   
    // init body - plain text
    $this->addElement('Textarea', 'body', array(
      'label' => 'Message',
      'order' => 2,
      'required' => true,
      'allowEmpty' => false,
      'filters' => array(
        new Engine_Filter_HtmlSpecialChars(),
        new Engine_Filter_Censor(),
        new Engine_Filter_EnableLinks(),
      ),
    ));
    // init title
    $this->addElement('Text', 'attachment_content_div', array(
      'label' => '',
      'order' => 3,
      'required' => false,
      'allowEmpty' => true,
    ));
   
    // init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Send Message',
      'order' => 4,
      'type' => 'submit',
      'ignore' => true
    ));
  }
}
