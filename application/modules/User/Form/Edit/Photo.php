<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Photo.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Form_Edit_Photo extends Engine_Form
{
  public function init()
  {
    $this->setTitle("Edit My Photo");
    $this
      ->setAttrib('enctype', 'multipart/form-data')
      ->setAttrib('name', 'EditPhoto');

    $this->addElement('Image', 'current', array(
      'label' => 'Current Photo',
      'ignore' => true,
      'decorators' => array(array('ViewScript', array(
        'viewScript' => '_formEditImage.tpl',
        //'viewScript' => '_formImageCrop.tpl',
        'class'      => 'form element',
        'testing' => 'testing'
      )))
    ));
    Engine_Form::addDefaultDecorators($this->current);
    
    $this->addElement('File', 'Filedata', array(
      'label' => 'Choose New Photo',
      'multiFile' => 1,
      'validators' => array(
        array('Count', false, 1),
        // array('Size', false, 612000),
        array('Extension', false, 'jpg,png,gif,jpeg,webp'),
      ),
      'data-function' => 'uploadSignupPhoto',
    ));

    $this->addElement('Hidden', 'coordinates', array(
      'filters' => array(
        'HtmlEntities',
      )
    ));

    $this->addElement('Button', 'done', array(
      'label' => 'Save Photo',
      'type' => 'submit',
      'decorators' => array(
        'ViewHelper'
      ),
    ));

    $this->addElement('Cancel', 'remove', array(
      'label' => 'remove photo',
      'link' => true,
      'prependText' => ' or ',
      'href' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array(
          'action' => 'remove-photo',
      )),
      'onclick' => null,
      'class' => 'smoothbox',
      'decorators' => array(
        'ViewHelper'
      ),
    ));

    $this->addDisplayGroup(array('done', 'remove'), 'buttons');
    
  }
}
