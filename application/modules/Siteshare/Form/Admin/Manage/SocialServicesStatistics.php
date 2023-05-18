<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Siteshare
 * @copyright  Copyright 2017-2021 BigStep Technologies Pvt. Ltd.
 * @license    http://www.socialengineaddons.com/license/
 * @version    $Id: Sharetype.php 2017-02-24 9:40:21Z SocialEngineAddOns $
 * @author     SocialEngineAddOns
 */
class Siteshare_Form_Admin_Manage_SocialServicesStatistics extends Engine_Form
{
  public function init()
  {
    $this
      ->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
    ;

    $this
      ->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box siteshare_share_stats_form',
      ))
      ->setMethod('GET')
    ;
    $socialShareHistories = Engine_Api::_()->getDbTable('socialShareHistories', 'siteshare');
    $multiOptions = $socialShareHistories->getSharePageUrls();
// Element: enabled
    $this->addElement('Select', 'pageUrl', array(
      'label' => 'Shared Page URL',
      'multiOptions' => array_merge(array('' => ''), $multiOptions),
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
      'value' => '',//key($multiOptions)
    ));
    $this->addElement('Text', 'start', array(
      'label' => 'From (Ex: 2017-06-21)',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
    ));

    $this->addElement('Text', 'end', array(
      'label' => 'To (Ex: 2017-07-30)',
      'decorators' => array(
        'ViewHelper',
        array('Label', array('tag' => null, 'placement' => 'PREPEND')),
        array('HtmlTag', array('tag' => 'div')),
      ),
    ));
    // Element: execute
    $this->addElement('Button', 'search', array(
      'label' => 'Search',
      'type' => 'submit',
      'value' => 1,
      'decorators' => array(
        'ViewHelper',
        array('HtmlTag', array('tag' => 'div', 'class' => 'buttons')),
        array('HtmlTag2', array('tag' => 'div')),
      ),
    ));
  }

}

?>