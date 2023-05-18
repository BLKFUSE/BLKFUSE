<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Share.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Form_Share extends Activity_Form_Share
{
  public function init()
  {
    $shareTypeOptions = array(); //
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewObject = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    
    if( $viewer && $viewer->getIdentity() ) {
      $shareTypeOptions = Engine_Api::_()->getDbTable('sharetypes', 'siteshare')->getShareableOptions();
      $usersAllowed = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('messages', $viewer->level_id, 'auth');
      if( (!$usersAllowed || $usersAllowed == 'none') && isset($shareTypeOptions['message']) ) {
        unset($shareTypeOptions['message']);
      }
    } else {
      $shareTypeOptions = array('email' => $viewObject->translate( 'Share via Email' ));
    }

    $this->addElement('Select', 'type', array(
      'label' => '',
      'multiOptions' => $shareTypeOptions,
      'onchange' => "changeType(this)",
      'value' => 'timeline',
    ));
    $this->addElement('Text', 'title', array(
      'label' => '',
      'placeholder' => 'Enter Email Address',
      'autocomplete' => 'off',
      'filters' => array(
        'StripTags',
        new Engine_Filter_Censor(),
      ),
    ));
    $this->addElement('hidden', 'item_id', array(
      'order' => 6001,
      'label' => 'Itme',
    ));
    parent::init();
    $this
      ->setAttrib('id', 'siteshare_share_form_popup')
      ->setTitle('')
      ->setDescription('')
    ;
  }

}
