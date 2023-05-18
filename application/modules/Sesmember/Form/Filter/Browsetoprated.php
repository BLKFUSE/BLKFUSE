<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Browsetoprated.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Filter_Browsetoprated extends Fields_Form_Search {

  protected $_locationSearch;
  protected $_kilometerMiles;
  protected $_friendsSearch;
  protected $_searchTitle;
  protected $_alphabetSearch;
  protected $_searchType;
  protected $_friendType;
  protected $_stateSearch;
  protected $_citySearch;
  protected $_countrySearch;
  protected $_zipSearch;
  protected $_isVip;
  protected $_memberType;
  protected $_hasPhoto;
  protected $_isOnline;
  protected $_networkGet;

  public function setAlphabetSearch($title) {
    $this->_alphabetSearch = $title;
    return $this;
  }

  public function getAlphabetSearch() {
    return $this->_alphabetSearch;
  }

  public function setLocationSearch($title) {
    $this->_locationSearch = $title;
    return $this;
  }

  public function getLocationSearch() {
    return $this->_locationSearch;
  }

  public function setKilometerMiles($title) {
    $this->_kilometerMiles = $title;
    return $this;
  }

  public function getKilometerMiles() {
    return $this->_kilometerMiles;
  }

  public function setFriendType($title) {
    $this->_friendType = $title;
    return $this;
  }

  public function getFriendType() {
    return $this->_friendType;
  }

  public function setSearchType($title) {
    $this->_searchType = $title;
    return $this;
  }

  public function getSearchType() {
    return $this->_searchType;
  }

  public function setSearchTitle($title) {
    $this->_searchTitle = $title;
    return $this;
  }

  public function getSearchTitle() {
    return $this->_searchTitle;
  }

  public function setMemberType($title) {
    $this->_memberType = $title;
    return $this;
  }

  public function getMemberType() {
    return $this->_memberType;
  }

  public function setIsVip($title) {
    $this->_isVip = $title;
    return $this;
  }

  public function getIsVip() {
    return $this->_isVip;
  }

  public function setHasPhoto($title) {
    $this->_hasPhoto = $title;
    return $this;
  }

  public function getHasPhoto() {
    return $this->_hasPhoto;
  }

  public function setIsOnline($title) {
    $this->_isOnline = $title;
    return $this;
  }

  public function getIsOnline() {
    return $this->_isOnline;
  }

  public function setCountrySearch($title) {
    $this->_countrySearch = $title;
    return $this;
  }

  public function getCountrySearch() {
    return $this->_countrySearch;
  }

  public function setStateSearch($title) {
    $this->_stateSearch = $title;
    return $this;
  }

  public function getStateSearch() {
    return $this->_stateSearch;
  }

  public function setCitySearch($title) {
    $this->_citySearch = $title;
    return $this;
  }

  public function getCitySearch() {
    return $this->_citySearch;
  }

  public function setZipSearch($title) {
    $this->_zipSearch = $title;
    return $this;
  }

  public function getZipSearch() {
    return $this->_zipSearch;
  }

  public function setNetworkGet($title) {
    $this->_networkGet = $title;
    return $this;
  }

  public function getNetworkGet() {
    return $this->_networkGet;
  }

  public function setFriendsSearch($title) {
    $this->_friendsSearch = $title;
    return $this;
  }

  public function getFriendsSearch() {
    return $this->_friendsSearch;
  }

  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $identity = $view->identity;

    $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET')
            ->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'top-members'), 'sesmember_general', true));

    $hideClass = 'sesmember_widget_advsearch_hide_' . $identity;

    if ($this->getSearchTitle() != 'no') {
      $this->addElement('Text', 'search_text', array(
          'label' => 'Search Members/Keyword:',
          'class' => $this->getSearchTitle() == 'hide' ? $hideClass : '',
          'order' => -999999,
      ));
    }

    if ($this->_friendsSearch != 'no') {
      $this->addElement('Select', 'view', array(
          'label' => 'View:',
          'multiOptions' => array(''),
          'class' => $this->getFriendsSearch() == 'hide' ? $hideClass : '',
          'order' => -999998,
      ));
    }

    if ($this->getAlphabetSearch() != 'no') {
      $alphabetArray[] = '';
      foreach (range('A', 'Z') as $char) {
        $alphabetArray[strtolower($char)] = $char;
      }
      $this->addElement('Select', 'alphabet', array(
          'label' => 'Alphabet:',
          'multiOptions' => $alphabetArray,
          'class' => $this->getAlphabetSearch() == 'hide' ? $hideClass : '',
          'order' => -999997,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
      ));
    }

    if ($this->getMemberType() != 'no') {
      $this->getMemberTypeElement();
    }
    
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { 
    $cookiedata = Engine_Api::_()->sesbasic()->getUserLocationBasedCookieData();
    if ($this->getLocationSearch() != 'no' && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)) {
      $this->addElement('Text', 'location', array(
          'label' => 'Location:',
          'id' => 'locationSesList',
          'class' => $this->getLocationSearch() == 'hide' ? $hideClass : '',
          'value' => !empty($cookiedata['location']) ? $cookiedata['location'] : '',
          'order' => -999995,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
      ));

      $this->addElement('Text', 'lat', array(
          'id' => 'latSesList',
          'style' => 'display:none',
          'value' => !empty($cookiedata['lat']) ? $cookiedata['lat'] : '',
          'order' => -999994,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
      ));

      $this->addElement('Text', 'lng', array(
          'id' => 'lngSesList',
          'style' => 'display:none',
          'value' => !empty($cookiedata['lng']) ? $cookiedata['lng'] : '',
          'order' => -999993,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
      ));
      if ($this->_kilometerMiles != 'no') {
        if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.search.type', 1) == 1)
          $searchType = 'Miles:';
        else
          $searchType = 'Kilometer:';
        //Add Element: Sub Category
        $this->addElement('Select', 'miles', array(
            'label' => $searchType,
            'allowEmpty' => true,
            'class' => $this->getKilometerMiles() == 'hide' ? $hideClass : '',
            'order' => -999992,
            'required' => false,
            'multiOptions' => array('0' => '', '1' => '1', '5' => '5', '10' => '10', '20' => '20', '50' => '50', '100' => '100', '200' => '200', '500' => '500', '1000' => '1000'),
            'value' => 1000,
            'registerInArrayValidator' => false,
        ));
      }
    }

    if ($this->_countrySearch != 'no') {
      $locale = Zend_Registry::get('Zend_Translate')->getLocale();
      $territories = Zend_Locale::getTranslationList('territory', $locale, 2);
      asort($territories);
      $arrayTerr = array('' => '');
      foreach ($territories as $key => $val)
        $arrayTerr[$val] = $val;
      //Add Element: country
      $this->addElement('Select', 'country', array(
          'label' => "Country:",
          'allowEmpty' => true,
          'order' => -999991,
          'class' => $this->getCountrySearch() == 'hide' ? $hideClass : '',
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => $arrayTerr,
      ));
    }
    if ($this->_stateSearch != 'no') {
      $this->addElement('Text', 'state', array(
          'label' => 'State:',
          'order' => -999990,
          'class' => $this->getStateSearch() == 'hide' ? $hideClass : '',
      ));
    }
    if ($this->_citySearch != 'no') {
      $this->addElement('Text', 'city', array(
          'label' => 'City:',
           'class' => $this->getCitySearch() == 'hide' ? $hideClass : '',
          'order' => -999989,
      ));
    }
    if ($this->_zipSearch != 'no') {
      $this->addElement('Text', 'zip', array(
          'label' => 'Zip:',
           'class' => $this->getZipSearch() == 'hide' ? $hideClass : '',
          'order' => -999988,
      ));
    }
    }

    if ($this->getNetworkGet() != 'no') {
      //check network exists
      $networkTable = Engine_Api::_()->getDbTable('networks', 'network');
      $networkTableName = $networkTable->info('name');
      $networks = $networkTable->fetchAll($networkTable->select()->from($networkTableName));

      if (engine_count($networks)) {
        //make network array
        $networkArray = array('' => '');
        foreach ($networks as $network) {
          $networkArray[$network['network_id']] = $network->title;
        }
        $this->addElement('Select', 'network', array(
            'label' => 'Network:',
            'multiOptions' => $networkArray,
            'class' => $this->getNetworkGet() == 'hide' ? $hideClass : '',
            'order' => -999987,
        ));
      }
    }

    if ($this->getHasPhoto() != 'no') {
      $this->addElement('Checkbox', 'has_photo', array(
          'label' => 'Only Members With Photos',
          'order' => '9998',
          'class' => $this->getHasPhoto() == 'hide' ? $hideClass : '',
      ));
    }

    if ($this->getIsOnline() != 'no') {
      $this->addElement('Checkbox', 'is_online', array(
          'label' => 'Only Online Members',
          'order' => '9997',
          'class' => $this->getIsOnline() == 'hide' ? $hideClass : '',
      ));
    }

    if ($this->getIsVip() != 'no') {
      $this->addElement('Checkbox', 'is_vip', array(
          'label' => 'Only Vip Members',
          'order' => '9996',
          'class' => $this->getIsVip() == 'hide' ? $hideClass : '',
      ));
    }

    $this->addElement('Button', 'submit', array(
        'label' => 'Search',
        'type' => 'submit',
        'order' => '9999',
    ));
     $this->addElement('Cancel', 'advanced_options_search_' . $identity, array(
        'label' => 'Show Advanced Settings',
        'link' => true,
        'class' => 'active',
        'order' => '10000',
        'href' => 'javascript:;',
        'onclick' => 'return false;',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => 'span')),
            array('HtmlTag', array('class' => 'advanced_search_link'))
        ),
    ));
  }

  public function getMemberTypeElement() {
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $identity = $view->identity;
    $hideClass = 'sesmember_widget_advsearch_hide_' . $identity;
    $multiOptions = array('' => ' ');
    $profileTypeFields = Engine_Api::_()->fields()->getFieldsObjectsByAlias($this->_fieldType, 'profile_type');
    if (engine_count($profileTypeFields) !== 1 || !isset($profileTypeFields['profile_type']))
      return;
    $profileTypeField = $profileTypeFields['profile_type'];

    $options = $profileTypeField->getOptions(array('profiletypeshow' => 1));
    if( engine_count($options) <= 1 ) {
      if( engine_count($options) == 1 ) {
        $this->_topLevelId = $profileTypeField->field_id;
        $this->_topLevelValue = $options[0]->option_id;
      }
      return;
    }
    if (engine_count($options) <= 1) {
      if (engine_count($options) == 1) {
        $this->_topLevelId = $profileTypeField->field_id;
        $this->_topLevelValue = $options[0]->option_id;
      }
      return;
    }

    foreach ($options as $option) {
      $multiOptions[$option->option_id] = $option->label;
    }
    $classForHide = $this->getMemberType() == 'hide' ? $hideClass : '';
    $this->addElement('Select', 'profile_type', array(
        'label' => 'Member Type',
        'order' => -999996,
        'class' =>
        'field_toggle' . ' ' .
        'parent_' . 0 . ' ' .
        'option_' . 0 . ' ' .
        'field_' . $profileTypeField->field_id . ' ' . $classForHide . ' ',
        'multiOptions' => $multiOptions,
    ));
    return $this->profile_type;
  }

}
