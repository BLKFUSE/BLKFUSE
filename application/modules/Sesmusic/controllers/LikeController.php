<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: LikeController.php 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
class Sesmusic_LikeController extends Core_Controller_Action_Standard {


  public function likeAction() {
  
    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }

    if ($this->_getParam('type') == 'sesmusic_album') {
      $type = 'sesmusic_album';
      $dbTable = 'albums';
      $resorces_id = 'album_id';
      $notificationType = 'liked';
    } else if ($this->_getParam('type') == 'sesmusic_albumsong') {
      $type = 'sesmusic_albumsong';
      $dbTable = 'albumsongs';
      $resorces_id = 'albumsong_id';
      $notificationType = 'liked';
    }
    
    $type = $this->_getParam('type');
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    
    $viewer = Engine_Api::_()->user()->getViewer();
    
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    
    $itemTable = Engine_Api::_()->getDbtable($dbTable, 'sesmusic');
    
    $select = $tableLike->select()->from($tableMainLike)->where('resource_type =?', $type)->where('poster_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('poster_type =?', 'user')->where('resource_id =?', $item_id);
    $Like = $tableLike->fetchRow($select);
    if (!empty($Like)) {
      //delete		
      $db = $Like->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $Like->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count - 1')), array($resorces_id . ' = ?' => $item_id));
      
      $item = Engine_Api::_()->getItem($type, $item_id);
//       Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
//       Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
//       Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($item);
      
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $item->like_count));
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
      $db->beginTransaction();
      try {
        $like = $tableLike->createRow();
        $like->poster_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $like->resource_type = $type;
        $like->resource_id = $item_id;
        $like->poster_type = 'user';
        $like->save();
        $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array($resorces_id . '= ?' => $item_id));
        
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      //send notification and activity feed work.
      $item = Engine_Api::_()->getItem($type, $item_id);
      $subject = $item;
      $owner = $subject->getOwner();
//       if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
//         $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
//         Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
//         Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, $notificationType);
//         $result = $activityTable->fetchRow(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
//         if (!$result) {
//           $action = $activityTable->addActivity($viewer, $subject, $notificationType);
//           if ($action)
//             $activityTable->attachActivity($action, $subject);
//         }
//       }
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->like_count));
      die;
    }
  }
  
  function favouriteAction() {
  
    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login')); die;
    }
    
    if ($this->_getParam('type') == 'sesmusic_album') {
      $type = 'sesmusic_album';
      $dbTable = 'albums';
      $resorces_id = 'album_id';
      //$notificationType = 'sesvideo_favourite_chanel';
    } elseif ($this->_getParam('type') == 'sesmusic_albumsong') {
      $type = 'sesmusic_albumsong';
      $dbTable = 'albumsongs';
      $resorces_id = 'albumsong_id';
     // $notificationType = 'sesvideo_favourite_playlist';
    }
    
    $type = $this->_getParam('type');
    $item_id = $this->_getParam('id');
    
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));die;
    }
    
    $viewer = Engine_Api::_()->user()->getViewer();
    $Fav = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->getItemfav($type, $item_id);
    $favItem = Engine_Api::_()->getDbtable($dbTable, 'sesmusic');
    
    if (!empty($Fav)) {
      //delete
      $db = $Fav->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $Fav->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count - 1')), array($resorces_id . ' = ?' => $item_id));
      
      $item = Engine_Api::_()->getItem($type, $item_id);
      
//       Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
//       Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
//       Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($item);
      
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $item->favourite_count));
      $this->view->favourite_id = 0;
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->getAdapter();
      $db->beginTransaction();
      try {
        $fav = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->createRow();
        $fav->user_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $fav->resource_type = $type;
        $fav->resource_id = $item_id;
        $fav->save();
        $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count + 1')), array( $resorces_id . '= ?' => $item_id));
        // Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      
      //send notification and activity feed work.
      $item = Engine_Api::_()->getItem(@$type, @$item_id);
      
//       if ($this->_getParam('type') != 'sesvideo_artist') {
//         $subject = $item;
//         $owner = $subject->getOwner();
//         if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
//           $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
//           Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
//           Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, $notificationType);
//           $result = $activityTable->fetchRow(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
//           if (!$result) {
//             $action = $activityTable->addActivity($viewer, $subject, $notificationType);
//             if ($action)
//               $activityTable->attachActivity($action, $subject);
//           }
//         }
//       }

      $this->view->favourite_id = 1;
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->favourite_count, 'favourite_id' => 1));
      die;
    }
  }
  
  public function indexAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if (empty($viewer_id))
      return;

    $resource_id = $this->_getParam('resource_id');
    $resource_type = $this->_getParam('resource_type');
    $like_id = $this->_getParam('like_id');

    $item = Engine_Api::_()->getItem($resource_type, $resource_id);

    $likeTable = Engine_Api::_()->getDbTable('likes', 'core');
    $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
    $activityStrameTable = Engine_Api::_()->getDbtable('stream', 'activity');

    if (empty($like_id)) {
      $isLike = $likeTable->isLike($item, $viewer);
      if (empty($isLike)) {
        $db = $likeTable->getAdapter();
        $db->beginTransaction();
        try {
          if (!empty($item))
            $like_id = $likeTable->addLike($item, $viewer)->like_id;
          $this->view->like_id = $like_id;
          $owner = $item->getOwner();
          if($owner->getIdentity() != $viewer_id) {
            if ($resource_type == 'sesmusic_album') {          
              $owner = $item->getOwner();
              
              Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'liked', array('label' => $item->getShortType()));
              
              $action = $activityTable->addActivity($viewer, $item, 'sesmusic_likealbum');
              if ($action)
                $activityTable->attachActivity($action, $item);
            } elseif ($resource_type == 'sesmusic_albumsong') {            
              $owner = $item->getOwner();
              Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'liked', array('label' => $item->getShortType()));          
              $action = $activityTable->addActivity($viewer, $item, 'sesmusic_likealbumsong');
              if ($action) {
                $activityStrameTable->delete(array('action_id =?' => $action->action_id));
                $db->query("INSERT INTO `engine4_activity_stream` (`target_type`, `target_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`, `action_id`) VALUES
                ('everyone', 0, 'user', $viewer_id, 'sesmusic_albumsong', $resource_id, 'sesmusic_likealbumsong', $action->action_id),
                ('members', $viewer_id, 'user', $viewer_id, 'sesmusic_albumsong', $resource_id, 'sesmusic_likealbumsong', $action->action_id),
                ('owner', $viewer_id, 'user', $viewer_id, 'sesmusic_albumsong', $resource_id, 'sesmusic_likealbumsong', $action->action_id),
                ('parent', $viewer_id, 'user', $viewer_id, 'sesmusic_albumsong', $resource_id, 'sesmusic_likealbumsong', $action->action_id),
                ('registered', 0, 'user', $viewer_id, 'sesmusic_albumsong', $resource_id, 'sesmusic_likealbumsong', $action->action_id);");
                $activityTable->attachActivity($action, $item);
              }
            }
          }
          
          $db->commit();
        } catch (Exception $e) {
          $db->rollBack();
          throw $e;
        }
      } else {
        $this->view->like_id = $isLike;
      }
    } else {
      if ($resource_type == 'sesmusic_album') {
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesmusic_like_musicalbum", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
        $action = $activityTable->fetchRow(array('type =?' => "sesmusic_likealbum", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
      } elseif ($resource_type == 'sesmusic_albumsong') {
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "sesmusic_like_song", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
        $action = $activityTable->fetchRow(array('type =?' => "sesmusic_likealbumsong", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
      }
      if (!empty($action)) {
        $action->deleteItem();
        $action->delete();
      }

      $likeTable->removeLike($item, $viewer);
    }
  }

}
