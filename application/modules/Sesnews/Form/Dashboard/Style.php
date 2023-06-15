<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Style.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Dashboard_Style extends Engine_Form {

  public function init() {
    $this
    ->setTitle('News Styles')
    ->setAttrib('id', 'sesnews_ajax_form_submit')
    ->setMethod("POST")
    ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));

    $this->removeDecorator('FormWrapper');

    // Element: style
    $this->addElement('Textarea', 'style', array(
      'label' => 'Custom Sesnews Styles',
      'description' => 'You can change the colors, fonts, and styles of your news by adding CSS code below. The contents of the text area below will be output between <style> tags on your news.'
    ));

    // Element: submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Save Changes',
      'type' => 'submit',
    ));

    // DisplayGroup: buttons
    //$this->addDisplayGroup(array('submit'), 'buttons', array());
    $this->addElement('Hidden', 'id');
  }
}
