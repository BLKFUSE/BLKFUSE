<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class User_Widget_HomePhotoController extends Engine_Content_Widget_Abstract {

  public function indexAction() {
  
    // Don't render this if not logged in
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if( !$viewer->getIdentity() ) {
      return $this->setNoRender();
    }
    
    $this->view->photo = '';
    if (isset($viewer->coverphoto)) {
      $this->view->photo = $photo = Engine_Api::_()->getItem('storage_file', $viewer->coverphoto);
    }
    
    // Multiple friend mode
    $select = $viewer->membership()->getMembersOfSelect();
    $this->view->friends = $friends = $paginator = Zend_Paginator::factory($select);  

    // Set item count per page and current page number
    $paginator->setItemCountPerPage(5);
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    
    // Get stuff
    $ids = array();
    foreach( $friends as $friend ) {
      $ids[] = $friend->resource_id;
    }
    $this->view->friendIds = $ids;

    // Get the items
    $friendUsers = array();
    foreach( Engine_Api::_()->getItemTable('user')->find($ids) as $friendUser ) {
      $friendUsers[$friendUser->getIdentity()] = $friendUser;
    }
    $this->view->friendUsers = $friendUsers;
  }
}
