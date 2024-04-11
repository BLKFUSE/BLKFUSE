<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Search.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_Search extends Engine_Form {
  protected $_searchTitle;
  protected $_searchFor;
  protected $_browseBy;
  protected $_categoriesSearch;
  protected $_locationSearch;
  protected $_kilometerMiles;
  protected $_friendsSearch;
  protected $_hasPhoto;
  public function setFriendsSearch($title) {
    $this->_friendsSearch = $title;
    return $this;
  }
  public function getFriendsSearch() {
    return $this->_friendsSearch;
  }
  public function setSearchTitle($title) {
    $this->_searchTitle = $title;
    return $this;
  }
  public function getSearchTitle() {
    return $this->_searchTitle;
  }
  public function setSearchFor($title) {
    $this->_searchFor = $title;
    return $this;
  }
  public function getSearchFor() {
    return $this->_searchFor;
  }
  public function setBrowseBy($title) {
    $this->_browseBy = $title;
    return $this;
  }
  public function getBrowseBy() {
    return $this->_browseBy;
  }
  public function setCategoriesSearch($title) {
    $this->_categoriesSearch = $title;
    return $this;
  }
  public function getCategoriesSearch() {
    return $this->_categoriesSearch;
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
  public function setHasPhoto($title) {
    $this->_hasPhoto = $title;
    return $this;
  }

  public function getHasPhoto() {
    return $this->_hasPhoto;
  }

  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $this->setAttribs(array('id' => 'filter_form','class' => 'global_form_box'))->setMethod('GET');
    $this->setAction($view->url(array('module' => 'sesnews', 'controller' => 'index', 'action' => 'browse'), 'default', true));

    $viewer = Engine_Api::_()->user()->getViewer();

    if ($this->_searchTitle == 'yes') {
      $this->addElement('Text', 'search', array(
	'label' => 'Search News'
      ));
    }

    if ($this->_browseBy == 'yes') {
      $this->addElement('Select', 'sort', array(
	'label' => 'Browse By',
	'multiOptions' => array(),
      ));
    }

    if ($this->_friendsSearch == 'yes' && $viewer->getIdentity() != 0) {
      $this->addElement('Select', 'show', array(
	'label' => 'Show',
	'multiOptions' => array(
	  '1' => 'Everyone\'s '.ucwords($this->getSearchFor()),
	  '2' => 'Only My Friend\'s '.ucwords($this->getSearchFor()),
        ),
      ));
    }

    $categories = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoriesAssoc();
    if (engine_count($categories) > 0 && $this->_categoriesSearch == 'yes') {
      $categories = array('0'=>'')+$categories;
      $this->addElement('Select', 'category_id', array(
	'label' => 'Category',
	'multiOptions' => $categories,
	'onchange' => "showSubCategory(this.value);",
      ));
			  //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
	'label' => "2nd-level Category",
	'allowEmpty' => true,
	'required' => false,
	'multiOptions' => array('0'=>''),
	'registerInArrayValidator' => false,
	'onchange' => "showSubSubCategory(this.value);"
      ));
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
	'label' => "3rd-level Category",
	'allowEmpty' => true,
	'registerInArrayValidator' => false,
	'required' => false,
	'multiOptions' => array('0'=>''),
      ));
    }

    $this->addElement('Hidden', 'page', array(
      'order' => 100
    ));

    $this->addElement('Hidden', 'tag', array(
      'order' => 101
    ));

    $this->addElement('Hidden', 'start_date', array(
      'order' => 102
    ));

    $this->addElement('Hidden', 'end_date', array(
      'order' => 103
    ));

    if ($this->_locationSearch == 'yes' && $this->getSearchFor() == 'news') {
      /*Location Elements*/
      $this->addElement('Text', 'location', array(
	'label' => 'Location',
	'id' =>'locationSesList',
	'filters' => array(
	  new Engine_Filter_Censor(),
	  new Engine_Filter_HtmlSpecialChars(),
	),
      ));
      $this->addElement('Text', 'lat', array(
	'label' => 'Lat',
	'id' =>'latSesList',
	'filters' => array(
	  new Engine_Filter_Censor(),
	  new Engine_Filter_HtmlSpecialChars(),
	),
      ));
      $this->addElement('Text', 'lng', array(
	'label' => 'Lng',
	'id' =>'lngSesList',
	'filters' => array(
	  new Engine_Filter_Censor(),
	  new Engine_Filter_HtmlSpecialChars(),
	),
      ));
      
    if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) {
    
      $optionsenableglotion = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('optionsenableglotion',''));
      if (engine_in_array('country', $optionsenableglotion)) {
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
            'registerInArrayValidator' => false,
            'required' => false,
            'multiOptions' => $arrayTerr,
        ));
      }
      if (engine_in_array('state', $optionsenableglotion)) {
        $this->addElement('Text', 'state', array(
            'label' => 'State:',
        ));
      }
      if (engine_in_array('city', $optionsenableglotion)) {
        $this->addElement('Text', 'city', array(
            'label' => 'City:',
        ));
      }
      if (engine_in_array('zip', $optionsenableglotion)) {
        $this->addElement('Text', 'zip', array(
            'label' => 'Zip:',
        ));
      }
    }
    
      if (Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && $this->_kilometerMiles == 'yes') {
	if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.search.type',1) == 1)
	$searchType = 'Miles';
	else
	$searchType = 'Kilometer:';
	$this->addElement('Select', 'miles', array(
	'label' => $searchType,
	'allowEmpty' => true,
	'required' => false,
	'multiOptions' => array('0'=>'','1'=>'1','5'=>'5','10'=>'10','20'=>'20','50'=>'50','100'=>'100','200'=>'200','500'=>'500','1000'=>'1000'),
	'value'=>'1000',
	'registerInArrayValidator' => false,
	));
      }
    }

    if ($this->getHasPhoto() != 'no') {
      $this->addElement('Checkbox', 'has_photo', array(
          'label' => 'Only News With Photos',
         // 'class' => $this->getHasPhoto() == 'hide' ? $hideClass : '',
      ));
    }

    $this->addElement('Button', 'submit', array(
      'label' => 'Search',
      'type' => 'submit'
    ));
  }
}
