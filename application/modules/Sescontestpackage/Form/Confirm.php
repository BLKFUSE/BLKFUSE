<?php

class Sescontestpackage_Form_Confirm extends Engine_Form {

  public function init() {
		$this
      ->setTitle('Upgrade Package')
      ->setDescription('Do you want to change your Contest Package. Once you confirm, you won’t be able to degrade your Package to previous.');
    $this->addElement('Button', 'submit', array(
		   'label' =>'Change Package',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'onclick' => 'javascript:parent.Smoothbox.close()',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }

}
