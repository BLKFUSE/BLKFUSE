<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Search.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sescommunityads_Form_Search extends Engine_Form {
  protected $_browseBy;
  protected $_categoriesSearch;
  protected $_locationSearch;
  protected $_friendsSearch;
  protected $_contentSearch;
  public function setContentSearch($title) {
    $this->_contentSearch = $title;
    return $this;
  }
  public function getContentSearch() {
    return $this->_contentSearch;
  }
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
  public function setLocationSearch($title) {
    $this->_locationSearch = $title;
    return $this;
  }
  public function getLocationSearch() {
    return $this->_locationSearch;
  }
  
  public function init() {
 
    $view = Zend_Registry::isRegistered('Zend_View') ? Zend_Registry::get('Zend_View') : null;
    $this->setAttribs(array('id' => 'filter_form','class' => 'global_form_box'))->setMethod('GET');
    $this->setAction($view->url(array('action' => 'browse'), 'default', true));
    
    $viewer = Engine_Api::_()->user()->getViewer();
    
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
          '1' => 'Everyone\'s Ad',
          '2' => 'Only My Friend\'s Ad',
        ),
      ));
    }
    
    $categories = Engine_Api::_()->getDbtable('categories', 'sescommunityads')->getCategoriesAssoc();
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

    if ($this->_contentSearch == 'yes') {
      $options = array();
      $settings = unserialize(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads_package_settings', 'a:16:{i:0;s:5:"price";i:1;s:12:"payment_type";i:2;s:16:"payment_duration";i:3;s:8:"ad_count";i:4;s:12:"auto_approve";i:5;s:9:"boos_post";i:6;s:12:"promote_page";i:7;s:15:"promote_content";i:8;s:15:"website_visitor";i:9;s:7:"carosel";i:10;s:5:"video";i:11;s:8:"featured";i:12;s:9:"sponsored";i:13;s:10:"targetting";i:14;s:11:"description";i:15;s:9:"advertise";}'));
      $promoteContent = engine_in_array('promote_content',$settings);
      $promotePage = engine_in_array('promote_page',$settings);
      $moreVisitor = engine_in_array('website_visitor',$settings);
      if($promotePage && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sespage')){
        $options['promote_page'] = 'Page Ads';
      }
      if($promoteContent){
        $options['promote_content'] = 'Content Ads';  
      }
      if($moreVisitor){
        $options['promote_website'] = 'Website Ads';  
      }
      $modules = Engine_Api::_()->getDbTable('modules','sescommunityads')->getEnabledModuleNames(array('enabled'=>1));
      if(engine_count($options) && engine_count($modules)){
        $this->addElement('Select', 'content_type', array(
          'label' => 'Content Type',
          'onChange'=>'getModule(this.value)',
          'multiOptions' => array(''=>'')+$options,
        ));
        
        foreach($modules as $module){
         $moduleArray[$module["content_type"]]  = $module["title"];
        }
        $this->addElement('Select', 'content_module', array(
          'label' => 'Module Name',
          'multiOptions' => array(''=>'')+$moduleArray,
        ));
        
      }
    }
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && $this->_locationSearch == 'yes') {
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
      
	    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sescommunityads.search.type',1) == 1)
        $searchType = 'Miles';
      else
        $searchType = 'Kilometer:';
      $this->addElement('Select', 'miles', array(
        'label' => $searchType,
        'allowEmpty' => true,
        'required' => false,
        'multiOptions' => array('0'=>'','1'=>'1','5'=>'5','10'=>'10','20'=>'20','50'=>'50','100'=>'100','200'=>'200','500'=>'500','1000'=>'1000'),
        'value'=>'1000',
      ));
      
    }
    
    $this->addElement('Button', 'submit', array(
      'label' => 'Search',
      'type' => 'submit'
    ));
    $this->addElement('Dummy','loading-img-sescommunityads', array(
        'content' => '<img src="application/modules/Core/externals/images/loading.gif" id="sescommunityads-search-order-img" alt="Loading" />',
   ));
  }
}
