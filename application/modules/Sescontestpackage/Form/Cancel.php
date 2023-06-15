<?php

class Sescontestpackage_Form_Cancel extends Engine_Form {

  public function init() {
		$this
      ->setTitle('Cancel Subscription?')
      ->setDescription('Are you sure you want to cancel this subscription plan as this action cannot be undone?');
    $this->addElement('Button', 'submit', array(
		   'label' =>'Delete',
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
