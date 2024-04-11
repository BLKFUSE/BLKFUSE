<?php

/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class User_Form_Admin_Manage_AddNewUser extends Engine_Form_Email {

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
    
    $translate = Zend_Registry::get('Zend_Translate');

    $this->_emailAntispamEnabled = ($settings
        ->getSetting('core.spam.email.antispam.signup', 1) == 1) &&
      empty($_SESSION['facebook_signup']) &&
      empty($_SESSION['twitter_signup']) &&
      empty($_SESSION['janrain_signup']);

    $inviteSession = new Zend_Session_Namespace('invite');
    $tabIndex = 1;

    // Init form
    $this->setTitle('Add New User');
    $this->setdescription('Here, you can add a new user to your site.');
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

    if( $settings->getSetting('user.signup.random', 0) == 0) {
    
      //Work For Show and Hide Password
      $this->addElement('dummy', 'showpassword', array(
        'label' => 'Password',
        'decorators' => array(array('ViewScript', array(
          'viewScript' => 'application/modules/User/views/scripts/admin-manage/_generatePassword.tpl',
        ))),
      ));

      // Element: password
      $this->addElement('Password', 'password', array(
        'label' => 'Password',
        'description' => 'Password must be at least 6 characters and contain one upper and one lower case letter, one number and one special character.',
        //'required' => true,
        //'allowEmpty' => false,
        'id' => 'signup_password',
        "autocomplete" => "off",
        'onkeyup' => 'passwordRoutine(this.value);',
        'validators' => array(
          array('NotEmpty', true),
          array('StringLength', false, array(6, 32)),
            array('Regex', true, array('/^(?=.*[A-Z].*)(?=.*[\!#\$%&\*\-\?\@\^])(?=.*[0-9].*)(?=.*[a-z].*).*$/')),

        ),
        'tabindex' => $tabIndex++,
      ));
      
      //$this->password->getDecorator('Description')->setOptions(array('placement' => 'APPEND'));
      $this->password->getValidator('Regex')->setMessage('Password must be at least 6 characters and contain one upper and one lower case letter, one number and one special character.');
      $this->password->getValidator('NotEmpty')->setMessage('Please enter a valid password.', 'isEmpty');

      $regexCheck = new Engine_Validate_Callback(array($this, 'regexCheck'), $this->password);
      $regexCheck->setMessage("Password must be at least 6 characters and contain one upper and one lower case letter, one number and one special character.");
      $this->password->addValidator($regexCheck);

      //Work For Show and Hide Password
      $this->addElement('dummy', 'showhidepassword', array(
        'decorators' => array(array('ViewScript', array(
          'viewScript' => 'application/modules/User/views/scripts/_showhidepassword.tpl',
        ))),
        'tabindex' => $tabIndex++,
      ));
      //Work For Show and Hide Password

      $this->addElement('dummy', 'copyPasswordLink', array(
        'content' => '<a href="javascript:;"  data-bs-toggle="tooltip" data-bs-placement="top" title=""data-bs-original-title="'.$translate->translate("Copy Password").'" class="copy_password" id="copy_password" title="Copy Password"><i class="far fa-copy"></i></a>',
      ));
      
			$this->addDisplayGroup(array('password', 'showhidepassword', 'copyPasswordLink'), 'password_settings_group');

      

      $this->addElement('Dummy', 'passwordroutine', array(
        'label' => '',
        'content' => '
          <div id="pswd_info">
            <ul>
                <li id="passwordroutine_length" class="invalid"><span>'.$translate->translate("6 characters").'</span></li>
                <li id="passwordroutine_capital" class="invalid"><span>'.$translate->translate("1 uppercase").'</span></li>
                <li id="passwordroutine_lowerLetter" class="invalid"><span>'.$translate->translate("1 lowercase").'</span></li>
                <li id="passwordroutine_number" class="invalid"><span>'.$translate->translate("1 number").'</span></li>
                <li id="passwordroutine_specialcharacters" class="invalid"><span>'.$translate->translate("1 special").'</span><span class="special_char_ques"> <i class="far fa-question-circle" data-bs-toggle="tooltip" title="'.$translate->translate("Special Characters Allowed !#$%&*-?@^").'"></i></span></li>
            </ul>
          </div>',
      ));
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
    $customFields = new Fields_Form_Standard(array(
        'item' => Engine_Api::_()->user()->getUser(null),
        'decorators' => array(
        'FormElements'
    )));
    $customFields->removeElement('submit');
    if ($customFields->getElement($defaultProfileId)) {
      $customFields->getElement($defaultProfileId)
              ->clearValidators()
              ->setRequired(true)
              ->setAllowEmpty(false);
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
        array('Extension', false, 'jpg,jpeg,png,gif,webp'),
      ),
      'tabindex' => $tabIndex++,
    ));

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
    $levelMultiOptions = array();
    $levels = Engine_Api::_()->getDbtable('levels', 'authorization')->fetchAll();
    foreach( $levels as $row ) {
      $levelMultiOptions[$row->level_id] = $row->getTitle();
    }
    $defaultLevelId = Engine_Api::_()->getItemTable('authorization_level')->getDefaultLevel()->level_id;
    $this->addElement('Select', 'level_id',array(
        'label'  => 'Select Member Level',
        'Description'  => 'Select the member level of this member.',
        'required'  => true,
        'multiOptions'  => $levelMultiOptions,
        'tabindex'  => $tabIndex++,
        'value' => $defaultLevelId,
    ));

    // Init level
    $networkMultiOptions = array();
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
        'label' => 'Is Email Verified?',
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
    
    $this->addElement('Checkbox', 'is_verified', array(
      'label' => 'Verified?',
      'validators' => array(
          'notEmpty',
          array('GreaterThan', false, array(0)),
      ),
      'tabindex' => $tabIndex++,
    ));

    
    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Add',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper'),
      'tabindex' => $tabIndex++,
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => 'admin/user/manage',
      'decorators' => array(
        'ViewHelper'
      ),
      'tabindex' => $tabIndex++,
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');
  }

  public function regexCheck($value)
  {
    if(preg_match("/([\\\\:\/])/", $value))
    {
        return false;
    }
    return true;
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
