<?php

class Egifts_Form_Paymentrequest extends Engine_Form {

	public function init() {

    $this->setTitle('Make Payment Request')
					->setDescription('Enter the information in below form and click on "Send" button to send the payment request to website administrators.')
					->setAttrib('id','estore_ppayment_request')
					->setMethod("POST");
		
		$this->addElement('Text', 'total_amount', array(
			'label' => 'Total Credit Amount',
			'readonly'=>'readonly',
    ));
    
		
// 		$this->addElement('Text', 'total_commission_amount', array(
// 			'label' => 'Total Commission Amount',
// 			'readonly'=>'readonly',
//     ));
    
		$this->addElement('Text', 'remaining_amount', array(
			'label' => 'Total Remaining Amount',
			'readonly'=>'readonly',
    ));
    
		$this->addElement('Text', 'requested_amount', array(
			'label' => 'Request Amount ('.Engine_Api::_()->sesbasic()->defaultCurrency().')',
			'allowEmpty' => false,
			'required' => true,
			'validators' => array(
        new Engine_Validate_AtLeast(0),
      ),
    ));
    
		$this->addElement('Textarea', 'user_message', array(
			'label' => 'Message',
    ));
    
		$this->addElement('Button', 'submit', array(
        'label' => 'Send',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'link' => true,
				'onclick'=>'parent.Smoothbox.close();',
        'prependText' => ' or ',
        'decorators' => array(
            'ViewHelper',
        ),
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons', array(
        'decorators' => array(
            'FormElements',
            'DivDivDivWrapper',
        ),
    ));
 }
}
