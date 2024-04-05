<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: SearchRss.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Form_SearchRss extends Engine_Form {
  protected $_searchTitle;
  protected $_searchFor;
  protected $_browseBy;
  protected $_categoriesSearch;

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

  public function init() {

    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $this->setAttribs(array('id' => 'filter_form','class' => 'global_form_box'))->setMethod('GET');
    $this->setAction($view->url(array('module' => 'sesnews', 'controller' => 'rss', 'action' => 'browse'), 'default', true));

    if ($this->_searchTitle == 'yes') {
      $this->addElement('Text', 'search', array(
            'label' => 'Search Rss'
      ));
    }

    if ($this->_browseBy == 'yes') {
      $this->addElement('Select', 'sort', array(
        'label' => 'Browse By',
        'multiOptions' => array(),
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

    $this->addElement('Button', 'submit', array(
      'label' => 'Search',
      'type' => 'submit'
    ));
  }
}
