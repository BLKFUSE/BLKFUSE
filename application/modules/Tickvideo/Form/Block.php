<?php

class Tickvideo_Form_Block extends Engine_Form
{
  public function init()
  {
    $this->setTitle('Block Member')
      ->setDescription('Do you want to block this member?')
      ->setMethod('POST')
      ->setAction($_SERVER['REQUEST_URI'])
      ->setAttrib('class', 'global_form_popup')
      ;

    $this->addElement('Hash', 'token');

    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Block Member',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onclick' => 'parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');

  }
}
