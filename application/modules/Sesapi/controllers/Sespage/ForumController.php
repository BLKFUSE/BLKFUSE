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

 class Sespage_ForumController extends Sesapi_Controller_Action_Standard
 {  
 	public function init() {
 		if( 0 !== ($topic_id = (int) $this->_getParam('topic_id')) &&
 			null !== ($topic = Engine_Api::_()->getItem('sespageforum_topic', $topic_id)) &&
 			$topic instanceof Sespageforum_Model_Topic ) {
 			Engine_Api::_()->core()->setSubject($topic);
 	}
 }
 public function postCreateAction()
 { 
   if( !$this->_helper->requireUser()->isValid() ) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
    return;
  }
  $topic_id = $this->_getParam('topic_id', null);

  $topic = Engine_Api::_()->getItem('sespageforum_topic', $topic_id);
  
  if(!$topic) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }
  $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
  $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');
  $this->view->page = Engine_Api::_()->getItem('sespage_page', $topic->page_id);
  if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'post_create')->isValid() ) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }
  if($topic->closed ) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }

  $this->view->form = $form = new Sespageforum_Form_Post_Create();

    // Remove the file element if there is no file being posted
  if( $this->getRequest()->isPost() && empty($_FILES['photo']) ) {
    $form->removeElement('photo');
  }

  $allowHtml = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum_html', 0);

  $allowBbcode = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum_bbcode', 0);

  $quote_id = $this->getRequest()->getParam('quote_id');
  if( !empty($quote_id) ) {
    $quote = Engine_Api::_()->getItem('sespageforum_post', $quote_id);
    if($quote->user_id == 0) {
      $owner_name = Zend_Registry::get('Zend_Translate')->_('Deleted Member');
    } else {
      $owner_name = $quote->getOwner()->__toString();
    }
    if ( !$allowHtml && !$allowBbcode ) {
      $form->body->setValue( strip_tags($this->view->translate('%1$s said:', $owner_name)) . " ''" . strip_tags($quote->body) . "''\n-------------\n" );
    } elseif( $allowHtml ) {
      $form->body->setValue("<blockquote><strong>" . $this->view->translate('%1$s said:', $owner_name) . "</strong><br />" . $quote->body . "</blockquote><br />");
    } else {
      $form->body->setValue("[quote][b]" . strip_tags($this->view->translate('%1$s said:', $owner_name)) . "[/b]\r\n" . htmlspecialchars_decode($quote->body, ENT_COMPAT) . "[/quote]\r\n");
    }
  }
  if($this->_getParam('getForm')) {
    $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields);
  }

  if( !$this->getRequest()->isPost() ) {
    return;
  }

  if( !$form->isValid($this->getRequest()->getPost()) ) {
    $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
    if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
  }

    // Process
  $values = $form->getValues();
  $values['body'] = Engine_Text_BBCode::prepare($values['body']);
  $values['user_id'] = $viewer->getIdentity();
  $values['topic_id'] = $topic->getIdentity();
    //$values['forum_id'] = $sespageforum->getIdentity();

  $topicTable = Engine_Api::_()->getDbtable('topics', 'sespageforum');
  $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sespageforum');
  $postTable = Engine_Api::_()->getDbtable('posts', 'sespageforum');
  $userTable = Engine_Api::_()->getItemTable('user');
  $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
  $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');

  $viewer = Engine_Api::_()->user()->getViewer();
  $topicOwner = $topic->getOwner();
  $isOwnTopic = $viewer->isSelf($topicOwner);

  $watch = 1;
  $isWatching = $topicWatchesTable
  ->select()
  ->from($topicWatchesTable->info('name'), 'watch')
      //->where('resource_id = ?', $sespageforum->getIdentity())
  ->where('topic_id = ?', $topic->getIdentity())
  ->where('user_id = ?', $viewer->getIdentity())
  ->limit(1)
  ->query()
  ->fetchColumn(0)
  ;

  $db = $postTable->getAdapter();
  $db->beginTransaction();

  try {

    $post = $postTable->createRow();
    $post->setFromArray($values);
    $post->save();

    if( !empty($values['photo']) ) {
      try {
        $post->setPhoto($form->photo);
      } catch( Engine_Image_Adapter_Exception $e ) {}
    }

      // Watch
    if( false === $isWatching ) {
      $topicWatchesTable->insert(array(
          //'resource_id' => $sespageforum->getIdentity(),
        'topic_id' => $topic->getIdentity(),
        'user_id' => $viewer->getIdentity(),
        'watch' => (bool) $watch,
      ));
    } else if( $watch != $isWatching ) {
      $topicWatchesTable->update(array(
        'watch' => (bool) $watch,
      ), array(
          //'resource_id = ?' => $sespageforum->getIdentity(),
        'topic_id = ?' => $topic->getIdentity(),
        'user_id = ?' => $viewer->getIdentity(),
      ));
    }
    $topicLink = '<a href="' . $topic->getHref() . '">' . $topic->getTitle() . '</a>';
      // Activity
    $action = $activityApi->addActivity($viewer, $topic, 'sespageforum_topic_reply',null,  array("topictitle" => $topicLink));
    if( $action ) {
      $action->attach($post, $topic);
    }

      // Notifications
    $notifyUserIds = $topicWatchesTable->select()
    ->from($topicWatchesTable->info('name'), 'user_id')
        //->where('resource_id = ?', $sespageforum->getIdentity())
    ->where('topic_id = ?', $topic->getIdentity())
    ->where('watch = ?', 1)
    ->query()
    ->fetchAll(Zend_Db::FETCH_COLUMN);

    foreach( $userTable->find($notifyUserIds) as $notifyUser ) {
        // Don't notify self
      if( $notifyUser->isSelf($viewer) ) {
        continue;
      }
      if($notifyUser->isSelf($topicOwner) ) {
        $type = 'sespageforum_topic_response';
      } else {
        $type = 'sespageforum_topic_reply';
      }
      
      $notifyApi->addNotification($notifyUser, $viewer, $topic, $type, array(
        'message' => $this->view->BBCode($post->body),
        'postGuid' => $post->getGuid(),
      ));
    }

    $db->commit();
    if(empty($topic_id) && empty($quote_id)) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('topic_id' => $topic->getIdentity(),'success_message' => $this->view->translate('Topic created successfully.'))));
    } elseif(empty($quote_id)) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('topic_id' => $topic->getIdentity(),'success_message' => $this->view->translate('Reply posted successfully.'))));
    } elseif(!empty($quote_id)) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('topic_id' => $topic->getIdentity(),'success_message' => $this->view->translate('Quote successfully.'))));
    }
  } catch( Exception $e ) {
    $db->rollBack();
      //throw $e;
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
  }

    //return $this->_redirectCustom($post);
}

public function browseAction()
{

  $this->view->page_id = $page_id = $_POST['page_id'];
  $this->view->search = $_POST['search'];

  $this->view->sespage = $sespage = Engine_Api::_()->getItem('sespage_page', $page_id);
  $show_criterias = isset($params['show_criteria']) ? $params['show_criteria'] : $this->_getParam('show_criteria', array('ownerName', 'ownerPhoto', 'likeCount',"ratings","showDatetime","viewCount","replyCount","latestPostDetails","postTopicButton","title","tags"));
  if(is_array($show_criterias)){
    foreach ($show_criterias as $show_criteria)
      $this->view->{$show_criteria} = $show_criteria;
  }
  switch($this->_getParam('sort', 'recent')) {
    case 'popular':
    $order = 'view_count';
    break;
    case 'recent':
    default:
    $order = 'modified_date';
    break;
  }

  $table = Engine_Api::_()->getItemTable('sespageforum_topic');
  $select = $table->select()
  ->where('page_id = ?', $sespage->getIdentity())
  ->order('sticky DESC')
  ->order($order . ' DESC');

  if ($this->_getParam('search', false)) {
    $select->where('title LIKE ? OR description LIKE ?', '%'.$this->_getParam('search').'%');
  }
  $this->view->paginator = $paginator = Zend_Paginator::factory($select);
  $paginator->setItemCountPerPage($this->_getParam('limit', 10));
  $paginator->setCurrentPageNumber($this->_getParam('page', 1));

  $result['topics'] = $this->getTopics($paginator);
  $result['button']['label'] = $this->view->translate('Post New Note');
  $result['button']['name'] = 'create';

  $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
  $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
  $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
  $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
  Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
}


public function viewAction()
{

  $this->view->topic_id = $topic_id = $_POST['topic_id'];
  $table = Engine_Api::_()->getItemTable('sespageforum_topic');
  $select = $table->select()
  ->where('topic_id = ?', $topic_id)
  ->order('sticky DESC');


  $this->view->paginator = $paginator = Zend_Paginator::factory($select);
  $paginator->setItemCountPerPage($this->_getParam('limit', 10));
  $paginator->setCurrentPageNumber($this->_getParam('page', 1));

  $result['topics'] = $this->getTopics($paginator);
  $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
  $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
  $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
  $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
  Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
}

public function getTopics($paginator){ 

  $counter = 0;
  $result = array();
  $viewer = Engine_Api::_()->user()->getViewer();
  $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
  foreach($paginator as $topics){ 
    $topic = $topics->toArray();
    $result = $topic;
    $owner = $topics->getOwner();

    $result['id'] = $owner->getIdentity();
    $result['owner_title'] = $owner->getTitle();
    $result['owner_image'] =  $owner->getPhotoUrl();  
    
    $result['resource_type'] = $topics->getType();
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.thanks', 1)) {
      $isThank = Engine_Api::_()->getDbTable('thanks', 'sespageforum')->isThank($topics, $viewer);
      if (empty($isThank) && !empty($viewer_id) && $viewer_id != $topics->user_id) {
        $result['isThanks'] = true;
      } else {
        $result['isThanks'] = false;
      }
    }
    
    $canLike = 1;
    $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topics, $viewer);
    if ($canLike && !empty($viewer_id)) {
      if(empty($isLike)) {
        $result['is_content_like'] = false;
      } else {
        $result['is_content_like'] = true;
      }
    }
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.reputation', 1)) {
      $getIncreaseReputation = Engine_Api::_()->getDbTable('reputations', 'sespageforum')->getIncreaseReputation(array('user_id' => $topics->user_id));
      $getDecreaseReputation = Engine_Api::_()->getDbTable('reputations', 'sespageforum')->getDecreaseReputation(array('user_id' => $topics->user_id));
      $result['reputations'] = $this->view->translate("%s - %s", $getIncreaseReputation, $getDecreaseReputation);
    }

   /*  $signature = $topics->getSignature();
     print_r($signature);die;
      if($signature) {
        $result[$counter]['post_count'] = $signature->post_count;
      }*/
      $result["share"]["url"] = $this->getBaseUrl(false,$topics->getHref());
      $result["share"]["title"] = $topics->getTitle();
      $result["share"]["description"] = strip_tags($topics->getDescription());
      $result["share"]["setting"] = $shareType;
      $result["share"]['urlParams'] = array(
        "type" => $topics->getType(),
        "id" => $topics->getIdentity()
      );

      $canPost = false;
      $canEdit = false;
      $canDelete = false;
      if( !$topics->closed && Engine_Api::_()->authorization()->isAllowed($sespageforum, $levelId, 'post_create') ) { die('d');
      $canPost = true;
    }
    if( Engine_Api::_()->authorization()->isAllowed($sespageforum, $levelId, 'topics_edit') ) {
      $canEdit = true;
    }
    if( Engine_Api::_()->authorization()->isAllowed($sespageforum, $levelId, 'topics_delete') ) {
      $canDelete = true;
    }
    
    $canEdit_Post = false;
    $canDelete_Post = false;
    if($viewer->getIdentity()){
      $canEdit_Post = Engine_Api::_()->authorization()->isAllowed('sespageforum', $viewer->level_id, 'post_edit');
      $canDelete_Post = Engine_Api::_()->authorization()->isAllowed('sespageforum', $viewer->level_id, 'post_delete');
    }

    if($topics->closed) {
      $canPost = 0;
    }
    $result['canPost'] = true;
    $menuoptions = $options = array();
    $counter = $option_counter = 0;
    if($canPost) { 
      $menuoptions[$counter]['name'] = "quote";
      $menuoptions[$counter]['label'] = $this->view->translate("Quote");
      $counter++;
    }
    
       // if(!empty($viewer->getIdentity())) {
    $canLike = 1;
    $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topics, $viewer);
    
    if ($canLike && !empty($viewer_id)) {
      if(empty($isLike)) {
        $menuoptions[$counter]['name'] = "like";
        $menuoptions[$counter]['label'] = $this->view->translate("Like");
        $counter++;
      } else {
        $menuoptions[$counter]['name'] = "unlike";
        $menuoptions[$counter]['label'] = $this->view->translate("Unlike");
        $counter++;
      }
    }

    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.thanks', 1)) {
      $isThank = Engine_Api::_()->getDbTable('thanks', 'sespageforum')->isThank($topics, $viewer);
      
      if (empty($isThank) && !empty($viewer_id)) {
        $menuoptions[$counter]['name'] = "thanks";
        $menuoptions[$counter]['label'] = $this->view->translate("Say Thank");
        $menuoptions[$counter]['isThanks'] = true;
        $counter++;
      } else {
        $menuoptions[$counter]['isThanks'] = false;
        $counter++;
      }
    }
    if($topics != $viewer->getIdentity() ) {
      $options[$option_counter]['name'] = "report";
      $options[$option_counter]['label'] = $this->view->translate("Report");
      $option_counter++;
    }
    $isReputation = Engine_Api::_()->getDbTable('reputations', 'sespageforum')->isReputation(array('post_id' => $topics->getIdentity(), 'resource_id' => $topics->user_id));
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.reputation', 1) && empty($isReputation) && $viewer_id != $topics->user_id) {
      $options[$option_counter]['name'] = "reputation";
      $options[$option_counter]['label'] = $this->view->translate("Add Reputation");
      $option_counter++;
      
    }
    $canEdit = Engine_Api::_()->authorization()->getPermission($viewer, 'sespageforum', 'topic_edit');
    if( $canEdit ) {
      $options[$option_counter]['name'] = "edit";
      $options[$option_counter]['label'] = $this->view->translate("Edit");
      $option_counter++;
      $options[$option_counter]['name'] = "delete";
      $options[$option_counter]['label'] = $this->view->translate("Delete");
      $option_counter++;
    }
    
    $topicContent['topic_title'] = $topics->getTitle();
    $topicContent['topic_id'] = $topics->getIdentity();
    $topicContent['can_rate'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.rating', 1) ? true : false;
    $topicContent['rating'] = $topics->rating;
    $topicContent['rating_count'] = Engine_Api::_()->sesforum()->ratingCount($topics->getIdentity());
    $topicContent['back_to_topics'] = $this->view->translate("Back to Topics");
    if( $canPost && !$topics->closed) {
      $topicContent['post_reply'] = $this->view->translate("Post Reply");
    }

    $isWatching = null;
    if( $viewer->getIdentity() ) {
      $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sespageforum');
      $isWatching = $topicWatchesTable
      ->select()
      ->from($topicWatchesTable->info('name'), 'watch')
      ->where('topic_id = ?', $topics->getIdentity())
      ->where('user_id = ?', $viewer->getIdentity())
      ->limit(1)
      ->query()
      ->fetchColumn(0)
      ;
      if( false === $isWatching ) {
        $isWatching = null;
      } else {
        $isWatching = (bool) $isWatching;
      }
    }
    
    $topicContent['can_subscribe'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.subscribe', 1);
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.subscribe', 1)) { 
      if( $viewer->getIdentity() ) {
        //$isSubscribe = Engine_Api::_()->getDbTable('subscribes', 'sesforum')->isSubscribe(array('resource_id' => $topic->getIdentity()));
        if( !$isWatching ) {
          $topicContent['subscribe'] = $this->view->translate("Subscribe");
          $topicContent['watch'] = 1;
        } else {
          $topicContent['unsubscribe'] = $this->view->translate("Unsubscribe");
          $topicContent['watch'] = 0;
        }
      }
    }
    if($viewer_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sespageforum.rating', 1)) {
      $topicContent['is_rated'] = $rated;
    }
    $topicContent['like_count'] = $topics->like_count;
    if( $viewer->getIdentity() ) {
      $canLike = 1;
      $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topics, $viewer);
      if ($canLike && !empty($viewer_id)) {
        if(empty($isLike)) {
          $topicContent['is_content_like'] = false;
        } else {
          $topicContent['is_content_like'] = true;
        }
      }
    }

    $tags = array();
    foreach ($topics->tags()->getTagMaps() as $tagmap) {
      $arrayTag = $tagmap->toArray();
      if(!$tagmap->getTag())
        continue;
      $tags[] = array_merge($tagmap->toArray(), array(
        'id' => $tagmap->getIdentity(),
        'text' => $tagmap->getTitle(),
        'href' => $tagmap->getHref(),
        'guid' => $tagmap->tag_type . '_' . $tagmap->tag_id
      ));
    }

    if (is_countable($tags) && engine_count($tags)) {
      $topicContent['tag'] = $tags;
    }
    
    if( !$topic->sticky ) {
      $topicContent['sticky'] = true;
    } else {
      $topicContent['sticky'] = false;
    }
    if( !$topic->closed ) {
      $topicContent['close'] = true;
    } else {
      $topicContent['close'] = false;
    }
    $topicContent["share"]["url"] = $this->getBaseUrl(false,$topics->getHref());
    $topicContent["share"]["title"] = $topics->getTitle();
    $topicContent["share"]["description"] = strip_tags($topics->getDescription());
    $topicContent["share"]["setting"] = $shareType;
    $topicContent["share"]['urlParams'] = array(
      "type" => $topics->getType(),
      "id" => $topics->getIdentity(),
    );
    
    if( $viewer->getIdentity() ) {
      $canLike = 1;
      $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topics, $viewer);
      
      if ($canLike && !empty($viewer_id)) {
        $topic_menuoptions = array();
        $topic_counter = 0;
        if(empty($isLike)) {
          $topic_menuoptions[$topic_counter]['name'] = "like";
          $topic_menuoptions[$topic_counter]['label'] = $this->view->translate("Like");
          $topic_counter++;
        } else {
          $topic_menuoptions[$topic_counter]['name'] = "unlike";
          $topic_menuoptions[$topic_counter]['label'] = $this->view->translate("Unlike");
          $topic_counter++;
        }
      }
      $topic_menuoptions[$topic_counter]['name'] = "share";
      $topic_menuoptions[$topic_counter]['label'] = $this->view->translate("Share");
      $topic_counter++;


      $topicContent['buttons'] = $topic_menuoptions;
    }

    $canEdit = false;
    $canDelete = false;

    $canEditPerminsion = Engine_Api::_()->sesforum()->isAllowed('sespageforum',$levelId, 'topic_edit');
    if($canEditPerminsion) {
      $canEdit = $canEditPerminsion->value;
    }
    // echo $canEdit;
    $canDeletePerminsion = Engine_Api::_()->sesforum()->isAllowed('sespageforum',$levelId, 'topic_delete');
    if($canDeletePerminsion) {
      $canDelete = $canDeletePerminsion->value;
    }
    if( ($canEdit || $canDelete) && ($viewer_id == $topics->user_id || $viewer->level_id == '1') || (($canEdit == 2) || ($canDelete == 2))) {
      if($can_edit) {
        $topicContent['canEdit'] = true;
      } else {
        $topicContent['canEdit'] = false;
      }
      if($can_delete) {
        $topicContent['canDelete'] = true;
      } else {
        $topicContent['canDelete'] = false;
      }
    }
    if( ($canEdit || $canDelete) && ($viewer_id == $topics->user_id || $viewer->level_id == '1')  || (($canEdit == 2) || ($canDelete == 2))) {
      $topic_options = array();
      $topic_opcounter = 0;

      if(($canEdit && $topics->user_id == $viewer->getIdentity()) || $canEdit == 2) {
        if( !$topic->sticky ) {
          $topic_options[$topic_opcounter]['name'] = "sticky";
          $topic_options[$topic_opcounter]['sticky'] = "1";
          $topic_options[$topic_opcounter]['label'] = $this->view->translate("Make Sticky");
          $topic_opcounter++;
        } else {
          $topic_options[$topic_opcounter]['name'] = "sticky";
          $topic_options[$topic_opcounter]['sticky'] = "0";
          $topic_options[$topic_opcounter]['label'] = $this->view->translate("Remove Sticky");
          $topic_opcounter++;
        }

        if( !$topic->closed ) {
          $topic_options[$topic_opcounter]['name'] = "forumclose";
          $topic_options[$topic_opcounter]['close'] = "1";
          $topic_options[$topic_opcounter]['label'] = $this->view->translate("Close");
          $topic_opcounter++;
        } else {
          $topic_options[$topic_opcounter]['name'] = "forumclose";
          $topic_options[$topic_opcounter]['close'] = "0";
          $topic_options[$topic_opcounter]['label'] = $this->view->translate("Open");
          $topic_opcounter++;
        }
        $topic_options[$topic_opcounter]['name'] = "rename";
        $topic_options[$topic_opcounter]['label'] = $this->view->translate("Rename");
        $topic_opcounter++;
        $topic_options[$topic_opcounter]['name'] = "move";
        $topic_options[$topic_opcounter]['label'] = $this->view->translate("Move");
        $topic_opcounter++;
      }
      if( ($canDelete && $topic->user_id == $viewer->getIdentity()) || $canDelete == 2 ) {
        $topic_options[$topic_opcounter]['name'] = "delete";
        $topic_options[$topic_opcounter]['label'] = $this->view->translate("Delete");
        $topic_opcounter++;
      }

      $topicContent['options'] = $topic_options;
    }

    $result['options'] = $options;
    $result['menus'] = $menuoptions;
    $result['topicContent'] = $topicContent;

   //$counter++;

  }
  

  return $result;
}


public function createAction() {

  if( !$this->_helper->requireUser()->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));

 }

 $this->view->page_id = $page_id = $this->_getParam('page_id', null);
 if(empty($page_id))
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
 $this->view->page = $page = Engine_Api::_()->getItem('sespage_page', $page_id);

 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    //$this->view->sespageforum = $sespageforum = Engine_Api::_()->core()->getSubject();
 if (!$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'topic_create')->isValid() ) { 
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
 }

 $this->view->form = $form = new Sespageforum_Form_Topic_Create();

    // Remove the file element if there is no file being posted
 if( $this->getRequest()->isPost() && empty($_FILES['photo']) ) {
   $form->removeElement('photo');
 }
 if ($this->_getParam('getForm')) {
   $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
   $this->generateFormFields($formFields, $pollData);
 }

 if( !$this->getRequest()->isPost() ) { 
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
 }
 if( !$form->isValid($this->getRequest()->getPost()) ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('validation_error'), 'result' => array()));
 }

    // Process
 $values = $form->getValues();
 $values['user_id'] = $viewer->getIdentity();
    //$values['forum_id'] = $sespageforum->getIdentity();

 $topicTable = Engine_Api::_()->getDbtable('topics', 'sespageforum');
 $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sespageforum');
 $postTable = Engine_Api::_()->getDbtable('posts', 'sespageforum');

 $db = $topicTable->getAdapter();
 $db->beginTransaction();

 try {

      // Create topic
   $topic = $topicTable->createRow();
   $topic->setFromArray($values);
   $topic->title = $values['title'];
   $topic->description = $values['body'];
   $topic->page_id = $page_id;
   $topic->save();

   $tags = preg_split('/[,]+/', $values['tags']);
   $topic->tags()->addTagMaps($viewer, $tags);
   $topic->seo_keywords = implode(',', $tags);

   $topic->save();

      // Create post
   $values['topic_id'] = $topic->getIdentity();

   $post = $postTable->createRow();
   $values['body'] = Engine_Text_BBCode::prepare($values['body']);
   $post->setFromArray($values);
   $post->save();

   if( !empty($values['photo']) ) {
    $post->setPhoto($form->photo);
  }

  $auth = Engine_Api::_()->authorization()->context;
  $auth->setAllowed($topic, 'registered', 'create', true);
  $auth->setAllowed($topic, 'registered', 'view', true);
      // Create topic watch
  $topicWatchesTable->insert(array(
        //'resource_id' => $sespageforum->getIdentity(),
    'topic_id' => $topic->getIdentity(),
    'user_id' => $viewer->getIdentity(),
    'watch' => (bool) $values['watch'],
  ));

  $topicLink = '<a href="' . $topic->getHref() . '">' . $topic->getTitle() . '</a>';

      // Add activity
  $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
  $action = $activityApi->addActivity($viewer, $topic, 'sespageforum_topic_create',null,  array("topictitle" => $topicLink));
  if( $action ) {
    $action->attach($topic);
  }
  $db->commit();
  $message['status'] = true;
  $message['message'] = $this->view->translate('Topic created successfully.');

}

catch( Exception $e ) {
 $db->rollback();
 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
}
Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
}
public function deleteAction(){
  $viewer = Engine_Api::_()->user()->getViewer();
  $data = array();
  if (!Engine_Api::_()->core()->hasSubject())
   $note = Engine_Api::_()->getItem('sespage_page', $this->_getParam('id', null));

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
public function editAction()
{
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid_request'), 'result' => array()));

 }
 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
 $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');
 if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'topic.edit')->isValid() ) {
  Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));

}

$this->view->form = $form = new Sespageforum_Form_Topic_Create();

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
$table = Engine_Api::_()->getItemTable('sespageforum_topic');
$db = $table->getAdapter();
$db->beginTransaction();

try
{
  $values = $form->getValues();

  $topic->setFromArray($values);
  $topic->save();

  $db->commit();
  $message['status'] = true;
  $message['message'] = $this->view->translate('Your offer entry has been Edited.');
}

catch( Exception $e )
{
 $db->rollBack();
 Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
}
Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
}

public function stickyAction()
{
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
   return;
 }
 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
 $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');
 /*print_r($topic);die;*/
 if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'topic.edit')->isValid() ) {
   return;
 }

 $table = $topic->getTable();
 $db = $table->getAdapter();
 $db->beginTransaction();

 try
 {
   $topic = Engine_Api::_()->core()->getSubject();
   $topic->sticky = ( null === $this->_getParam('sticky') ? !$topic->sticky : (bool) $this->_getParam('sticky') );
   $topic->save();
   $temp['message'] = $this->view->translate('Done');
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
 }

 catch( Exception $e )
 {
   $db->rollBack();
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
 }

 $this->_redirectCustom($topic);
}
public function closeAction()
{
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }
 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
 $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');
 if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'topic.edit')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }

 $table = $topic->getTable();
 $db = $table->getAdapter();
 $db->beginTransaction();

 try
 {
   $topic = Engine_Api::_()->core()->getSubject();
   $topic->closed = ( null === $this->_getParam('closed') ? !$topic->closed : (bool) $this->_getParam('closed') );
   $topic->save();

   $db->commit();
   $temp['message'] = $this->view->translate('Done');
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
 }

 catch( Exception $e )
 {
   $db->rollBack();
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
 }

 $this->_redirectCustom($topic);
}
public function renameAction()
{
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }
 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
 $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');
 if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'topic.edit')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }

 $this->view->form = $form = new Sespageforum_Form_Topic_Rename();
 if ($this->_getParam('getForm')) {
   $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
   $this->generateFormFields($formFields);
 }
 if( !$this->getRequest()->isPost() )
 {
   $form->title->setValue(htmlspecialchars_decode(($topic->title)));
   $status['status'] = false;
   $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => $status));
 }

 if( !$form->isValid($this->getRequest()->getPost()) ) {
   $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
   if(is_countable($validateFields) && engine_count($validateFields))
    $this->validateFormFields($validateFields);
}

$table = $topic->getTable();
$db = $table->getAdapter();
$db->beginTransaction();

try
{
 $title = $form->getValue('title');
 $topic = Engine_Api::_()->core()->getSubject();
 $topic->title = $title;
 $topic->save();

 $db->commit();
 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('success_message' => $this->view->translate('You have rename topic.'))));
}

catch( Exception $e )
{
 $db->rollBack();
 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
}

}

public function moveAction()
{
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }
 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
 $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');

 if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', null, 'topic.edit')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }

 $this->view->form = $form = new Sespageforum_Form_Topic_Move();
 if ($this->_getParam('getForm')) {
   $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
   $this->generateFormFields($formFields);
 }
    // Populate with options
 $multiOptions = array();
 foreach( Engine_Api::_()->getItemTable('sespageforum')->fetchAll() as $sespageforum ) {
   $multiOptions[$sespageforum->getIdentity()] = $this->view->translate($sespageforum->getTitle());
 }
 $form->getElement('forum_id')->setMultiOptions($multiOptions);

 if( !$this->getRequest()->isPost() ) {
   $status['status'] = false;
   $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => $status));
 }

 if( !$form->isValid($this->getRequest()->getPost()) ) {
   $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
   if(is_countable($validateFields) && engine_count($validateFields))
    $this->validateFormFields($validateFields);
}

$values = $form->getValues();
$table = $topic->getTable();
$db = $table->getAdapter();
$db->beginTransaction();

try
{
      // Update topic
 $topic->forum_id = $values['forum_id'];
 $topic->save();

 $db->commit();
 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('success_message' => $this->view->translate('Topic moved.'))));
}

catch( Exception $e )
{
 $db->rollBack();
 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
}

}
public function watchAction()
{
  if( !$this->_helper->requireSubject('sespageforum_topic')->isValid() ) {
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
 }
 $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
 $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sespageforum_topic');
 if( !$this->_helper->requireAuth()->setAuthParams('sespageforum', $viewer, 'view')->isValid() ) {
   return;
 }

 $watch = $this->_getParam('watch', true);

 $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sespageforum');
 $db = $topicWatchesTable->getAdapter();
 $db->beginTransaction();

 try
 {
   $isWatching = $topicWatchesTable
   ->select()
   ->from($topicWatchesTable->info('name'), 'watch')
        //->where('resource_id = ?', $sespageforum->getIdentity())
   ->where('topic_id = ?', $topic->getIdentity())
   ->where('user_id = ?', $viewer->getIdentity())
   ->limit(1)
   ->query()
   ->fetchColumn(0)
   ;

   if($topic->user_id != $viewer->getIdentity() && $watch == 1) {
    $owner = Engine_Api::_()->getItem('user', $topic->user_id);
    Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sespageforum_topicsubs');
  }
  if( false === $isWatching ) {
    $topicWatchesTable->insert(array(
          //'resource_id' => $sespageforum->getIdentity(),
     'topic_id' => $topic->getIdentity(),
     'user_id' => $viewer->getIdentity(),
     'watch' => (bool) $watch,
   ));
  } else if( $watch != $isWatching ) {
    $topicWatchesTable->update(array(
     'watch' => (bool) $watch,
   ), array(
          //'resource_id = ?' => $sespageforum->getIdentity(),
     'topic_id = ?' => $topic->getIdentity(),
     'user_id = ?' => $viewer->getIdentity(),
   ));
  }

  $db->commit();
}

catch( Exception $e )
{
 $db->rollBack();
 Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
}
}
public function rateAction()
{
  $viewer = Engine_Api::_()->user()->getViewer();
  $user_id = $viewer->getIdentity();

  $rating = $this->_getParam('rating');
  $topic_id =  $this->_getParam('topic_id');


  $table = Engine_Api::_()->getDbtable('ratings', 'sespageforum');
  $db = $table->getAdapter();
  $db->beginTransaction();

  try {
    Engine_Api::_()->sespageforum()->setRating($topic_id, $user_id, $rating);

    $forum_topic = Engine_Api::_()->getItem('sespageforum_topic', $topic_id);
    $forum_topic->rating = Engine_Api::_()->sespageforum()->getRating($forum_topic->getIdentity());
    $forum_topic->save();

    if($forum_topic->user_id != $viewer->getIdentity()) {
      $owner = Engine_Api::_()->getItem('user', $forum_topic->user_id);
      Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $forum_topic, 'sespageforum_rating');
    }

    $db->commit();
  } catch (Exception $e) {
    $db->rollBack();
    throw $e;
  }

  $total = Engine_Api::_()->sespageforum()->ratingCount($forum_topic->getIdentity());

  $data = array();
  $data[] = array(
    'total' => $total,
    'rating' => $rating,
  );
  Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'','error_message'=>'', 'result' => array('success_message'=>$this->view->translate('Successfully rated.'))));
}
public function likeAction() {

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
          if ($resource_type == 'sespageforum_topic') {
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sespageforum_like_topic', array('label' => $item->getShortType()));

            $action = $activityTable->addActivity($viewer, $item, 'sespageforum_like_topic');
            if ($action)
              $activityTable->attachActivity($action, $item);
          } else if ($resource_type == 'sespageforum_post') {
            $topic = Engine_Api::_()->getItem('sespageforum_topic', $item->topic_id);
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sespageforum_like_post', array('label' => $item->getShortType()));

            $action = $activityTable->addActivity($viewer, $topic, 'sespageforum_like_post');
            if ($action)
              $activityTable->attachActivity($action, $item);
          }
        }

        $db->commit();
        $temp['message'] = array('like_id' => $like_id);
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
      } catch (Exception $e) {
        $db->rollBack();
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
      }
    } else {
     $temp['message'] = array('like_id' => $isLike);
     Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
   }
 } else {
  if ($resource_type == 'sespageforum_topic') {
    Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "liked", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => 'sespageforum_topic', "object_id = ?" => $item->getIdentity()));
    $action = $activityTable->fetchRow(array('type =?' => "sespageforum_like_topic", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
  }
  if ($resource_type == 'sespageforum_post') {
        //Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "liked", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => 'sespageforum_post', "object_id = ?" => $item->getIdentity()));
    $topic = Engine_Api::_()->getItem('sespageforum_topic', $item->topic_id);
    $action = $activityTable->fetchRow(array('type =?' => "sespageforum_like_post", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $topic->getType(), "object_id = ?" => $topic->getIdentity()));

  }
  if (!empty($action)) {
    $action->delete();
  }

  $likeTable->removeLike($item, $viewer);
  $temp['message'] = array('like_id' => 0);
  Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
}
}
public function subscribeAction() {

  $viewer = Engine_Api::_()->user()->getViewer();
  $viewer_id = $viewer->getIdentity();
  if (empty($viewer_id))
    return;

  $resource_id = $this->_getParam('resource_id');
  $resource_type = $this->_getParam('resource_type');
  $subscribe_id = $this->_getParam('subscribe_id');

  $item = Engine_Api::_()->getItem($resource_type, $resource_id);
  $subscribeTable = Engine_Api::_()->getDbTable('subscribes', 'sespageforum');
  $activityTable = Engine_Api::_()->getDbtable('actions', 'activity');
  $activityStrameTable = Engine_Api::_()->getDbtable('stream', 'activity');

  if (empty($subscribe_id)) {
    $isSubscribe = $subscribeTable->isSubscribe(array('resource_id' => $resource_id));
    if (empty($isSubscribe)) {
      $db = $subscribeTable->getAdapter();
      $db->beginTransaction();
      try {
        $row = $subscribeTable->createRow();
        $row->poster_id = $viewer_id;
        $row->resource_id = $resource_id;
        $row->save();
        $this->view->subscribe_id = $row->subscribe_id;

        $owner = $item->getOwner();
        if($owner->getIdentity() != $viewer_id) {
          if ($resource_type == 'sespageforum') {
            $owner = $item->getOwner();
          }
        }

        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw $e;
      }
    } else {
      $this->view->subscribe_id = $isSubscribe;
    }
  } else {
    $subsitem = Engine_Api::_()->getItem('sespageforum_subscribe', $subscribe_id);

    $subsitem->delete();
    $this->view->subscribe_id = 0;

  }
  Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'','error_message'=>'', 'result' => array('success_message'=>$this->view->translate('Successfully rated.'))));

}
public function thankAction() {

  $viewer = Engine_Api::_()->user()->getViewer();
  $viewer_id = $viewer->getIdentity();
  if (empty($viewer_id))
    return;

  $topicuser_id = $this->_getParam('topicuser_id');
  $resource_id = $this->_getParam('resource_id');
  $thank_id = $this->_getParam('thank_id');

  $resource_id = $this->_getParam('resource_id', null);
  $resource_type = $this->_getParam('resource_type', null);
  $resource = Engine_Api::_()->getItem($resource_type, $resource_id);

  $topic = Engine_Api::_()->getItem('sespageforum_topic', $resource->topic_id);

  $thankTable = Engine_Api::_()->getDbTable('thanks', 'sespageforum');

  if (empty($thank_id)) {
    $db = $thankTable->getAdapter();
    $db->beginTransaction();
    try {
      $row = $thankTable->createRow();
      $row->poster_id = $viewer_id;
      $row->resource_id = $topicuser_id;
      $row->post_id = $resource_id;
      $row->save();
      $resource->thanks_count++;
      $resource->save();
      $this->view->thank_id = $row->thank_id;
      $owner = Engine_Api::_()->getItem('user', $resource->user_id);
      if($owner->getIdentity() != $viewer_id) {
        if ($resource_type == 'sespageforum_post') {
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sespageforum_post_thanks', array('label' => $topic->getShortType()));
          $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $topic, 'sespageforum_post_thanks');
          if ($action)
            Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $topic);
        }
      }
      $db->commit();
      $temp['message'] = $this->view->translate('Done');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
    } catch (Exception $e) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
  }
}


public function addreputationAction() { 

  if (!$this->_helper->requireUser()->isValid()) {
    return;
  }

  $viewer = Engine_Api::_()->user()->getViewer();
  $viewer_id = $viewer->getIdentity();

  $resource_id = $this->_getParam('resource_id', null);
  $post_id = $this->_getParam('post_id', null);

  $resource_type = $this->_getParam('resource_type', null);
  $resource = Engine_Api::_()->getItem($resource_type, $post_id);
  $topic = Engine_Api::_()->getItem('sespageforum_topic', $resource->topic_id);

  $this->view->form = $form = new Sespageforum_Form_Reputation();
  if ($this->_getParam('getForm')) {
    $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields);
  }
  if (!$this->getRequest()->isPost()) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
  }

  if (!$form->isValid($this->getRequest()->getPost())) {
    $validateFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->validateFormFields($form);
    if (is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
  }

  $values = $form->getValues();

        // Process
  $table = Engine_Api::_()->getDbTable('reputations', 'sespageforum');
  $db = $table->getAdapter();
  $db->beginTransaction();

  try
  {
    $row = $table->createRow();
    $row->resource_id = $resource_id;
    $row->post_id = $post_id;
    $row->poster_id = $viewer_id;
    $row->reputation = $values['reputation'];
    $row->save();

    $owner = Engine_Api::_()->getItem('user', $resource_id);
    if($owner->getIdentity() != $viewer_id) {
      if ($resource_type == 'sespageforum_post') {
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sespageforum_post_reputation', array('label' => $topic->getShortType()));
        $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $topic, 'sespageforum_post_reputation');
        if ($action)
          Engine_Api::_()->getDbtable('actions', 'activity')->attachActivity($action, $topic);
      }
    }
    $db->commit();
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('success_message' => $this->view->translate('You have successfully reputed this post.'))));
  } catch( Exception $e ) {
    $db->rollBack();
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
  }
}

public function deletepostAction(){
 {
  if( !$this->_helper->requireUser()->isValid() ) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
  }
  if( !$this->_helper->requireSubject('sespageforum_post')->isValid() ) {
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
  }
  $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
  $this->view->post = $post = Engine_Api::_()->core()->getSubject('sespageforum_post');
  $this->view->topic = $topic = $post->getParent();
  $sespage = Engine_Api::_()->getItem('sespage_page', $topic->page_id);
  if( !$this->_helper->requireAuth()->setAuthParams('sespageforum_po', null, 'delete')->checkRequire()) {
    return $this->_helper->requireAuth()->forward();
  }

  $this->view->form = $form = new Sespageforum_Form_Post_Delete();

  if( !$this->getRequest()->isPost() ) {
    $status['status'] = false;
    $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => $status));
  }

  if( !$form->isValid($this->getRequest()->getPost()) ) {
    $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
    if(is_countable($validateFields) && engine_count($validateFields))
      $this->validateFormFields($validateFields);
  }
  
  if($this->_getParam('getForm')) {
    $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
    $this->generateFormFields($formFields);
  }


    // Process
  $table = Engine_Api::_()->getItemTable('sespageforum_post');
  $db = $table->getAdapter();
  $db->beginTransaction();

  $topic_id = $post->topic_id;

  try
  {
    $post->delete();
    $db->commit();

  }

  catch( Exception $e )
  {
    $db->rollBack();
    throw $e;
  }

  $topic = Engine_Api::_()->getItem('sespageforum_topic', $topic_id);
  $href = ( null === $topic ? $sespage->getHref() : $topic->getHref() );
  return $this->_forward('success', 'utility', 'core', array(
    'closeSmoothbox' => true,
    'parentRedirect' => $href,
    'messages' => array(Zend_Registry::get('Zend_Translate')->_('Post deleted.')),
    'format' => 'smoothbox'
  ));
}
public function editpostAction() {
  if( !$this->_helper->requireUser()->isValid() ) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }

  $post_id = $this->_getParam('post_id', null);
  $post = Engine_Api::_()->getItem('sespageforum_post', $post_id);
  if(!$post) {
    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
  }
  $canEdit_Post = Engine_Api::_()->authorization()->isAllowed('sespageforum', $viewer->level_id, 'post_edit');

  if(!$canEdit_Post) 
   Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));

 $viewer = Engine_Api::_()->user()->getViewer();

 $this->view->form = $form = new Sesforum_Form_Post_Edit(array('post'=>$post));
 $allowHtml = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesforum_html', 0);
 $allowBbcode = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesforum_bbcode', 0);

 if( $allowHtml ) {
  $body = $post->body;
  $body = preg_replace_callback('/href=["\']?([^"\'>]+)["\']?/', function($matches) {
    return 'href="' . str_replace(['&gt;', '&lt;'], '', $matches[1]) . '"';
  }, $body);
} else {
  $body = htmlspecialchars_decode($post->body, ENT_COMPAT);
}
$form->body->setValue($body);
if($post->file_id)
  $form->photo->setValue($post->file_id);

if($this->_getParam('getForm')) {
  if($form->getElement('body')) {
    $form->getElement('body')->setLabel('Body');
  }
  $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
  $formFields[1]['name'] = "file";
  $this->generateFormFields($formFields);
}

if( !$this->getRequest()->isPost() ) {
  $status['status'] = false;
  $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
  Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => $status));
}

if( !$form->isValid($this->getRequest()->getPost()) ) {
  $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
  if(is_countable($validateFields) && engine_count($validateFields))
    $this->validateFormFields($validateFields);
}

    // Process
$table = Engine_Api::_()->getItemTable('sesforum_post');
$db = $table->getAdapter();
$db->beginTransaction();

try
{
  $values = $form->getValues();

  $post->body = $values['body'];
  $post->body = Engine_Text_BBCode::prepare($post->body);

  $post->edit_id = $viewer->getIdentity();

      //DELETE photo here.
  if( !empty($values['photo_delete']) && $values['photo_delete'] ) {
    $post->deletePhoto();
  }

  if( !empty($values['photo']) ) {
    $post->setPhoto($form->photo);
  }

  $post->save();
  $db->commit();
  Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'0','error_message'=>'', 'result' => array('post_id' => $post->getIdentity(),'message' => $this->view->translate('Post edited successfully.'))));
} catch( Exception $e ) {
  $db->rollBack();
  Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
}
}

}
