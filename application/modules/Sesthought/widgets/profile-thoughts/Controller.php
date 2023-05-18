<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesthought
 * @package    Sesthought
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Controller.php  2017-12-12 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesthought_Widget_ProfileThoughtsController extends Engine_Content_Widget_Abstract {
  
  protected $_childCount;
  
  public function indexAction() {

    if (isset($_POST['params']))
      $params = json_decode($_POST['params'], true);
  
    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject() ) {
      return $this->setNoRender();
    }
    
    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject('user');
    if( !$subject->authorization()->isAllowed($viewer, 'view') ) {
      return $this->setNoRender();
    }
    if($params) {
      $this->view->allParams = $params;
    } else {
      $this->view->allParams = $this->_getAllParams();
    }
    $this->view->ise_ajax = $is_ajax = $this->_getParam('is_ajax', 0);
    if($is_ajax)
      $this->getElement()->removeDecorator('Title');
    
    $values['owner_id'] = $subject->getIdentity();
    
    $paginator = Engine_Api::_()->getItemTable('sesthought_thought')->getThoughtsPaginator($values);
    $paginator->setItemCountPerPage($this->view->allParams['limit']);

    $this->view->paginator = $paginator->setCurrentPageNumber( $this->_getParam('page', 1) );
    

    // Add count to title if configured
    if($paginator->getTotalItemCount() > 0 ) {
      $this->_childCount = $paginator->getTotalItemCount();
    }
  }

  public function getChildCount()
  {
    return $this->_childCount;
  }
}