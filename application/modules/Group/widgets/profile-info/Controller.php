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
class Group_Widget_ProfileInfoController extends Engine_Content_Widget_Abstract
{
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

    // Get staff
    $ids = array();
    $ids[] = $subject->getOwner()->getIdentity();
    $list = $subject->getOfficerList();
    foreach( $list->getAll() as $listiteminfo )
    {
      $ids[] = $listiteminfo->child_id;
    }

    $staff = array();
    foreach( $ids as $id )
    {
      $user = Engine_Api::_()->getItem('user', $id);
      $staff[] = array(
        'membership' => $subject->membership()->getMemberInfo($user),
        'user' => $user,
      );
    }
    $this->view->viewer_id = $viewer->getIdentity();
    $this->view->rating_count = Engine_Api::_()->getDbTable('ratings', 'group')->ratingCount($subject->getIdentity());
    $this->view->rated = Engine_Api::_()->getDbTable('ratings', 'group')->checkRated($subject->getIdentity(), $viewer->getIdentity());
    $this->view->group = $subject;
    $this->view->staff = $staff;
  }
}
