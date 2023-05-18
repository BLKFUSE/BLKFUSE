<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Modules
 * @package    Everification
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Upload.php 2019-06-05 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Everification_Form_Upload extends Engine_Form {

  public function init() {

    $this->setTitle('Upload document for verification');

    $extensions = unserialize('a:7:{i:0;s:3:"PDF";i:1;s:3:"PNG";i:2;s:3:"JPG";i:3;s:4:"JPEG";i:4;s:4:"DOCX";i:5;s:4:"XLSX";i:6;s:4:"PPTX";}');
    $string = implode(", ",$extensions) . ', '. strtolower(implode(", ",$extensions));


    $this->addElement('File', 'file', array(
      'description' => 'Upload your document for verification.',
      'allowEmpty' => false,
      'required' => true,
    ));
    $this->file->addValidator('Extension', false, $string)->addValidator(new Zend_Validate_File_FilesSize(array('max' => $size)));

    $this->addElement('Button', 'submit', array(
        'label' => 'Upload',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array(
            'ViewHelper',
        ),
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'cancel',
        'onclick' => 'javascript:parent.Smoothbox.close()',
        'link' => true,
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
