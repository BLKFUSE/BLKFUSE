<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedcomment
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Controller.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesadvancedcomment_Widget_CommentsController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    // Get subject
    $subject = null;
    $sesadvancedcomment_eplycomment = Zend_Registry::isRegistered('sesadvancedcomment_eplycomment') ? Zend_Registry::get('sesadvancedcomment_eplycomment') : null;
    if (empty($sesadvancedcomment_eplycomment))
      return $this->setNoRender();
    if( Engine_Api::_()->core()->hasSubject() ) {
      $subject = Engine_Api::_()->core()->getSubject();
    } else if( ($subject = $this->_getParam('subject')) ) {
      list($type, $id) = explode('_', $subject);
      $subject = Engine_Api::_()->getItem($type, $id);
    } else if( ($type = $this->_getParam('type')) &&
        ($id = $this->_getParam('id')) ) {
      $subject = Engine_Api::_()->getItem($type, $id);
    }

    if( !($subject instanceof Core_Model_Item_Abstract) ||
        !$subject->getIdentity() ||
        (!method_exists($subject, 'comments') && !method_exists($subject, 'likes')) ) {
      return $this->setNoRender();
    }
    $sesadvancedcomment_loadcommntswidget = Zend_Registry::isRegistered('sesadvancedcomment_loadcommntswidget') ? Zend_Registry::get('sesadvancedcomment_loadcommntswidget') : null;
    if (empty($sesadvancedcomment_loadcommntswidget))
      return $this->setNoRender();
    // Perms
    $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->canComment = $canComment = $subject->authorization()->isAllowed($viewer, 'comment');
    $this->view->canDelete = $canDelete = $subject->authorization()->isAllowed($viewer, 'edit');
    
      
    // Hide if can't post and no comments
    if( !$canComment && !$canDelete ) {
      //$this->setNoRender();
    }
    $this->view->subject = $subject;
  }
}