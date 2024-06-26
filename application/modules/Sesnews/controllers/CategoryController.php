<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: CategoryController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_CategoryController extends Core_Controller_Action_Standard {
	
	public function init() {
		if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid() ) return;
	} 
	
  public function browseAction() {
    $this->_helper->content->setEnabled();
  }

  public function indexAction() {
    $category_id = $this->_getParam('category_id', false);
    if ($category_id) {
      $category_id = Engine_Api::_()->getDbtable('categories', 'sesnews')->getCategoryId($category_id);
    } else {
      return;
    }
    $category = Engine_Api::_()->getItem('sesnews_category', $category_id);
    if ($category) {
      Engine_Api::_()->core()->setSubject($category);
    }
    if (!$this->_helper->requireSubject()->isValid())
      return;
    $sesnews_category = Zend_Registry::isRegistered('sesnews_category') ? Zend_Registry::get('sesnews_category') : null;
    if (empty($sesnews_category))
      return $this->_forward('notfound', 'error', 'core');
    // video is a type of object chanel
    // if this is sending a message id, the user is being directed from a coversation
    // check if member is part of the conversation
    $message_id = $this->getRequest()->getParam('message');
    $message_view = false;
    if ($message_id) {
      $conversation = Engine_Api::_()->getItem('messages_conversation', $message_id);
      if ($conversation->hasRecipient(Engine_Api::_()->user()->getViewer())) {
        $message_view = true;
      }
    }

    $this->view->message_view = $message_view;
    // Render
    $this->_helper->content->setEnabled();
  }

}
