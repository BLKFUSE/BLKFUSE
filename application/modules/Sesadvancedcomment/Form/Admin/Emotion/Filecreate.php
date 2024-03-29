<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Filecreate.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesadvancedcomment_Form_Admin_Emotion_Filecreate extends Engine_Form {

  public function init() {
  
    $this->setTitle('Add Sticker')
            ->setDescription('');
    $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
    if(empty($id)) {
      $re = true;
      $all = false;
    } else {
      $re = false;
      $all = true;
    }
    
    $this->addElement('File', 'file', array(
        'allowEmpty' => $all,
        'required' => $re,
        'label' => 'Upload Sticker',
        'description' => 'Upload a sticker [Note: sticker (photos) with extension: "jpg, png, jpeg and gif" only.]',
    ));
    $this->file->addValidator('Extension', false, 'jpg,png,jpeg,gif,GIF,PNG,JPG,JPEG');
    
    //Search options
    $this->addElement('Text', 'tags',array(
      'label'=>'Sticker Categories',
      'autocomplete' => 'off',
      'description' => 'Enter the categories for this sticker and separate them key commas. (Note: The categories in "Sticker Categories" section will come in auto-suggest and the categories which are not in that section will show results when users will search for them.)',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    $this->tags->getDecorator("Description")->setOption("placement", "append");

    
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