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

 class Sespage_NoteController extends Sesapi_Controller_Action_Standard
 {
    public function init() {
        $id = $this->_getParam('pagenote_id', $this->_getParam('pagenote_id', null));
        if ($id && intval($id)) {
            $note = Engine_Api::_()->getItem('pagenote', $id);
            if ($note) {
                Engine_Api::_()->core()->setSubject($note);
            }
        }
    }

    public function browseAction()
    {
        $isSearch = isset($_POST['search']) ? $_POST['search'] : '';
        $form = new Sespagenote_Form_Search(array('searchTitle'=>$this->_getParam('search_title')));
        if(!empty($isSearch))
            $params['search'] = $isSearch;
        $params['sort'] = $this->_getParam('sort');

        $paginator = Engine_Api::_()->getDbTable('pagenotes', 'sespagenote')->getNotesPaginator($params);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $result['notes'] = $this->getNotes($paginator);
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
    }
    public function getNotes($paginator){
        $counter = 0;
        $result = array();
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        foreach ($paginator as $note){
            $notes = $note->toArray();
            $result[$counter] = $notes;
            $owner = $note->getOwner();
            $page = Engine_Api::_()->getItem('sespage_page', $note->parent_id);
            $result[$counter]['owner_title'] = $note->getOwner()->getTitle();
            $result[$counter]['owner_image'] =  $note->getOwner()->getPhotoUrl();  

            $result[$counter]['note']['id'] = $page->getIdentity();
            $result[$counter]['note']['title'] = $page->getTitle();
            $result[$counter]['note']['image']= $note->getPhotoUrl();


            $likeStatus = Engine_Api::_()->sespage()->getLikeStatus($note->pagenote_id,'sespagenote_pagenotes');
            $can_fav = Engine_Api::_()->getApi('settings', 'core')->getSetting('sespagenote.allow.favourite', 1);
            $favouriteStatus = Engine_Api::_()->getDbTable('favourites', 'sespagepoll')->isFavourite(array('resource_id' => $note->pagenote_id,'resource_type' => 'sespagenote_pagenotes'));
            if($user){
                $ownerimage = Engine_Api::_()->sesapi()->getPhotoUrls($user, "", "");
                $result[$counter]['owner_image'] = $ownerimage;
            }
            $page = Engine_Api::_()->getItem('sespage_page', $page_id);
            if($page)
                $result[$counter]['content_title'] = $page->title;
            if($viewer_id)
                $result[$counter]['is_content_like'] = $likeStatus>0 ? true : false;
            if($can_fav)
                $result[$counter]['is_content_favourite'] = $favouriteStatus>0 ? true : false;
            $counterOption = 0;
            if($note->authorization()->isAllowed($viewer, 'edit')) {
                $result[$counter]['options'][$counterOption]['name'] = 'edit'; 
                $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Edit Notes'); 
                $counterOption++;
            }
            if($note->authorization()->isAllowed($viewer, 'delete')) {
                $result[$counter]['options'][$counterOption]['name'] = 'delete'; 
                $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Delete Notes'); 
                $counterOption++;
            }
            if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespagenote.allow.share', 1) && $viewer->getIdentity()){
                $result[$counter]['options'][$counterOption]['name'] = 'share'; 
                $result[$counter]['options'][$counterOption]['label'] = $this->view->translate('Share'); 
                $counterOption++;
            }
            $counter++;

        }
        return $result;
    }
    public function pageviewAction(){

        $params = array();
        $result = array();
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = $viewer->getIdentity();
        $params['page_id']= $page_id = $this->_getParam('page_id',null);
        $params['sort'] = $this->_getParam('sort','creation_date');
        if(!$page_id)
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
        $params['closed'] = 0;
        $paginator = Engine_Api::_()->getDbTable('pagenotes', 'sespagenote')->getNotesPaginator($params);
        $paginator->setItemCountPerPage($this->_getParam('limit', 10));
        $paginator->setCurrentPageNumber($this->_getParam('page', 1));
        $allowPoll  = Engine_Api::_()->authorization()->isAllowed('sespage_page', $viewer, 'note');
        $canUpload =  Engine_Api::_()->authorization()->isAllowed('sespagepoll_poll',$viewer, 'create');
        if($allowPoll && $canUpload){
            $result['button']['label'] = $this->view->translate('Post New Note');
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
        $result['polls'] = $this->getNotes($paginator);
        $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
        $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
        $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
        $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
    }
    public function viewAction(){

        if (!Engine_Api::_()->core()->hasSubject())
            $subject = Engine_Api::_()->getItem('pagenote', $this->_getParam('id', null));
        else
            $subject = Engine_Api::_()->core()->getSubject();

        $result = array();
        $viewer = Engine_Api::_()->user()->getViewer();
        $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
        $result['note'] = $subject->toArray();
        
        $page = Engine_Api::_()->getItem('sespage_page',  $subject->parent_id);
        $result['note']['owner_title'] = $subject->getOwner()->getTitle();
        $result['note']['note_image'] = $subject->getPhotoUrl();
        $result['note']['owner_image'] =  $subject->getOwner()->getPhotoUrl();  
        $result['note']['title'] = $page->getTitle();

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
    public function deleteAction(){
        $viewer = Engine_Api::_()->user()->getViewer();
        $data = array();

        if (!Engine_Api::_()->core()->hasSubject())
            $note = Engine_Api::_()->getItem('pagenote', $this->_getParam('id', null));
        else
            $note = Engine_Api::_()->core()->getSubject();
        if(!$note)
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
        $can_delete = $note->authorization()->isAllowed($viewer, 'delete');
        if(!$can_delete)
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
        if (!$this->getRequest()->isPost()) {
            $data['status'] = false;
            $data['message'] = $this->view->translate('Invalid request method');
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$data['status'], 'result' => $data));
        }
        $db = $note->getTable()->getAdapter();
        $db->beginTransaction();

        try {
          $note->delete();
          $db->commit();
          $data['status'] = true;
          $data['message'] = $this->view->translate('Note has been deleted succuessfully.');
      } catch( Exception $e ) {
          $db->rollBack();
          Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' =>array()));
      }
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $data));
  }
  public function searchAction(){
   $view_type = $this->_getParam('view_type', 'horizontal');
   $defaultProfileId = 1;
   $search_for = $search_for = $this->_getParam('search_for', 'page');
   $coreContentTable = Engine_Api::_()->getDbTable('content', 'core');
   $coreContentTableName = $coreContentTable->info('name');
   $corePagesTable = Engine_Api::_()->getDbTable('pages', 'core');
   $corePagesTableName = $corePagesTable->info('name');
   $select = $corePagesTable->select()
   ->setIntegrityCheck(false)
   ->from($corePagesTable, null)
   ->where($coreContentTableName . '.name=?', 'sespage.browse-search')
   ->joinLeft($coreContentTableName, $corePagesTableName . '.page_id = ' . $coreContentTableName . '.page_id', $coreContentTableName . '.content_id')
   ->where($corePagesTableName . '.name = ?', 'sespage_index_browse');
   $id = $select->query()->fetchColumn();

   $form = new Sespagenote_Form_Search(array('contentId' => $id));
   $request = Zend_Controller_Front::getInstance()->getRequest();
   $form->setMethod('get')->populate($request->getParams());

   $form->removeElement('cancel');
   if ($this->_getParam('getForm')) {        
    $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields,array('resources_type'=>'sespagenote'));

}
else {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
}
}
public function likeAction(){
    if (!Engine_Api::_()->core()->hasSubject())
        $subject = Engine_Api::_()->getItem('pagenote', $this->_getParam('id', null));
    else
        $subject = Engine_Api::_()->core()->getSubject();
    if(!$subject)
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
    $type = 'pagenote';
    $dbTable = 'pagenotes';
    $resorces_id = 'pagenote_id';
    $notificationType = 'sespagenote_like_note';
    $item_id = $subject->pagenote_id;
    if (intval($item_id) == 0) {
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
    }
    $viewer = Engine_Api::_()->user()->getViewer();
    $tableLike = Engine_Api::_()->getDbtable('likes', 'core');
    $tableMainLike = $tableLike->info('name');
    $itemTable = Engine_Api::_()->getDbtable($dbTable, 'sespagenote');
    $select = $tableLike->select()->from($tableMainLike)->where('resource_type =?', $type)->where('poster_id =?', Engine_Api::_()->user()->getViewer()->getIdentity())->where('poster_type =?', 'user')->where('resource_id =?', $item_id);
    $Like = $tableLike->fetchRow($select);
    $item = Engine_Api::_()->getItem('pagenote', $item_id);
    $page = Engine_Api::_()->getItem('pagenote', $item->pagenote_id);
    if (!empty($Like)) {

        $db = $Like->getTable()->getAdapter();
        $db->beginTransaction();
        try {
            $Like->delete();
            $db->commit();
            $temp['data']['message'] = $this->view->translate('Note Successfully Unliked.');
        } catch (Exception $e) {
            $db->rollBack();
            Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
        }
        $item = Engine_Api::_()->getItem($type, $item_id);
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
        Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => 'like_sespagnote_notes', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
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
            $temp['data']['message'] = $this->view->translate('Note Successfully liked.');
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
            $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $page, 'like_sespagnote_notes');
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
    $subject = Engine_Api::_()->getItem('pagenote', $this->_getParam('id', null));
else
    $subject = Engine_Api::_()->core()->getSubject();

if(!$subject)
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
$type = 'pagenote';
$dbTable = 'pagenotes';
$resorces_id = 'pagenote_id';
$notificationType = 'sespagenote_like_note';
$item_id = $subject->pagenote_id;
if (intval($item_id) == 0) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid argument supplied.'), 'result' => array()));
}
$viewer = Engine_Api::_()->user()->getViewer();
$Fav = Engine_Api::_()->getDbTable('favourites', 'sespage')->getItemfav($type, $item_id);

$favItem = Engine_Api::_()->getDbtable($dbTable, 'sespagenote');
$item = Engine_Api::_()->getItem('pagenote', $item_id);
$page = Engine_Api::_()->getItem('pagenote', $item->pagenote_id);
if (!empty($Fav)) {
            //delete
    $db = $Fav->getTable()->getAdapter();
    $db->beginTransaction();
    try {
        $Fav->delete();
        $db->commit();
        $temp['data']['message'] = 'Note Successfully Unfavourited.';
    } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' => array()));
    }
    $favItem->update(array('favourite_count' => new Zend_Db_Expr('favourite_count - 1')), array($resorces_id . ' = ?' => $item_id));
    $item = Engine_Api::_()->getItem($type, $item_id);
    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => $notificationType, "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
    Engine_Api::_()->getDbtable('actions', 'activity')->delete(array('type =?' => 'favourite_sespagnote_notes', "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $page->getType(), "object_id = ?" => $page->getIdentity()));
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
        $temp['data']['message'] = 'Note Successfully favourited.';
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
            $action = Engine_Api::_()->getDbTable('actions', 'activity')->addActivity($viewer, $page, 'favourite_sespagnote_notes');
            if( $action != null ) {
                Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $item);
            }
        }
    }
    $temp['data']['favourite_count'] = $item->favourite_count;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
}
}
public function createAction(){
  if (!$this->_helper->requireUser->isValid())
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));


  if( !$this->_helper->requireAuth()->setAuthParams('pagenote', null, 'create')->isValid()) return;
  $parent_id = $this->_getParam('parent_id', null);
  if(empty($parent_id))
     Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));

 $this->view->parentItem = $parentItem = Engine_Api::_()->getItem('sespage_page', $parent_id);

 $getPageRolePermission = Engine_Api::_()->sespage()->getPageRolePermission($parentItem->getIdentity(),'post_content','note',false);

 $allowed = true;
 $canUpload = $getPageRolePermission ? $getPageRolePermission : $parentItem->authorization()->isAllowed($viewer, 'note');
 $canUpload = !$allowed ? false : $canUpload;
 if(!$canUpload)
     Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));

 

 $viewer = Engine_Api::_()->user()->getViewer();
 $values['user_id'] = $viewer->getIdentity();
 $values['parent_id'] = $parent_id;
 $form = new Sespagenote_Form_Create();


 if ($this->_getParam('getForm')) {
    $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields);
}
if (!$this->getRequest()->isPost()) { 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
}
        //is post
if (!$form->isValid($this->getRequest()->getPost())) { 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('validation_error'), 'result' => array()));
}
$table = Engine_Api::_()->getItemTable('pagenote');
$db = $table->getAdapter();
$db->beginTransaction();

try {
        // Create pagenote
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

    $note = $table->createRow();
    $note->setFromArray($values);
    $note->save();

    if( !empty($values['photo']) ) {
        $note->setPhoto($form->photo);
    }

        // Auth
    $auth = Engine_Api::_()->authorization()->context;
    $roles = array('owner', 'owner_member', 'owner_member_member', 'owner_network', 'registered', 'everyone');

    $viewMax = array_search($formValues['auth_view'], $roles);
    $commentMax = array_search($formValues['auth_comment'], $roles);

    foreach( $roles as $i => $role ) {
        $auth->setAllowed($note, $role, 'view', ($i <= $viewMax));
        $auth->setAllowed($note, $role, 'comment', ($i <= $commentMax));
    }

        // Add tags
    $tags = preg_split('/[,]+/', $values['tags']);
    $note->tags()->addTagMaps($viewer, $tags);

        // Add activity only if pagenote is published
    if( $values['draft'] == 0 ) {
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $parentItem, 'sespagenote_new');
        if( $action ) {
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $note);
        }
    }
    $db->commit();
} catch( Exception $e ) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
}
}
public function editAction(){
    $viewer = Engine_Api::_()->user()->getViewer();
    $pagenote_id = $this->_getParam('pagenote_id');
    $user_id = $this->_getParam('user_id'); 

    if (Engine_Api::_()->core()->hasSubject()){
        $note = Engine_Api::_()->core()->getSubject();
    }else{
        $note= Engine_Api::_()->getItem('pagenote',$pagenote_id);
        Engine_Api::_()->core()->setSubject($note);
    }

    if(empty($note)){
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Data not found.'), 'result' => array()));
    }

    $form = new Sespagenote_Form_Edit(array('fromApi'=>true));
    $form->populate($note->toArray());


    if( !$this->getRequest()->isPost() ) {
        $this->view->status = false;
        $this->view->error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid.'), 'result' => array()));
    }

    if($this->_getParam('getForm')) {
        $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
        $this->generateFormFields($formFields,array('resources_type'=>'sespagenote'));
    } 
    if( !$form->isValid($_POST) ) {
        $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
        if(is_countable($validateFields) && engine_count($validateFields))
            $this->validateFormFields($validateFields);
    }

    $values = $form->getValues();

    $db = Engine_Api::_()->getDbTable('pagenotes', 'sespagenote')->getAdapter();
    $db->beginTransaction();
    try {  
        $note = Engine_Api::_()->getItem('pagenote',$pagenote_id);
        $note->setFromArray($values);
        $note->save();
        $db->commit();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('pagenote_id' => $note->getIdentity(),'message' => $this->view->translate('You have successfully edit the Note.'))));
    } catch (Exception $e) { 
        $db->rollBack();
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage()));
    }
}

}
