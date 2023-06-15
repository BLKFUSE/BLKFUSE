<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: EditPhoto.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Album_EditPhoto extends Engine_Form
{
  protected $_isArray = true;

  public function init()
  {
    $this->clearDecorators()
      ->addDecorator('FormElements');

    $this->addElement('Text', 'title', array(
      'placeholder' => 'Title',
      'filters' => array(
        new Engine_Filter_Censor(),
        new Engine_Filter_HtmlSpecialChars(),
      ),
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div', 'class'=>'sesnews_editphotos_title_input')),
        array('Label', array('tag' => 'div', 'placement' => 'PREPEND', 'class' => 'sesnews_editphotos_title')),
      ),
    ));

    $this->addElement('Textarea', 'description', array(
      'placeholder' => 'Caption',
      'rows' => 2,
      'cols' => 120,
      'filters' => array(
        new Engine_Filter_Censor(),
      ),
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div', 'class'=>'sesnews_editphotos_caption_input')),
        array('Label', array('tag' => 'div', 'placement' => 'PREPEND', 'class'=>'sesnews_editphotos_caption_label')),
      ),
    ));

    $this->addElement('Select', 'move', array(
      'label' => 'Move to',
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div', 'class'=>'sesnews_editphotos_move_input')),
        array('Label', array('tag' => 'div', 'placement' => 'PREPEND', 'class'=>'sesnews_editphotos_moveto_label')),
      ),
    ));

    $this->addElement('Checkbox', 'delete', array(
      'label' => "Delete Photo",
      'decorators' => array(
        'ViewHelper',
        array('Label', array('placement' => 'APPEND')),
        array('HtmlTag', array('tag' => 'div', 'class' => 'photo-delete-wrapper')),
      ),
    ));


    $this->addElement('Hidden', 'photo_id', array(
      'validators' => array(
        'Int',
      )
    ));
  }
}
