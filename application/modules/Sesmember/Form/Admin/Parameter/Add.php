<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Add.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Admin_Parameter_Add extends Engine_Form {

  public function init() {

    $this->setMethod('post');
    $profile_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
    $reviewParameters = Engine_Api::_()->getDbtable('parameters', 'sesmember')->getParameterResult(array('profile_type' => $profile_id));
    $this->setMethod('post');
    if (engine_count($reviewParameters)) {
      foreach ($reviewParameters as $val) {
        $this->addElement('Text', 'sesmember_review_' . $val['parameter_id'], array(
            'label' => '',
            'class' => 'sesmember_added_parameter',
            'allowEmpty' => true,
            'value' => $val['title'],
            'required' => false,
            'maxlength' => "255",
        ));
      }
    }
    $this->addElement('Dummy', 'addmore', array('content' => '
      <div><input type="text" name="parameters[]" value="" class="reviewparameter"><a href="javascript:;" class="removeAddedElem sesbasic_icon_delete">Remove</a></div>
      <a href="javascript:;" id="addmoreelem" class="fa fa-plus">Add more parameters</a>
    '));
    $this->addElement('Hidden', 'deletedIds', array('order' => 999));
    $this->addElement('Button', 'submit', array(
        'label' => 'Add',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => '',
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }

}
