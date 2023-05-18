<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesuserimport
 * @package    Sesuserimport
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: AddSingleMember.php  2018-11-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sesuserimport_Form_Admin_AddSingleMember extends Engine_Form_Email {

  protected $_defaultProfileId;
  public function getDefaultProfileId() {
    return $this->_defaultProfileId;
  }

  public function setDefaultProfileId($default_profile_id) {
    $this->_defaultProfileId = $default_profile_id;
    return $this;
  }

  public function init() {

    $settings = Engine_Api::_()->getApi('settings', 'core');

    $this->_emailAntispamEnabled = ($settings
        ->getSetting('core.spam.email.antispam.signup', 1) == 1) &&
      empty($_SESSION['facebook_signup']) &&
      empty($_SESSION['twitter_signup']) &&
      empty($_SESSION['janrain_signup']);

    $inviteSession = new Zend_Session_Namespace('invite');
    $tabIndex = 1;

    // Init form
    $this->setTitle('Add Single Member');
    $this->setdescription('Here, you can create a single member on your website. This user will be a site member similar to creating a member during normal signup process.');
    $this->setAttrib('id', 'signup_account_form');

    // Element: name (trap)
    $this->addElement('Text', 'name', array(
      'class' => 'signup-name',
      'label' => 'Name',
      'validators' => array(
	      array('StringLength', true, array('max' => 0)))));

    $this->name->getValidator('StringLength')->setMessage('An error has occured, please try again later.');

    // Element: email
    $emailElement = $this->addEmailElement(array(
      'label' => 'Email Address',

      'description' => 'Enter the email address of the member. This email will be used to login.',

      'required' => true,
      'allowEmpty' => false,
      'validators' => array(
        array('NotEmpty', true),
        array('EmailAddress', true),
        array('Db_NoRecordExists', true, array(Engine_Db_Table::getTablePrefix() . 'users', 'email'))
      ),
      'filters' => array(
        'StringTrim'
      ),
      // fancy stuff
      'inputType' => 'text',
      'autofocus' => 'autofocus',
      'tabindex' => $tabIndex++,
    ));

    $emailElement->getValidator('NotEmpty')->setMessage('Please enter a valid email address.', 'isEmpty');
    $emailElement->getValidator('Db_NoRecordExists')->setMessage('Someone has already registered this email address, please use another one.', 'recordFound');
    $emailElement->getValidator('EmailAddress')->getHostnameValidator()->setValidateTld(false);
    // Add banned email validator
    $bannedEmailValidator = new Engine_Validate_Callback(array($this, 'checkBannedEmail'), $emailElement);
    $bannedEmailValidator->setMessage("This email address is not available, please use another one.");
    $emailElement->addValidator($bannedEmailValidator);

    if( !empty($inviteSession->invite_email) ) {
      $emailElement->setValue($inviteSession->invite_email);
    }

    if( $settings->getSetting('user.signup.random', 0) == 0 &&
        empty($_SESSION['facebook_signup']) &&
        empty($_SESSION['twitter_signup']) &&
        empty($_SESSION['janrain_signup']) ) {

      // Element: password
      $this->addElement('Password', 'password', array(
        'label' => 'Password',
        'description' => 'Enter the password. Password must be at least 6 characters in length. This password will be used while login.',
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
          array('NotEmpty', true),
          array('StringLength', false, array(6, 32)),
        ),
        'tabindex' => $tabIndex++,
      ));

      $this->password->getValidator('NotEmpty')->setMessage('Please enter a valid password.', 'isEmpty');

      // Element: passconf
      $this->addElement('Password', 'passconf', array(
        'label' => 'Password Again',
        'description' => 'Enter the password again for confirmation.',
        'required' => true,
        'validators' => array(
          array('NotEmpty', true),
        ),
        'tabindex' => $tabIndex++,
      ));

      $this->passconf->getValidator('NotEmpty')->setMessage('Please make sure the "password" and "password again" fields match.', 'isEmpty');

      $specialValidator = new Engine_Validate_Callback(array($this, 'checkPasswordConfirm'), $this->password);
      $specialValidator->setMessage('Password did not match', 'invalid');
      $this->passconf->addValidator($specialValidator);
    }


    // Element: username
    if( $settings->getSetting('user.signup.username', 1) > 0 ) {
      $description = Zend_Registry::get('Zend_Translate')

          ->_('Enter the Profile address for this member. This will be the end of profile link for this member.');

      $this->addElement('Text', 'username', array(
        'label' => 'Profile Address',
        'description' => $description,
        'required' => true,
        'allowEmpty' => false,
        'validators' => array(
          array('NotEmpty', true),
          array('Alnum', true),
          array('StringLength', true, array(4, 64)),
          array('Regex', true, array('/^[a-z][a-z0-9]*$/i')),
          array('Db_NoRecordExists', true, array(Engine_Db_Table::getTablePrefix() . 'users', 'username'))
        ),
        'tabindex' => $tabIndex++,
          //'onblur' => 'var el = this; en4.user.checkUsernameTaken(this.value, function(taken){ el.style.marginBottom = taken * 100 + "px" });'
      ));
  //    $this->username->getDecorator('Description')->setOptions(array('placement' => 'APPEND', 'escape' => false));
      $this->username->getValidator('NotEmpty')->setMessage('Please enter a valid profile address.', 'isEmpty');
      $this->username->getValidator('Db_NoRecordExists')->setMessage('Someone has already picked this profile address, please use another one.', 'recordFound');
      $this->username->getValidator('Regex')->setMessage('Profile addresses must start with a letter.', 'regexNotMatch');
      $this->username->getValidator('Alnum')->setMessage('Profile addresses must be alphanumeric.', 'notAlnum');

      // Add banned username validator
      $bannedUsernameValidator = new Engine_Validate_Callback(array($this, 'checkBannedUsername'), $this->username);
      $bannedUsernameValidator->setMessage("This profile address is not available, please use another one.");
      $this->username->addValidator($bannedUsernameValidator);
    }

    //Profile Type Work
    $defaultProfileId = "0_0_" . $this->getDefaultProfileId();
    $customFields = new Sesbasic_Form_Custom_Fields(array(
        'item' => 'user',
        'decorators' => array(
        'FormElements'
    )));
    $customFields->removeElement('submit');
    if ($customFields->getElement($defaultProfileId)) {
      $customFields->getElement($defaultProfileId)
              ->clearValidators()
              ->setRequired(false)
              ->setAllowEmpty(true);
    }
    $this->addSubForms(array(
        'fields' => $customFields
    ));

    // Element: timezone
    $this->addElement('Select', 'timezone', array(
      'label' => 'Timezone',
      'value' => $settings->getSetting('core.locale.timezone'),
      'multiOptions' => array(
        'US/Pacific' => '(UTC-8) Pacific Time (US & Canada)',
        'US/Mountain' => '(UTC-7) Mountain Time (US & Canada)',
        'US/Central' => '(UTC-6) Central Time (US & Canada)',
        'US/Eastern' => '(UTC-5) Eastern Time (US & Canada)',
        'America/Halifax' => '(UTC-4)  Atlantic Time (Canada)',
        'America/Anchorage' => '(UTC-9)  Alaska (US & Canada)',
        'Pacific/Honolulu' => '(UTC-10) Hawaii (US)',
        'Pacific/Samoa' => '(UTC-11) Midway Island, Samoa',
        'Etc/GMT-12' => '(UTC-12) Eniwetok, Kwajalein',
        'Canada/Newfoundland' => '(UTC-3:30) Canada/Newfoundland',
        'America/Buenos_Aires' => '(UTC-3) Brasilia, Buenos Aires, Georgetown',
        'Atlantic/South_Georgia' => '(UTC-2) Mid-Atlantic',
        'Atlantic/Azores' => '(UTC-1) Azores, Cape Verde Is.',
        'Europe/London' => 'Greenwich Mean Time (Lisbon, London)',
        'Europe/Berlin' => '(UTC+1) Amsterdam, Berlin, Paris, Rome, Madrid',
        'Europe/Athens' => '(UTC+2) Athens, Helsinki, Istanbul, Cairo, E. Europe',
        'Europe/Moscow' => '(UTC+3) Baghdad, Kuwait, Nairobi, Moscow',
        'Iran' => '(UTC+3:30) Tehran',
        'Asia/Dubai' => '(UTC+4) Abu Dhabi, Kazan, Muscat',
        'Asia/Kabul' => '(UTC+4:30) Kabul',
        'Asia/Yekaterinburg' => '(UTC+5) Islamabad, Karachi, Tashkent',
        'Asia/Calcutta' => '(UTC+5:30) Bombay, Calcutta, New Delhi',
        'Asia/Katmandu' => '(UTC+5:45) Nepal',
        'Asia/Omsk' => '(UTC+6) Almaty, Dhaka',
        'Indian/Cocos' => '(UTC+6:30) Cocos Islands, Yangon',
        'Asia/Krasnoyarsk' => '(UTC+7) Bangkok, Jakarta, Hanoi',
        'Asia/Hong_Kong' => '(UTC+8) Beijing, Hong Kong, Singapore, Taipei',
        'Asia/Tokyo' => '(UTC+9) Tokyo, Osaka, Sapporto, Seoul, Yakutsk',
        'Australia/Adelaide' => '(UTC+9:30) Adelaide, Darwin',
        'Australia/Sydney' => '(UTC+10) Brisbane, Melbourne, Sydney, Guam',
        'Asia/Magadan' => '(UTC+11) Magadan, Solomon Is., New Caledonia',
        'Pacific/Auckland' => '(UTC+12) Fiji, Kamchatka, Marshall Is., Wellington',
      ),
      'tabindex' => $tabIndex++,
    ));
    $this->timezone->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));

    $this->addElement('File', 'photo', array(
      'label' => 'Profile Photo',
      'description' => 'Upload profile photo for this member.',
      'multiFile' => 1,
      'validators' => array(
        array('Count', false, 1),
        array('Extension', false, 'jpg,jpeg,png,gif'),
      ),
      'tabindex' => $tabIndex++,
    ));

    $this->addElement('File', 'cover', array(
      'label' => 'Cover Photo',
      'description' => 'Upload cover photo for this member.',
      'multiFile' => 1,
      'validators' => array(
        array('Count', false, 1),
        array('Extension', false, 'jpg,jpeg,png,gif'),
      ),
      'tabindex' => $tabIndex++,
    ));

    // Element: language

    // Languages
    $translate = Zend_Registry::get('Zend_Translate');
    $languageList = $translate->getList();

    //$currentLocale = Zend_Registry::get('Locale')->__toString();
    // Prepare default langauge
    $defaultLanguage = Engine_Api::_()->getApi('settings', 'core')->getSetting('core.locale.locale', 'en');
    if( !engine_in_array($defaultLanguage, $languageList) ) {
      if( $defaultLanguage == 'auto' && isset($languageList['en']) ) {
        $defaultLanguage = 'en';
      } else {
        $defaultLanguage = null;
      }
    }

    // Prepare language name list
    $localeObject = Zend_Registry::get('Locale');

    $languageNameList = array();
    $languageDataList = Zend_Locale_Data::getList($localeObject, 'language');
    $territoryDataList = Zend_Locale_Data::getList($localeObject, 'territory');

    foreach( $languageList as $localeCode ) {
      $languageNameList[$localeCode] = Zend_Locale::getTranslation($localeCode, 'language', $localeCode);
      if( empty($languageNameList[$localeCode]) ) {
        list($locale, $territory) = explode('_', $localeCode);
        $languageNameList[$localeCode] = "{$territoryDataList[$territory]} {$languageDataList[$locale]}";
      }
    }
    $languageNameList = array_merge(array(
      $defaultLanguage => $defaultLanguage
    ), $languageNameList);

    if(engine_count($languageNameList)>1){
      $this->addElement('Select', 'language', array(
        'label' => 'Language',
        'multiOptions' => $languageNameList,
        'tabindex' => $tabIndex++,
      ));
      $this->language->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));
    }
    else{
      $this->addElement('Hidden', 'language', array(
        'value' => current((array)$languageNameList),
        'order' => 1002
      ));
    }

    //Element member level
    $authorizationTable = Engine_Api::_()->getDbtable('levels', 'authorization');

    $selectAuth = $authorizationTable->select()
                                    ->from( $authorizationTable->info('name'), array('level_id','title'));
    $levelResults =  $authorizationTable->fetchAll($selectAuth);
    $memberLevelsArray = array();
    foreach($levelResults as $key  => $levelResult) {
        if($levelResult->level_id == 5)   continue;
        $memberLevelsArray[$levelResult->level_id] = $levelResult->title;
    }

    $defaultLevel = Engine_Api::_()->sesuserimport()->defaultLevel();

    $this->addElement('Select', 'level_id',array(
        'label'  => 'Select Member Level',
        'Description'  => 'Select the member level of this member.',
        'required'  => true,
        'multiOptions'  => $memberLevelsArray,
        'tabindex'  => $tabIndex++,
        'value' => $defaultLevel['level_id'],
    ));

    // Init level
    $networkMultiOptions = array(); //0 => ' ');
    $networks = Engine_Api::_()->getDbtable('networks', 'network')->fetchAll();
    foreach( $networks as $row ) {
      $networkMultiOptions[$row->network_id] = $row->getTitle();
    }
    $this->addElement('Multiselect', 'network_id', array(
      'label' => 'Networks',
	  'Description'  => 'Select the networks which will be joined by this member.',
      'multiOptions' => $networkMultiOptions,
      'tabindex'  => $tabIndex++,
    ));

    $this->addElement('Checkbox', 'approved', array(
        'label' => 'Approved?',
        'validators' => array(
            'notEmpty',
            array('GreaterThan', false, array(0)),
        ),
        'tabindex' => $tabIndex++,
    ));

    $this->addElement('Checkbox', 'verified', array(
        'label' => 'Verified?',
        'validators' => array(
            'notEmpty',
            array('GreaterThan', false, array(0)),
        ),
        'tabindex' => $tabIndex++,
    ));

    $this->addElement('Checkbox', 'enabled', array(
        'label' => 'Enabled?',
        'validators' => array(
            'notEmpty',
            array('GreaterThan', false, array(0)),
        ),
        'tabindex' => $tabIndex++,
    ));

    // Init submit
    $this->addElement('Button', 'submit', array(
      'label' => 'Continue',
      'type' => 'submit',
      'ignore' => true,
      'tabindex' => $tabIndex++,
    ));
  }

  public function checkPasswordConfirm($value, $passwordElement)
  {
    return ( $value == $passwordElement->getValue() );
  }

  public function checkBannedEmail($value, $emailElement)
  {
    $bannedEmailsTable = Engine_Api::_()->getDbtable('BannedEmails', 'core');
    if ($bannedEmailsTable->isEmailBanned($value)) {
      return false;
    }
    $isValidEmail = true;
    $event = Engine_Hooks_Dispatcher::getInstance()->callEvent('onCheckBannedEmail', $value);
    foreach ((array)$event->getResponses() as $response) {
      if ($response) {
        $isValidEmail = false;
        break;
      }
    }
    return $isValidEmail;
  }

  public function checkBannedUsername($value, $usernameElement)
  {
    $bannedUsernamesTable = Engine_Api::_()->getDbtable('BannedUsernames', 'core');
    return !$bannedUsernamesTable->isUsernameBanned($value);
  }
}
