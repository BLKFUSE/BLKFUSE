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

class Sesgroup_ForumController extends Sesapi_Controller_Action_Standard {

 	public function init() {
 		if( 0 !== ($topic_id = (int) $this->_getParam('topic_id')) &&
 			null !== ($topic = Engine_Api::_()->getItem('sesgroupforum_topic', $topic_id)) &&
 			$topic instanceof Sesgroupforum_Model_Topic ) {
 			Engine_Api::_()->core()->setSubject($topic);
    }
  }
 
  public function postCreateAction() {
  
    if( !$this->_helper->requireUser()->isValid() ) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }
    
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      return;
    }
    
    $topic_id = $this->_getParam('topic_id', null);

    $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $topic_id);

    if(!$topic) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }
    
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');
    $this->view->group = Engine_Api::_()->getItem('sesgroup_group', $topic->group_id);
    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'post_create')->isValid() ) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }
    if($topic->closed ) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }

    $this->view->form = $form = new Sesgroupforum_Form_Post_Create();

    // Remove the file element if there is no file being posted
    if( $this->getRequest()->isPost() && empty($_FILES['photo']) ) {
      $form->removeElement('photo');
    }

    $allowHtml = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum_html', 0);

    $allowBbcode = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum_bbcode', 0);

    $quote_id = $this->getRequest()->getParam('quote_id');
    if( !empty($quote_id) ) {
      $quote = Engine_Api::_()->getItem('sesgroupforum_post', $quote_id);
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
      //$values['forum_id'] = $sesgroupforum->getIdentity();

    $topicTable = Engine_Api::_()->getDbtable('topics', 'sesgroupforum');
    $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sesgroupforum');
    $postTable = Engine_Api::_()->getDbtable('posts', 'sesgroupforum');
    $userTable = Engine_Api::_()->getItemTable('user');
    $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
    $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');

    $viewer = Engine_Api::_()->user()->getViewer();
    $topicOwner = $topic->getOwner();
    $isOwnTopic = $viewer->isSelf($topicOwner);

    $watch = 1;
    $isWatching = $topicWatchesTable->select()
                  ->from($topicWatchesTable->info('name'), 'watch')
                  //->where('resource_id = ?', $sesgroupforum->getIdentity())
                  ->where('topic_id = ?', $topic->getIdentity())
                  ->where('user_id = ?', $viewer->getIdentity())
                  ->limit(1)
                  ->query()
                  ->fetchColumn(0);
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
            //'resource_id' => $sesgroupforum->getIdentity(),
          'topic_id' => $topic->getIdentity(),
          'user_id' => $viewer->getIdentity(),
          'watch' => (bool) $watch,
        ));
      } else if( $watch != $isWatching ) {
        $topicWatchesTable->update(array(
          'watch' => (bool) $watch,
        ), array(
            //'resource_id = ?' => $sesgroupforum->getIdentity(),
          'topic_id = ?' => $topic->getIdentity(),
          'user_id = ?' => $viewer->getIdentity(),
        ));
      }
      
      $topicLink = '<a href="' . $topic->getHref() . '">' . $topic->getTitle() . '</a>';
      //Activity
      $action = $activityApi->addActivity($viewer, $topic, 'sesgroupforum_topic_reply',null,  array("topictitle" => $topicLink));
      if( $action ) {
        $action->attach($post, $topic);
      }

      // Notifications
      $notifyUserIds = $topicWatchesTable->select()
                                        ->from($topicWatchesTable->info('name'), 'user_id')
                                        //->where('resource_id = ?', $sesgroupforum->getIdentity())
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
          $type = 'sesgroupforum_topic_response';
        } else {
          $type = 'sesgroupforum_topic_reply';
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
  }

  public function browseAction() {

    $this->view->group_id = $group_id = $this->_getParam('group_id'); //$_POST['group_id'];
    $this->view->search = $_POST['search'];
    $viewer = Engine_Api::_()->user()->getViewer();
    
    $this->view->sesgroup = $sesgroup = Engine_Api::_()->getItem('sesgroup_group', $group_id);
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

    $table = Engine_Api::_()->getItemTable('sesgroupforum_topic');
    $select = $table->select()
                  ->where('group_id = ?', $sesgroup->getIdentity())
                  ->order('sticky DESC')
                  ->order($order . ' DESC');

    if ($this->_getParam('search', false)) {
      $select->where('title LIKE ? OR description LIKE ?', '%'.$this->_getParam('search').'%');
    }
    
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($this->_getParam('limit', 10));
    $paginator->setCurrentPageNumber($this->_getParam('page', 1));

    $result['topics'] = $this->getTopics($paginator);
    
    $canCreateTopic = $sesgroup->authorization()->isAllowed($viewer, 'forum');
    
    if($viewer->getIdentity())
      $levelId = $viewer->level_id;
    else
      $levelId = 5;
      
    $canPost =  Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum', $levelId, 'topic_create')->value;
    if($canCreateTopic && $canPost) {
      $result['button']['label'] = $this->view->translate('Post New Topic');
      $result['button']['name'] = 'create';
    }
    
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page'] + 1;
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array_merge(array('error' => '', 'error_message' => '', 'result' => $result), $extraParams));
  }
  
  public function topicviewpageAction() {

    $topic_id = (int) $this->_getParam('topic_id', null);

    $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $topic_id);
    
    $sesgroup = Engine_Api::_()->getItem('sesgroup_group', $topic->group_id);
    
//     if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
//       Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
//     }


    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    //$topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');
    //$sesgroupforum = $topic->getParent();

//     if( !$this->_helper->requireAuth()->setAuthParams($sesgroupforum, null, 'view')->isValid() ) {
//       Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
//     }

    $rating_count = Engine_Api::_()->sesgroupforum()->ratingCount($topic->getIdentity());
    $rated = Engine_Api::_()->sesgroupforum()->checkRated($topic->getIdentity(), $viewer->getIdentity());

    // Settings
    $settings = Engine_Api::_()->getApi('settings', 'core');
    $post_id = (int) $this->_getParam('post_id');
    $decode_bbcode = $settings->getSetting('sesgroupforum_bbcode');

    if($viewer->getIdentity())
      $levelId = $viewer->level_id;
    else
      $levelId = 5;
    // Views
    if( !$viewer || !$viewer->getIdentity() || $viewer->getIdentity() != $topic->user_id ) {
      $topic->view_count = new Zend_Db_Expr('view_count + 1');
      $topic->save();
    }

    // Check watching
    $isWatching = null;
    if( $viewer->getIdentity() ) {
      $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sesgroupforum');
      $isWatching = $topicWatchesTable
        ->select()
        ->from($topicWatchesTable->info('name'), 'watch')
        //->where('resource_id = ?', $sesgroupforum->getIdentity())
        ->where('topic_id = ?', $topic->getIdentity())
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
    $isWatching = $isWatching;
    
    // Auth for topic
    $canPost = false;
    $canEdit = false;
    $canDelete = false;
    $canPostPerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'post_create');
    if(!$topic->closed && $canPostPerminsion) {
      $canPost = $canPostPerminsion->value;
    }
    $canEditPerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'topic_edit');
    if($canEditPerminsion) {
      $canEdit = $canEditPerminsion->value;
    }
    // echo $canEdit;
    $canDeletePerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'topic_delete');
    if($canDeletePerminsion) {
      $canDelete = $canDeletePerminsion->value;
    }

    $canPost = $canPost;
    $canEdit = $canEdit;
    $canDelete = $canDelete;

    // Auth for posts
    $canEdit_Post = false;
    $canDelete_Post = false;
    if($viewer->getIdentity()){
      $canEdit_Post = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'post_edit')->value;
      $canDelete_Post = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'post_delete')->value;
    }
    $canEdit_Post = $canEdit_Post;
    $canDelete_Post = $canDelete_Post;

    // Make form
    if( $canPost ) {
      $this->view->form = $form = new Sesgroupforum_Form_Post_Quick();
      $form->setAction($topic->getHref(array('action' => 'post-create')));
      $form->populate(array(
        'topic_id' => $topic->getIdentity(),
        'ref' => $topic->getHref(),
        'watch' => ( false === $isWatching ? '0' : '1' ),
      ));
    }
    // Keep track of topic user views to show them which ones have new posts
    if( $viewer->getIdentity() ) {
      $topic->registerView($viewer);
    }
    

    $table = Engine_Api::_()->getItemTable('sesgroupforum_post');
    $select = $topic->getChildrenSelect('sesgroupforum_post', array('order'=>'post_id ASC'));
    $paginator = Zend_Paginator::factory($select);
    $paginator->setItemCountPerPage($settings->getSetting('sesgroupforum_topic_pagelength'));

    // set up variables for pages
    $page_param = (int) $this->_getParam('page');
    $post = Engine_Api::_()->getItem('sesgroupforum_post', $post_id);

    // if there is a post_id
    if( $post_id && $post && !$page_param )
    {
      $icpp = $paginator->getItemCountPerPage();
      $post_page = ceil(($post->getPostIndex() + 1) / $icpp);

      $paginator->setCurrentPageNumber($post_page);
    }
    // Use specified page
    else if( $page_param )
    {
      $paginator->setCurrentPageNumber($page_param);
    }

    //$post_content = $topic->toArray();
    $counterPost =  0;
    foreach( $paginator as $i => $post ) {
      $post_content = $post->toArray();
      
//       $signature = $post->getSignature();
//       $signature_body = $signature->body; 
//       $doNl2br = false;
//       if( strip_tags($signature_body) == $signature_body ) {
//         $signature_body = nl2br($signature_body);
//       }
//       if( !$this->decode_html && $this->decode_bbcode ) {
//         $signature_body = $this->BBCode($signature_body, array('link_no_preparse' => true));
//       }
      
//       $isModeratorPost = $sesgroupforum->isModerator($post->getOwner());
// 
//       if( $post->user_id != 0 ) {
//         if( $post->getOwner() ) {
//           if( $isModeratorPost ) {
//             $post_content['moderator_label'] = $this->view->translate('Moderator');
//           }
//         }
// 
//       }
//       if($signature_body) {
//         $post_content['signature'] = $signature_body;
//       }
      
      $post_content['owner_title'] = Engine_Api::_()->getItem('user',$post->user_id)->getTitle();
      $post_content['description'] = $description;
      $post_content['owner_images'] = $this->userImage($post->user_id,"thumb.icon");
      $post_content['resource_type'] = $post->getType();
      
      $postCount = Engine_Api::_()->sesgroupforum()->getPostCount($post->user_id);
      $post_content['post_count'] = $postCount; //$this->view->translate('%s posts', $postCount);
      
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.thanks', 1)) {
        $isThank = Engine_Api::_()->getDbTable('thanks', 'sesgroupforum')->isThank(array('post_id' => $post->post_id,'resource_id' => $post->user_id));
        if (empty($isThank) && !empty($viewer_id) && $viewer_id != $post->user_id) {
            $post_content['isThanks'] = true;
        } else {
            $post_content['isThanks'] = false;
        }
      }

      $canLike = 1;
      $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($post, $viewer);
      if ($canLike && !empty($viewer_id)) {
          if(empty($isLike)) {
            $post_content['is_content_like'] = false;
          } else {
            $post_content['is_content_like'] = true;
          }
      }

      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.thanks', 1)) {
        $thanks = Engine_Api::_()->getDbTable('thanks', 'sesgroupforum')->getAllUserThanks($post->user_id);
        if($thanks) {
          $post_content['thanks'] = $this->view->translate("%s Thank(s)", $thanks);
          $post_content['thanks_count'] = $thanks;
        }
      }

      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.reputation', 1)) {
        $getIncreaseReputation = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum')->getIncreaseReputation(array('user_id' => $post->user_id));
        $getDecreaseReputation = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum')->getDecreaseReputation(array('user_id' => $post->user_id));
        $post_content['reputations'] = $this->view->translate("%s - %s", $getIncreaseReputation, $getDecreaseReputation);
      }

//       $signature = $post->getSignature();
//       if($signature) {
//         $post_content['post_count'] = $signature->post_count;
//       }
      //$pagedata["share"]["imageUrl"] = $this->getBaseUrl(false, $page->getPhotoUrl());
      $post_content["share"]["url"] = $this->getBaseUrl(false,$post->getHref());
      $post_content["share"]["title"] = $post->getTitle();
      $post_content["share"]["description"] = strip_tags($post->getDescription());
      $post_content["share"]["setting"] = $shareType;
      $post_content["share"]['urlParams'] = array(
        "type" => $post->getType(),
        "id" => $post->getIdentity()
      );
      
//       // Auth for topic
//       $canPost = 0;
//       $canEdit = false;
//       $canDelete = false;
//       if($viewer->getIdentity())
//         $levelId = $viewer->level_id;
//       else
//         $levelId = 5;
//       $canPostPerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'post_create');
//       if(!$topic->closed && $canPostPerminsion) {
//         $canPost = $canPostPerminsion->value;
//       }
//       $canEditPerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'topic_edit');
//       if($canEditPerminsion) {
//         $canEdit = $canEditPerminsion->value;
//       }
//       // echo $canEdit;
//       $canDeletePerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'topic_delete');
//       if($canDeletePerminsion) {
//         $canDelete = $canDeletePerminsion->value;
//       }
// 
//       $isModeratorPost = $sesgroupforum->isModerator($viewer);
//       if($isModeratorPost) {
//           $canPost = 1;
//           $canEdit = true;
//           $canDelete = true;
//       }
//       
//       // Auth for posts
//       $canEdit_Post = false;
//       $canDelete_Post = false;
//       if($viewer->getIdentity()){
//         $canEdit_Post = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'post_edit')->value;
//         $canDelete_Post = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'post_delete')->value;
//       }
//       
//       if($topic->closed) {
//         $canPost = 0;
//       }
      
      $post_content['canPost'] = true;
      $menuoptions = $options = array();
      $counter = $option_counter = 0;
      if($canPost && !$topic->closed) {
        $menuoptions[$counter]['name'] = "quote";
        $menuoptions[$counter]['label'] = $this->view->translate("Quote");
        $counter++;
      }

      if(!empty($viewer->getIdentity())) {

        $canLike = 1;
        $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($post, $viewer);
        
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

        if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.thanks', 1)) {
          $isThank = Engine_Api::_()->getDbTable('thanks', 'sesgroupforum')->isThank(array('resource_id' => $post->post_id));
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

        if($post->user_id != $viewer->getIdentity() ) {
          $options[$option_counter]['name'] = "report";
          $options[$option_counter]['label'] = $this->view->translate("Report");
          $option_counter++;
        }
        
        $isReputation = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum')->isReputation(array('post_id' => $post->getIdentity(), 'resource_id' => $post->user_id));
        if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.reputation', 1) && empty($isReputation) && $viewer_id != $post->user_id) {
          $options[$option_counter]['name'] = "reputation";
          $options[$option_counter]['label'] = $this->view->translate("Add Reputation");
          $option_counter++;

        }
        
        
        if( $canEdit && ($viewer_id == $post->user_id || $viewer->level_id == '1') ) {
          $post_content['canEdit'] = true;
          $post_content['canDelete'] = true;
        } elseif( $post->user_id != 0 && $post->isOwner($viewer) && !$topic->closed  && ($viewer_id == $post->user_id || $viewer->level_id == '1' || $sesgroupforum->isModerator($viewer))) {
          //$post_content['post_count'] = $signature->post_count;
          $post_content['canEdit'] = true;
          if( $this->canDelete_Post ) {
            $post_content['canDelete'] = true;
          } else {
            $post_content['canDelete'] = false;
          }
        } else {
          $post_content['canEdit'] = false;
          $post_content['canDelete'] = false;
        }

        if( $canEdit_Post && $canDelete_Post && ($viewer_id == $post->user_id || $viewer->level_id == '1') ) {
          $options[$option_counter]['name'] = "edit";
          $options[$option_counter]['label'] = $this->view->translate("Edit");
          $option_counter++;
          $options[$option_counter]['name'] = "delete";
          $options[$option_counter]['label'] = $this->view->translate("Delete");
          $option_counter++;
        } elseif( $post->user_id != 0 && $post->isOwner($viewer) && !$topic->closed  && ($viewer_id == $post->user_id || $viewer->level_id == '1')) {
          if( $canEdit_Post ) {
            $options[$option_counter]['name'] = "edit";
            $options[$option_counter]['label'] = $this->view->translate("Edit");
            $option_counter++;
          }

          if( $canDelete_Post ) {
            $options[$option_counter]['name'] = "delete";
            $options[$option_counter]['label'] = $this->view->translate("Delete");
            $option_counter++;
          }
        } else if(($canDelete_Post || $canEdit_Post || ($post->user_id != $viewer->getIdentity() || $viewer_id)) && $viewer_id) {
          if(!$post->isOwner($viewer)) {
            if( $canEdit_Post == 2 ) {
              $options[$option_counter]['name'] = "edit";
              $options[$option_counter]['label'] = $this->view->translate("Edit");
              $option_counter++;
            }

            if( $canDelete_Post == 2 ) {
              $options[$option_counter]['name'] = "delete";
              $options[$option_counter]['label'] = $this->view->translate("Delete");
              $option_counter++;
            }
          }
        }
      }
      $post_content['options'] = $options;
      $post_content['menus'] = $menuoptions;

      $result['posts'][$counterPost] = $post_content;

      $counterPost++;
    }
    $extraParams['pagging']['total_page'] = $paginator->getPages()->pageCount;
    $extraParams['pagging']['total'] = $paginator->getTotalItemCount();
    $extraParams['pagging']['current_page'] = $paginator->getCurrentPageNumber();
    $extraParams['pagging']['next_page'] = $extraParams['pagging']['current_page']+1;

    //Topic Content
    $topicContent['topic_title'] = $topic->getTitle();
    $topicContent['topic_id'] = $topic->getIdentity();
    $topicContent['can_rate'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.rating', 1) ? true : false;
    $topicContent['rating'] = $topic->rating;
    $topicContent['rating_count'] = Engine_Api::_()->sesgroupforum()->ratingCount($topic->getIdentity());
    $topicContent['back_to_topics'] = $this->view->translate("Back to Topics");
    if( $canPost && !$topic->closed) {
      $topicContent['post_reply'] = $this->view->translate("Post Reply");
    }

    $topicContent['can_subscribe'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.subscribe', 1);
    if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.subscribe', 1)) { 
      if( $viewer->getIdentity() ) {
        //$isSubscribe = Engine_Api::_()->getDbTable('subscribes', 'sesgroupforum')->isSubscribe(array('resource_id' => $topic->getIdentity()));
        if( !$isWatching ) {
          $topicContent['subscribe'] = $this->view->translate("Subscribe");
          $topicContent['watch'] = 1;
        } else {
          $topicContent['unsubscribe'] = $this->view->translate("Unsubscribe");
          $topicContent['watch'] = 0;
        }
      }
    }
    if($viewer_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.rating', 1)) {
      $topicContent['is_rated'] = $rated;
    }
    $topicContent['like_count'] = $topic->like_count;
    if( $viewer->getIdentity() ) {
      $canLike = 1;
      $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topic, $viewer);
      if ($canLike && !empty($viewer_id)) {
        if(empty($isLike)) {
          $topicContent['is_content_like'] = false;
        } else {
          $topicContent['is_content_like'] = true;
        }
      }
    }
    
    $tags = array();
    foreach ($topic->tags()->getTagMaps() as $tagmap) {
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
    //$pagedata["share"]["imageUrl"] = $this->getBaseUrl(false, $page->getPhotoUrl());
    $topicContent["share"]["url"] = $this->getBaseUrl(false,$topic->getHref());
    $topicContent["share"]["title"] = $topic->getTitle();
    $topicContent["share"]["description"] = strip_tags($topic->getDescription());
    $topicContent["share"]["setting"] = $shareType;
    $topicContent["share"]['urlParams'] = array(
      "type" => $topic->getType(),
      "id" => $topic->getIdentity(),
    );
    if( $viewer->getIdentity() ) {
      $canLike = 1;
      $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topic, $viewer);
      
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
    
//     // Auth for topic
//     $canEdit = false;
//     $canDelete = false;
// 
//     $canEditPerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'topic_edit');
//     if($canEditPerminsion) {
//       $canEdit = $canEditPerminsion->value;
//     }
//     // echo $canEdit;
//     $canDeletePerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum_forum',$levelId, 'topic_delete');
//     if($canDeletePerminsion) {
//       $canDelete = $canDeletePerminsion->value;
//     }
// 
//     $isModeratorPost = $sesgroupforum->isModerator($viewer);
//     if($isModeratorPost) {
//         $canEdit = true;
//         $canDelete = true;
//     }
    
    if( ($canEdit || $canDelete) && ($viewer_id == $topic->user_id || $viewer->level_id == '1') || (($canEdit == 2) || ($canDelete == 2))) {
      if($canEdit) {
        $topicContent['canEdit'] = true;
      } else {
        $topicContent['canEdit'] = false;
      }
      if($canDelete) {
        $topicContent['canDelete'] = true;
      } else {
        $topicContent['canDelete'] = false;
      }
    }

    if( ($canEdit || $canDelete) && ($viewer_id == $topic->user_id || $viewer->level_id == '1') || (($canEdit == 2) || ($canDelete == 2))) {
      $topic_options = array();
      $topic_opcounter = 0;

      if(($canEdit && $topic->user_id == $viewer->getIdentity()) || $canEdit == 2) {
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
//         $topic_options[$topic_opcounter]['name'] = "move";
//         $topic_options[$topic_opcounter]['label'] = $this->view->translate("Move");
//         $topic_opcounter++;
      }
      if( ($canDelete && $topic->user_id == $viewer->getIdentity()) || $canDelete == 2 ) {
        $topic_options[$topic_opcounter]['name'] = "delete";
        $topic_options[$topic_opcounter]['label'] = $this->view->translate("Delete");
        $topic_opcounter++;
      }

      $topicContent['options'] = $topic_options;
    }

    $result['topic_content'] = $topicContent;

    //Reply Form
//     if( $canPost && $form ) {
//       $result['reply_form'] = $topicContent;
//
//     }

    Engine_Api::_()->getApi('response','sesapi')->sendResponse(array_merge(array('error'=>'0','error_message'=>'', 'result' => $result),$extraParams));

  }

  public function viewAction() {

    $this->view->topic_id = $topic_id = $_POST['topic_id'];
    $table = Engine_Api::_()->getItemTable('sesgroupforum_topic');
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

  public function getTopics($paginator) { 

    $maincounter = 0;
    $result = array();
    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
    foreach($paginator as $topics){ 
      $topic = $topics->toArray();
      $resource = $topic;
      $owner = $topics->getOwner();

      $resource['id'] = $owner->getIdentity();
      $resource['owner_title'] = $owner->getTitle();
      $resource['owner_image'] =  $this->userImage($owner->user_id,"thumb.profile"); //$owner->getPhotoUrl();  
      
      $resource['resource_type'] = $topics->getType();
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.thanks', 1)) {
        $isThank = Engine_Api::_()->getDbTable('thanks', 'sesgroupforum')->isThank($topics, $viewer);
        if (empty($isThank) && !empty($viewer_id) && $viewer_id != $topics->user_id) {
          $resource['isThanks'] = true;
        } else {
          $resource['isThanks'] = false;
        }
      }
      
      $canLike = 1;
      $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($topics, $viewer);
      if ($canLike && !empty($viewer_id)) {
        if(empty($isLike)) {
          $resource['is_content_like'] = false;
        } else {
          $resource['is_content_like'] = true;
        }
      }
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.reputation', 1)) {
        $getIncreaseReputation = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum')->getIncreaseReputation(array('user_id' => $topics->user_id));
        $getDecreaseReputation = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum')->getDecreaseReputation(array('user_id' => $topics->user_id));
        $resource['reputations'] = $this->view->translate("%s - %s", $getIncreaseReputation, $getDecreaseReputation);
      }
      
      $resource["share"]["url"] = $this->getBaseUrl(false,$topics->getHref());
      $resource["share"]["title"] = $topics->getTitle();
      $resource["share"]["description"] = strip_tags($topics->getDescription());
      $resource["share"]["setting"] = $shareType;
      $resource["share"]['urlParams'] = array(
        "type" => $topics->getType(),
        "id" => $topics->getIdentity()
      );

      $canPost = false;
      $canEdit = false;
      $canDelete = false;
      if( !$topics->closed && Engine_Api::_()->authorization()->isAllowed($sesgroupforum, $levelId, 'post_create') ) { die('d');
        $canPost = true;
      }
      if( Engine_Api::_()->authorization()->isAllowed($sesgroupforum, $levelId, 'topics_edit') ) {
        $canEdit = true;
      }
      if( Engine_Api::_()->authorization()->isAllowed($sesgroupforum, $levelId, 'topics_delete') ) {
        $canDelete = true;
      }
      
      $canEdit_Post = false;
      $canDelete_Post = false;
      if($viewer->getIdentity()){
        $canEdit_Post = Engine_Api::_()->authorization()->isAllowed('sesgroupforum', $viewer->level_id, 'post_edit');
        $canDelete_Post = Engine_Api::_()->authorization()->isAllowed('sesgroupforum', $viewer->level_id, 'post_delete');
      }

      if($topics->closed) {
        $canPost = 0;
      }
      $resource['canPost'] = true;
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

      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.thanks', 1)) {
        $isThank = Engine_Api::_()->getDbTable('thanks', 'sesgroupforum')->isThank($topics, $viewer);
        
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
      $isReputation = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum')->isReputation(array('post_id' => $topics->getIdentity(), 'resource_id' => $topics->user_id));
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.reputation', 1) && empty($isReputation) && $viewer_id != $topics->user_id) {
        $options[$option_counter]['name'] = "reputation";
        $options[$option_counter]['label'] = $this->view->translate("Add Reputation");
        $option_counter++;
        
      }
      $canEdit = Engine_Api::_()->authorization()->getPermission($viewer, 'sesgroupforum', 'topic_edit');
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
      $topicContent['can_rate'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.rating', 1) ? true : false;
      $topicContent['rating'] = $topics->rating;
      $topicContent['rating_count'] = Engine_Api::_()->sesgroupforum()->ratingCount($topics->getIdentity());
      $topicContent['back_to_topics'] = $this->view->translate("Back to Topics");
      if( $canPost && !$topics->closed) {
        $topicContent['post_reply'] = $this->view->translate("Post Reply");
      }

      $isWatching = null;
      if( $viewer->getIdentity() ) {
        $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sesgroupforum');
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
      
      $topicContent['can_subscribe'] = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.subscribe', 1);
      if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.subscribe', 1)) { 
        if( $viewer->getIdentity() ) {
          //$isSubscribe = Engine_Api::_()->getDbTable('subscribes', 'sesgroupforum')->isSubscribe(array('resource_id' => $topic->getIdentity()));
          if( !$isWatching ) {
            $topicContent['subscribe'] = $this->view->translate("Subscribe");
            $topicContent['watch'] = 1;
          } else {
            $topicContent['unsubscribe'] = $this->view->translate("Unsubscribe");
            $topicContent['watch'] = 0;
          }
        }
      }
      if($viewer_id && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum.rating', 1)) {
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

      $canEditPerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'topic_edit');
      if($canEditPerminsion) {
        $canEdit = $canEditPerminsion->value;
      }

      // echo $canEdit;
      $canDeletePerminsion = Engine_Api::_()->sesgroupforum()->isAllowed('sesgroupforum',$levelId, 'topic_delete');
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
      
      $lastPoster = Engine_Api::_()->getItem('user', $topics->lastposter_id);
      $lastPostCount = 0;
      if( $lastPoster) {
        $resource['last_post'][$lastPostCount]['user_images'] = $this->userImage($lastPoster->user_id,"thumb.icon");
        $resource['last_post'][$lastPostCount]['user_id'] = $lastPoster->user_id;
        $resource['last_post'][$lastPostCount]['user_title'] = $lastPoster->getTitle();
        $resource['last_post'][$lastPostCount]['creation_date'] = $topic->modified_date;
        $lastPostCount++;
      }
      
      $resource['options'] = $options;
      $resource['menus'] = $menuoptions;
      $resource['topicContent'] = $topicContent;
      
      $result[$maincounter] = $resource;
      $maincounter++;
    }
    return $result;
  }

  public function createAction() {

    if( !$this->_helper->requireUser()->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    $this->view->group_id = $group_id = $this->_getParam('group_id', null);
    if(empty($group_id))
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('parameter_missing'), 'result' => array()));
      
    $this->view->group = $group = Engine_Api::_()->getItem('sesgroup_group', $group_id);

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    if (!$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'topic_create')->isValid() ) { 
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    }

    $this->view->form = $form = new Sesgroupforum_Form_Topic_Create();

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

    $values = $form->getValues();
    $values['user_id'] = $viewer->getIdentity();

    $topicTable = Engine_Api::_()->getDbtable('topics', 'sesgroupforum');
    $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sesgroupforum');
    $postTable = Engine_Api::_()->getDbtable('posts', 'sesgroupforum');

    $db = $topicTable->getAdapter();
    $db->beginTransaction();

    try {

      $topic = $topicTable->createRow();
      $topic->setFromArray($values);
      $topic->title = $values['title'];
      $topic->description = $values['body'];
      $topic->group_id = $group_id;
      $topic->save();
      $tags = preg_split('/[,]+/', $values['tags']);
      $topic->tags()->addTagMaps($viewer, $tags);
      $topic->seo_keywords = implode(',', $tags);
      $topic->save();
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

      $topicWatchesTable->insert(array(
        'topic_id' => $topic->getIdentity(),
        'user_id' => $viewer->getIdentity(),
        'watch' => (bool) $values['watch'],
      ));

      $topicLink = '<a href="' . $topic->getHref() . '">' . $topic->getTitle() . '</a>';

      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
      $action = $activityApi->addActivity($viewer, $topic, 'sesgroupforum_topic_create',null,  array("topictitle" => $topicLink));
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
    
  public function deleteAction() {
  
    $viewer = Engine_Api::_()->user()->getViewer();
    $data = array();
    
    if (!Engine_Api::_()->core()->hasSubject())
      $note = Engine_Api::_()->getItem('sesgroup_group', $this->_getParam('id', null));
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
      $data['message'] = $this->view->translate('Topic has been deleted succuessfully.');
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' =>$e->getMessage(), 'result' =>array()));
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $data));
  }
  
  public function editAction() {
  
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('Invalid_request'), 'result' => array()));
    }
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');
    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'topic.edit')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('permission_error'), 'result' => array()));
    }

    $this->view->form = $form = new Sesgroupforum_Form_Topic_Create();

    if($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields','sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields);
    }

    if( !$this->getRequest()->isPost() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('invalid_request'), 'result' => array()));
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      $validateFields = Engine_Api::_()->getApi('FormFields','sesapi')->validateFormFields($form);
      if(is_countable($validateFields) && engine_count($validateFields))
        $this->validateFormFields($validateFields);
    }

    $table = Engine_Api::_()->getItemTable('sesgroupforum_topic');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $values = $form->getValues();
      $topic->setFromArray($values);
      $topic->save();

      $db->commit();
      $message['status'] = true;
      $message['message'] = $this->view->translate('Your offer entry has been Edited.');
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>$e->getMessage(), 'result' => array()));
    }
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' =>'', 'result' => $message));
  }

  public function stickyAction() {
  
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      return;
    }
    
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');

    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'topic.edit')->isValid() ) {
      return;
    }

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $topic = Engine_Api::_()->core()->getSubject();
      
      $topic->sticky = ( null === $this->_getParam('sticky') ? !$topic->sticky : (bool) $this->_getParam('sticky') );
      $topic->save();
      $db->commit();
      $temp['message'] = $this->view->translate('Done');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
  }
  
  public function closeAction() {
  
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');
    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'topic.edit')->isValid() ) {
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
  }
  
  public function renameAction() {
  
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');
    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'topic.edit')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    $this->view->form = $form = new Sesgroupforum_Form_Topic_Rename();
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

  public function moveAction() {
  
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');

    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', null, 'topic.edit')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    $this->view->form = $form = new Sesgroupforum_Form_Topic_Move();
    if ($this->_getParam('getForm')) {
      $formFields = Engine_Api::_()->getApi('FormFields', 'sesapi')->generateFormFields($form);
      $this->generateFormFields($formFields);
    }

    $multiOptions = array();
    foreach( Engine_Api::_()->getItemTable('sesgroupforum')->fetchAll() as $sesgroupforum ) {
      $multiOptions[$sesgroupforum->getIdentity()] = $this->view->translate($sesgroupforum->getTitle());
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
    try {
      $topic->forum_id = $values['forum_id'];
      $topic->save();

      $db->commit();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('success_message' => $this->view->translate('Topic moved.'))));
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
  }
  
  public function watchAction() {
  
    if( !$this->_helper->requireSubject('sesgroupforum_topic')->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }
    
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject('sesgroupforum_topic');
    if( !$this->_helper->requireAuth()->setAuthParams('sesgroupforum', $viewer, 'view')->isValid() ) {
      return;
    }

    $watch = $this->_getParam('watch', true);

    $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sesgroupforum');
    $db = $topicWatchesTable->getAdapter();
    $db->beginTransaction();

    try {
      $isWatching = $topicWatchesTable->select()
                              ->from($topicWatchesTable->info('name'), 'watch')
                              ->where('topic_id = ?', $topic->getIdentity())
                              ->where('user_id = ?', $viewer->getIdentity())
                              ->limit(1)
                              ->query()
                              ->fetchColumn(0);

      if($topic->user_id != $viewer->getIdentity() && $watch == 1) {
        $owner = Engine_Api::_()->getItem('user', $topic->user_id);
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sesgroupforum_topicsubs');
      }
      if( false === $isWatching ) {
        $topicWatchesTable->insert(array(
              //'resource_id' => $sesgroupforum->getIdentity(),
        'topic_id' => $topic->getIdentity(),
        'user_id' => $viewer->getIdentity(),
        'watch' => (bool) $watch,
      ));
      } else if( $watch != $isWatching ) {
        $topicWatchesTable->update(array(
        'watch' => (bool) $watch,
      ), array(
              //'resource_id = ?' => $sesgroupforum->getIdentity(),
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
  
  public function rateAction() {
    $viewer = Engine_Api::_()->user()->getViewer();
    $user_id = $viewer->getIdentity();

    $rating = $this->_getParam('rating');
    $topic_id =  $this->_getParam('topic_id');


    $table = Engine_Api::_()->getDbtable('ratings', 'sesgroupforum');
    $db = $table->getAdapter();
    $db->beginTransaction();

    try {
      Engine_Api::_()->sesgroupforum()->setRating($topic_id, $user_id, $rating);

      $forum_topic = Engine_Api::_()->getItem('sesgroupforum_topic', $topic_id);
      $forum_topic->rating = Engine_Api::_()->sesgroupforum()->getRating($forum_topic->getIdentity());
      $forum_topic->save();

      if($forum_topic->user_id != $viewer->getIdentity()) {
        $owner = Engine_Api::_()->getItem('user', $forum_topic->user_id);
        Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $forum_topic, 'sesgroupforum_rating');
      }

      $db->commit();
    } catch (Exception $e) {
      $db->rollBack();
      throw $e;
    }

    $total = Engine_Api::_()->sesgroupforum()->ratingCount($forum_topic->getIdentity());

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
            if ($resource_type == 'sesgroupforum_topic') {
              Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $item, 'sesgroupforum_like_topic', array('label' => $item->getShortType()));

              $action = $activityTable->addActivity($viewer, $item, 'sesgroupforum_like_topic');
              if ($action)
                $activityTable->attachActivity($action, $item);
            } else if ($resource_type == 'sesgroupforum_post') {
              $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $item->topic_id);
              Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sesgroupforum_like_post', array('label' => $item->getShortType()));

              $action = $activityTable->addActivity($viewer, $topic, 'sesgroupforum_like_post');
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
        //$temp['message'] = array('like_id' => $isLike);
        //Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
        if ($resource_type == 'sesgroupforum_topic') {
          Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "liked", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => 'sesgroupforum_topic', "object_id = ?" => $item->getIdentity()));
          $action = $activityTable->fetchRow(array('type =?' => "sesgroupforum_like_topic", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
        }
        
        if ($resource_type == 'sesgroupforum_post') {
          $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $item->topic_id);
          $action = $activityTable->fetchRow(array('type =?' => "sesgroupforum_like_post", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $topic->getType(), "object_id = ?" => $topic->getIdentity()));
        }
        
        if (!empty($action)) {
          $action->delete();
        }

        $likeTable->removeLike($item, $viewer);
        $temp['message'] = array('like_id' => 0);
        Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '', 'error_message' => '', 'result' => $temp));
      }
    } else {
    
      if ($resource_type == 'sesgroupforum_topic') {
        Engine_Api::_()->getDbtable('notifications', 'activity')->delete(array('type =?' => "liked", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => 'sesgroupforum_topic', "object_id = ?" => $item->getIdentity()));
        $action = $activityTable->fetchRow(array('type =?' => "sesgroupforum_like_topic", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $item->getType(), "object_id = ?" => $item->getIdentity()));
      }
      
      if ($resource_type == 'sesgroupforum_post') {
        $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $item->topic_id);
        $action = $activityTable->fetchRow(array('type =?' => "sesgroupforum_like_post", "subject_id =?" => $viewer->getIdentity(), "object_type =? " => $topic->getType(), "object_id = ?" => $topic->getIdentity()));
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
    
//     if (empty($viewer_id)) 
//       return;

    $resource_id = $this->_getParam('resource_id');
    $resource_type = $this->_getParam('resource_type');
    $subscribe_id = $this->_getParam('subscribe_id');
    
    $watch = $this->_getParam('watch', true);

    $topic = Engine_Api::_()->getItem($resource_type, $resource_id);
    
    $topicWatchesTable = Engine_Api::_()->getDbtable('topicwatches', 'sesgroupforum');
    $db = $topicWatchesTable->getAdapter();
    $db->beginTransaction();

    try
    {
      $isWatching = $topicWatchesTable
        ->select()
        ->from($topicWatchesTable->info('name'), 'watch')
        //->where('resource_id = ?', $sesgroupforum->getIdentity())
        ->where('topic_id = ?', $topic->getIdentity())
        ->where('user_id = ?', $viewer->getIdentity())
        ->limit(1)
        ->query()
        ->fetchColumn(0);

      if($topic->user_id != $viewer->getIdentity() && $watch == 1) {
          $owner = Engine_Api::_()->getItem('user', $topic->user_id);
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sesgroupforum_topicsubs');
      }
      if( false === $isWatching ) {
        $topicWatchesTable->insert(array(
          //'resource_id' => $sesgroupforum->getIdentity(),
          'topic_id' => $topic->getIdentity(),
          'user_id' => $viewer->getIdentity(),
          'watch' => (bool) $watch,
        ));
      } else if( $watch != $isWatching ) {
        $topicWatchesTable->update(array(
          'watch' => (bool) $watch,
        ), array(
          //'resource_id = ?' => $sesgroupforum->getIdentity(),
          'topic_id = ?' => $topic->getIdentity(),
          'user_id = ?' => $viewer->getIdentity(),
        ));
      }

      $db->commit();
      if($watch) {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'','error_message'=>'', 'result' => array('success_message'=>$this->view->translate('Successfully Subscribed.'))));
      } else {
        Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'','error_message'=>'', 'result' => array('success_message'=>$this->view->translate('Successfully Unsubscribed.'))));
      }
    } catch( Exception $e ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
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

    $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $resource->topic_id);

    $thankTable = Engine_Api::_()->getDbTable('thanks', 'sesgroupforum');

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
          if ($resource_type == 'sesgroupforum_post') {
            Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sesgroupforum_post_thanks', array('label' => $topic->getShortType()));
            $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $topic, 'sesgroupforum_post_thanks');
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

//     if (!$this->_helper->requireUser()->isValid())
//       Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();
    $viewer_id = $viewer->getIdentity();

    $resource_id = $this->_getParam('resource_id', null);
    $post_id = $this->_getParam('post_id', null);

    $resource_type = $this->_getParam('resource_type', null);
    $resource = Engine_Api::_()->getItem($resource_type, $post_id);
    $topic = Engine_Api::_()->getItem('sesgroupforum_topic', $resource->topic_id);

    $this->view->form = $form = new Sesgroupforum_Form_Reputation();
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
    $table = Engine_Api::_()->getDbTable('reputations', 'sesgroupforum');
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
        if ($resource_type == 'sesgroupforum_post') {
          Engine_Api::_()->getDbtable('notifications', 'activity')->addNotification($owner, $viewer, $topic, 'sesgroupforum_post_reputation', array('label' => $topic->getShortType()));
          $action = Engine_Api::_()->getDbtable('actions', 'activity')->addActivity($viewer, $topic, 'sesgroupforum_post_reputation');
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
  
  public function deletepostAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) {
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));
    }

    $post_id = $this->_getParam('post_id', null);
    $post = Engine_Api::_()->getItem('sesgroupforum_post',$post_id);
    $form = new Sesgroupforum_Form_Post_Delete();
    if (!$this->getRequest()->isPost()) {
      $error = Zend_Registry::get('Zend_Translate')->_('Invalid request method');
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $error, 'result' => $status));
    }

    // Process
    $table = Engine_Api::_()->getItemTable('sesgroupforum_post');
    $db = $table->getAdapter();
    $db->beginTransaction();
    try {
      $post->delete();
      $db->commit();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '0', 'error_message' => '', 'result' => array('success_message' => $this->view->translate('You have successfully deleted to this post.'))));
    } catch( Exception $e ) {
      $db->rollBack();
      Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $e->getMessage(), 'result' => array()));
    }
  }
  
  public function editpostAction() {
  
    if( !$this->_helper->requireUser()->isValid() ) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }

    $post_id = $this->_getParam('post_id', null);
    $post = Engine_Api::_()->getItem('sesgroupforum_post', $post_id);
    if(!$post) {
      Engine_Api::_()->getApi('response','sesapi')->sendResponse(array('error'=>'1','error_message'=>'permission_error', 'result' => array()));
    }
    $canEdit_Post = Engine_Api::_()->authorization()->isAllowed('sesgroupforum', $viewer->level_id, 'post_edit');

    if(!$canEdit_Post) 
    Engine_Api::_()->getApi('response', 'sesapi')->sendResponse(array('error' => '1', 'error_message' => $this->view->translate('user_not_autheticate'), 'result' => array()));

    $viewer = Engine_Api::_()->user()->getViewer();

    $this->view->form = $form = new Sesgroupforum_Form_Post_Edit(array('post'=>$post));
    $allowHtml = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum_html', 0);
    $allowBbcode = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('sesgroupforum_bbcode', 0);

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
    $table = Engine_Api::_()->getItemTable('sesgroupforum_post');
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
