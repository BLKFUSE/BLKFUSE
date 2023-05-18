<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Upload.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Form_Upload extends Engine_Form { 

  public function init() {

    $this->setTitle('Add Photos')
        ->setDescription('Choose photos on your computer to add on your dating photos.')
        ->setAttrib('id', 'form-upload')
        ->setAttrib('class', 'global_form event_form_upload')
        ->setAttrib('enctype','multipart/form-data')
        ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    
    $this->addElement('HTMLUpload', 'file', [
      'form' => '#form-upload',
      'multi' => true,
      'url' => $this->getView()->url([
        'controller' => 'index',
        'action' => 'upload-photo'
      ], 'edating_general'),
      'accept' => 'image/*',
    ]);

    $this->addElement('Button', 'submit', array(
      'label' => 'Save Photos',
      'type' => 'submit',
    ));
  }
}
