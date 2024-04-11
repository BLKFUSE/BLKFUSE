<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbasic
 * @package    Sesbasic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ContentForm.php 2015-07-25 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesbasic_Form_ContentForm extends Engine_Form {

  public function init() {

    //UPLOAD PHOTO URL
    $editorOptions = array(
      'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
    );

    $languages = Zend_Locale::getTranslationList('language', Zend_Registry::get('Locale'));
    $languageList = Zend_Registry::get('Zend_Translate')->getList();

    foreach ($languageList as $key => $language) {
      if ($language == 'en')
        $coulmnName = 'body';
      else
        $coulmnName = $language . '_body';

      $this->addElement('TinyMce', $coulmnName, array(
          'label' => 'Content for ' . $languages[$key],
          'required' => true,
          'editorOptions' => $editorOptions,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_Html(array('AllowedTags' => $allowed_html))),
      ));
    }

    $this->addElement('Text', 'content_height', array(
        'label' => 'Height',
        'description' => '',
        'value' => '',
    ));
    $this->addElement('Text', 'content_width', array(
        'label' => 'Width',
        'description' => '',
        'value' => '',
    ));
    $this->addElement('Text', 'content_class', array(
        'label' => 'CSS Class',
        'description' => '',
        'value' => '',
    ));

    $this->addElement('Radio', 'show_content', array(
        'label' => 'Do you want to show this block to non-logged in users?',
        'multiOptions' => array("1" => "Yes", "0" => "No"),
        'value' => '1'
    ));
  }

}