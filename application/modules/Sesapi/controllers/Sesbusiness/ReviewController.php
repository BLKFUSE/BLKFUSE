<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: ReviewController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
class Sesbusiness_ReviewController extends Sesapi_Controller_Action_Standard
{
	public function init(){
			
	}
	
	public function browseSearchAction(){
		
		$viewOptionsArray = array('likeSPcount' => 'Most Liked', 'viewSPcount' => 'Most Viewed', 'commentSPcount' => 'Most Commented', 'mostSPrated' => 'Most Rated', 'leastSPrated' => 'Least Rated', Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.first111', 'useful') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.first', 'Useful'), Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.second111', 'funny') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.second', 'Funny'), Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.third111', 'cool') . 'SPcount' => 'Most ' . Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.third', 'Cool'), 'verified' => 'Verified Only', 'featured' => 'Featured Only',);
    
		if($this->_getParam('business_id',0)){
			$formArray = array('reviewSearch'=>1,'reviewStars'=>1,'reviewRecommended'=>1,'reviewTitle'=>0,'view_type'=>'horizontal','business_id'=>$this->_getParam('business_id',0));
		}else{
			$formArray = array('reviewTitle' => 1, 'reviewSearch' =>1,'reviewStars' =>1, 'reviewRecommended' => true,'view_type'=>'vertical');
		}
    $form = $formFilter = new Sesbusinessreview_Form_Review_Browse($formArray);
		$form->removeElement('loading-img-sesbusinessreview-review');
    if ($formFilter) {
      if (!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.votes', '1')) {
        unset($viewOptionsArray[Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.first11', 'useful') . 'SPcount']);
        unset($viewOptionsArray[Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.second11', 'funny') . 'SPcount']);
        unset($viewOptionsArray[Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.third11', 'cool') . 'SPcount']);
      }
      $viewOptionsArray = array_merge(array('' => ''), $viewOptionsArray);
      if(isset($formFilter->order))
      $formFilter->order->setMultiOptions($viewOptionsArray);
    }
		if ($this->_getParam('getForm')) {
			$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
			$this->generateFormFields($formFields, array('resources_type' => 'businessreview'));
		} else {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
		}
	}
	
	public function homeAction(){
		$params['search_text'] = $this->_getParam('search_text');
		$params['info'] =  $this->_getParam('order');
		$params['review_stars'] =  $this->_getParam('review_stars');
		$params['review_recommended'] =  $this->_getParam('review_recommended');
        $select = Engine_Api::_()->getItemTable('businessreview')->getPageReviewSelect($params);
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage($limit);
        $paginator->setCurrentPageNumber($businesses);
		$result['reviews'] = $this->getReviews($paginator);
		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
		Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
	}
	
	protected function getReviews($paginator){
		$counter = 0;
		$result = array();
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewerId = $viewer->getIdentity();
		foreach($paginator as $review){
			$result[$counter] = $review->toArray();
			$reviewer = Engine_Api::_()->getItem('user', $review->owner_id);
			$businesses = Engine_Api::_()->getItem('businesses', $review->business_id);
			$owner = $reviewer->getOwner();
			$reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesbusinessreview')->getParameters(array('content_id'=>$review->getIdentity(),'business_id'=>$review->business_id));
			$ownerSelf = $viewerId == $review->owner_id ? true : false;
			$parameterCounter = 0;
			$likeStatus = Engine_Api::_()->sesbusinessreview()->getLikeStatus($review->review_id,$review->getType());
			if(engine_count($reviewParameters)>0){
				foreach($reviewParameters as $reviewP){ 
					$result[$counter]['review_perameter'][$parameterCounter] = $reviewP->toArray();
					$parameterCounter++;
				}
			}
			$result[$counter]['business']['images'] = $this->getBaseUrl(true, $businesses->getPhotoUrl());
			$result[$counter]['business']['title'] = $businesses->getTitle();
			$result[$counter]['business']['Guid'] = $businesses->getGuid();
			$result[$counter]['business']['id'] = $businesses->getIdentity();
			
			$result[$counter]['owner']['id'] = $owner->getIdentity();
			$result[$counter]['owner']['Guid'] = $owner->getGuid();
			$result[$counter]['owner']['title'] = $owner->getTitle();
			$result[$counter]['owner']['images'] = $this->getBaseUrl(true, $owner->getPhotoUrl());
			$result[$counter]['show_pros'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.show.pros', 1)?true:false;
			$result[$counter]['show_pros'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.show.cons', 1)?true:false;
			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.votes', 1)){
				$item = $review; 
				$isGivenVoteTypeone = Engine_Api::_()->getDbTable('reviewvotes','sesbusinessreview')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>1));
				$isGivenVoteTypetwo = Engine_Api::_()->getDbTable('reviewvotes','sesbusinessreview')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>2));
				$isGivenVoteTypethree = Engine_Api::_()->getDbTable('reviewvotes','sesbusinessreview')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>3));
				$result[$counter]['voting']['label'] = $this->view->translate("SESPAGE Was this Review...?");
				$bttonCounter	= 0 ;			
				$result[$counter]['voting']['buttons'][$bttonCounter]['name'] = 'useful';
				$result[$counter]['voting']['buttons'][$bttonCounter]['label'] = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.first', 'Useful'));
				$result[$counter]['voting']['buttons'][$bttonCounter]['value'] = $isGivenVoteTypeone ? true : false;
				$result[$counter]['voting']['buttons'][$bttonCounter]['count'] = $item->useful_count;
				$bttonCounter++;
				$result[$counter]['voting']['buttons'][$bttonCounter]['name'] = 'funny';
				$result[$counter]['voting']['buttons'][$bttonCounter]['label'] = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.second', 'Funny'));
				$result[$counter]['voting']['buttons'][$bttonCounter]['value'] = $isGivenVoteTypetwo ? true : false;
				$result[$counter]['voting']['buttons'][$bttonCounter]['count'] = $item->funny_count;
				$bttonCounter++;
				$result[$counter]['voting']['buttons'][$bttonCounter]['name'] = 'cool';
				$result[$counter]['voting']['buttons'][$bttonCounter]['label'] = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.third', 'Cool'));
				$result[$counter]['voting']['buttons'][$bttonCounter]['value'] = $isGivenVoteTypethree ? true : false;
				$result[$counter]['voting']['buttons'][$bttonCounter]['count'] = $item->cool_count;
				
			}
			if($item->authorization()->isAllowed($viewer, 'comment')){
				$result[$counter]['is_content_like'] = $likeStatus?true:false;
			}
			$optionCounter = 0;
			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.show.report', 1) && $viewerId && $viewerId != $owner){
				$result[$counter]['options'][$optionCounter]['name'] = 'report';
				$result[$counter]['options'][$optionCounter]['label'] = $this->view->translate('Report');
				$optionCounter++;
			}
			
			if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.share', 1) && $viewerId){
				$result[$counter]['options'][$optionCounter]['name'] = 'share';
				$result[$counter]['options'][$optionCounter]['label'] = $this->view->translate('Share');
				$optionCounter++;
				
				/*------------- share object -----------------*/
				$result[$counter]["share"]["imageUrl"] = $this->getBaseUrl(false, $review->getPhotoUrl());
				$result[$counter]["share"]["url"] = $this->getBaseUrl(false,$review->getHref());
				$result[$counter]["share"]["title"] = $review->getTitle();
				$result[$counter]["share"]["description"] = strip_tags($review->getDescription());
				$result[$counter]["share"]["setting"] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.share', 1);
				$result[$counter]["share"]['urlParams'] = array(
					"type" => $review->getType(),
					"id" => $review->getIdentity()
				);
				/*------------- share object -----------------*/
			}
			
			if($item->authorization()->isAllowed($viewer, 'edit')) { 
				$result[$counter]['options'][$optionCounter]['name'] = 'edit';
				$result[$counter]['options'][$optionCounter]['label'] = $this->view->translate('SESPAGE Edit Review');
				$optionCounter++;
			}
			if($item->authorization()->isAllowed($viewer, 'delete')) {
				$result[$counter]['options'][$optionCounter]['name'] = 'delete';
				$result[$counter]['options'][$optionCounter]['label'] = $this->view->translate('SESPAGE Delete Review');
				$optionCounter++;
			}
			$counter++;
		}
		return $result;
	}
	
	public function businessReviewsAction(){
		$params = array();
		$result = array();
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewer_id = $viewer->getIdentity();
		$params['business_id'] = $business_id = $this->_getParam('business_id',null);
		
		/*---------------------Start Check for PageID ------------------*/
		if(!$business_id)
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
		/*---------------------End Check for PageID --------------------*/
		 
		/*---------------------Get Page Item --------------------*/
		$businesses = $subject = Engine_Api::_()->getItem('businesses',$business_id);
		
		/*--------------------- Start Filter Work -----------------------*/
		$params['info'] = $this->_getParam('order','');
		$params['review_stars'] =  $this->_getParam('review_stars');
		$params['review_recommended'] =  $this->_getParam('review_recommended');
		/*--------------------- End Filter Work -------------------------*/
		
		/*---------------------- Start Settings -------------------------*/
		$cancreate = Engine_Api::_()->sesapi()->getViewerPrivacy('businessreview', 'create');
		$reviewTable = Engine_Api::_()->getDbtable('businessreviews', 'sesbusinessreview');
		$isReview = $hasReview = $reviewTable->isReview(array('business_id' => $subject->getIdentity(), 'column_name' => 'review_id'));
		
		$editReviewPrivacy = Engine_Api::_()->sesapi()->getViewerPrivacy('businessreview', 'edit');
		if (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.owner', 1)) {
			$allowedCreate = true;
		} else {
			if ($subject->owner_id == $viewer->getIdentity())
				$allowedCreate = false;
			else
				$allowedCreate = true;
		}
		/*---------------------- End Settings -------------------------*/
		
		/*---------------------- start Create / Update Buttons -------------*/
		if($viewer->getIdentity() && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.review', 1) && $allowedCreate){
			if($cancreate && !$isReview){
				$result['button']['label'] = $this->view->translate('Write a Review');
				$result['button']['name'] = 'create';
			}
			if($editReviewPrivacy && $isReview){
				$result['button']['label'] = $this->view->translate('Update Review');
				$result['button']['name'] = 'edit';
				$result['button']['value'] = $isReview;
			}
		}
		/*---------------------- End Create / Update Buttons ----------------*/
		
		
		$table = Engine_Api::_()->getItemTable('businessreview');
    $select = $table->getPageReviewSelect($params); 
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($limit);
    $paginator->setCurrentPageNumber($businesses);
		
		$result['reviews'] = $this->getReviews($paginator);
		
		$extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
		$extraParams['pagging']['total'] = $paginator->getTotalItemCount();
		$extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
		$extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
		Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
	}

	public function createAction(){
		/*----------------------- check permission ------------------*/
		if (!Engine_Api::_()->sesapi()->getViewerPrivacy('businessreview', 'create'))
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    	$subjectId = $this->_getParam('business_id', 0);
		if(!$subjectId )
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    	$item = Engine_Api::_()->getItem('businesses', $subjectId);
		/*----------------------- check for business ------------------*/
    	if (!$item)
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
  
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewerId = $viewer->getIdentity();
    /*----------------------- check review exists ------------------*/
    $isReview = Engine_Api::_()->getDbtable('businessreviews', 'sesbusinessreview')->isReview(array('business_id' => $item->business_id, 'column_name' => 'review_id'));
    $allowedCreate = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.owner', 1) ? true : ($item->owner_id == $viewerId ? false : true);
		/*----------------------- check create permission ------------------*/
    if ($isReview || !$allowedCreate)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
		
		$form = new Sesbusinessreview_Form_Review_Create(array('businessId'=>$subjectId));
		if ($this->_getParam('getForm')) {
			$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
			$this->generateFormFields($formFields, array('resources_type' => 'businessreview'));
		}
		
		if (!$form->isValid($_POST)) {
			$validateFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->validateFormFields($form);
			if (is_countable($validateFields) && engine_count($validateFields))
					$this->validateFormFields($validateFields);
		}
		
		if (!$this->getRequest()->isPost()) {
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
		}
		
    $values = $_POST;
    $values['rating'] = $_POST['rate_value'];
    $values['owner_id'] = $viewerId;
    $values['business_id'] = $item->business_id;
    $reviews_table = Engine_Api::_()->getDbtable('businessreviews', 'sesbusinessreview');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
      $review = $reviews_table->createRow();
      $review->setFromArray($values);
      $review->description = $_POST['description'];
      $review->save();
      $reviewObject = $review;
      $dbObject = Engine_Db_Table::getDefaultAdapter();
			/*----------------------- tak review ids from post ------------------*/
      $parameterValueTable = Engine_Api::_()->getDbtable('parametervalues', 'sesbusinessreview');
      $parameterTableName = $parameterValueTable->info('name');
      foreach ($_POST as $key => $reviewC) {
				if (engine_count(explode('_', $key)) != 4 || !$reviewC)
					continue;

        $key = str_replace('review_parameter_value_', '', $key);
        if (!is_numeric($key))
          continue;
				
        $parameter = Engine_Api::_()->getItem('sesbusinessreview_parameter', $key);
        $query = 'INSERT INTO ' . $parameterTableName . ' (`parameter_id`, `rating`, `business_id`,`content_id`) VALUES ("' . $key . '","' . $reviewC . '","' . $item->business_id . '","' . $review->getIdentity() . '") ON DUPLICATE KEY UPDATE rating = "' . $reviewC . '"';
        $dbObject->query($query);
        $ratingP = $parameterValueTable->getRating($key);
        $parameter->rating = $ratingP;
        $parameter->save();
      }
      $db->commit();
      /*------------------------- save rating in parent table if exists --------------*/
      if (isset($item->rating)) {
        $item->rating = Engine_Api::_()->getDbtable('businessreviews', 'sesbusinessreview')->getRating($review->business_id);
        $item->review_count = $item->review_count + 1;
        $item->save();
      }
      $review->save();
      $auth = Engine_Api::_()->authorization()->context;
      $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');
      $viewMax = array_search('everyone', $roles);
      $commentMax = array_search('everyone', $roles);
      foreach ($roles as $i => $role) {
        $auth->setAllowed($review, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($review, $role, 'comment', ($i <= $commentMax));
      }
      $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $item, 'sesbusinessreview_reviewpost');
      if ($action != null) {
        Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $review);
      }
      if ($item->owner_id != $viewerId) {
        $itemOwner = $item->getOwner('user');
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($itemOwner, $viewer, $review, 'sesbusinessreview_reviewpost');
      }
      $db->commit();
			
      $rating_count = Engine_Api::_()->getDbTable('businessreviews', 'sesbusinessreview')->ratingCount($reviewObject->business_id);
      $rating_sum = $item->rating;
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message'=>'Review Added Review Succuessfully.','review_id'=>$reviewObject->getIdentity())));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => $e->getMessage(), 'result' => array()));
    }
	}
	
	public function editAction() {
    
		/*----------------------- check permission ------------------*/
		if (!Engine_Api::_()->sesapi()->getViewerPrivacy('businessreview', 'edit'))
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
		
    $review_id = $this->_getParam('review_id', null);
		/*----------------------- check for review ID ------------------*/
		if (!$review_id)
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
		
    $subject = Engine_Api::_()->getItem('businessreview', $review_id);
    $item = $item = Engine_Api::_()->getItem('businesses', $subject->business_id);
    
		/*----------------------- check for business ------------------*/
    if (!$subject)
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
		
		$category_id = $item->category_id;
    $form = $form = new Sesbusinessreview_Form_Review_Edit(array('businessId' => $subject->business_id, 'reviewId' => $subject->review_id));
        $form->populate($subject->toArray());
        if($form->rate_value){
            $form->rate_value->setValue($subject->rating);
        }
    $form->setAttrib('id', 'sesbusinessreview_edit_review');
    $title = Zend_Registry::get('Zend_Translate')->_('SESPAGE Edit a Review for "<b>%s</b>".');
    $form->setTitle(sprintf($title, $subject->getTitle()));
    $form->setDescription("Please fill below information.");
    
		if ($this->_getParam('getForm')) {
			$formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
			$this->generateFormFields($formFields, array('resources_type' => 'businessreview'));
		}
		
		if (!$form->isValid($_POST)) {
			$validateFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->validateFormFields($form);
			if (is_countable($validateFields) && engine_count($validateFields))
					$this->validateFormFields($validateFields);
		}
		

    if (!$this->getRequest()->isPost()) {
      $form->populate($subject->toArray());
      $form->rate_value->setValue($subject->rating);
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
    }
    if (!$form->isValid($this->getRequest()->getPost()))
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));

    $values = $_POST;
    $values['rating'] = $_POST['rate_value'];
    $values['owner_id'] = $subject->owner_id;
    $values['business_id'] = $item->business_id;
    $reviews_table = Engine_Api::_()->getDbtable('businessreviews', 'sesbusinessreview');
    $db = $reviews_table->getAdapter();
    $db->beginTransaction();
    try {
      $subject->setFromArray($values);
      $subject->save();
      $table = Engine_Api::_()->getDbtable('parametervalues', 'sesbusinessreview');
      $tablename = $table->info('name');
      $dbObject = Engine_Db_Table::getDefaultAdapter();
			
      foreach ($_POST as $key => $reviewC) {
        if (engine_count(explode('_', $key)) != 4 || !$reviewC)
					continue;

        $key = str_replace('review_parameter_value_', '', $key);
        if (!is_numeric($key))
          continue;
        $parameter = Engine_Api::_()->getItem('sesbusinessreview_parameter', $key);
        $query = 'INSERT INTO ' . $tablename . ' (`parameter_id`, `rating`, `business_id`, `content_id`) VALUES ("' . $key . '","' . $reviewC . '","' . $item->business_id . '","' . $subject->review_id . '") ON DUPLICATE KEY UPDATE rating = "' . $reviewC . '"';
        $dbObject->query($query);
        $ratingP = $table->getRating($key);
        $parameter->rating = $ratingP;
        $parameter->save();
      }
      if (isset($item->rating)) {
        $item->rating = Engine_Api::_()->getDbtable('businessreviews', 'sesbusinessreview')->getRating($subject->business_id);
        $item->save();
      }
      $subject->save();
      $reviewObject = $subject;
      $db->commit();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('message'=>'Review Edited Review Succuessfully.','review_id'=>$reviewObject->getIdentity())));
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }
  }

	public function deleteAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
		$reviewId = $this->_getParam('review_id',0);
		/*----------------------- check for review ID ------------------*/
		if(!$reviewId)
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
		
    $review = Engine_Api::_()->getItem('businessreview', $reviewId);
		$content_item = Engine_Api::_()->getItem('businesses', $review->business_id);
		if(!$review || !$content_item)
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not Found'), 'result' => array()));
		
		/*----------------------- check permission ------------------*/
    if (!$this->_helper->requireAuth()->setAuthParams($review, $viewer, 'delete')->isValid())
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
 
    
    if ($this->getRequest()->isPost()) {
      $db = $review->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $review->delete();
        $db->commit();
        $message = Zend_Registry::get('Zend_Translate')->_('SESPAGE The selected review has been deleted.');
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('success_message'=>$message)));
      } catch (Exception $e) {
        $db->rollBack();
         Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
      }
    }
  }
	
	public function likeAction() {

    if (Engine_Api::_()->user()->getViewer()->getIdentity() == 0) {
       Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    $item_id = $this->_getParam('id');
    if (intval($item_id) == 0) {
			 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $itemTable = Engine_Api::_()->getItemTable('businessreview');
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $select = $tableLike->select()
            ->from($tableMainLike)
            ->where('resource_type = ?', 'businessreview')
            ->where('poster_id = ?', $viewer_id)
            ->where('poster_type = ?', 'user')
            ->where('resource_id = ?', $item_id);
    $result = $tableLike->fetchRow($select);
    if (!empty($result)) {
      /*----------------------------------delete----------------------------*/		
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();
        //$itemTable->update(array('like_count' => new Zend_Db_Expr('like_count - 1')), array('review_id = ?' => $item_id));
        $db->commit();
				 $temp['data']['message'] = $this->view->translate('Business review Successfully Unliked.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
      }
      $selectPageReview = $itemTable->select()->where('review_id =?', $item_id);
      $businessesReview = $itemTable->fetchRow($selectPageReview);
    
			$temp['data']['like_count'] = $businessesReview->like_count;
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    } else {
      /*---------------------------------update-----------------------*/
      $db = Engine_Api::_()->getDbTable('likes', 'core')->getAdapter();
      $db->beginTransaction();
      try {
        $like = $tableLike->createRow();
        $like->poster_id = $viewer_id;
        $like->resource_type = 'businessreview';
        $like->resource_id = $item_id;
        $like->poster_type = 'user';
        $like->save();
        $itemTable->update(array('like_count' => new Zend_Db_Expr('like_count + 1')), array('review_id = ?' => $item_id));
        /*------------------------Commit --------------------------------*/
        $db->commit();
				$temp['data']['message'] = $this->view->translate('Page Successfully Liked.');
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
      }
      /*-------------------Send notification and activity feed work.----------*/
      $selectPageReview = $itemTable->select()->where('review_id =?', $item_id);
      $item = $itemTable->fetchRow($selectPageReview);
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
      
			$temp['data']['like_count'] = $item->like_count;
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    }
  }
	
	public function reviewVotesAction() {
	  $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    if ($viewer_id == 0) {
     Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    $item_id = $this->_getParam('id');
    $type = $this->_getParam('type');
    if (intval($item_id) == 0 || ($type != 1 && $type != 2 && $type != 3)) {
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
    }
    $itemTable = Engine_Api::_()->getItemTable('businessreview');
    $tableVotes = Engine_Api::_()->getDbtable('reviewvotes', 'sesbusinessreview');
    $tableMainVotes = $tableVotes->info('name');

    $select = $tableVotes->select()
            ->from($tableMainVotes)
            ->where('review_id = ?', $item_id)
            ->where('user_id = ?', $viewer_id)
            ->where('type =?', $type);
    $result = $tableVotes->fetchRow($select);
    if ($type == 1)
      $votesTitle = 'useful_count';
    else if ($type == 2)
      $votesTitle = 'funny_count';
    else
      $votesTitle = 'cool_count';

    if (!empty($result)) {
      /*--------------------------------delete----------------------------*/		
      $db = $result->getTable()->getAdapter();
      $db->beginTransaction();
      try {
        $result->delete();
        $itemTable->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' - 1')), array('review_id = ?' => $item_id));
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      $selectReview = $itemTable->select()->where('review_id =?', $item_id);
      $review = $itemTable->fetchRow($selectReview);

      /*-----------------------------get review owner--------------------*/
      $businessesId = $review->business_id;
      $sesbusiness = Engine_Api::_()->getItemTable('businesses');
      $sesbusiness->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' - 1')), array('business_id = ?' => $businessesId));
			$temp['data']['count'] = $review->{$votesTitle};
			$temp['data']['condition'] = 'reduced';
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    } else {
      /*---------------------------------update----------------------------*/
      $db = Engine_Api::_()->getDbTable('reviewvotes', 'sesbusinessreview')->getAdapter();
      $db->beginTransaction();
      try {
        $votereview = $tableVotes->createRow();
        $votereview->user_id = $viewer_id;
        $votereview->review_id = $item_id;
        $votereview->type = $type;
        $votereview->save();
        $itemTable->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' + 1')), array('review_id = ?' => $item_id));
        /*---------------------------------------Commit---------------------*/
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
      /*-------------------Send notification and activity feed work.-------------*/
      $selectReview = $itemTable->select()->where('review_id =?', $item_id);
      $review = $itemTable->fetchRow($selectReview);

      /*--------------------get review owner-------------------------*/ 
      $businessesId = $review->business_id;
      $sesbusiness = Engine_Api::_()->getItemTable('businesses');
      $sesbusiness->update(array($votesTitle => new Zend_Db_Expr($votesTitle . ' + 1')), array('business_id = ?' => $businessesId));

      $temp['data']['count'] = $review->{$votesTitle};
			$temp['data']['condition'] = 'increment';
			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $temp));
    }
  }

	public function viewAction(){
		$params = array();
		$result = array();
		$params['review_id'] = $review_id = $this->_getParam('review_id',null);
		if(!$review_id){
				Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
		}
		$review = Engine_Api::_()->getItem('businessreview', $review_id);
    $businesses = Engine_Api::_()->getItem('businesses', $review->business_id);
		
		/*----------------make data-----------------------------*/
		$counter = 0;
		$result = array();
		$viewer = Engine_Api::_()->user()->getViewer();
		$viewerId = $viewer->getIdentity();
		$result = $review->toArray();
		$reviewer = Engine_Api::_()->getItem('user', $review->owner_id);
		$owner = $reviewer->getOwner();
		$reviewParameters = Engine_Api::_()->getDbtable('parametervalues', 'sesbusinessreview')->getParameters(array('content_id'=>$review->getIdentity(),'business_id'=>$review->business_id));
		$likeStatus = Engine_Api::_()->sesbusinessreview()->getLikeStatus($review->review_id,$review->getType());
		$ownerSelf = $viewerId == $review->owner_id ? true : false;
		$parameterCounter = 0;
		if(engine_count($reviewParameters)>0){
			foreach($reviewParameters as $reviewP){ 
				$result['review_perameter'][$parameterCounter] = $reviewP->toArray();
				$parameterCounter++;
			}
		}
		$result['business']['images'] = $this->getBaseUrl(true, $businesses->getPhotoUrl());
		$result['business']['title'] = $businesses->getTitle();
		$result['business']['Guid'] = $businesses->getGuid();
		$result['business']['id'] = $businesses->getIdentity();
		
		$result['owner']['id'] = $owner->getIdentity();
		$result['owner']['Guid'] = $owner->getGuid();
		$result['owner']['title'] = $owner->getTitle();
		$result['owner']['images'] = $this->getBaseUrl(true, $owner->getPhotoUrl());
		$result['show_pros'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.show.pros', 1)?true:false;
		$result['show_pros'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.show.cons', 1)?true:false;
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.votes', 1)){
			$item = $review; 
			$isGivenVoteTypeone = Engine_Api::_()->getDbTable('reviewvotes','sesbusinessreview')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>1));
			$isGivenVoteTypetwo = Engine_Api::_()->getDbTable('reviewvotes','sesbusinessreview')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>2));
			$isGivenVoteTypethree = Engine_Api::_()->getDbTable('reviewvotes','sesbusinessreview')->isReviewVote(array('review_id'=>$item->getIdentity(),'user_id'=>$viewer->getIdentity(),'type'=>3));
			$result['voting']['label'] = $this->view->translate("SESPAGE Was this Review...?");
			$bttonCounter	= 0 ;			
			$result['voting']['buttons'][$bttonCounter]['name'] = 'useful';
			$result['voting']['buttons'][$bttonCounter]['label'] = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.first', 'Useful'));
			$result['voting']['buttons'][$bttonCounter]['value'] = $isGivenVoteTypeone ? true : false;
			$result['voting']['buttons'][$bttonCounter]['action'] = $item->useful_count;
			$bttonCounter++;
			$result['voting']['buttons'][$bttonCounter]['name'] = 'funny';
			$result['voting']['buttons'][$bttonCounter]['label'] = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.second', 'Funny'));
			$result['voting']['buttons'][$bttonCounter]['value'] = $isGivenVoteTypetwo ? true : false;
			$result['voting']['buttons'][$bttonCounter]['action'] = $item->funny_count;
			$bttonCounter++;
			$result['voting']['buttons'][$bttonCounter]['name'] = 'cool';
			$result['voting']['buttons'][$bttonCounter]['label'] = $this->view->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.review.third', 'Cool'));
			$result['voting']['buttons'][$bttonCounter]['value'] = $isGivenVoteTypethree ? true : false;
			$result['voting']['buttons'][$bttonCounter]['action'] = $item->cool_count;
			
		}
		if($item->authorization()->isAllowed($viewer, 'comment')){
			$result['is_content_like'] = $likeStatus?true:false;
		}
		$optionCounter = 0;
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.show.report', 1) && $viewerId && $viewerId != $owner){
			$result['options'][$optionCounter]['name'] = 'report';
			$result['options'][$optionCounter]['label'] = $this->view->translate('Report');
			$optionCounter++;
		}
		
		if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.share', 1) && $viewerId){
			$result['options'][$optionCounter]['name'] = 'share';
			$result['options'][$optionCounter]['label'] = $this->view->translate('Share');
			$optionCounter++;
			
			/*------------- share object -----------------*/
				$result["share"]["imageUrl"] = $this->getBaseUrl(false, $review->getPhotoUrl());
				$result["share"]["url"] = $this->getBaseUrl(false,$review->getHref());
				$result["share"]["title"] = $review->getTitle();
				$result["share"]["description"] = strip_tags($review->getDescription());
				$result["share"]["setting"] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbusinessreview.allow.share', 1);
				$result["share"]['urlParams'] = array(
					"type" => $review->getType(),
					"id" => $review->getIdentity()
				);
				/*------------- share object -----------------*/
		}
		
		
		if($item->authorization()->isAllowed($viewer, 'edit')) { 
			$result['options'][$optionCounter]['name'] = 'edit';
			$result['options'][$optionCounter]['label'] = $this->view->translate('SESPAGE Edit Review');
			$optionCounter++;
		}
		if($item->authorization()->isAllowed($viewer, 'delete')) {
			$result['options'][$optionCounter]['name'] = 'delete';
			$result['options'][$optionCounter]['label'] = $this->view->translate('SESPAGE Delete Review');
			$optionCounter++;
		}
		/*----------------make data-----------------------------*/
		$data['review'] = $result;
		
		Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '0', 'error_message' => '', 'result' => $data)));
	}
}
