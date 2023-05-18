<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesdating
 * @package    Sesdating
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: CustomTheme.php  2018-09-21 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesdating_Form_Admin_CustomTheme extends Engine_Form {

  public function init() {

    $this->setTitle('Add New Custom Theme');
    $this->setMethod('post');
    $sesdating_landingpage = Zend_Registry::isRegistered('sesdating_landingpage') ? Zend_Registry::get('sesdating_landingpage') : null;
    $this->addElement('Text', 'name', array(
        'label' => 'Enter the new custom theme name.',
        'allowEmpty' => false,
        'required' => true,
    ));
    if($sesdating_landingpage) {
    $getCustomThemes = Engine_Api::_()->getDbTable('customthemes', 'sesdating')->getCustomThemes(array('all' => 1));
    foreach($getCustomThemes as $getCustomTheme){
      $sestheme[$getCustomTheme['customtheme_id']] = $getCustomTheme['name'];
    }
    }
    $this->addElement('Select', 'customthemeid', array(
        'label' => 'Choose From Existing Theme',
        'multiOptions' => $sestheme,
        'escape' => false,
    ));
    
    // Buttons
    $this->addElement('Button', 'submit', array(
        'label' => 'Create',
        'type' => 'submit',
        'ignore' => true,
        'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
        'label' => 'Cancel',
        'link' => true,
        'prependText' => ' or ',
        'href' => '',
        'onClick' => 'javascript:parent.Smoothbox.close();',
        'decorators' => array(
            'ViewHelper'
        )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
  }

}
