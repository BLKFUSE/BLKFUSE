<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: MemberLocation.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_Controller_Action_Helper_MemberLocation extends Zend_Controller_Action_Helper_Abstract {

  public function preDispatch() {
  
    $front = Zend_Controller_Front::getInstance();
    $module = $front->getRequest()->getModuleName();
    $action = $front->getRequest()->getActionName();
    $controller = $front->getRequest()->getControllerName();
    if ($module == 'user' && $controller == 'profile' && $action == 'index') {
      $isLikeBased = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.approve.criteria', 1);
      if (empty($isLikeBased)) {
        $id = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        $subject = Engine_Api::_()->user()->getUser($id);
        $viewCountForApproved = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.view.count', 10);
        $userViewCount = $subject->view_count;
        $view_count = $userViewCount + 1;
        if ($view_count >= $viewCountForApproved) {
            $getUserInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($subject->user_id);
            $getUserInfoItem->user_verified = 1;
            $getUserInfoItem->save();
        }
      }
    }
  }

  public function postDispatch() {
  
    $front = Zend_Controller_Front::getInstance();
    $module = $front->getRequest()->getModuleName();
    $action = $front->getRequest()->getActionName();
    $controller = $front->getRequest()->getControllerName();
    
    //For Signup
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)) {
      if (isset($_POST['ses_location'])) {
        $_SESSION['ses_location'] = $_POST['ses_location'];
        $_SESSION['ses_lat'] = $_POST['ses_lat'];
        $_SESSION['ses_lng'] = $_POST['ses_lng'];
        $_SESSION['ses_zip'] = $_POST['ses_zip'];
        $_SESSION['ses_city'] = $_POST['ses_city'];
        $_SESSION['ses_state'] = $_POST['ses_state'];
        $_SESSION['ses_country'] = $_POST['ses_country'];
      }
    }
    
    if ($module == 'user' && $controller == 'admin-fields' && ($action == 'field-create' || $action == 'field-edit' || $action == 'heading-edit' || $action == 'heading-create')) {
      $form = $this->getActionController()->view->form;
      if (!$this->getRequest()->isPost()) {
        $form->addElement('Select', 'ses_field', array(
            'label' => 'Show on SNS - Ultimate Members Plugin Widgets?',
            'multiOptions' => array(
                0 => 'Hide on SNS - Ultimate Members Plugin Widgets',
                1 => 'Show on SNS - Ultimate Members Plugin Widgets',
            ),
        ));
        $form->buttons->setOrder(500);
        $fieldId = $front->getRequest()->getParam('field_id', 0);
        if ($fieldId) {
          $form->ses_field->setValue(Engine_Api::_()->fields()->getField($fieldId, 'user')->ses_field);
        }
      }
    }
  }
}
