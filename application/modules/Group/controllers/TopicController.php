<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: TopicController.php 9921 2013-02-16 01:38:52Z jung $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_TopicController extends Core_Controller_Action_Standard
{
  public function init()
  {
    if( Engine_Api::_()->core()->hasSubject() ) return;

    /*
    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('group_post', $post_id)) )
    {
      Engine_Api::_()->core()->setSubject($post);
    }
    
    else */if( 0 !== ($topic_id = (int) $this->_getParam('topic_id')) &&
        null !== ($topic = Engine_Api::_()->getItem('group_topic', $topic_id)) )
    {
      Engine_Api::_()->core()->setSubject($topic);
    }
    
    else if( 0 !== ($group_id = (int) $this->_getParam('group_id')) &&
        null !== ($group = Engine_Api::_()->getItem('group', $group_id)) )
    {
      Engine_Api::_()->core()->setSubject($group);
    }
  }
  
  public function indexAction()
  {
    if( !$this->_helper->requireSubject('group')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid() ) return;
    
    $this->view->group = $group = Engine_Api::_()->core()->getSubject();

    $table = Engine_Api::_()->getDbtable('topics', 'group');
    $select = $table->select()
      ->where('group_id = ?', $group->getIdentity())
      ->order('sticky DESC')
      ->order('modified_date DESC');

    $this->view->paginator = $paginator = Zend_Paginator::factory($select);
    $this->view->can_post = $can_post = $this->_helper->requireAuth->setAuthParams(null, null, 'comment')->checkRequire();
    $paginator->setCurrentPageNumber($this->_getParam('page'));
  }
  
  public function viewAction()
  {
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'view')->isValid() ) return;

    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->group = $group = $topic->getParentGroup();
    $this->view->canEdit = $topic->canEdit(Engine_Api::_()->user()->getViewer());
    $this->view->canDelete = $topic->canDelete(Engine_Api::_()->user()->getViewer());
    $this->view->canPostCreate = $canPostCreate = $topic->canPostCreate(Engine_Api::_()->user()->getViewer());
    $this->view->canPostEdit = $topic->canPostEdit(Engine_Api::_()->user()->getViewer());
    $this->view->canPostDelete = $topic->canPostDelete(Engine_Api::_()->user()->getViewer());
    $this->view->officerList = $group->getOfficerList();
    
    if( !$group || !$group->getIdentity() || ((!$group->approved) && !$group->isOwner($viewer)) ) {
      if(!empty($viewer->getIdentity()) && $viewer->isAdmin()) {
      } else
        return $this->_forward('requireauth', 'error', 'core');
    }
    
    $this->view->canAdminEdit = Engine_Api::_()->authorization()->isAllowed($group, null, 'edit');

    $this->view->canPost = $canPost = $group->authorization()->isAllowed(null,  'topic_create');

    if( !$viewer || !$viewer->getIdentity() || $viewer->getIdentity() != $topic->user_id ) {
      $topic->view_count = new Zend_Db_Expr('view_count + 1');
      $topic->save();
    }

    // Check watching
    $isWatching = null;
    if( $viewer->getIdentity() ) {
      $topicWatchesTable = Engine_Api::_()->getDbtable('topicWatches', 'group');
      $isWatching = $topicWatchesTable
        ->select()
        ->from($topicWatchesTable->info('name'), 'watch')
        ->where('resource_id = ?', $group->getIdentity())
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
    $this->view->isWatching = $isWatching;
    
    // @todo implement scan to post
    $this->view->post_id = $post_id = (int) $this->_getParam('post');

    $settings = Engine_Api::_()->getApi('settings', 'core');
    $this->view->decode_bbcode = $settings->getSetting('forum_bbcode');

    $table = Engine_Api::_()->getDbtable('posts', 'group');
    $select = $table->select()
      ->where('group_id = ?', $group->getIdentity())
      ->where('topic_id = ?', $topic->getIdentity())
      ->order('creation_date ASC');
    
    $this->view->paginator = $paginator = Zend_Paginator::factory($select);

    // Skip to page of specified post
    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('group_post', $post_id)) )
    {
      $icpp = $paginator->getItemCountPerPage();
      $page = ceil(($post->getPostIndex() + 1) / $icpp);
      $paginator->setCurrentPageNumber($page);
    }

    // Use specified page
    else if( 0 !== ($page = (int) $this->_getParam('page')) )
    {
      $paginator->setCurrentPageNumber($this->_getParam('page'));
    }
    
    $canPost = $group->authorization()->isAllowed($viewer, 'comment');

    if( $canPostCreate && !$topic->closed ) {
      $this->view->form = $form = new Group_Form_Post_Create();
      $form->populate(array(
        'topic_id' => $topic->getIdentity(),
        'ref' => $topic->getHref(),
        'watch' => ( false === $isWatching ? '0' : '1' ),
      ));
    }
  }

  public function createAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireSubject('group')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'topic_create')->isValid() ) return;

    $this->view->group = $group = Engine_Api::_()->core()->getSubject('group');
    $this->view->viewer = $viewer = Engine_Api::_()->user()->getViewer();
    
    // Make form
    $this->view->form = $form = new Group_Form_Topic_Create();

    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process
    $values = $form->getValues();
    $values['user_id'] = $viewer->getIdentity();
    $values['group_id'] = $group->getIdentity();

    $topicTable = Engine_Api::_()->getDbtable('topics', 'group');
    $topicWatchesTable = Engine_Api::_()->getDbtable('topicWatches', 'group');
    $postTable = Engine_Api::_()->getDbtable('posts', 'group');

    $db = $group->getTable()->getAdapter();
    $db->beginTransaction();

    try
    {
      // Create topic
      $topic = $topicTable->createRow();
      $values['body'] = Engine_Text_BBCode::prepare($values['body']);
      $topic->setFromArray($values);
      $topic->save();

      // Create post
      $values['topic_id'] = $topic->topic_id;

      $post = $postTable->createRow();
      $post->setFromArray($values);
      $post->save();
      
      //Save editor images
      Engine_Api::_()->core()->saveTinyMceImages($values['body'], $post);

      // Create topic watch
      $topicWatchesTable->insert(array(
        'resource_id' => $group->getIdentity(),
        'topic_id' => $topic->getIdentity(),
        'user_id' => $viewer->getIdentity(),
        'watch' => (bool) $values['watch'],
      ));

      // Add activity
      $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');
      $action = $activityApi->addActivity($viewer, $group, 'group_topic_create', null, array('child_id' => $topic->getIdentity()));
      if( $action ) {
        $action->attach($topic, Activity_Model_Action::ATTACH_DESCRIPTION);
      }
      
      //Send to all group members
      $members = Engine_Api::_()->group()->groupMembers($group->getIdentity());
      foreach($members as $member) {
        Engine_Api::_()->getDbTable('notifications', 'activity')->addNotification($member, $viewer, $group, 'group_discussioncreate');
      }
      
      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    // Redirect to the post
    $this->_redirectCustom($post);
  }
  
  public function postAction()
  {
    if( !$this->_helper->requireUser()->isValid() ) return;
    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'comment')->isValid() ) return;

    $this->view->topic = $topic = Engine_Api::_()->core()->getSubject();
    $this->view->group = $group = $topic->getParentGroup();

    if( $topic->closed ) {
      $this->view->status = false;
      $this->view->message = Zend_Registry::get('Zend_Translate')->_('This has been closed for posting.');
      return;
    }
    
    // Make form
    $this->view->form = $form = new Group_Form_Post_Create();

    // Check method/data
    if( !$this->getRequest()->isPost() ) {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) ) {
      return;
    }

    // Process
    $viewer = Engine_Api::_()->user()->getViewer();
    $topicOwner = $topic->getOwner();
    $isOwnTopic = $viewer->isSelf($topicOwner);

    $postTable = Engine_Api::_()->getDbtable('posts', 'group');
    $topicWatchesTable = Engine_Api::_()->getDbtable('topicWatches', 'group');
    $userTable = Engine_Api::_()->getItemTable('user');
    $notifyApi = Engine_Api::_()->getDbtable('notifications', 'activity');
    $activityApi = Engine_Api::_()->getDbtable('actions', 'activity');

    $values = $form->getValues();
    $values['user_id'] = $viewer->getIdentity();
    $values['group_id'] = $group->getIdentity();
    $values['topic_id'] = $topic->getIdentity();

    $watch = (bool) $values['watch'];
    $isWatching = $topicWatchesTable
      ->select()
      ->from($topicWatchesTable->info('name'), 'watch')
      ->where('resource_id = ?', $group->getIdentity())
      ->where('topic_id = ?', $topic->getIdentity())
      ->where('user_id = ?', $viewer->getIdentity())
      ->limit(1)
      ->query()
      ->fetchColumn(0)
      ;

    $db = $group->getTable()->getAdapter();
    $db->beginTransaction();

    try
    {
      // Create post
      $post = $postTable->createRow();
      $post->setFromArray($values);
      $post->save();
      
      //Save editor images
      Engine_Api::_()->core()->saveTinyMceImages($values['body'], $post);

      // Watch
      if( false === $isWatching ) {
        $topicWatchesTable->insert(array(
          'resource_id' => $group->getIdentity(),
          'topic_id' => $topic->getIdentity(),
          'user_id' => $viewer->getIdentity(),
          'watch' => (bool) $watch,
        ));
      } else if( $watch != $isWatching ) {
        $topicWatchesTable->update(array(
          'watch' => (bool) $watch,
        ), array(
          'resource_id = ?' => $group->getIdentity(),
          'topic_id = ?' => $topic->getIdentity(),
          'user_id = ?' => $viewer->getIdentity(),
        ));
      }

      // Activity
      $action = $activityApi->addActivity($viewer, $group, 'group_topic_reply', null, array('child_id' => $topic->getIdentity()));
      if( $action ) {
        $action->attach($post, Activity_Model_Action::ATTACH_DESCRIPTION);
      }
      
      
      // Notifications
      $notifyUserIds = $topicWatchesTable->select()
        ->from($topicWatchesTable->info('name'), 'user_id')
        ->where('resource_id = ?', $group->getIdentity())
        ->where('topic_id = ?', $topic->getIdentity())
        ->where('watch = ?', 1)
        ->query()
        ->fetchAll(Zend_Db::FETCH_COLUMN)
        ;

      foreach( $userTable->find($notifyUserIds) as $notifyUser ) {
        // Don't notify self
        if( $notifyUser->isSelf($viewer) ) {
          continue;
        }

        if( $notifyUser->isSelf($topicOwner) ) {
          $type = 'group_discussion_response';
        } else {
          $type = 'group_discussion_reply';
        }
        $notifyApi->addNotification($notifyUser, $viewer, $topic, $type, array(
          'message' => $this->view->BBCode($post->body),
        ));
      }

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    // Redirect to the post
    $this->_redirectCustom($post);
  }

  public function stickyAction()
  {
    $topic = Engine_Api::_()->core()->getSubject('group_topic');
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( $viewer->getIdentity() != $topic->user_id){
      if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'topic_edit')->isValid() ) return;
    }

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $topic = Engine_Api::_()->core()->getSubject();
      $topic->sticky = ( null === $this->_getParam('sticky') ? !$topic->sticky : (bool) $this->_getParam('sticky') );
      $topic->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $this->_redirectCustom($topic);
  }

  public function closeAction()
  {
    $topic = Engine_Api::_()->core()->getSubject();
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( $viewer->getIdentity() != $topic->user_id){
      if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'topic_edit')->isValid() ) return;
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
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $this->_redirectCustom($topic);
  }

  public function renameAction()
  {
    $topic = Engine_Api::_()->core()->getSubject('group_topic');
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( $viewer->getIdentity() != $topic->user_id){
      if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'topic_edit')->isValid() ) return;
    }
    
    $this->view->form = $form = new Group_Form_Topic_Rename();

    if( !$this->getRequest()->isPost() )
    {
      $form->title->setValue(htmlspecialchars_decode($topic->title));
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $title = htmlspecialchars($form->getValue('title'));

      $topic = Engine_Api::_()->core()->getSubject();
      $topic->title = $title;
      $topic->save();

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Topic renamed.')),
      'layout' => 'default-simple',
      'parentRefresh' => true,
    ));
  }

  public function deleteAction()
  {
    $topic = Engine_Api::_()->core()->getSubject('group_topic');
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$this->_helper->requireSubject('group_topic')->isValid() ) return;
    if( $viewer->getIdentity() != $topic->user_id){
      if( !$this->_helper->requireAuth()->setAuthParams(null, null, 'topic_delete')->isValid() ) return;
    }
    
    $this->view->form = $form = new Group_Form_Topic_Delete();

    if( !$this->getRequest()->isPost() )
    {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    $table = $topic->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $topic = Engine_Api::_()->core()->getSubject();
      $group = $topic->getParent('group');
      $topic->delete();
      
      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    return $this->_forward('success', 'utility', 'core', array(
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Topic deleted.')),
      'layout' => 'default-simple',
      'parentRedirect' => $group->getHref(),
    ));
  }

  public function watchAction()
  {
    $topic = Engine_Api::_()->core()->getSubject();
    $group = Engine_Api::_()->getItem('group', $topic->group_id);
    $viewer = Engine_Api::_()->user()->getViewer();
    if( !$this->_helper->requireAuth()->setAuthParams($group, null, 'view')->isValid() ) {
      return;
    }

    $watch = $this->_getParam('watch', true);

    $topicWatchesTable = Engine_Api::_()->getDbtable('topicWatches', 'group');
    $db = $topicWatchesTable->getAdapter();
    $db->beginTransaction();

    try
    {
      $isWatching = $topicWatchesTable
        ->select()
        ->from($topicWatchesTable->info('name'), 'watch')
        ->where('resource_id = ?', $group->getIdentity())
        ->where('topic_id = ?', $topic->getIdentity())
        ->where('user_id = ?', $viewer->getIdentity())
        ->limit(1)
        ->query()
        ->fetchColumn(0)
        ;

      if( false === $isWatching ) {
        $topicWatchesTable->insert(array(
          'resource_id' => $group->getIdentity(),
          'topic_id' => $topic->getIdentity(),
          'user_id' => $viewer->getIdentity(),
          'watch' => (bool) $watch,
        ));
      } else if( $watch != $isWatching ) {
        $topicWatchesTable->update(array(
          'watch' => (bool) $watch,
        ), array(
          'resource_id = ?' => $group->getIdentity(),
          'topic_id = ?' => $topic->getIdentity(),
          'user_id = ?' => $viewer->getIdentity(),
        ));
      }

      $db->commit();
    }

    catch( Exception $e )
    {
      $db->rollBack();
      throw $e;
    }

    $this->_redirectCustom($topic);
  }
}

