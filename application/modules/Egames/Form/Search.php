<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Search.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Egames_Form_Search extends Engine_Form {
	protected $_browseBy;
	protected $_categoriesSearch;
	protected $_friendsSearch;
	public function setFriendsSearch($title) {
    $this->_friendsSearch = $title;
    return $this;
  }
  public function getFriendsSearch() {
    return $this->_friendsSearch;
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
    parent::init();
		
			$searchFor = Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module'=>'egames','controller'=>'index','action'=>'browse'));
    $this
            ->setAttribs(array(
                'id' => 'filter_form',
                'class' => 'global_form_box',
            ))
            ->setAction($searchFor);

      $this->addElement('Text', 'search', array(
          'label' => 'Search Games:'
      ));
    
    if ($this->_browseBy == 'yes') {
    $this->addElement('Select', 'sort', array(
        'label' => 'Browse By:',
        'multiOptions' => array(''),
    ));
		}
		if ($this->_friendsSearch == 'yes' && Engine_Api::_()->user()->getViewer()->getIdentity() != 0) {
      $this->addElement('Select', 'show', array(
          'label' => 'View:',
          'multiOptions' => array(
              '1' => 'Everyone\'s Game',
              '2' => 'Only My Friend\'s Game',
          ),
      ));
    }
		 if ($this->_categoriesSearch == 'yes') {
    // prepare categories
    $categories = Engine_Api::_()->getDbtable('categories', 'egames')->getCategoriesAssoc();
    if (engine_count($categories) > 0) {
			$categories = array('0'=>'')+$categories;
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category:',
          'multiOptions' => $categories,
					'onchange' => "showSubCategory(this.value);",
      ));
			//Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category:",
          'allowEmpty' => true,
          'required' => false,
					'multiOptions' => array('0'=>''),
          'registerInArrayValidator' => false,
          'onchange' => "showSubSubCategory(this.value);"
      ));			
      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category:",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
					'multiOptions' => array('0'=>''),
      ));
    }
		 }
    
    $this->addElement('Button', 'submit', array(
        'label' => 'Search',
        'type' => 'submit'
    ));
		$this->addElement('Dummy','loading-img-egames', array(
        'content' => '<img src="application/modules/Core/externals/images/loading.gif" id="egames-category-widget-img" alt="Loading" />',
   ));
  }

}
