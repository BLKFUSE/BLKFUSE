<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: PostController.php 9992 2013-03-21 01:23:57Z jung $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Group
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 */
class Group_PostController extends Core_Controller_Action_Standard
{
  public function init()
  {
    if( Engine_Api::_()->core()->hasSubject() ) return;

    if( 0 !== ($post_id = (int) $this->_getParam('post_id')) &&
        null !== ($post = Engine_Api::_()->getItem('group_post', $post_id)) )
    {
      Engine_Api::_()->core()->setSubject($post);
    }

    else if( 0 !== ($topic_id = (int) $this->_getParam('topic_id')) &&
        null !== ($topic = Engine_Api::_()->getItem('group_topic', $topic_id)) )
    {
      Engine_Api::_()->core()->setSubject($topic);
    }
    
    $this->_helper->requireUser->addActionRequires(array(
      'edit',
      'delete',
    ));

    $this->_helper->requireSubject->setActionRequireTypes(array(
      'edit' => 'group_post',
      'delete' => 'group_post',
    ));
  }
  
  public function editAction()
  {
    $post = Engine_Api::_()->core()->getSubject('group_post');
    $group = $post->getParent('group');
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$group->isOwner($viewer) && !$post->isOwner($viewer) && !$group->authorization()->isAllowed($viewer, 'topic_edit') )
    {
      return $this->_helper->requireAuth->forward();
    }

    $this->view->form = $form = new Group_Form_Post_Edit();

    if( !$this->getRequest()->isPost() )
    {
      $form->populate($post->toArray());
      $allowHtml = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('group_bbcode', 0);
      $allowBbcode = (bool) Engine_Api::_()->getApi('settings', 'core')->getSetting('group_bbcode', 0);
      $body = $post->body;
      if( $allowHtml ) {
        $body = preg_replace_callback('/href=["\']?([^"\'>]+)["\']?/', function($matches) {
            return 'href="' . str_replace(['&gt;', '&lt;'], '', $matches[1]) . '"';
        }, $body);
      } else {
        $body = htmlspecialchars_decode($body, ENT_COMPAT);
      }
      $form->body->setValue($body);      
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    // Process
    $table = $post->getTable();
    $db = $table->getAdapter();
    $db->beginTransaction();

    try
    {
      $values = $form->getValues();
      $post->setFromArray($values);
      $post->body = Engine_Text_BBCode::prepare($values['body']);
      $post->modified_date = date('Y-m-d H:i:s');
      $post->body = $post->body;
      $post->save();
      
      //Save editor images
      Engine_Api::_()->core()->saveTinyMceImages($values['body'], $post);
      
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

  public function deleteAction()
  {
    $post = Engine_Api::_()->core()->getSubject('group_post');
    $group = $post->getParent('group');
    $viewer = Engine_Api::_()->user()->getViewer();

    if( !$group->isOwner($viewer) && !$post->isOwner($viewer) && !$group->authorization()->isAllowed($user, 'topic_edit') )
    {
      return $this->_helper->requireAuth->forward();
    }

    $this->view->form = $form = new Group_Form_Post_Delete();

    if( !$this->getRequest()->isPost() )
    {
      return;
    }

    if( !$form->isValid($this->getRequest()->getPost()) )
    {
      return;
    }

    // Process
    $table = $post->getTable();
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

    // Try to get topic
    $topic = Engine_Api::_()->getItem('group_topic', $topic_id);
    $href = ( null === $topic ? $group->getHref() : $topic->getHref() );
    return $this->_forward('success', 'utility', 'core', array(
      'closeSmoothbox' => true,
      'parentRedirect' => $href,
      'messages' => array(Zend_Registry::get('Zend_Translate')->_('Post deleted.')),
    ));
  }

  public function canEdit($user)
  {
    return $this->getParent()->getParent()->authorization()->isAllowed($user, 'edit') || $this->getParent()->getParent()->authorization()->isAllowed($user, 'topic_edit') || $this->isOwner($user);
  }
}
