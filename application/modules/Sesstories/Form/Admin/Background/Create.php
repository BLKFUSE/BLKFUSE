<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesstories
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Create.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Sesstories_Form_Admin_Background_Create extends Engine_Form {

  public function init() {
  
    $this->setTitle('Upload New Background Image')
            ->setDescription('');
            
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);

    if(!$id){
      $re = true;
      $all = false;  
    } else{
      $re = false;
      $all = true;
    }
    $this->addElement('File', 'file', array(
        'allowEmpty' => $all,
        'required' => $re,
        'label' => 'Choose Image',
        'description' => 'Below, choose a background image. [Note: photos with extension: "jpg, png and jpeg" only.]',
        'accept'=>"image/*",
        'onchange' => 'validFileSize(this.value)',
    ));
    $this->file->addValidator('Extension', false, 'jpg,png,jpeg,PNG,JPG,JPEG');
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Upload',
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
