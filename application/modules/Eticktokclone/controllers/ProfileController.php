<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: ProfileController.php 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

class Eticktokclone_ProfileController extends Core_Controller_Action_Standard
{
    public function init()
    {
      // @todo this may not work with some of the content stuff in here, double-check
      $subject = null;
      if( !Engine_Api::_()->core()->hasSubject() )
      {
        $id = $this->_getParam('id');
  
        // use viewer ID if not specified
        //if( is_null($id) )
        //  $id = Engine_Api::_()->user()->getViewer()->getIdentity();
  
        if( null !== $id )
        {
          $subject = Engine_Api::_()->user()->getUser($id);
          if( $subject->getIdentity() )
          {
            Engine_Api::_()->core()->setSubject(Engine_Api::_()->getItem("user",$subject->getIdentity()));
          }
        }
      }
  
      $this->_helper->requireSubject('user');
     
    }
    
    public function indexAction()
    {
      $subject = Engine_Api::_()->core()->getSubject();
      $viewer = Engine_Api::_()->user()->getViewer();
      if($subject->user_id != $viewer->getIdentity()){
       // check profile block
       $isBlocked = Engine_Api::_()->getDbTable("blocks",'eticktokclone')->anyOneBlocked(array("user_id"=>$subject->getIdentity()));
        
       if($isBlocked){
         if($viewer->getIdentity() != $isBlocked->user_id){
           return $this->_forward('notfound', 'error', 'core');
         }
       }
      }
      // Render
      $this->_helper->content
          ->setNoRender()
          ->setEnabled()
          ;
    }
    
}
