<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egames
 * @copyright  Copyright 2014-2021 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Filter.php 2021-08-19 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Egames_Form_Admin_Manage_Filter extends Engine_Form
{
	
	
  public function init()
  {
		parent::init();
    $this
      ->clearDecorators()
      ->addDecorator('FormElements')
      ->addDecorator('Form')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'search'))
      ->addDecorator('HtmlTag2', array('tag' => 'div', 'class' => 'clear'))
      ;

    $this
      ->setAttribs(array(
        'id' => 'filter_form',
        'class' => 'global_form_box',
      ))
      ->setMethod('GET');

    $titlename = new Zend_Form_Element_Text('title');
    $titlename
      ->setLabel('Title')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));

		$owner_name = new Zend_Form_Element_Text('owner_name');
    $owner_name
      ->setLabel('Owner Name')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
   
		$date = new Zend_Form_Element_Text('creation_date');
    $date
      ->setLabel('Creation Date: ex (2000-12-01)')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));		
	
	
		 // prepare categories
    $categories = Engine_Api::_()->egames()->getCategories();
    
    if (engine_count($categories)!=0){
      $categories_prepared['']= "";
      foreach ($categories as $category){
        $categories_prepared[$category->category_id]= $category->category_name;
      }
		
	 // category field
		$category = new Zend_Form_Element_Select('category_id',array('onchange' => 'showSubCategory(this.value)'));
    $category
      ->setLabel('Category')
      ->clearDecorators()
			->setMultiOptions($categories_prepared)
       ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
     
     //Add Element: Sub Category
		 
		 $subCategory = new Zend_Form_Element_Select('subcat_id',array('onchange' => 'showSubSubCategory(this.value)'));
		  $subCategory
      ->setLabel('2nd-level Category')
      ->clearDecorators()
			->setMultiOptions(array('0'=>''))
       ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
      //Add Element: Sub Sub Category
			$subsubCategory = new Zend_Form_Element_Select('subsubcat_id');
		  $subsubCategory
      ->setLabel('3rd-level Category')
      ->clearDecorators()
			->setMultiOptions(array('0'=>''))
      ->addDecorator('ViewHelper')
      ->addDecorator('Label', array('tag' => null, 'placement' => 'PREPEND'))
      ->addDecorator('HtmlTag', array('tag' => 'div'));
		
	}
    $submit = new Zend_Form_Element_Button('search', array('type' => 'submit'));
    $submit
      ->setLabel('Search')
      ->clearDecorators()
      ->addDecorator('ViewHelper')
      ->addDecorator('HtmlTag', array('tag' => 'div', 'class' => 'buttons'))
      ->addDecorator('HtmlTag2', array('tag' => 'div'));
		
		$arrayItem = array();
		$arrayItem = !empty($titlename)?	array_merge($arrayItem,array($titlename)) : '';
		$arrayItem = !empty($owner_name) ?	array_merge($arrayItem,array($owner_name)) : $arrayItem;
		
		$arrayItem = !empty($date)?	array_merge($arrayItem,array($date)) : $arrayItem;
	
		$arrayItem = !empty($category)?	array_merge($arrayItem,array($category)) : $arrayItem;
		$arrayItem = !empty($subCategory)?	array_merge($arrayItem,array($subCategory)) : $arrayItem;
		$arrayItem = !empty($subsubCategory)?	array_merge($arrayItem,array($subsubCategory)) : $arrayItem;
		$arrayItem = !empty($submit)?	array_merge($arrayItem,array($submit)) : '';
    $this->addElements($arrayItem);
  }
}
