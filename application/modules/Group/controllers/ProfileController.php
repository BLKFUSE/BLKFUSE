<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: ProfileController.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_ProfileController extends Core_Controller_Action_Standard
{
    public function init()
    {
        // @todo this may not work with some of the content stuff in here, double-check
        $subject = null;
        if( !Engine_Api::_()->core()->hasSubject() )
        {
            $id = $this->_getParam('id');
            if( null !== $id )
            {
                $subject = Engine_Api::_()->getItem('group', $id);
                if( $subject && $subject->getIdentity() )
                {
                    Engine_Api::_()->core()->setSubject($subject);
                }
            }
        }

        $viewer = Engine_Api::_()->user()->getViewer();
        $this->_helper->requireSubject('group');
//         $this->_helper->requireAuth()
// //      ->setNoForward()                          // for showing image and title irrespective of privacy
//             ->setAuthParams($subject, $viewer, 'view')
//             ->isValid();
    }

    public function indexAction()
    {
        $subject = Engine_Api::_()->core()->getSubject();
        $viewer = Engine_Api::_()->user()->getViewer();

        // Increment view count
        if( !$subject->getOwner()->isSelf($viewer) )
        {
            $subject->view_count++;
            $subject->save();
        }

        $viewPermission = $subject->authorization()->isAllowed($viewer, 'view');
        if(empty($viewPermission))
            return $this->_forward('requireauth', 'error', 'core');
            
        if( !$subject || !$subject->getIdentity() || ((!$subject->approved) && !$subject->isOwner($viewer)) ) {
					if(!empty($viewer->getIdentity()) && $viewer->isAdmin()) {
					} else
            return $this->_forward('requireauth', 'error', 'core');
        }
        
        // Network check
        $networkPrivacy = Engine_Api::_()->network()->getViewerNetworkPrivacy($subject, 'user_id');
        if(empty($networkPrivacy))
            return $this->_forward('requireauth', 'error', 'core');

        // Get styles
        $table = Engine_Api::_()->getDbtable('styles', 'core');
        $select = $table->select()
            ->where('type = ?', $subject->getType())
            ->where('id = ?', $subject->getIdentity())
            ->limit();

        $row = $table->fetchRow($select);

        if( null !== $row && !empty($row->style) ) {
            $this->view->headStyle()->appendStyle($row->style);
        }

        // Render
        $this->_helper->content
            ->setNoRender()
            ->setEnabled()
        ;
    }
}
