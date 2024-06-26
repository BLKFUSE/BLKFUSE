<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     Jung
 */

/**
 * @category   Application_Core
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class Core_Form_Admin_Widget_RichText extends Engine_Form
{
  public function init()
  {
    $this
      ->setAttrib('class', 'global_form_popup')
      ->setAction($_SERVER['REQUEST_URI']);

    $this->addElement('Text', 'title', array(
      'label' => 'Title',
      'order' => -100,
    ));
    $this->addElement('Text', 'adminTitle', array(
      'label' => 'Admin Title',
      'order' => -99,
      'maxlength'=> 64,
    ));

    $languages = Zend_Locale::getTranslationList('language', Zend_Registry::get('Locale'));
    $languageList = Zend_Registry::get('Zend_Translate')->getList();
    
    $uploadUrl = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true);
    
    foreach ($languageList as $key => $language) {
      if ($language == 'en')
        $coulmnName = 'data';
      else
        $coulmnName = $language . '_data';
      $this->addElement('TinyMce', $coulmnName, array(
          'label' => 'Content for ' . @$languages[$key],
          'editorOptions' => array(
            'uploadUrl' => $uploadUrl,
            'code_dialog_width' => '500',
          ),
          'filters' => array(
            new Engine_Filter_Censor(),
          ),
      ));
    }

    $this->addElement('Hidden', 'name', array(
      'order' => 100005,
    ));

    $this->addElement('Button', 'execute', array(
      'label' => 'Save Changes',
      'type' => 'submit',
      'order' => 100006,
      'ignore' => true,
      'decorators' => array('ViewHelper'),
    ));
    
    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'onclick' => 'parent.Smoothbox.close();',
      'ignore' => true,
      'order' => 100007,
      'decorators' => array('ViewHelper'),
    ));

     $this->addDisplayGroup(array('execute', 'cancel'), 'buttons', array(
      'order' => 100008,
    ));
  }
}
