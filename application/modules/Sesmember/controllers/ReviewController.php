<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: ReviewController.php 2016-05-25  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesmember_ReviewController extends Core_Controller_Action_Standard {

  public function init() {
    if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.review', 1) || !Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view'))
      return $this->_forward('notfound', 'error', 'core');
    $sesmember_reviews = Zend_Registry::isRegistered('sesmember_reviews') ? Zend_Registry::get('sesmember_reviews') : null;
    if (empty($sesmember_reviews))
      return $this->_forward('notfound', 'error', 'core');
    //Get subject
    if (null !== ($review_id = $this->_getParam('review_id')) && null !== ($review = Engine_Api::_()->getItem('sesmember_review', $review_id)) && $review instanceof Sesmember_Model_Review && !Engine_Api::_()->core()->hasSubject()) {
      Engine_Api::_()->core()->setSubject($review);
    }
  }

  public function browseAction() {
    // Render
    $this->_helper->content->setEnabled();
  }

  public function createAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $subjectId = $this->_getParam('object_id', 0);
    $this->view->item = $item = Engine_Api::_()->getItem('user', $subjectId);
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($item->user_id);
    if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'create'))
      return $this->_forward('notfound', 'error', 'core');
    if (!$item)
      return $this->_forward('notfound', 'error', 'core');
    //check review exists
    $isReview = Engine_Api::_()->getDbtable('reviews', 'sesmember')->isReview(array('user_id' => $item->user_id, 'column_name' => 'review_id'));
    if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.allow.owner', 1)) {
      $allowedCreate = true;
    } else {
      if ($item->user_id == $viewer->getIdentity())
        $allowedCreate = false;
      else
        $allowedCreate = true;
    }
    if ($isReview || !$allowedCreate)
      return $this->_forward('notfound', 'error', 'core');
    $sesmember_reviews = Zend_Registry::isRegistered('sesmember_reviews') ? Zend_Registry::get('sesmember_reviews') : null;
    if (empty($sesmember_reviews))
      return $this->_forward('notfound', 'error', 'core');
    $values = $_POST;
    $values['rating'] = $_POST['rate_value'];
    $values['owner_id'] = $viewer->getIdentity();
    $values['user_id'] = $item->user_id;
    $reviews_table = Engine_Api::_()->getDbtable('reviews', 'sesmember');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
      $review = $reviews_table->createRow();
      $review->setFromArray($values);
      if(!empty($_POST['description']))
      { $review->description = $_POST['description']; }
      $review->save();
      $reviewObject = $review;
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      //tak review ids from post
      $table = Engine_Api::_()->getDbtable('parametervalues', 'sesmember');
      $tablename = $table->info('name');
      foreach ($_POST as $key => $reviewC) {
        if (engine_count(explode('_', $key)) != 3 || !$reviewC)
          continue;
        $key = str_replace('review_parameter_', '', $key);
        if (!is_numeric($key))
          continue;
        $parameter = Engine_Api::_()->getItem('sesmember_parameter', $key);
        $query = 'INSERT INTO ' . $tablename . ' (`parameter_id`, `rating`, `user_id`, `resources_id`,`content_id`) VALUES ("' . $key . '","' . $reviewC . '","' . $viewer->getIdentity() . '","' . $item->user_id . '","' . $review->getIdentity() . '") ON DUPLICATE KEY UPDATE rating = "' . $reviewC . '"';
        $dbObject->query($query);
        $ratingP = $table->getRating($key);
        $parameter->rating = $ratingP;
        $parameter->save();
      }
      $db->commit();
      //save rating in parent table if exists
      if (isset($userInfoItem->rating)) {
        $rating = Engine_Api::_()->getDbtable('reviews', 'sesmember')->getRating($review->user_id);
        //$item->save();

        $userInfoItem->rating = $rating;
        $userInfoItem->save();
      }
      $review->save();

      $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'sesmember_reviewpost');
      if ($action != null) {
        Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $review);
      }

      if ($item->user_id != $viewer->getIdentity()) {
        $itemOwner = $item->getOwner('user');
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($itemOwner, $viewer, $review, 'sesmember_reviewpost');
      }

      $db->commit();
      $stats = Engine_Api::_()->sesmember()->getWidgetParams($item->level_id);
      $this->view->stats = engine_count($stats) ? $stats : $this->_getParam('stats', array('featured', 'sponsored', 'likeCount', 'commentCount', 'viewCount', 'title', 'postedBy', 'pros', 'cons', 'description', 'creationDate', 'recommended', 'parameter', 'rating'));
      $this->view->review = $reviewObject;
      if (Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'edit')) {
        $this->view->form = $form = new Sesmember_Form_Review_Create(array('userId' => $reviewObject->owner_id, 'reviewId' => $reviewObject->review_id, 'profileId' => $subjectId));
        $form->populate($reviewObject->toArray());
        $form->rate_value->setvalue($reviewObject->rating);
        $form->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesmember', 'controller' => 'review', 'action' => 'edit', 'review_id' => $reviewObject->review_id), 'default', true));
      }
      $this->view->rating_count = Engine_Api::_()->getDbTable('reviews', 'sesmember')->ratingCount($reviewObject->user_id);
      $this->view->rating_sum = $userInfoItem->rating;
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editAction() {
    $review_id = $this->_getParam('review_id', null);
    $subject = Engine_Api::_()->getItem('sesmember_review', $review_id);

    if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'edit'))
      return $this->_forward('notfound', 'error', 'core');

    $this->view->item = $item = Engine_Api::_()->getItem('user', $subject->user_id);
    $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($subject->user_id);
    if (!$review_id || !$subject)
      return $this->_forward('notfound', 'error', 'core');
    $sesmember_reviews = Zend_Registry::isRegistered('sesmember_reviews') ? Zend_Registry::get('sesmember_reviews') : null;
    if (empty($sesmember_reviews))
      return $this->_forward('notfound', 'error', 'core');
    $values = $_POST;
    $values['rating'] = $_POST['rate_value'];
    $values['owner_id'] = $subject->owner_id;
    $values['user_id'] = $item->user_id;
    $reviews_table = Engine_Api::_()->getDbtable('reviews', 'sesmember');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
      $subject->setFromArray($values);
      $subject->save();
      $table = Engine_Api::_()->getDbtable('parametervalues', 'sesmember');
      $tablename = $table->info('name');
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      foreach ($_POST as $key => $reviewC) {
        if (engine_count(explode('_', $key)) != 3 || !$reviewC)
          continue;
        $key = str_replace('review_parameter_', '', $key);
        if (!is_numeric($key))
          continue;
        $parameter = Engine_Api::_()->getItem('sesmember_parameter', $key);
        $query = 'INSERT INTO ' . $tablename . ' (`parameter_id`, `rating`, `user_id`, `resources_id`,`content_id`) VALUES ("' . $key . '","' . $reviewC . '","' . $subject->owner_id . '","' . $item->user_id . '","' . $subject->review_id . '") ON DUPLICATE KEY UPDATE rating = "' . $reviewC . '"';
        $dbObject->query($query);
        $ratingP = $table->getRating($key);
        $parameter->rating = $ratingP;
        $parameter->save();
      }
      if (isset($userInfoItem->rating)) {
        $userInfoItem->rating = Engine_Api::_()->getDbtable('reviews', 'sesmember')->getRating($subject->user_id);
        $userInfoItem->save();
        //$item->save();
      }
      $subject->save();
      $reviewObject = $subject;
      $db->commit();

      $stats = Engine_Api::_()->sesmember()->getWidgetParams($item->level_id);
      $this->view->stats = engine_count($stats) ? $stats : $this->_getParam('stats', array('featured', 'sponsored', 'likeCount', 'commentCount', 'viewCount', 'title', 'postedBy', 'pros', 'cons', 'description', 'creationDate', 'recommended', 'parameter', 'rating'));
      $this->view->review = $reviewObject;
      $this->view->form = $form = new Sesmember_Form_Review_Create(array('userId' => $reviewObject->owner_id, 'reviewId' => $reviewObject->review_id, 'profileId' => $reviewObject->user_id));
      $form->populate($reviewObject->toArray());
      $form->rate_value->setvalue($reviewObject->rating);
      $form->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesmember', 'controller' => 'review', 'action' => 'edit', 'review_id' => $reviewObject->review_id), 'default', true));
      $this->view->rating_count = Engine_Api::_()->getDbTable('reviews', 'sesmember')->ratingCount($reviewObject->user_id);
      $this->view->total_rating_average = Engine_Api::_()->getDbtable('reviews', 'sesmember')->getRating($reviewObject->user_id);
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function deleteAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->getItem('sesmember_review', $this->getRequest()->getParam('review_id'));
    $content_item = Engine_Api::_()->getItem('user', $review->user_id);
    if (!$this->_helper->requireAuth()->setAuthParams($review, $viewer, 'delete')->isValid())
      return;
    // In smoothbox
    $this->_helper->layout->setLayout('default-simple');
    $this->view->form = $form = new Sesbasic_Form_Delete();
    $form->setTitle('Delete Review?');
    $form->setDescription('Are you sure that you want to delete this review? It will not be recoverable after being deleted.');
    $form->submit->setLabel('Delete');
    if ($this->getRequest()->isPost()) {
      $db = $review->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $reviewParameterTable = Engine_Api::_()->getDbTable('parametervalues', 'sesmember');
        $select = $reviewParameterTable->select()->where('content_id =?', $review->review_id);
        $parameters = $reviewParameterTable->fetchAll($select);
        if (engine_count($parameters) > 0) {
          foreach ($parameters as $parameter) {
            $reviewParameterTable->delete(array('parametervalue_id =?' => $parameter->parametervalue_id));
          }
        }
        $review->delete();
        $db->commit();
        $this->view->message = Zend_Registry::get('Zend_Translate')->_('The selected review has been deleted.');
        return $this->_forward('success', 'utility', 'core', array('parentRedirect' => $content_item->gethref(), 'messages' => array($this->view->message)));
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    }
  }

  public function viewAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    if (Engine_Api::_()->core()->hasSubject('sesmember_review'))
      $subject = Engine_Api::_()->core()->getSubject();
    else
      return $this->_forward('notfound', 'error', 'core');

    $review_id = $this->_getParam('review_id', null);
    $sesmember_reviews = Zend_Registry::isRegistered('sesmember_reviews') ? Zend_Registry::get('sesmember_reviews') : null;
    if (empty($sesmember_reviews))
      return $this->_forward('notfound', 'error', 'core');
    if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view'))
      return $this->_forward('notfound', 'error', 'core');
    //Increment view count
    if (!$viewer->isSelf($subject->getOwner())) {
      $subject->view_count++;
      $subject->save();
    }
    //Render
    $this->_helper->content->setEnabled();
  }

  public function editReviewAction() {
    $this->_helper->layout->setLayout('default-simple');
    $review_id = $this->_getParam('review_id', null);
    $subject = Engine_Api::_()->getItem('sesmember_review', $review_id);

    if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'edit'))
      return $this->_forward('notfound', 'error', 'core');

    $this->view->item = $item = Engine_Api::_()->getItem('user', $subject->user_id);

    if (!$review_id || !$subject)
      return $this->_forward('notfound', 'error', 'core');
    $sesmember_reviews = Zend_Registry::isRegistered('sesmember_reviews') ? Zend_Registry::get('sesmember_reviews') : null;
    if (empty($sesmember_reviews))
      return $this->_forward('notfound', 'error', 'core');
    $this->view->form = $form = new Sesmember_Form_Review_Edit(array('userId' => $subject->owner_id, 'reviewId' => $subject->review_id, 'profileId' => $subject->user_id));
    $form->setAction(Zend_Controller_Front::getInstance()->getRouter()->assemble(array('module' => 'sesmember', 'controller' => 'review', 'action' => 'edit-review', 'review_id' => $review_id), 'default', true));
    $title = Zend_Registry::get('Zend_Translate')->_('Edit a Review for "<b>%s</b>".');
    $form->setTitle(sprintf($title, $subject->getTitle()));
    $form->setDescription("Please fill below information.");

    if (!$this->getRequest()->isPost()) {
      $form->populate($subject->toArray());
      $form->rate_value->setValue($subject->rating);
      return;
    }
    if (!$form->isValid($this->getRequest()->getPost()))
      return;

    $values = $_POST;
    $values['rating'] = $_POST['rate_value'];
    $values['owner_id'] = $subject->owner_id;
    $values['user_id'] = $item->user_id;
    $reviews_table = Engine_Api::_()->getDbtable('reviews', 'sesmember');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
      $subject->setFromArray($values);
      $subject->save();
      $table = Engine_Api::_()->getDbtable('parametervalues', 'sesmember');
      $tablename = $table->info('name');
      $dbObject = Engine_Db_Table::getDefaultAdapter();
      foreach ($_POST as $key => $reviewC) {
        if (engine_count(explode('_', $key)) != 3 || !$reviewC)
          continue;
        $key = str_replace('review_parameter_', '', $key);
        if (!is_numeric($key))
          continue;
        $parameter = Engine_Api::_()->getItem('sesmember_parameter', $key);
        $query = 'INSERT INTO ' . $tablename . ' (`parameter_id`, `rating`, `user_id`, `resources_id`,`content_id`) VALUES ("' . $key . '","' . $reviewC . '","' . $subject->owner_id . '","' . $item->user_id . '","' . $subject->review_id . '") ON DUPLICATE KEY UPDATE rating = "' . $reviewC . '"';
        $dbObject->query($query);
        $ratingP = $table->getRating($key);
        $parameter->rating = $ratingP;
        $parameter->save();
      }
      if (isset($item->rating)) {
        $item->rating = Engine_Api::_()->getDbtable('reviews', 'sesmember')->getRating($subject->user_id);
        $item->save();
      }
      $subject->save();
      $reviewObject = $subject;
      $db->commit();
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('The selected review has been edited.');
      return $this->_forward('success', 'utility', 'core', array('parentRedirect' => $reviewObject->gethref(), 'messages' => array($this->view->message)));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  function likeAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $itemTable = Engine_Api::_()->getItemTable('sesmember_review');
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $select = $tableLike->select()
            ->from($tableMainLike)
            ->where('resource_type = ?', 'sesmember_review')
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
        $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count - 1')), array('review_id = ?' => $item_id));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $selectUser = $itemTable->select()->where('review_id =?', $item_id);
      $user = $itemTable->fetchRow($selectUser);
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $user->like_count));
      die;
    } else {
      //update
      $db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
      $db->beginTransaction();
      try {
        $like = $tableLike->createRow();
        $like->poster_id = $viewer_id;
        $like->resource_type = 'sesmember_review';
        $like->resource_id = $item_id;
        $like->poster_type = 'user';
        $like->save();
        $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array('review_id = ?' => $item_id));
        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      //Send notification and activity feed work.
      $selectUser = $itemTable->select()->where('review_id =?', $item_id);
      $item = $itemTable->fetchRow($selectUser);
      $subject = $item;
      $owner = $subject->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer_id) {
        $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => 'liked', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, 'liked');
        $result = $activityTable->fetchRow(array('type =?' => 'liked', "subject_id =?" => $viewer_id, "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        if (!$result) {
          $action = $activityTable->addActivity($viewer, $subject, 'liked');
          if ($action)
            $activityTable->attachActivity($action, $subject);
        }
      }
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->like_count));
      die;
    }
  }

}
