<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: RssSettings.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesnews_Form_Admin_RssSettings extends Engine_Form {

  public function init() {

    $this->setTitle('Rss Settings')
        ->setDescription('These settings affect all members in your community.');

      $this->addElement('Radio', 'sesnews_rss_enable', array(
          'label' => 'Enable Rss',
          'description' => 'Do you want to enable RSS feature on your website?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'onchange' => 'hideshow(this.value);',
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.rss.enable', 1),
      ));

      $this->addElement('Text', 'sesnews_mercurykey', array(
//           'label' => 'Mercury Web Parser API Key',
//           'description' => 'Enter the Mercury Web Parser API Key. Please click <a href="https://mercury.postlight.com/" target="_blank">here</a>.',
          'label' => 'rss2json API Key',
          'description' => 'Enter the rss2json API Key. Please click <a href="https://rss2json.com" target="_blank">here</a>.',
          'allowEmpty' => false,
          'required' => true,
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.mercurykey', ''),
      ));
      $this->sesnews_mercurykey->addDecorator('Description', array('placement' => Zend_Form_Decorator_Abstract::PREPEND, 'escape' => false));

      $this->addElement('Radio', 'sesnews_enablesubs', array(
          'label' => 'Allow Subscribe Rss',
          'description' => 'Do you want to allow user subscribe RSS on your website?',
          'multiOptions' => array(
              '1' => 'Yes',
              '0' => 'No',
          ),
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.enablesubs', 1),
      ));

      $this->addElement('Text', 'sesnews_maxfetchnews', array(
          'label' => 'Maximum Fetched News',
          'description' => 'Enter the maximum number of news you want to fetched from rss.',
          'allowEmpty' => false,
          'required' => true,
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.maxfetchnews', 10),
      ));

      $this->addElement('Text', 'sesnews_cronjon', array(
          'label' => 'Cron Job Schedule',
          'description' => 'Enter number of days you want to fetched news from your rss feeds.',
          'allowEmpty' => false,
          'required' => true,
          'value' => Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.cronjon', '1'),
      ));

      // Add submit button
      $this->addElement('Button', 'submit', array(
          'label' => 'Save Changes',
          'type' => 'submit',
          'ignore' => true
      ));
  }
}
