<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Field.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    Fields
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */
class Fields_Form_Admin_Field extends Engine_Form
{
  public function init()
  {
    $this->setMethod('POST')
      ->setAttrib('class', 'global_form_smoothbox')
      ->setTitle('Add Profile Question');

    // Add type
    $categories = Engine_Api::_()->fields()->getFieldInfo('categories');
    $types = Engine_Api::_()->fields()->getFieldInfo('fields');
    $fieldByCat = array();
    $availableTypes = array();
    foreach( $types as $fieldType => $info ) {
      $fieldByCat[$info['category']][$fieldType] = $info['label'];
    }
    foreach( $categories as $catType => $categoryInfo ) {
      $label = $categoryInfo['label'];
      $availableTypes[$label] = $fieldByCat[$catType];
    }

    $this->addElement('Select', 'type', array(
      'label' => 'Question Type',
      'required' => true,
      'allowEmpty' => false,
      'multiOptions' => $availableTypes,
      /* 'multiOptions' => array(
        'text' => 'Text Field',
        'textarea' => 'Multi-line Textbox',
        'select' => 'Pull-down Select Box',
        'radio' => 'Radio Buttons',
        'checkbox' => 'Checkboxes',
        'date' => 'Date Field'
      ) */
      'onchange' => 'this.form.method = "get"; this.form.submit();',
    ));

    // Add label
    $this->addElement('Text', 'label', array(
      'label' => 'Question Label',
      'required' => true,
      'allowEmpty' => false,
    ));

    // Add description
    $this->addElement('Textarea', 'description', array(
      'label' => 'Description',
      'rows' => 6,
    ));

    // Add Css
    $this->addElement('Text', 'style', array(
      'label' => 'Inline CSS',
      'description' => "You can provide a CSS class to the question so that you can style specific questions differently. You will need to define this class either in the source code or one of the CSS files editable in the Theme Editor.",
    ));
    $this->style->getDecorator("Description")->setOption("placement", "append");

    // Add error
    $this->addElement('Text', 'error', array(
      'label' => 'Custom Error Message',
    ));

    // Add Icon
    $this->addElement('Text', 'icon', array(
      'label' => 'Icon / Icon Class',
    ));

    // Add required
    $this->addElement('Select', 'required', array(
      'label' => 'Required?',
      'multiOptions' => array(
        0 => 'Not Required',
        1 => 'Required'
      ),
    ));

    // Add search
    $this->addElement('Select', 'search', array(
      'label' => 'Show on Browse Members Page?',
      'multiOptions' => array(
        0 => 'Hide on Browse Members',
        1 => 'Show on Browse Members',
        2 => 'Show when no profile type has been selected',
      ),
    ));

    // Display
    $this->addElement('Select', 'display', array(
      'label' => 'Show on Member Profiles? (Note: Show on Member Profiles (with links) option will only work if you have selected "Show on Browse Members" option for this field from the above setting.)',
      'multiOptions' => array(
        1 => 'Show on Member Profiles',
        2 => 'Show on Member Profiles (with links)',
        0 => 'Hide on Member Profiles'
      )
    ));

    // Show
    $this->addElement('Select', 'show', array(
      'label' => 'Show on Signup/Creation?',
      'multiOptions' => array(
        1 => 'Show on signup/creation',
        0 => 'Hide on signup/creation',
      )
    ));

    // Add submit
    $this->addElement('Button', 'execute', array(
      'label' => 'Save Question',
      'type' => 'submit',
      'decorators' => array(
        'ViewHelper',
      ),
      'order' => 10000,
      'ignore' => true,
    ));

    // Add cancel
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'onclick' => 'parent.Smoothbox.close();',
      'prependText' => ' or ',
      'decorators' => array(
        'ViewHelper',
      ),
      'order' => 10001,
      'ignore' => true,
    ));

    $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
      'order' => 10002,
    ));
  }
}
