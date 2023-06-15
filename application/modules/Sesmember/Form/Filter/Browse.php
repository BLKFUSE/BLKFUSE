<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Browse.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Form_Filter_Browse extends Fields_Form_Search {

  protected $_locationSearch;
  protected $_kilometerMiles;
  protected $_friendsSearch;
  protected $_searchTitle;
  protected $_alphabetSearch;
  protected $_searchType;
  protected $_friendType;
  protected $_browseBy;
  protected $_stateSearch;
  protected $_citySearch;
  protected $_countrySearch;
  protected $_zipSearch;
  protected $_isVip;
  protected $_memberType;
  protected $_hasPhoto;
  protected $_isOnline;
  protected $_networkGet;
  protected $_nearestLocation;
  protected $_profileTypes;
  protected $_defaultProfiletypes;
  protected $_isInterest;

  public function setDefaultProfiletypes($title) {
    $this->_defaultProfiletypes = $title;
    return $this;
  }

  public function getDefaultProfiletypes() {
    return $this->_defaultProfiletypes;
  }

  public function setProfileTypes($title) {
    $this->_profileTypes = $title;
    return $this;
  }

  public function getProfileTypes() {
    return $this->_profileTypes;
  }

  public function setNearestLocation($title) {
    $this->_nearestLocation = $title;
    return $this;
  }

  public function getNearestLocation() {
    return $this->_nearestLocation;
  }

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

  public function setBrowseBy($title) {
    $this->_browseBy = $title;
    return $this;
  }

  public function getBrowseBy() {
    return $this->_browseBy;
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


  public function setIsInterest($title) {
    $this->_isInterest = $title;
    return $this;
  }

  public function getIsInterest() {
    return $this->_isInterest;
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

    $homepage_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('homepage_id', 0);

    if ($this->getMemberType() != 'no' && empty($homepage_id)) {
      $this->getMemberTypeElement($identity);
    }

    $request = Zend_Controller_Front::getInstance()->getRequest();
    $getModuleName = $request->getModuleName();
    $getControllerName = $request->getControllerName();
    $getActionName = $request->getActionName();

    if ($getControllerName == 'index' && $getActionName == 'pinborad-view-members') {
      $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET')->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    } else if ($getModuleName == 'sesblog' && $getControllerName == 'index' && $getActionName == 'contributors') {
      $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET')->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    } else if ($getModuleName == 'sesmember' && $getControllerName == 'index' && $getActionName == 'profiletype') {
      $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET')->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'profiletype'), "sesmember_index_$homepage_id", true));
    } else {
      $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET')->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'browse'), 'sesmember_general', true));
    }

    $hideClass = 'sesmember_widget_advsearch_hide_' . $identity;

    if ($this->getSearchTitle() != 'no') {
      $this->addElement('Text', 'search_text', array(
          'label' => 'Search Members/Keyword:',
          'class' => $this->getSearchTitle() == 'hide' ? $hideClass : '',
          'order' => -999999,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
      ));
    }

    if ($this->getBrowseBy() != 'no') {
      $viewerId = Engine_Api::_()->user()->getViewer()->getIdentity();
      $filterOptions = $this->_searchType;
      $arrayOptions = $filterOptions;
      $filterOptions = array();
      foreach ((array) $arrayOptions as $key => $filterOption) {
        $value = str_replace(array('SP', ''), array(' ', ' '), $filterOption);
        $optionKey = Engine_Api::_()->sesbasic()->getColumnName($value);
        if(!$viewerId && $value == 'mylike')
        continue;
        if($value == 'mylike')
        $value = 'Members I Liked';
        if(!$viewerId && $value == 'myfollow')
        continue;
        if($value == 'myfollow')
        $value = 'Members I Followed';
        if($value == 'atoz')
        $value = 'A to Z';
        if($value == 'ztoa')
        $value = 'Z to A';
        if (Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesblog')) {
          if($value == 'mostcontributors')
            $value = 'Most Contributors';
        }
        $filterOptions[$optionKey] = ucwords($value);
      }
      $filterOptions = array('' => '') + $filterOptions;
      if (engine_count($filterOptions) > 1) {

        $this->addElement('Select', 'order', array(
            'label' => 'Browse By:',
            'multiOptions' => $filterOptions,
            'class' => $this->getBrowseBy() == 'hide' ? $hideClass : '',
            'order' => -999998,
            'decorators' => array(
                'ViewHelper',
                array('Label', array('tag' => 'span')),
                array('HtmlTag', array('tag' => 'li', 'class' => ''))
            ),
        ));
      }
    }

    if ($this->getFriendsSearch() != 'no' && $this->getFriendsSearch() != '') {
      $this->addElement('Select', 'view', array(
          'label' => 'View:',
          'multiOptions' => array(''),
          'class' => $this->getFriendsSearch() == 'hide' ? $hideClass : '',
          'order' => -999997,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
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
          'order' => -999996,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
      ));
    }

    if ($this->getNearestLocation()) {
      $this->addElement('Hidden', 'location', array(
          'order' => 3953223,
      ));
      $this->addElement('Hidden', 'lat', array(
          'order' => 39623234,
      ));
      $this->addElement('Hidden', 'lng', array(
          'order' => 39723324,
      ));
      $this->addElement('Hidden', 'miles', array(
          'order' => 39743324,
      ));
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
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
      ));

      $this->addElement('Hidden', 'lat', array(
          'id' => 'latSesList',
          'style' => 'display:none',
          'value' => !empty($cookiedata['lat']) ? $cookiedata['lat'] : '',
          'order' => -999994,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'balank_list'))
          ),
      ));

      $this->addElement('Hidden', 'lng', array(
          'id' => 'lngSesList',
          'style' => 'display:none',
          'value' => !empty($cookiedata['lng']) ? $cookiedata['lng'] : '',
          'order' => -999993,
          'filters' => array(
              new Engine_Filter_Censor(),
              new Engine_Filter_HtmlSpecialChars(),
          ),
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'balank_list'))
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
            'decorators' => array(
                'ViewHelper',
                array('Label', array('tag' => 'span')),
                array('HtmlTag', array('tag' => 'li', 'class' => ''))
            ),
        ));
      }
    }

    if ($this->_countrySearch != 'no' && !$this->getNearestLocation()) {
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
          'class' => $this->getCountrySearch() == 'hide' ? $hideClass : '',
          'order' => -999991,
          'registerInArrayValidator' => false,
          'required' => false,
          'multiOptions' => $arrayTerr,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
      ));
    }
    if ($this->_stateSearch != 'no' && !$this->getNearestLocation()) {
      $this->addElement('Text', 'state', array(
          'label' => 'State:',
          'class' => $this->getStateSearch() == 'hide' ? $hideClass : '',
          'order' => -999990,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
      ));
    }
    if ($this->_citySearch != 'no' && !$this->getNearestLocation()) {
      $this->addElement('Text', 'city', array(
          'label' => 'City:',
          'class' => $this->getCitySearch() == 'hide' ? $hideClass : '',
          'order' => -999989,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
      ));
    }
    if ($this->_zipSearch != 'no' && !$this->getNearestLocation()) {
      $this->addElement('Text', 'zip', array(
          'label' => 'Zip:',
          'class' => $this->getZipSearch() == 'hide' ? $hideClass : '',
          'order' => -999988,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => 'span')),
              array('HtmlTag', array('tag' => 'li', 'class' => ''))
          ),
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
            'decorators' => array(
                'ViewHelper',
                array('Label', array('tag' => 'span')),
                array('HtmlTag', array('tag' => 'li', 'class' => ''))
            ),
        ));
      }
    }

    //Interests Plugin Intgration
    if (Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesinterest') && $this->getIsInterest() != 'no') {
      $interests = Engine_Api::_()->getDbtable('interests', 'sesinterest')->getResults(array('column_name' => '*', 'approved' => 1));
      if (engine_count($interests)) {
        $interestsArray = array('' => '');
        foreach($interests as $interest) {
            $interestsArray[$interest->interest_id] = $interest->interest_name;
        }
        $this->addElement('Select', 'interest_id', array(
            'label' => 'Interests:',
            'multiOptions' => $interestsArray,
            'class' => $this->getIsInterest() == 'hide' ? $hideClass : '',
            'order' => -999985,
            'decorators' => array(
                'ViewHelper',
                array('Label', array('tag' => 'span')),
                array('HtmlTag', array('tag' => 'li', 'class' => ''))
            ),
        ));
      }
    }

    if ($this->getHasPhoto() != 'no') {
      $this->addElement('Checkbox', 'has_photo', array(
          'label' => 'Only Members With Photos',
          'order' => '9998',
          'class' => $this->getHasPhoto() == 'hide' ? $hideClass : '',
          'decorators' => array(
              'ViewHelper',
              array('Description',array()),
              array('Label', array('placement' => 'APPEND', 'tag' => 'label')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'only_member member_photo'))
          ),
      ));
    }

    if ($this->getIsOnline() != 'no') {
      $this->addElement('Checkbox', 'is_online', array(
          'label' => 'Only Online Members',
          'order' => '9997',
          'class' => $this->getIsOnline() == 'hide' ? $hideClass : '',
          'decorators' => array(
              'ViewHelper',
              array('Description',array()),
              array('Label', array('placement' => 'APPEND', 'tag' => 'label')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'only_member online_member'))
          ),
      ));
    }

    if ($this->getIsVip() != 'no') {
      $this->addElement('Checkbox', 'is_vip', array(
          'label' => 'Only Vip Members',
          'order' => '9996',
          'class' => $this->getIsVip() == 'hide' ? $hideClass : '',
          'decorators' => array(
              'ViewHelper',
              array('Description',array()),
              array('Label', array('placement' => 'APPEND', 'tag' => 'label')),
              array('HtmlTag', array('tag' => 'li', 'class' => 'only_member vip_member'))
          ),
      ));
    }
    parent::init();
    $this->addElement('Button', 'submit', array(
        'label' => 'Search',
        'type' => 'submit',
        'order' => '9999',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => 'span')),
            array('HtmlTag', array('tag' => 'li', 'class' => 'submit_button'))
        ),
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
            array('HtmlTag', array('tag' => 'div', 'class' => 'advanced_search_link'))
        ),
    ));
    $this->addElement('Dummy', 'loadingimgsesmember', array(
        'content' => '<img src="application/modules/Core/externals/images/loading.gif" alt="Loading" />',
        'order' => '10001',
    ));

  }

  public function getMemberTypeElement($identity) {

    $profileTypes = $this->_profileTypes;

    $defaultProfiletypes = $this->_defaultProfiletypes;

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
    foreach ($options as $option) {
      if($profileTypes && engine_in_array($option->option_id, $profileTypes)) {
        continue;
      }
      $multiOptions[$option->option_id] = $option->label;
    }

    $classForHide = $this->getMemberType() == 'hide' ? $hideClass : '';
    $this->addElement('Select', 'profile_type', array(
        'label' => 'Member Type:',
        'order' => -1000001,
        'class' =>
        'field_toggle' . ' ' .
        'parent_' . 0 . ' ' .
        'option_' . 0 . ' ' .
        'field_' . $profileTypeField->field_id . ' ' . $classForHide . ' ',
        'onchange' => 'changeFields(scriptJquery(this));',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => 'span')),
            array('HtmlTag', array('tag' => 'li'))
        ),
        'multiOptions' => $multiOptions,
				'value' => ($this->getMemberType() == 'hide') ? 0 : $defaultProfiletypes,
    ));
    return $this->profile_type;
  }

}
