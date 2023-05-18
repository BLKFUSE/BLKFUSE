<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesapi
 * @copyright  Copyright 2014-2019 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: PollController.php 2018-08-14 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */

 class Sespage_OfferController extends Sesapi_Controller_Action_Standard
 {  
 	public function init() {
 		$id = $this->_getParam('pageoffer_id', $this->_getParam('pageoffer_id', null));
 		if ($id && intval($id)) {
 			$offer = Engine_Api::_()->getItem('pageoffer', $id);
 			if ($offer) {
 				Engine_Api::_()->core()->setSubject($offer);
 			}
 		}
 	}
 	public function createAction() {
 		if (!$this->_helper->requireUser->isValid())
 			Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));

 		if( !$this->_helper->requireAuth()->setAuthParams('pageoffer', null, 'create')->isValid()) 
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));

        $parent_id = $this->_getParam('parent_id', null);
        if(empty($parent_id))
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
        $this->view->parentItem = $parentItem = Engine_Api::_()->getItem('sespage_page', $parent_id);

        $getPageRolePermission = Engine_Api::_()->sespage()->getPageRolePermission($parentItem->getIdentity(),'post_content','offer',false);

        $allowed = true;
        $canUpload = $getPageRolePermission ? $getPageRolePermission : $parentItem->authorization()->isAllowed($viewer, 'offer');
        $canUpload = !$allowed ? false : $canUpload;
        if(!$canUpload)
            Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
        // Render
        $this->_helper->content->setEnabled();

        // set up data needed to check quota
        $viewer = Engine_Api::_()->user()->getViewer();
        $values['user_id'] = $viewer->getIdentity();
        $values['parent_id'] = $parent_id;

        // Prepare form
        $this->view->form = $form = new Sespageoffer_Form_Create();

        if($this->_getParam('getForm')) {
            $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
            $this->generateFormFields($formFields);
        }


        // If not post or form not valid, return
        if( !$this->getRequest()->isPost() ) {
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
        }

        if( !$form->isValid($this->getRequest()->getPost()) ) {
            $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
            if(is_countable($validateFields) && engine_count($validateFields))
               $this->validateFormFields($validateFields);
       }

        // Process
       $table = Engine_Api::_()->getItemTable('pageoffer');
       $db = $table->getAdapter();
       $db->beginTransaction();

       try {
        // Create pageoffer
        $viewer = Engine_Api::_()->user()->getViewer();
        $formValues = $form->getValues();

        if( empty($formValues['auth_view']) ) {
           $formValues['auth_view'] = 'everyone';
       }

       if( empty($formValues['auth_comment']) ) {
           $formValues['auth_comment'] = 'everyone';
       }

       $values = array_merge($formValues, array(
           'owner_type' => $viewer->getType(),
           'owner_id' => $viewer->getIdentity(),
           'parent_id' => $parent_id,
           'view_privacy' => $formValues['auth_view'],
       ));

       $offer = $table->createRow();
       $offer->setFromArray($values);
       $offer->save();

       if( !empty($values['photo']) ) {
           $offer->setPhoto($form->photo);
       }

        // Auth
       $auth = Engine_Api::_()->authorization()->context;
       $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

       $viewMax = array_search($formValues['auth_view'], $roles);
       $commentMax = array_search($formValues['auth_comment'], $roles);

       foreach( $roles as $i => $role ) {
           $auth->setAllowed($offer, $role, 'view', ($i <= $viewMax));
           $auth->setAllowed($offer, $role, 'comment', ($i <= $commentMax));
       }

        // Add activity only if pageoffer is published
       if( $values['draft'] == 0 ) {
           $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $parentItem, 'sespageoffer_new');
            // make sure action exists before attaching the pageoffer to the activity
           if( $action ) {
              Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $offer);
          }
      }
        // Commit
      $db->commit();
      $message['status'] = true;
      $message['message'] = $this->view->translate('Offer created successfully.');
  } catch( Exception $e ) {
    $db->rollback();
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
}
Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
}

public function deleteAction() {

 $viewer = Engine_Api::_()->user()->getViewer();
 $offer = Engine_Api::_()->getItem('pageoffer', $this->getRequest()->getParam('pageoffer_id'));
 if( !$this->_helper->requireAuth()->setAuthParams($offer, null, 'delete')->isValid()) 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
        // In smoothbox
$this->_helper->layout->setLayout('default-simple');

$this->view->form = $form = new Sespageoffer_Form_Delete();
if($this->_getParam('getForm')) {
    $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields);
}

if( !$offer ) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
}
if (!$this->getRequest()->isPost()) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
}

$db = $offer->getTable()->getAdapter();
$db->beginTransaction();

try {
    Engine_Api::_()->getApi('core', 'sespageoffer')->deleteOffer($offer);
    $db->commit();
    $message['status'] = true;
    $message['message'] = $this->view->translate('Your offer entry has been deleted.');
} catch( Exception $e ) {
    $db->rollBack();
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
}	
Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
}
public function editAction() {

 if( !$this->_helper->requireUser()->isValid() ) 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid_request'), 'result' => array()));

$viewer = Engine_Api::_()->user()->getViewer();

$offer = Engine_Api::_()->getItem('pageoffer', $this->_getParam('pageoffer_id'));
if( !Engine_Api::_()->core()->hasSubject('pageoffer') ) {
    Engine_Api::_()->core()->setSubject($offer);
}

$parent_id = $this->_getParam('parent_id', null);
if(empty($parent_id))
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));

$this->view->parentItem = $parentItem = Engine_Api::_()->getItem('sespage_page', $parent_id);

if( !$this->_helper->requireSubject()->isValid() ) 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid_request'), 'result' => array()));
if( !$this->_helper->requireAuth()->setAuthParams($offer, $viewer, 'edit')->isValid() )
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));

        // Prepare form
$this->view->form = $form = new Sespageoffer_Form_Edit();

        // Populate form
$form->populate($offer->toArray());

$auth = Engine_Api::_()->authorization()->context;
$roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

foreach( $roles as $role ) {
    if ($form->auth_view){
       if( $auth->isAllowed($offer, $role, 'view') ) {
          $form->auth_view->setValue($role);
      }
  }

  if ($form->auth_comment){
   if( $auth->isAllowed($offer, $role, 'comment') ) {
      $form->auth_comment->setValue($role);
  }
}
}

        // hide status change if it has been already published
if( $offer->draft == "0" ) {
    $form->removeElement('draft');
}

        // Check post/form
if($this->_getParam('getForm')) {
    $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields);
}

        // If not post or form not valid, return
if( !$this->getRequest()->isPost() ) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
}

if( !$form->isValid($this->getRequest()->getPost()) ) {
    $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
    if(is_countable($validateFields) && engine_count($validateFields))
       $this->validateFormFields($validateFields);
}

$db = Engine_Db_Table::getDefaultAdapter();
$db->beginTransaction();

try {
    $values = $form->getValues();

    if( empty($values['auth_view']) ) {
       $values['auth_view'] = 'everyone';
   }
   if( empty($values['auth_comment']) ) {
       $values['auth_comment'] = 'everyone';
   }

   $values['view_privacy'] = $values['auth_view'];
   $values['parent_id'] = $parent_id;

   $offer->setFromArray($values);
   $offer->modified_date = date('Y-m-d H:i:s');
   $offer->save();

            // Add photo
   if( !empty($values['photo']) ) {
       $offer->setPhoto($form->photo);
   }

            // Auth
   $viewMax = array_search($values['auth_view'], $roles);
   $commentMax = array_search($values['auth_comment'], $roles);

   foreach( $roles as $i => $role ) {
       $auth->setAllowed($offer, $role, 'view', ($i <= $viewMax));
       $auth->setAllowed($offer, $role, 'comment', ($i <= $commentMax));
   }

            // insert new activity if pageoffer is just getting published
   $action = Engine_Api::_()->getDbtable('actions', 'activity')->getActionsByObject($offer);
   if( is_countable($action->toArray()) && engine_count($action->toArray()) <= 0 && $values['draft'] == '0' ) {
       $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $offer, 'sespageoffer_new');
                // make sure action exists before attaching the pageoffer to the activity
       if( $action != null ) {
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $offer);
      }
  }

            // Rebuild privacy
  $actionTable = Engine_Api::_()->getDbtable('actions', 'activity');
  foreach( $actionTable->getActionsByObject($offer) as $action ) {
   $actionTable->resetActivityBindings($action);
}
$db->commit();
$message['status'] = true;
$message['message'] = $this->view->translate('Your offer entry has been Edited.');

} catch( Exception $e ) {
    $db->rollBack();
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
}
Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
}

public function browseAction()
{
    $Search = isset($_POST['search']) ? $_POST['search'] : '';
    if(!empty($Search))
        $params['alphabet'] = $Search;
    $Order = isset($_POST['search_type']) ? $_POST['search_type'] : '';
    if(!empty($Order))
        $params['order'] = $Order;
    $paginator = Engine_Api::_()->getDbTable('pageoffers', 'sespageoffer')->getOffersPaginator($params);
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $result['offers'] = $this->getOffers($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
}
public function getOffers($paginator){
 $counter = 0;
 $result = array();
 $viewer = Engine_Api::_()->user()->getViewer();
 $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
 foreach ($paginator as $offer){
    $result[$counter] = $offer->toArray();
    $result[$counter]['owner_id'] = $offer->getOwner()->getIdentity();
    $result[$counter]['owner_title'] = $offer->getOwner()->getTitle();
    $result[$counter]['offer_image'] = $offer->getPhotoUrl();
    $result[$counter]['user_image'] = $offer->getOwner()->getPhotoUrl();
    $page = Engine_Api::_()->getItem('sespage_page', $offer->parent_id);
    $result[$counter]['page']['id'] = $page->getIdentity();
    $result[$counter]['page']['title'] = $page->getTitle();
    $can_fav = Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageoffer.allow.favourite', 1);
    $canFollow = Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageoffer_allow_follow', 0);     
    
    if($viewer_id)
        $result[$counter]['is_content_like'] = $likeStatus>0 ? true : false;
    if($can_fav)
        $result[$counter]['is_content_favourite'] = $favouriteStatus>0 ? true : false;
    if ($canFollow) {
     $result[$counter]['is_content_follow'] = $followStatus>0 ? true : false;
 }
 if($offer->authorization()->isAllowed($viewer, 'edit')) {
    $result[$counter]['options'][$counterOption]['name'] = 'edit'; 
    $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Edit Notes'); 
    $counterOption++;
}
if($offer->authorization()->isAllowed($viewer, 'delete')) {
    $result[$counter]['options'][$counterOption]['name'] = 'delete'; 
    $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Delete Notes'); 
    $counterOption++;
}
if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageoffer.allow.share', 1) && $viewer->getIdentity()){
    $result[$counter]['options'][$counterOption]['name'] = 'share'; 
    $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Share'); 
    $counterOption++;
}

$counter++;
}
return $result;
}

public function pageOfferAction(){
    $params = array();
    $result = array();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();
    $params['page_id']= $page_id = $this->_getParam('page_id',null);
    $params['sort'] = $this->_getParam('sort','creation_date');
    if(!$page_id)
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    $params['closed'] = 0;
    $paginator = Engine_Api::_()->getDbTable('pageoffers', 'sespageoffer')->getOffersPaginator($params);
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));
    $allowPoll  = Engine_Api::_()->authorization()->isAllowed('sespage_page', $viewer, 'offer');
    $canUpload =  Engine_Api::_()->authorization()->isAllowed('pageoffer',$viewer, 'create');
    if($allowPoll && $canUpload){
        $result['button']['label'] = $this->view->translate('Create Offer');
        $result['button']['name'] = 'create';
    }
    $sortCounter = 0;
    $result['sort'][$sortCounter]['name'] = 'creation_date';
    $result['sort'][$sortCounter]['label'] = $this->view->translate('Recently Created');
    $sortCounter++;
    $result['sort'][$sortCounter]['name'] = 'most_liked';
    $result['sort'][$sortCounter]['label'] = $this->view->translate('Most Liked');
    $sortCounter++;
    $result['sort'][$sortCounter]['name'] = 'most_viewed';
    $result['sort'][$sortCounter]['label'] = $this->view->translate('Most Viewed');
    $sortCounter++;
    $result['sort'][$sortCounter]['name'] = 'most_commented';
    $result['sort'][$sortCounter]['label'] = $this->view->translate('Most Commented');
    $sortCounter++;
    $result['offers'] = $this->getOffers($paginator);
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
}


public function viewAction(){

    if (!Engine_Api::_()->core()->hasSubject())
        $subject = Engine_Api::_()->getItem('pageoffer', $this->_getParam('id', null));
    else
        $subject = Engine_Api::_()->core()->getSubject();

    $result = array();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    $result['offer'] = $subject->toArray();
    $result['offer']['offer_image'] = $subject->getPhotoUrl();
    $counterOption = 0;
    if($subject->authorization()->isAllowed($viewer, 'edit')) {
        $result['options'][$counterOption]['name'] = 'edit'; 
        $result['options'][$counterOption]['label'] = $this->view->translate('Edit Notes'); 
        $counterOption++;
    }
    if($subject->authorization()->isAllowed($viewer, 'delete')) {
        $result['options'][$counterOption]['name'] = 'delete'; 
        $result['options'][$counterOption]['label'] = $this->view->translate('Delete Notes'); 
        $counterOption++;
    }
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageoffer.allow.share', 1) && $viewer->getIdentity()){
        $result['options'][$counterOption]['name'] = 'share'; 
        $result['options'][$counterOption]['label'] = $this->view->translate('Share'); 
        $counterOption++;
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => $result));  
}

public function likeAction(){
    if (!Engine_Api::_()->core()->hasSubject())
        $subject = Engine_Api::_()->getItem('pageoffer', $this->_getParam('id', null));
    else
        $subject = Engine_Api::_()->core()->getSubject();
    if(!$subject)
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    $type = 'pageoffer';
    $dbTable = 'pageoffers';
    $resorces_id = 'pageoffer_id';
    $notificationType = 'sespageoffer_like_offer';
    $item_id = $subject->pageoffer_id;
    if (intval($item_id) == 0) {
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $itemTable = Engine_Api::_()->getDbtable($dbTable, 'sespageoffer');
    $select = $tableLike->select()->from($tableMainLike)->where('resource_type =?', $type)->where('poster_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('poster_type =?', 'user')->where('resource_id =?', $item_id);
    $Like = $tableLike->fetchRow($select);
    $item = Engine_Api::_()->getItem('pageoffer', $item_id);
    $page = Engine_Api::_()->getItem('pageoffer', $item->pageoffer_id);
    if (!empty($Like)) {

        $db = $Like->getTable()->getAdapter();
        $db->beginTransaction();
        try {
            $Like->delete();
            $db->commit();
            $temp['data']['message'] = $this->view->translate('Offer Successfully Unliked.');
        } catch (Exception $e) {
            $db->rollBack();
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
        }
        $item = Engine_Api::_()->getItem($type, $item_id);
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
        Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => 'like_sespageoffer_offers', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
        $temp['data']['like_count'] = $item->like_count;
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
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
            $itemTable->update(array(
                'like_count' => new Zend_Db_Expr('like_count + 1'),
            ), array(
                $resorces_id . '= ?' => $item_id,
            ));
                // Commit
            $db->commit();
            $temp['data']['message'] = $this->view->translate('Offer Successfully liked.');
        } catch (Exception $e) {
            $db->rollBack();
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
        }
            //send notification and activity feed work.
        $item = Engine_Api::_()->getItem($type, $item_id);
        $subject = $item;
        $owner = $subject->getOwner();
        if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $page, $notificationType);
            $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $page, 'like_sespageoffer_offers');
            if( $action != null ) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $item);
            }
        }
        $temp['data']['like_count'] = $item->like_count;
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
    }
}
public function favouriteAction(){
   if (!Engine_Api::_()->core()->hasSubject())
    $subject = Engine_Api::_()->getItem('pageoffer', $this->_getParam('id', null));
else
    $subject = Engine_Api::_()->core()->getSubject();
if(!$subject)
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
$type = 'pageoffer';
$dbTable = 'pageoffers';
$resorces_id = 'pageoffer_id';
$notificationType = 'sespageoffer_fav_offer';
$item_id = $subject->pageoffer_id;
if (intval($item_id) == 0) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
}
$viewer = Engine_Api::_()->user()->getViewer();
$Fav = Engine_Api::_()->getDbTable('favourites', 'sespage')->getItemfav($type, $item_id);

$favItem = Engine_Api::_()->getDbtable($dbTable, 'sespageoffer');
$item = Engine_Api::_()->getItem('pageoffer', $item_id);
$page = Engine_Api::_()->getItem('pageoffer', $item->pageoffer_id);
if (!empty($Fav)) { 
            //delete
    $db = $Fav->getTable()->getAdapter();
    $db->beginTransaction();
    try {
        $Fav->delete();
        $db->commit();
        $temp['data']['message'] = 'Offer Successfully Unfavourited.';
    } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
    }
    $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count - 1')), array($resorces_id . ' = ?' => $item_id));
    $item = Engine_Api::_()->getItem($type, $item_id);
    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
    Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => 'favourite_sespageoffer_offers', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
    Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($item);
    $temp['data']['favourite_count'] = $item->favourite_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
} else {
            //update
    $db = Engine_Api::_()->getDbTable('favourites', 'sespage')->getAdapter();
    $db->beginTransaction();
    try {
        $fav = Engine_Api::_()->getDbTable('favourites', 'sespage')->createRow();
        $fav->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $fav->resource_type = $type;
        $fav->resource_id = $item_id;
        $fav->save();
        $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count + 1'),
    ), array(
        $resorces_id . '= ?' => $item_id,
    ));
                // Commit
        $db->commit();
        $temp['data']['message'] = 'Offer Successfully favourited.';
    } catch (Exception $e) { 
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
    }
            //send notification and activity feed work.
    $item = Engine_Api::_()->getItem(@$type, @$item_id);
    if ($this->_getParam('type') != 'sespagenote_artist') {
        $subject = $item;
        $owner = $subject->getOwner();
        if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
            $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $page, $notificationType);
            $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $page, 'favourite_sespageoffer_offers');
            if( $action != null ) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $item);
            }
        }
    }
    $temp['data']['favourite_count'] = $item->favourite_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
}
}

public function followsAction(){
   if (!Engine_Api::_()->core()->hasSubject())
    $subject = Engine_Api::_()->getItem('pageoffer', $this->_getParam('id', null));
else
    $subject = Engine_Api::_()->core()->getSubject();

if(!$subject)
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
$type = 'pageoffer';
$dbTable = 'pageoffers';
$resorces_id = 'pageoffer_id';
$notificationType = 'sespageoffer_follow_offer';
$item_id = $subject->pageoffer_id;
if (intval($item_id) == 0) { 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
}
$viewer = Engine_Api::_()->user()->getViewer();
$Fav = Engine_Api::_()->getDbTable('followers', 'sespage')->getItemFollower($type, $item_id);


$favItem = Engine_Api::_()->getDbtable($dbTable, 'sespageoffer');
$item = Engine_Api::_()->getItem('pageoffer', $item_id);
$page = Engine_Api::_()->getItem('pageoffer', $item->pageoffer_id);
if (!empty($Fav)) { 
            //delete
    $db = $Fav->getTable()->getAdapter();
    $db->beginTransaction();
    try { 
        $Fav->delete();
        $db->commit();
        $temp['data']['message'] = 'Offer Successfully follow.';
    } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
    }
    $favItem->update(array('follow_count' => new Zend_Db_Expr('follow_count - 1')), array($resorces_id . ' = ?' => $item_id));
    $item = Engine_Api::_()->getItem($type, $item_id);
    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
    Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => 'follow_sespageoffer_offers', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
    Engine_Api::_()->getDbtable('actions', 'activity')->detachFromActivity($item);
    $temp['data']['follow_count'] = $item->follow_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
} else { 
            //update
    $db = Engine_Api::_()->getDbTable('followers', 'sespage')->getAdapter();
    $db->beginTransaction();
    try {
        $fav = Engine_Api::_()->getDbTable('followers', 'sespage')->createRow();
        $fav->owner_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $fav->resource_type = $type;
        $fav->resource_id = $item_id;
        $fav->save();
        $favItem->update(array('follow_count' => new Zend_Db_Expr('follow_count + 1'),
    ), array(
        $resorces_id . '= ?' => $item_id,
    ));
                // Commit
        $db->commit();
        $temp['data']['message'] = 'Offer Successfully follow.';
    } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
    }
            //send notification and activity feed work.
    $item = Engine_Api::_()->getItem(@$type, @$item_id);
    if ($this->_getParam('type') != 'sespagenote_artist') {
        $subject = $item;
        $owner = $subject->getOwner();
        if ($owner->getType() == 'user' && $owner->getIdentity() != $viewer->getIdentity()) {
            $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
            Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $page, $notificationType);
            $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $page, 'follow_sespageoffer_offers');
            if( $action != null ) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $item);
            }
        }
    }
    $temp['data']['follow_count'] = $item->follow_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
}
}


}
