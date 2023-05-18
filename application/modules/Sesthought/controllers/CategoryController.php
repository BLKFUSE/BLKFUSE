<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: CategoryController.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesthought_CategoryController extends Core_Controller_Action_Standard {

  public function browseAction() {
    $sesthought_qcategories = Zend_Registry::isRegistered('sesthought_qcategories') ? Zend_Registry::get('sesthought_qcategories') : null;
    if(!empty($sesthought_qcategories)) {
      $this->_helper->content->setEnabled();
    }
  }
  
  public function subcategoryAction() {
  
    $category_id = $this->_getParam('category_id', null);
    if ($category_id) {
			$subcategory = Engine_Api::_()->getDbtable('categories', 'sesthought')->getModuleSubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;
    die;
  }
	// get thought subsubcategory 
  public function subsubcategoryAction() {
    $category_id = $this->_getParam('subcategory_id', null);
    if ($category_id) {
      $subcategory = Engine_Api::_()->getDbtable('categories', 'sesthought')->getModuleSubsubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '">' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;die;
  }
}
