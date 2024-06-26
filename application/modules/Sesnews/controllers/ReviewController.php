<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: ReviewController.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_ReviewController extends Core_Controller_Action_Standard {

	public function init() {
		
		if( !$this->_helper->requireAuth()->setAuthParams('sesnews_news', null, 'view')->isValid() ) return;
		
		$viewer = Engine_Api::_()->user()->getViewer();
		if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.review', 1) || !Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view'))
		return $this->_forward('notfound', 'error', 'core');

		if($news_id = $this->_getParam('news_id')){
			$news = Engine_Api::_()->getItem('sesnews_news',$news_id);
		}

		//Get subject
		if (null !== ($review_id = $this->_getParam('review_id')) && null !== ($review = Engine_Api::_()->getItem('sesnews_review', $review_id)) && $review instanceof Sesnews_Model_Review && !Engine_Api::_()->core()->hasSubject()) {
			$news = $review->getParent();
			Engine_Api::_()->core()->setSubject($review);
		}
		if(isset($news) && $news && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesnewspackage') && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnewspackage.enable.package', 1)){
			$package = $news->getPackage();
			$viewAllowed = $package->getItemModule('review');
			if(!$viewAllowed)
				return $this->_forward('notfound', 'error', 'core');
		}
	}

  public function browseAction() {
    // Render
    $this->_helper->content->setEnabled();
  }

  public function createAction() {

		$viewer = Engine_Api::_()->user()->getViewer();
		$this->view->item = $item = Engine_Api::_()->getItem('sesnews_news', $this->getParam('news_id'));
		if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'create'))
		return $this->_forward('notfound', 'error', 'core');
		if(!$item)
		return $this->_forward('notfound', 'error', 'core');
		//check review exists
		$isReview = Engine_Api::_()->getDbtable('reviews', 'sesnews')->isReview(array('news_id' => $item->getIdentity()));
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesnews.allow.owner', 1)){
			$allowedCreate = true;
		}
		else{
			if($item->owner_id == $viewer->getIdentity())
			$allowedCreate = false;
			else
			$allowedCreate = true;
		}
		if($isReview || !$allowedCreate)
		return $this->_forward('notfound', 'error', 'core');
		if (isset($item->category_id) && $item->category_id != 0)
		$this->view->category_id = $item->category_id;
		else
		$this->view->category_id = 0;
		if (isset($item->subsubcat_id) && $item->subsubcat_id != 0)
		$this->view->subsubcat_id = $item->subsubcat_id;
		else
		$this->view->subsubcat_id = 0;
		if (isset($item->subcat_id) && $item->subcat_id != 0)
		$this->view->subcat_id = $item->subcat_id;
		else
		$this->view->subcat_id = 0;
    $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('metas', 'sesnews')->profileFieldId();
    $this->view->form = $form = new Sesnews_Form_Review_Create(array('defaultProfileId' => $defaultProfileId));
    $title = Zend_Registry::get('Zend_Translate')->_('Write Review for "<b>%s</b>".');
    $form->setTitle(sprintf($title, $item->getTitle()));
    $form->setDescription('Compose your review below, then click "Post Review" button.');
    if (!$this->getRequest()->isPost())
      return;
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
    $values = $form->getValues();
    $values['rating'] = $_POST['rate_value'];
    $values['owner_id'] = $viewer->getIdentity();
    $values['news_id'] = $item->getIdentity();
    $reviews_table = Engine_Api::_()->getDbtable('reviews', 'sesnews');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
        $review = $reviews_table->createRow();
        $review->setFromArray($values);
        $review->save();
        $dbObject = Engine_Db_Table::getDefaultAdapter();
        //tak review ids from post
        $table = Engine_Api::_()->getDbtable('parametervalues', 'sesnews');
        $tablename = $table->info('name');
        foreach($_POST as $key => $reviewC){
            if(engine_count(explode('_',$key)) != 3 || !$reviewC)
                continue;
            $key = str_replace('review_parameter_','',$key);
            if(!is_numeric($key))
                continue;
            $parameter = Engine_Api::_()->getItem('sesnews_parameter',$key);
            $query = 'INSERT INTO '.$tablename.' (`parameter_id`, `rating`, `user_id`, `resources_id`,`content_id`) VALUES ("'.$key.'","'.$reviewC.'","'.$viewer->getIdentity().'","'.$item->getIdentity().'","'.$review->getIdentity().'") ON DUPLICATE KEY UPDATE rating = "'.$reviewC.'"';
            $dbObject->query($query);
            $ratingP = $table->getRating($key);
            $parameter->rating  = $ratingP;
            $parameter->save();
        }
        //save rating in parent table if exists
        if(isset($item->rating)){
            $item->rating = Engine_Api::_()->getDbtable('reviews', 'sesnews')->getRating($review->news_id);
            $item->save();
        }

        $auth = Engine_Api::_()->authorization()->context;
        $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
        $values['auth_comment'] = 'everyone';
        $commentMax = array_search($values['auth_comment'], $roles);
        foreach( $roles as $i => $role ) {
            $auth->setAllowed($review, $role, 'comment', ($i <= $commentMax));
        }

        //Add fields
        $customfieldform = $form->getSubForm('fields');
        $customfieldform->setItem($review);
        $customfieldform->saveValues();
                $review->save();
        $db->commit();
        return $this->_helper->redirector->gotoRoute(array('action' => 'view', 'review_id' => $review->review_id, 'slug' => $review->getSlug()), 'sesnewsreview_view', true);
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function editAction() {

    $viewer = Engine_Api::_()->user()->getViewer();
    $subject = Engine_Api::_()->core()->getSubject();
    $review_id = $this->_getParam('review_id', null);
		if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'edit'))
    return $this->_forward('notfound', 'error', 'core');
		$this->view->item = $item = Engine_Api::_()->getItem('sesnews_news', $subject->news_id);
		if (isset($item->category_id) && $item->category_id != 0)
		$this->view->category_id = $item->category_id;
		else
		$this->view->category_id = 0;
		if (isset($item->subsubcat_id) && $item->subsubcat_id != 0)
		$this->view->subsubcat_id = $item->subsubcat_id;
		else
		$this->view->subsubcat_id = 0;
		if (isset($item->subcat_id) && $item->subcat_id != 0)
		$this->view->subcat_id = $item->subcat_id;
		else
		$this->view->subcat_id = 0;
    if(!$review_id || !$subject)
	  return $this->_forward('notfound', 'error', 'core');
    $this->view->defaultProfileId = $defaultProfileId = Engine_Api::_()->getDbTable('reviewmetas', 'sesnews')->profileFieldId();
    $this->view->form = $form = new Sesnews_Form_Review_Edit(array('defaultProfileId' => $defaultProfileId));
    $title = Zend_Registry::get('Zend_Translate')->_('Edit Review for "<b>%s</b>".');
    $form->setTitle(sprintf($title, $subject->getTitle()));
    $form->setDescription('Compose your review below, then click "Post Review" button.');
    if (!$this->getRequest()->isPost()){
			$form->populate($subject->toArray());
			$form->rate_value->setValue($subject->rating);
      return;
		}
    if (!$form->isValid($this->getRequest()->getPost()))
      return;
    $values = $form->getValues();
    $values['rating'] = $_POST['rate_value'];
    $reviews_table = Engine_Api::_()->getDbtable('reviews', 'sesnews');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
      $subject->setFromArray($values);
      $subject->save();
			$table = Engine_Api::_()->getDbtable('parametervalues', 'sesnews');
			$tablename = $table->info('name');
			$dbObject = Engine_Db_Table::getDefaultAdapter();
			foreach($_POST as $key => $reviewC){
				if(engine_count(explode('_',$key)) != 3 || !$reviewC)
					continue;
				$key = str_replace('review_parameter_','',$key);
				if(!is_numeric($key))
					continue;
				$parameter = Engine_Api::_()->getItem('sesnews_parameter',$key);
				$query = 'INSERT INTO '.$tablename.' (`parameter_id`, `rating`, `user_id`, `resources_id`,`content_id`) VALUES ("'.$key.'","'.$reviewC.'","'.$subject->owner_id.'","'.$item->getIdentity().'","'.$subject->getIdentity().'") ON DUPLICATE KEY UPDATE rating = "'.$reviewC.'"';
				$dbObject->query($query);
				$ratingP = $table->getRating($key);
				$parameter->rating  = $ratingP;
				$parameter->save();
			}
			if(isset($item->rating)){
				$item->rating = Engine_Api::_()->getDbtable('reviews', 'sesnews')->getRating($subject->news_id);
				$item->save();
			}
			 //Add fields
      $customfieldform = $form->getSubForm('fields');
      $customfieldform->setItem($subject);
      $customfieldform->saveValues();
			$subject->save();
      $db->commit();
      return $this->_helper->redirector->gotoRoute(array('action' => 'view', 'review_id' => $subject->review_id, 'slug' => $subject->getSlug()), 'sesnewsreview_view', true);
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

  public function deleteAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $review = Engine_Api::_()->getItem('sesnews_review', $this->getRequest()->getParam('review_id'));
    $content_item = Engine_Api::_()->getItem('sesnews_news', $review->news_id);
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
		if( Engine_Api::_()->core()->hasSubject('sesnews_review'))
		$subject = Engine_Api::_()->core()->getSubject();
		else
		return $this->_forward('notfound', 'error', 'core');
		$review_id = $this->_getParam('review_id', null);
		if (!Engine_Api::_()->sesbasic()->getViewerPrivacy('sesnews_review', 'view'))
		return $this->_forward('notfound', 'error', 'core');
		//Increment view count
		if (!$viewer->isSelf($subject->getOwner())) {
			$subject->view_count++;
			$subject->save();
		}
		//Render
		$this->_helper->content->setEnabled();
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
    $itemTable = Engine_Api::_()->getItemTable('sesnews_review');
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $select = $tableLike->select()
            ->from($tableMainLike)
            ->where('resource_type = ?', 'sesnews_review')
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
        $like->resource_type = 'sesnews_review';
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
