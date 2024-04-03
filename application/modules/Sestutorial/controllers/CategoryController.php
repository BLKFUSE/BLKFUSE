<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: CategoryController.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_CategoryController extends Core_Controller_Action_Standard {

	public function browseAction() {
	
    if (!$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'view')->isValid())
      return;
      
    $this->_helper->content->setEnabled();
	}

	public function indexAction() {
	
    if (!$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'view')->isValid())
      return;
      
    $category_id = $this->_getParam('category_id', 0);
    if($category_id) {
      $category_id = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getCategoryId($category_id); 
    } else {
      return;
    }

    $category = Engine_Api::_()->getItem('sestutorial_category', $category_id);
    if($category) {
      Engine_Api::_()->core()->setSubject($category);
    } else
      $this->_forward('requireauth', 'error', 'core');
      
    // Render
    $this->_helper->content->setEnabled();		
	}
}