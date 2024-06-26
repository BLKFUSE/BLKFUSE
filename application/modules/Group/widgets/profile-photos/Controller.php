<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Controller.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_Widget_ProfilePhotosController extends Engine_Content_Widget_Abstract
{
  protected $_childCount;
  
  public function indexAction()
  {

    // Don't render this if not authorized
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !Engine_Api::_()->core()->hasSubject() ) {
      return $this->setNoRender();
    }

    // Get subject and check auth
    $subject = Engine_Api::_()->core()->getSubject('group');
    if( !$subject->authorization()->isAllowed($viewer, 'view') && !Engine_Api::_()->network()->getViewerNetworkPrivacy($subject, 'user_id')) {
      return $this->setNoRender();
    }

    // Get paginator
    $album = $subject->getSingletonAlbum();
    $this->view->paginator = $paginator = $album->getCollectiblesPaginator();
    $this->view->canUpload = $canUpload = Engine_Api::_()->authorization()->isAllowed('group', $viewer, 'photo');

    // Set item count per page and current page number
    $paginator->setItemCountPerPage($this->_getParam('itemCountPerPage', 9));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

    // Do not render if nothing to show and cannot upload
    if($paginator->getTotalItemCount() <= 0 && !$canUpload ) {
      return $this->setNoRender();
    }

    // Add count to title if configured
    if( $this->_getParam('titleCount', false) && $paginator->getTotalItemCount() > 0 ) {
      $this->_childCount = $paginator->getTotalItemCount();
    }
  }

  public function getChildCount()
  {
    return $this->_childCount;
  }
}
