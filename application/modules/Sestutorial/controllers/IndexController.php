<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sestutorial
 * @package    Sestutorial
 * @copyright  Copyright 2017-2018 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: IndexController.php  2017-10-16 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */


class Sestutorial_IndexController extends Core_Controller_Action_Standard {
	
	public function init() {
    if (!$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'view')->isValid())
      return;
	}
	
  function likeAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Login'));
      die;
    }

    $type = 'sestutorial_tutorial';
    $dbTable = 'tutorials';
    $resorces_id = 'tutorial_id';
    $notificationType = 'liked';
    $actionType = 'sestutorial_tutorial_like';
		
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
      echo json_encode(array('status' => 'false', 'error' => 'Invalid argument supplied.'));
      die;
    }

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $itemTable = Engine_Api::_()->getDbtable($dbTable, 'sestutorial');
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $item = Engine_Api::_()->getItem($type, $item_id);
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
      
      $item->like_count--;
      $item->save();
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'reduced', 'count' => $item->like_count));
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

        $item->like_count++;
        $item->save();

        //Commit
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }

      //Send notification and activity feed work.
      $item = Engine_Api::_()->getItem($type, $item_id);
      $subject = $item;
      $owner = $subject->getOwner();
      if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
        $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $subject, $notificationType);
        $result = $activityTable->fetchRow(array('type =?' => $actionType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $subject->getType(), "object_id = ?" => $subject->getIdentity()));
	       
        if (!$result) {
	        $action = $activityTable->addActivity($viewer, $subject, $actionType);
	        if ($action)
	          $activityTable->attachActivity($action, $subject);
        }
      }
      echo json_encode(array('status' => 'true', 'error' => '', 'condition' => 'increment', 'count' => $item->like_count));
      die;
    }
  }
	
	public function helpfulAction() {
    
    $tutorial_id = $this->_getParam('tutorial_id', null);
    $helpfultutorial = $this->_getParam('helpfultutorial', null);
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
    $reason_id = $this->_getParam('reason_id', 0);
    $checkHelpful = Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->checkHelpful($tutorial_id, $viewer_id);
    if($checkHelpful) {
      Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->setHelpful($tutorial_id, $viewer_id, $reason_id, $helpfultutorial);
    } else {
      Engine_Api::_()->getDbTable('helptutorials', 'sestutorial')->setHelpful($tutorial_id, $viewer_id, $reason_id, $helpfultutorial);
    }
	}
	
  public function askquestionAction() {

    if (null === $this->_helper->ajaxContext->getCurrentContext()) {
      $this->_helper->layout->setLayout('default-simple');
    } else {
      $this->_helper->layout->disableLayout(true);
    }

    if( !$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'askquestion')->isValid() )
        return;

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $this->view->form = $form = new Sestutorial_Form_Askquestion();
    if(!$this->getRequest()->isPost()) return;
    if(!$form->isValid($this->getRequest()->getPost())) return;
    $db = Engine_Api::_()->getDbtable('askquestions', 'sestutorial')->getAdapter();
    $db->beginTransaction();
    $askquestionsTable = Engine_Api::_()->getDbtable('askquestions', 'sestutorial');
    $settings = Engine_Api::_()->getApi('settings', 'core');
    try {
    
      $values = $form->getValues();
      if(!empty($viewer_id)) {
        $values['user_id'] = $viewer_id;
        $values['name'] = $viewer->getTitle();
        $values['email'] = $viewer->email;
      }
      $askquestion = $askquestionsTable->createRow();
      $askquestion->setFromArray($values);
      $askquestion->save();

      //Email and notification send to admin
      $superAdmins = Engine_Api::_()->sestutorial()->getAllSuperAdmins();
      foreach ($superAdmins as $superAdmin) {
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($superAdmin['email'], 'SESTutorial_ASKQUESTION_EMAIL', array(
          'site_title' => $settings->getSetting('core.general.site.title', 'My Community'),
          'description' => $askquestion->description,
          'email' => $superAdmin['email'],
        ));
      }

      $db->commit();
			$this->_forward('success', 'utility', 'core', array(
					'smoothboxClose' => 300,
					'messages' => array('Your question posted successfully.')
			));
    } catch( Exception $e ) {
      $db->rollBack();
      throw $e;
    }
  }
  
  public function homeAction() {
		 if (!$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'view')->isValid())
			 return;
    //Render
    $this->_helper->content->setEnabled();
  }
	
  public function browseAction() {
		 if (!$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'view')->isValid())
			 return;
    //Render
    $this->_helper->content->setEnabled();
  }
  
  public function tagsAction() {
		 if (!$this->_helper->requireAuth()->setAuthParams('sestutorial_tutorial', null, 'view')->isValid())
			 return;
    //Render
    $this->_helper->content->setEnabled();
  }
  
  public function rateAction()
  {
    $viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();
    
    $rating = $this->_getParam('rating');
    $tutorial_id =  $this->_getParam('tutorial_id');

    
    $table = Engine_Api::_()->getDbtable('ratings', 'sestutorial');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      Engine_Api::_()->sestutorial()->setRating($tutorial_id, $user_id, $rating);
         
      $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $tutorial_id);
      $tutorial->rating = Engine_Api::_()->sestutorial()->getRating($tutorial->getIdentity());
      $tutorial->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $total = Engine_Api::_()->sestutorial()->ratingCount($tutorial->getIdentity());

    $data = array();
    $data[] = array(
      'total' => $total,
      'rating' => $rating,
    );
    return $this->_helper->json($data);
    $data = Zend_Json::encode($data);
    $this->getResponse()->setBody($data);
  }
  
  public function viewAction() {
  
    // Check permission
    $viewer = Engine_Api::_()->user()->getViewer();
    $tutorial = Engine_Api::_()->getItem('sestutorial_tutorial', $this->_getParam('tutorial_id'));
    if( $tutorial ) {
      Engine_Api::_()->core()->setSubject($tutorial);
    }
    
    // Increment view count
		if($tutorial) {
			if (!$tutorial->getOwner()->isSelf($viewer)) {
				$tutorial->view_count++;
				$tutorial->save();
			}
		}
    
    if( !$this->_helper->requireSubject()->isValid() ) {
      return;
    }
    
    if( !$this->_helper->requireAuth()->setAuthParams($tutorial, $viewer, 'view')->isValid() ) {
      return;
    }
    
    $returnValue = Engine_Api::_()->sestutorial()->checkPrivacySetting($tutorial->tutorial_id);
    if ($returnValue == false) {
      return $this->_forward('requireauth', 'error', 'core');
    }
    
    //Render
    $this->_helper->content->setEnabled();
  }
  
  public function searchAction() {

    $text = $this->_getParam('text', null);
    $table = Engine_Api::_()->getDbTable('tutorials', 'sestutorial');
    $tableName = $table->info('name');
    $id = 'tutorial_id';
    $route = 'sestutorial_profile';
    $photo = 'thumb.icon';
    $label = 'title';


    $data = array();
    $select = $table->select()->from($tableName);
    $select->where('title  LIKE ? ', '%' . $text . '%')->where('search =?', 1)->order('title ASC');
    $select->where('status = ?', 1);
    $select->limit('40');
    $results = $table->fetchAll($select);

    foreach ($results as $result) {
      $url = $this->view->url(array($id => $result->$id,'slug'=>$result->getSlug()), $route, true);
      $photo = $this->view->itemPhoto($result, $photo);
      $data[] = array(
				'id' => $result->$id,
				'label' => $result->$label,
				'photo' => $photo,
				'url' => $url,
      );
    }
    return $this->_helper->json($data);
  }
  
  public function subcategoryAction() {
  
    $category_id = $this->_getParam('category_id', null);
    if ($category_id) {
			$subcategory = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getModuleSubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '" >' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;
    die;
  }
  
	// get tutorial subsubcategory 
  public function subsubcategoryAction() {
    $category_id = $this->_getParam('subcategory_id', null);
    if ($category_id) {
      $subcategory = Engine_Api::_()->getDbtable('categories', 'sestutorial')->getModuleSubsubcategory(array('category_id'=>$category_id,'column_name'=>'*'));
      $count_subcat = engine_count($subcategory->toarray());
      if (isset($_POST['selected']))
        $selected = $_POST['selected'];
      else
        $selected = '';
      $data = '';
      if ($subcategory && $count_subcat) {
        $data .= '<option value=""></option>';
        foreach ($subcategory as $category) {
          $data .= '<option ' . ($selected == $category['category_id'] ? 'selected = "selected"' : '') . ' value="' . $category["category_id"] . '">' . Zend_Registry::get('Zend_Translate')->_($category["category_name"]) . '</option>';
        }
      }
    }
    else
      $data = '';
    echo $data;die;
  }
}
