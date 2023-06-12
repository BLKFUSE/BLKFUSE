<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesfeedbg
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Zipupload.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesfeedbg_Form_Admin_Background_Zipupload extends Engine_Form {

  public function init() {
  
    $this->setTitle('Upload zipped folder of the background images')
            ->setDescription('');

    $this->addElement('File', 'file', array(
        'allowEmpty' => false,
        'required' => true,
        'label' => 'Choose Folder',
        'description' => 'Below, choose a zipped folder background images. [Note: folder with extension: ".zip" only.]',
    ));
    $this->file->addValidator('Extension', false, 'zip');
    
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