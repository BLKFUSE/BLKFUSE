<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Egifts
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: AjaxController.php 2020-06-13  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Egifts_AjaxController extends Core_Controller_Action_Standard {
  function likeAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if ($viewer_id == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }
    $type = $this->_getParam('type', false);
    if ($type == 'egifts_gift') {
      $dbTable = 'gifts';
      $resorces_id = 'gift_id';
      $notificationType = 'egifts_gift_like';
    } else {
       echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $itemTable = Engine_Api::_()->getDbTable($dbTable, 'egifts');
    $tableLike = Engine_Api::_()->getDbTable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $select = $tableLike->select()
            ->from($tableMainLike)
            ->where('resource_type = ?', $type)
            ->where('poster_id = ?', $viewer_id)
            ->where('poster_type = ?', 'user')
            ->where('resource_id = ?', $item_id);
    $result = $tableLike->fetchRow($select);
    if (!empty($result)) {
      //delete
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $item = Engine_Api::_()->getItem($type, $item_id);
      if(!empty($notificationType)) {
        Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
      }
      $titleMessage = $this->view->translate(array('%s Like', '%s Likes', $item->like_count), $this->view->locale()->toNumber($item->like_count));
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $item->like_count,'title'=>$titleMessage));
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
      $db->beginTransaction();
      try {
        $like = $tableLike->createRow();
        $like->poster_id = $viewer_id;
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
      //Send notification and activity feed work.
      $item = Engine_Api::_()->getItem($type, $item_id);
      $subject = $item;
      $titleMessage = $this->view->translate(array('%s Like', '%s Likes', $item->like_count), $this->view->locale()->toNumber($item->like_count));
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->like_count,'title'=>$titleMessage));
      die;
    }
  }
  //item favourite as per item tye given
  function favouriteAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }
     $type = $this->_getParam('type', false);
    if ($type == 'egifts_gift') {
      $dbTable = 'gifts';
      $resorces_id = 'gift_id';
      $notificationType = 'egifts_gift_favourite';
    } else {
       echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $Fav = Engine_Api::_()->getDbTable('favourites', 'egifts')->getItemfav($type, $item_id);
    $favItem = Engine_Api::_()->getDbTable($dbTable, 'egifts');
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
      if($notificationType) {
        Engine_Api::_()->getDbTable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
        Engine_Api::_()->sesbasic()->deleteFeed(array('type' => $notificationType, "subject_id" => $viewer->getIdentity(), "object_type" => $item->getType(), "object_id" => $item->getIdentity()));
      }
      $titleMessage = $this->view->translate(array('%s Favourite', '%s Favourites', $item->favourite_count), $this->view->locale()->toNumber($item->favourite_count));
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $item->favourite_count,'title'=>$titleMessage));
      $this->view->favourite_id = 0;
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('favourites', 'egifts')->getAdapter();
      $db->beginTransaction();
      try {
        $fav = Engine_Api::_()->getDbTable('favourites', 'egifts')->createRow();
        $fav->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $fav->resource_type = $type;
        $fav->resource_id = $item_id;
        $fav->save();
        $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count + 1')), array($resorces_id . '= ?' => $item_id));
        // Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      //Send Notification and Activity Feed Work.
      $item = Engine_Api::_()->getItem(@$type, @$item_id);
      //End Activity Feed Work
      $this->view->favourite_id = 1;
      $titleMessage = $this->view->translate(array('%s Favourites', '%s Favourite', $item->favourite_count), $this->view->locale()->toNumber($item->favourite_count));
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->favourite_count, 'favourite_id' => 1,'title'=>$titleMessage));
      die;
    }
  }
}
