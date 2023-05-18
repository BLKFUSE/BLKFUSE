<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesbday
 * @package    Sesbday
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Birthday.php  2018-12-20 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesbday_Form_Admin_Settings_Birthday extends Engine_Form {

  public function init() {

    $this->setTitle("Member Birthday Email Template")
            ->setDescription('Configure the Email message for birthday wish to members.')
            ->setMethod('post')
            ->setAttrib('class', 'global_form_box')
            ->setAttrib('id', 'birthday_main_test_email');
    if (isset($_POST['sesbday_birthday_enable']) && $_POST['sesbday_birthday_enable']) {
      $required = true;
      $empty = false;
    } else {
      $required = false;
      $empty = true;
    }
    $this->addElement('Select', 'sesbday_birthday_enable', array(
        'label' => 'Birthday Email',
        'onchange' => 'enableContent(this.value);return false;',
        'description' => 'Do you want to enable Bithday email to the site member',
        'multiOptions' => array('1' => 'Yes, want to enable birthday wish to site member', '0' => 'No, don\'t want to enable birthday wish to site member'),
        'allowEmpty' => false,
        'required' => true,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbday.birthday.enable', 1),
    ));

    $this->addElement('Text', 'sesbday_birthday_subject', array(
        'label' => 'Birthday Email Subject',
        'description' => 'Subject send in the birthday wish email to the site user.',
        'allowEmpty' => $empty,
        'required' => $required,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbday.birthday.subject', ''),
    ));

    $this->addElement('Hidden', 'testemailval', array('order' => 878));
    //UPLOAD PHOTO URL
    $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesbasic', 'controller' => 'index', 'action' => "upload-image"), 'default', true);

    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

    $editorOptions = array(
			'upload_url' => $upload_url,
			'html' => (bool) $allowed_html,
    );

    if (!empty($upload_url)) {
      $editorOptions['editor_selector'] = 'tinymce';
      $editorOptions['mode'] = 'specific_textareas';
      $editorOptions['plugins'] = array(
          'table', 'fullscreen', 'preview', 'paste',
          'code', 'textcolor', 'jbimages', 'link'
      );

      $editorOptions['toolbar1'] = array(
          'undo', 'redo', 'removeformat', 'pastetext', '|', 'code',
          'media', 'image', 'jbimages', 'link', 'fullscreen',
          'preview'
      );
    }

    $this->addElement('TinyMce', 'sesbday_birthday_content', array(
        'label' => 'Birthday Email Content',
        'editorOptions' => $editorOptions,
        'class' => 'tinymce',
        'description' => 'Content send in the birthday wish email to the site user.',
        'allowEmpty' => $empty,
        'required' => $required,
        'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbday.birthday.content', ''),
    ));

    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
    $this->addElement('Button', 'testemail', array(
        'label' => 'Send Test Email',
        'type' => 'button',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
  }

}
