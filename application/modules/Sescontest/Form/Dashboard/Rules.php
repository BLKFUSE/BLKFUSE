<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescontest
 * @package    Sescontest
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Rules.php  2017-12-01 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescontest_Form_Dashboard_Rules extends Engine_Form {

  public function init() {
    $this->setTitle('Rules')
            ->setDescription('Below, you can enter rules for your contest.')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()))
            ->setMethod('POST');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $upload_url = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesbasic', 'controller' => 'index', 'action' => "upload-image"), 'default', true);

    $allowed_html = 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr';

    $editorOptions = array(
        'upload_url' => $upload_url,
        'html' => (bool) $allowed_html,
    );
    if ($settings->getSetting('sescontest.rules.editor', 1)) {
      $this->addElement('TinyMce', 'rules', array(
          'label' => 'Rules',
          'allowEmpty' => false,
          'required' => true,
          'class' => 'tinymce',
          'editorOptions' => $editorOptions,
      ));
    } else {
      $this->addElement('Textarea', 'rules', array(
          'label' => 'Rules',
          'allowEmpty' => false,
          'required' => true
      ));
    }


    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Save',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));
  }

}
