<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesadvancedactivity
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: Notifications.php 2017-01-12  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */


class Sesadvancedactivity_Model_DbTable_Notifications extends Engine_Db_Table
{
  protected $_rowClass = 'Sesadvancedactivity_Model_Notification';
	protected $_name = 'activity_notifications';
  protected $_serializedColumns = array('params');
  
  /**
   * Add a notification
   *
   * @param User_Model_User $user The user to receive the notification
   * @param Core_Model_Item_Abstract $subject The item responsible for causing the notification
   * @param Core_Model_Item_Abstract $object Bleh
   * @param string $type
   * @param array $params
   * @return Sesadvancedactivity_Model_Notification
   */
  public function addNotification(User_Model_User $user, Core_Model_Item_Abstract $subject,
          Core_Model_Item_Abstract $object, $type, array $params = null)
  {
    // We may want to check later if a request exists of the same type already
    $row = $this->createRow();
    $row->user_id = $user->getIdentity();
    $row->subject_type = $subject->getType();
    $row->subject_id = $subject->getIdentity();
    $row->object_type = $object->getType();
    $row->object_id = $object->getIdentity();
    $row->type = $type;
    $row->params = $params;
    $row->date = date('Y-m-d H:i:s');
    $row->save();

    // Try to add row to caching
    if( Zend_Registry::isRegistered('Zend_Cache') )
    {
      $cache = Zend_Registry::get('Zend_Cache');
      $id = __CLASS__ . '_new_' . $user->getIdentity();
      $cache->save(true, $id);
    }

    // Try to send an email
    $notificationSettingsTable = Engine_Api::_()->getDbtable('notificationSettings', 'sesadvancedactivity');
    if( $notificationSettingsTable->checkEnabledNotification($user, $type) && !empty($user->email) )
    {
      $view = Zend_Registry::get('Zend_View');
      
      $sender_photo = $subject->getPhotoUrl('thumb.icon');
      if( !$sender_photo ) {
        $sender_photo = '/' . $view->getHelper('itemPhoto')->getNoPhoto($subject, 'thumb.icon');
      }
      
      $recipient_photo = $user->getPhotoUrl('thumb.icon');
      if( !$recipient_photo ) {
        $recipient_photo = '/' . $view->getHelper('itemPhoto')->getNoPhoto($user, 'thumb.icon');
      }
      
      // Main params
      $defaultParams = array(
        'host' => $_SERVER['HTTP_HOST'],
        'email' => $user->email,
        'date' => time(),
        'recipient_title' => $user->getTitle(),
        'recipient_link' => $user->getHref(),
        'recipient_photo' => $recipient_photo,
        'sender_title' => $subject->getTitle(),
        'sender_link' => $subject->getHref(),
        'sender_photo' => $sender_photo,
        'object_title' => $object->getTitle(),
        'object_link' => $object->getHref(),
        'object_photo' => $object->getPhotoUrl('thumb.icon'),
        'object_description' => $object->getDescription(),
      );
      // Extra params
      try {
        $objectParent = $object->getParent();
        if( $objectParent && !$objectParent->isSelf($object) ) {
          $defaultParams['object_parent_title'] = $objectParent->getTitle();
          $defaultParams['object_parent_link'] = $objectParent->getHref();
          $defaultParams['object_parent_photo'] = $objectParent->getPhotoUrl('thumb.icon');
          $defaultParams['object_parent_description'] = $objectParent->getDescription();
        }
      } catch( Exception $e ) {}
      try {
        $objectOwner = $object->getParent();
        if( $objectOwner && !$objectOwner->isSelf($object) ) {
          $defaultParams['object_owner_title'] = $objectOwner->getTitle();
          $defaultParams['object_owner_link'] = $objectOwner->getHref();
          $defaultParams['object_owner_photo'] = $objectOwner->getPhotoUrl('thumb.icon');
          $defaultParams['object_owner_description'] = $objectOwner->getDescription();
        }
      } catch( Exception $e ) {}
      // Send
      try {
        Engine_Api::_()->getApi('mail', 'core')->sendSystem($user,
            'notify_' . $type, array_merge($defaultParams, (array) $params));
      } catch( Exception $e ) {
        // Silence exception
      }
    }

    return $row;
  }

  public function checkNotifications(User_Model_User $user)
  {
    // Try to add row to caching
    if( Zend_Registry::isRegistered('Zend_Cache') &&
        ($cache = Zend_Registry::get('Zend_Cache')) instanceof Zend_Cache_Core &&
        $cache->getOption('caching') ) {
      $id = __CLASS__ . '_new_' . $user->getIdentity();
      $val = (bool) $cache->load($id);
      $cache->save(false);
      $cache->remove($id);
      return $val;
    }

    else
    {
      // We could have it poll the database here, if we wanted to crash the server
      $session = new Zend_Session_Namespace(get_class($this));
      $lastCount = (int) @$session->lastCount;
      $nowCount = $this->hasNotifications($user);
      $isNew = ( $lastCount < $nowCount );
      if( $isNew ) {
        $session->lastCount = $nowCount;
      } else if( $lastCount > $nowCount ) {
        $session->lastCount = $nowCount; // Something strange happened
      }
      return $isNew;
    }
  }

  /**
   * Get a notification row by id
   *
   * @param integer $notification_id
   * @return Sesadvancedactivity_Model_Notification|null
   */
  public function getNotificationById($notification_id)
  {
    $select = $this->select()
      ->where('notification_id = ?')
      ->limit(1);

    return $this->fetchRow($select);
  }

  /**
   * Get a notification by subject and type (and recipient). Useful for checking
   * if a request exists already. (i.e. type=friend_request user_id=1 subject_type=user subject_id=2)
   * will return the existing request-type notification for 2->1 request.
   *
   * @param User_Model_User $user
   * @param Core_Model_Item_Abstract $subject
   * @param string $type
   * @return Sesadvancedactivity_Model_Notification
   */
  public function getNotificationBySubjectAndType(User_Model_User $user, Core_Model_Item_Abstract $subject, $type)
  {
    $select = $this->select()
      ->where('user_id = ?', $user->getIdentity())
      ->where('type = ?', $type)
      ->where('subject_type = ?', $subject->getType())
      ->where('subject_id = ?', $subject->getIdentity())
      ->where('mitigated = ?', 0)
      ->order('notification_id DESC')
      ->limit(1);

    return $this->fetchRow($select);
  }

  public function getNotificationByObjectAndType(User_Model_User $user, Core_Model_Item_Abstract $object, $type)
  {
    $select = $this->select()
      ->where('user_id = ?', $user->getIdentity())
      ->where('type = ?', $type)
      ->where('object_type = ?', $object->getType())
      ->where('object_id = ?', $object->getIdentity())
      ->where('mitigated = ?', 0)
      ->order('notification_id DESC')
      ->limit(1);

    return $this->fetchRow($select);
  }

  /**
   * Gets all notifications matching given subject and type. Useful for finding
   * duplicates of types like friend_request
   *
   * @param User_Model_User $user
   * @param Core_Model_Item_Abstract $subject
   * @param string $type
   * @return Engine_Db_Table_Rowset
   */
  public function getNotificationsBySubjectAndType(User_Model_User $user, Core_Model_Item_Abstract $subject, $type)
  {
    $select = $this->select()
      ->where('user_id = ?', $user->getIdentity())
      ->where('type = ?', $type)
      ->where('subject_type = ?', $subject->getType())
      ->where('subject_id = ?', $subject->getIdentity())
      ->where('mitigated = ?', 0)
      ->order('notification_id DESC')
      ;

    return $this->fetchAll($select);
  }

  /**
   * Get all notifications for a user
   *
   * @param User_Model_User $user
   * @return Engine_Db_Table_Rowset
   */
  public function getNotifications(User_Model_User $user)
  {
    $select = $this->select()
      ->where('user_id = ?', $user->getIdentity())
      ->order('date DESC')
      ;

    return $this->fetchAll($select);
  }
  
  //Advanced Update results same like our theme
  public function getUpdateNotificationsPaginator(User_Model_User $user) {

    $enable_type = array();

    foreach (Engine_Api::_()->getDbtable('NotificationTypes', 'activity')->getNotificationTypes() as $type) {
      $enable_type[] = $type->type;
    }

    $select = Engine_Api::_()->getDbtable('notifications', 'activity')->select()
            ->where('user_id = ?', $user->getIdentity())
            ->where('type IN(?)', $enable_type)
            ->where('type != ?', 'friend_request')
            ->where('type != ?', 'message_new')
            ->order('date DESC');

    return Zend_Paginator::factory($select);
  }
  
  /**
   * Get notification paginator
   *
   * @param User_Model_User $user
   * @return Zend_Paginator
   */
  public function getNotificationsPaginator(User_Model_User $user)
  {
    $enabledNotificationTypes = array();
    foreach( Engine_Api::_()->getDbtable('NotificationTypes', 'sesadvancedactivity')->getNotificationTypes() as $type ) {
      $enabledNotificationTypes[] = $type->type;
    }

    // Only get enabled-messages notifications
    $rName = Engine_Api::_()->getDbtable('recipients', 'messages')->info('name');
    $cName = Engine_Api::_()->getDbtable('conversations', 'messages')->info('name');
    $enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    $disMessages = new Zend_Db_Select($this->getAdapter());
    $disMessages
      ->from($cName,'conversation_id')
      ->joinRight($rName, "`{$rName}`.`conversation_id` = `{$cName}`.`conversation_id`", null)
      ->where("`{$rName}`.`user_id` = ?", $user->getIdentity())
      ->where("`{$rName}`.`inbox_deleted` = ?", 0)
      ->where("`{$cName}`.`resource_type` NOT IN (?)", $enabledModules)
      ->order(new Zend_Db_Expr('inbox_updated DESC'));
    
    $disabledMessages = $disMessages->query()->fetchAll();

    if(empty($disabledMessages)){
      $disabledMessages = array(null);
    }
    
    $sql1 = $this->select()
      ->where('user_id = ?', $user->getIdentity())
      ->where('`type` IN(?)', $enabledNotificationTypes)
      ->where("`object_type`<>'messages_conversation'");

    $sql2 = $this->select()
      ->where('user_id = ?', $user->getIdentity())
      ->where('`type` IN(?)', $enabledNotificationTypes)
      ->where("`object_type`='messages_conversation' and `object_id` NOT IN (?)", $disabledMessages);
     
    $select = $this->select()
      ->union(array($sql1,$sql2))
      ->order('date DESC');

    return Zend_Paginator::factory($select);
  }

  /**
   * Does the user have notifications, returns the number or 0
   *
   * @param User_Model_User $user
   * @return int The number of notifications the user has
   */
  public function hasNotifications(User_Model_User $user)
  {
    $sql1 = new Zend_Db_Select($this->getAdapter());
    $sql2 = new Zend_Db_Select($this->getAdapter());

    // Only count enabled-messages notifications
    $rName = Engine_Api::_()->getDbtable('recipients', 'messages')->info('name');
    $cName = Engine_Api::_()->getDbtable('conversations', 'messages')->info('name');
    $enabledModules = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    $disMessages = new Zend_Db_Select($this->getAdapter());
    $disMessages
      ->from($cName,'conversation_id')
      ->joinRight($rName, "`{$rName}`.`conversation_id` = `{$cName}`.`conversation_id`", null)
      ->where("`{$rName}`.`user_id` = ?", $user->getIdentity())
      ->where("`{$rName}`.`inbox_deleted` = ?", 0)
      ->where("`{$cName}`.`resource_type` NOT IN (?)", $enabledModules)
      ->order(new Zend_Db_Expr('inbox_updated DESC'));
    
    $disabledMessages = $disMessages->query()->fetchAll();

    if(empty($disabledMessages)){
      $disabledMessages = array(null);
    }

    $sql1
      ->from($this->info('name'), 'COUNT(notification_id) AS notification_count')
      ->where('`user_id` = ?', $user->getIdentity())
      ->where('`read` = ?', 0)
      ->where("`object_type`='messages_conversation' and `object_id` NOT IN (?)", $disabledMessages);
    $data1 = $this->getAdapter()->fetchRow($sql1);
    
    $sql2
      ->from($this->info('name'), 'COUNT(notification_id) AS notification_count')
      ->where('`user_id` = ?', $user->getIdentity())
      ->where('`read` = ?', 0)
      ->where("`object_type`<>'messages_conversation'");
    $data2 = $this->getAdapter()->fetchRow($sql2);

    return (int) @$data1['notification_count'] + (int) @$data2['notification_count'];
  }


  /**
   * Mark all unread notifications for a user as read
   *
   * @param User_Model_User $user
   * @return Sesadvancedactivity_Api_Notifications
   */
  public function markNotificationsAsRead(User_Model_User $user, array $ids = null)
  {
    if( is_array($ids) && empty($ids) ) {
      return $this;
    }

    $where = array(
      '`user_id` = ?' => $user->getIdentity(),
      '`read` = ?' => 0
    );

    if( !empty($ids) ) {
      $where['`notification_id` IN(?)'] = $ids;
    }

    $this->update(array('read' => 1), $where);

    return $this;
  }
  

  /**
   * Remove an existing notification
   *
   * @param User_Model_User $user The recipient of the notification
   * @param integer|Sesadvancedactivity_Model_Notification $notification
   * @return Sesadvancedactivity_Api_Notifications
   */
  public function removeNotification(User_Model_User $user, $notification)
  {
    if( is_numeric($notification) )
    {
      $notification = $this->getNotificationById($notification);
    }

    if( !($notification instanceof Sesadvancedactivity_Model_Notification) )
    {
      throw new Sesadvancedactivity_Model_Exception("Notification not valid");
    }

    $notification->delete();

    return $this;
  }

  /**
   * Remove a notification by subject and type. This is useful for requests, as
   * you can easily delete the request when canceled/ignored etc
   *
   * @param User_Model_User $user The user that received the notification
   * @param Core_Model_Item_Abstract $subject The user the caused the notification
   * @param string $type
   * @return Sesadvancedactivity_Api_Notifications
   */
  public function removeNotificationsBySubjectAndType(User_Model_User $user, Core_Model_Item_Abstract $subject, $type)
  {
    $this->delete(array(
      'user_id' => $user->getIdentity(),
      'subject_type' => $subject->getType(),
      'subject_id' => $subject->getIdentity(),
      'type' => $type,
    ));

    return $this;
  }



  // Requests

  /**
   * Get all request-type notifications for a user
   *
   * @param User_Model_User $user
   * @return Engine_Db_Table_Rowset
   */
  public function getRequests(User_Model_User $user)
  {
    // Only get enabled types
    $enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    $typeTable = Engine_Api::_()->getDbtable('notificationTypes', 'sesadvancedactivity');
    $select = $this->select()
      ->from($this->info('name'))
      ->join($typeTable->info('name'), $typeTable->info('name').'.type = '.$this->info('name').'.type', null)
      ->where('module IN(?)', $enabledModuleNames)
      ->where('user_id = ?', $user->getIdentity())
      ->where('is_request = ?', 1)
      ->where('mitigated = ?', 0)
      ->order('date ASC')
      ;

    return $this->fetchAll($select);
  }

  /**
   * Get a paginator for request-type notifications
   *
   * @param User_Model_User $user
   * @return Zend_Paginator
   */
  public function getRequestsPaginator(User_Model_User $user)
  {
    // Only get enabled types
    $enabledModuleNames = Engine_Api::_()->getDbtable('modules', 'core')->getEnabledModuleNames();

    $typeTable = Engine_Api::_()->getDbtable('notificationTypes', 'sesadvancedactivity');
    $select = $this->select()
      ->from($this->info('name'))
      ->join($typeTable->info('name'), $typeTable->info('name').'.type = '.$this->info('name').'.type', null)
      ->where('module IN(?)', $enabledModuleNames)
      ->where('user_id = ?', $user->getIdentity())
      ->where('is_request = ?', 1)
      ->where('mitigated = ?', 0)
      ->order('date ASC')
      ;

    return Zend_Paginator::factory($select);
  }

  /**
   * Gets an assoc array of request types and the number the specified user has,
   * along with the request type info
   *
   * @param User_Model_User $user
   * @return array
   */
  public function getRequestCountsByType(User_Model_User $user)
  {
    $counts = array();
    foreach( $this->getRequests($user) as $request )
    {
      if( !isset($counts[$request->type]) )
      {
        $counts[$request->type] = array(
          'count' => 0,
          'info' => $request->getTypeInfo()
        );
      }
      $counts[$request->type]['count']++;
    }

    return $counts;
  }
}
