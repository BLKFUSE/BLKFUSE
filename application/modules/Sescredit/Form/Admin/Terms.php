<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescredit
 * @package    Sescredit
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Terms.php  2019-01-18 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescredit_Form_Admin_Terms extends Engine_Form {

  public function init() {
    //UPLOAD PHOTO URL
    $editorOptions = array(
      'uploadUrl' => Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'core', 'controller' => 'index', 'action' => 'upload-photo'), 'default', true),
    );
    $this->addElement('TinyMce', 'terms', array(
        'disableLoadDefaultDecorators' => true,
        'required' => true,
        'allowEmpty' => false,
        'decorators' => array(
            'ViewHelper'
        ),
        'editorOptions' => $editorOptions,
        'filters' => array(
            new Engine_Filter_Censor(),
            new Engine_Filter_Html(array('AllowedTags' => $allowedHtml))),
    ));
  }

}
