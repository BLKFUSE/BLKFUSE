<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Search.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sestutorial_Form_Search extends Engine_Form {

  public function init() {
  
    $request = Zend_Controller_Front::getInstance()->getRequest();
    $moduleName = $request->getModuleName();
    $controllerName = $request->getControllerName();
    $actionName = $request->getActionName();

    $this->setAttribs(array('id' => 'filter_form', 'class' => 'global_form_box'))->setMethod('GET');
    
    if ($moduleName == 'sestutorial' && $controllerName == 'index' && $actionName == 'browse') {
      $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array()));
    } else {
      $this->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('action' => 'browse'), 'sestutorial_general', true));
    }

    parent::init();

    $this->addElement('Text', 'title_name', array(
      'label' => 'Tutorial Title',
    ));

    //Category Work
    $categories = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getCategory(array('column_name' => '*'));
    $data[] = 'Select Category';
    foreach ($categories as $category) {
      $data[$category['category_id']] = $category['category_name'];
    }
    
    if (engine_count($data) > 1) {

      //Add Element: Category
      $this->addElement('Select', 'category_id', array(
          'label' => 'Category',
          'multiOptions' => $data,
          'onchange' => "ses_subcategory(this.value)",
      ));

      //Add Element: Sub Category
      $this->addElement('Select', 'subcat_id', array(
          'label' => "2nd-level Category",
          'allowEmpty' => true,
          'required' => false,
          'registerInArrayValidator' => false,
          'onchange' => "sessubsubcat_category(this.value)"
      ));

      //Add Element: Sub Sub Category
      $this->addElement('Select', 'subsubcat_id', array(
          'label' => "3rd-level Category",
          'allowEmpty' => true,
          'registerInArrayValidator' => false,
          'required' => false,
      ));
    }

    $this->addElement('Hidden', 'search_params', array(
        'order' => 200
    ));

    $this->addElement('Select', 'popularity', array(
      'label' => 'Browse By',
      'multiOptions' => array(
        '' => 'Select Popularity',
        'creation_date' => 'Most Recent',
        'comment_count' => 'Most Commented',
        'like_count' => 'Most Liked',
        'view_count' => 'Most Viewed',
        'rating' => 'Most Rated',
        'helpful_count' => 'Most Helpful',
      ),
    ));

    $this->addElement('Hidden', 'user');

    //Element: execute
    $this->addElement('Button', 'execute', array(
        'label' => 'Search',
        'type' => 'submit',
        'ignore' => true,
    ));
  }

}
