<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sescommunityads
 * @package    Sescommunityads
 * @copyright  Copyright 2018-2019 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Filter.php  2018-10-09 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sescommunityads_Form_Admin_Filter extends Engine_Form {

  public function init() {

    $this->clearDecorators()
            ->addDecorator('FormElements')
            ->addDecorator('Form')
            ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
            ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'));
    $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET');

    $this->addElement('Text', 'name', array(
        'label' => 'Advertisement Title',
        'placeholder' => 'Enter Advertisement Title',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    $this->addElement('Text', 'campaign', array(
        'label' => 'Campaign Title',
        'placeholder' => 'Enter Campaign Title',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    $this->addElement('Text', 'owner_name', array(
        'label' => 'Owner Name',
        'placeholder' => 'Enter Owner Name',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    
    $this->addElement('Text', 'creation_date', array(
        'label' => 'Creation Date Ex(yyyy-mm-dd)',
        'placeholder' => 'Enter Creation Date',
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    $typeArray[""] = "";
    $typeArray['promote_website_cnt'] = 'Promote Website';
      if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedactivity')) {
          $typeArray['boost_post_cnt'] = 'Boost Post';
      }
    $typeArray['promote_content_cnt'] = 'Promote Content';
    $typeArray['promote_page'] = 'Promote Page';
    
    $this->addElement('Select', 'type', array(
        'label' => "Type",
        'required' => true,
        'multiOptions' => $typeArray,
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
   
    
    $categories = Engine_Api::_()->getDbTable('categories', 'sescommunityads')->getCategory(array('column_name' => '*'));
    $data[''] = 'Choose a Category';
    foreach ($categories as $category) {
      $data[$category['category_id']] = $category['category_name'];
      $categoryId = $category['category_id'];
    }
    if (engine_count($categories)) {
      $this->addElement('Select', 'category_id', array(
          'label' => "Category",
          'required' => true,
          'multiOptions' => $data,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => null, 'placement' => 'PREPEND')),
              array('HtmlTag', array('tag' => 'div'))
          ),
          'onchange' => "showSubCategory(this.value)",
      ));
      //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "Sub Category",
          'onchange' => "showSubSubCategory(this.value)",
      ));
      //Add Element: Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "Sub Sub Category",
      ));
    }
    $this->addElement('Select', 'featured', array(
        'label' => "Featured",
        'required' => true,
        'multiOptions' => array("" => 'Select', "1" => "Yes", "0" => "No"),
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    $this->addElement('Select', 'sponsored', array(
        'label' => "Sponsored",
        'required' => true,
        'multiOptions' => array("" => 'Select', "1" => "Yes", "0" => "No"),
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    
    $this->addElement('Select', 'is_approved', array(
        'label' => "Approved",
        'required' => true,
        'multiOptions' => array("" => 'Select', "1" => "Yes", "0" => "No"),
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));
    
    $packages = Engine_Api::_()->getDbTable('packages', 'sescommunityads')->getPackage(array('default' => true));
    $packagesArray = array('' => 'Select');
    foreach ($packages as $package) {
      $packagesArray[$package['package_id']] = $package['title'];
    }
    if (engine_count($packagesArray) > 1) {
      $this->addElement('Select', 'package_id', array(
          'label' => "Packages",
          'required' => true,
          'multiOptions' => $packagesArray,
          'decorators' => array(
              'ViewHelper',
              array('Label', array('tag' => null, 'placement' => 'PREPEND')),
              array('HtmlTag', array('tag' => 'div'))
          ),
      ));
    }
    
     $filterArray[0] = "";
    $filterArray[2] = "Running";
    $filterArray[3] = "Paused";
    $filterArray[4] = "Completed";
    $filterArray[5] = "Deleted";
    $filterArray[6] = "Declined";
    
    $this->addElement('Select', 'filter', array(
        'label' => "Status Filter",
        'required' => true,
        'multiOptions' => $filterArray,
        'decorators' => array(
            'ViewHelper',
            array('Label', array('tag' => null, 'placement' => 'PREPEND')),
            array('HtmlTag', array('tag' => 'div'))
        ),
    ));  
    
    $this->addElement('Button', 'search', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true,
    ));
    $this->addElement('Hidden', 'order', array(
        'order' => 10004,
    ));
    $this->addElement('Hidden', 'order_direction', array(
        'order' => 10002,
    ));

    
    //Set default action without URL-specified params
    $params = array();
    foreach (array_keys($this->getValues()) as $key) {
      $params[$key] = null;
    }
    $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble($params));
  }

}
