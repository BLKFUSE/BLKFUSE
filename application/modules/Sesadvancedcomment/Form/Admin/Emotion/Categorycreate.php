<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Categorycreate.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesadvancedcomment_Form_Admin_Emotion_Categorycreate extends Engine_Form {
  public function init() {
    $this->setTitle('Add New Category')
            ->setDescription('');
     $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
      
      $this->addElement('Text', 'title', array(
        'label' => 'Category Title',
        'required'=>true,
        'allowEmpty'=>false,
        'description' => '',
      ));     
      
      $this->addElement('Text', 'color', array(
      'label' => 'Color',
      'description' => '',
      'class' => 'SEScolor',
      'required'=>true,
      'allowEmpty'=>false,
      'value' => '',
    ));     
    if(!$id){
      $re = true;
      $all = false;  
    }else{
      $re = false;
      $all = true;
    }
    $this->addElement('File', 'file', array(
        'allowEmpty' => $all,
        'required' => $re,
        'label' => 'Category Photo',
        'description' => 'Upload a photo [Note: photos with extension: "jpg, png, jpeg and gif" only.]',
    ));
    $this->file->addValidator('Extension', false, 'jpg,png,jpeg,gif,GIF,PNG,JPG,JPEG');
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
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