<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Category.php 9747 2012-07-26 02:08:08Z john $
 * @author     Jung
 */

/**
 * @category   Application_Extensions
 * @package    Core
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */

class Core_Form_Admin_Category_Edit extends Engine_Form
{
  protected $_field;

  public function init()
  {
    $category_id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
    $category = Engine_Api::_()->getItem('core_category', $category_id);
    $type = Zend_Controller_Front::getInstance()->getRequest()->getParam('type', null);
    
    $this->setTitle("Edit Category")
        ->setDescription('Here, you can edit the category title. If you do not want to change the parent category then do not choose any category from the parent category dropdown.')
        ->setMethod('post')
        ->setAttrib('class', 'global_form_box');

    $categoryTable = Engine_Api::_()->getDbTable('categories', 'core');
    $ticketsTable = Engine_Api::_()->getDbTable('tickets', 'core');
    
    $subcategory = $categoryTable->getSubcategory(array('category_id' => $category_id, 'type' => $type));
    $subsubcategory = $categoryTable->getSubsubcategory(array('category_id' => $category_id, 'type' => $type));
    
    $categoryTicket = $ticketsTable->isTicketExists($category_id, 'category_id');
    $subcatTicket = $ticketsTable->isTicketExists($category_id, 'subcat_id');
    $subsubcatTicket = $ticketsTable->isTicketExists($category_id, 'subsubcat_id');
    
    if(engine_count($subcategory) == 0 && engine_count($subsubcategory) == 0 && empty($category->subsubcat_id) && empty($categoryTicket) && empty($subcatTicket) && empty($subsubcatTicket)) {
      
      $categories = $categoryTable->getEditCategories(array('category_id' => $category_id, 'type' => $type));
      if(engine_count($categories) > 0) {
        if($category && $category->subsubcat_id == 0) {
          $value = $category->subcat_id ? $category->subcat_id : '';
        }
        $this->addElement('Select', 'parentcategory_id', array(
          'label' => 'Parent Category',
          'multiOptions' => $categories,
          'value' => $value,
        ));
      }
    }

    $label = new Zend_Form_Element_Text('label');
    $label->setLabel('Category Name')
      ->addValidator('NotEmpty')
      ->setRequired(true)
      ->setAttrib('class', 'text');


    $id = new Zend_Form_Element_Hidden('id');


    $this->addElements(array(
      //$type,
      $label,
      $id
    ));
    
    // Buttons
    $this->addElement('Button', 'submit', array(
      'label' => 'Add Category',
      'type' => 'submit',
      'ignore' => true,
      'decorators' => array('ViewHelper')
    ));

    $this->addElement('Cancel', 'cancel', array(
      'label' => 'cancel',
      'link' => true,
      'prependText' => ' or ',
      'href' => '',
      'onClick'=> 'javascript:parent.Smoothbox.close();',
      'decorators' => array(
        'ViewHelper'
      )
    ));
    $this->addDisplayGroup(array('submit', 'cancel'), 'buttons');
    $button_group = $this->getDisplayGroup('buttons');


  }

  public function setField($category)
  {
    $this->_field = $category;

    // Set up elements
    //$this->removeElement('type');
    $this->label->setValue($category->category_name);
    $this->id->setValue($category->category_id);
    $this->submit->setLabel('Edit Category');

    // @todo add the rest of the parameters
  }
}
