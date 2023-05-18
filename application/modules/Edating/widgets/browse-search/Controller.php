<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Edating
 * @copyright  Copyright 2014-2022 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2022-06-09 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Edating_Widget_BrowseSearchController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
    $this->view->allParams = $this->_getAllParams();
    // Prepare form
    $this->view->form = $form = new User_Form_Search(array(
        'type' => 'user'
    ));

    $p = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    $form->populate($p);
    $this->view->topLevelId = $form->getTopLevelId();
    $this->view->topLevelValue = $form->getTopLevelValue();
  }
}
