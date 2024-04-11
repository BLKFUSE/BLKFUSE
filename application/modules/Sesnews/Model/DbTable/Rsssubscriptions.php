<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesnews
 * @package    Sesnews
 * @copyright  Copyright 2019-2020 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: Rsssubscriptions.php  2019-02-27 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

class Sesnews_Model_DbTable_Rsssubscriptions extends Engine_Db_Table {

  public function sendNotifications(Sesnews_Model_News $sesnews) {

    if( !empty($sesnews->draft) || $sesnews->owner_type != 'user' ) {
      return $this;
    }

    // Get sesnews owner
    $owner = $sesnews->getOwner('user');

    // Get notification table
    $notificationTable = Engine_Api::_()->getDbtable('notifications', 'activity');

    // Get all subscribers
    $identities = $this->select()
      ->from($this, 'subscriber_user_id')
      ->where('rss_id = ?', $sesnews->owner_id)
      ->query()
      ->fetchAll(Zend_Db::FETCH_COLUMN);

    if( empty($identities) || engine_count($identities) <= 0 ) {
      return $this;
    }

    $users = Engine_Api::_()->getItemMulti('user', $identities);

    if( empty($users) || engine_count($users) <= 0 ) {
      return $this;
    }

    // Send notifications
    foreach( $users as $user ) {
      $notificationTable->addNotification($user, $owner, $sesnews, 'sesnews_subscribed_new');
    }

    return $this;
  }

  public function checkSubscription($subscriber, $rss) {

    return (bool) $this->select()
        ->from($this, 'rsssubscription_id')
        ->where('rss_id = ?', $rss->getIdentity())
        ->where('subscriber_user_id = ?', $subscriber->getIdentity())
        ->query()
        ->fetchColumn();
  }

  public function createSubscription(User_Model_User $user, User_Model_User $subscriber) {

    // Ignore if already subscribed
    if( $this->checkSubscription($user, $subscriber) ) {
      return $this;
    }

    // Create
    $this->insert(array(
      'rss_id' => $user->getIdentity(),
      'subscriber_user_id' => $subscriber->getIdentity(),
    ));

    return $this;
  }

  public function getAllSubscribers($rss_id) {
    return $this->select()
      ->from($this, 'subscriber_user_id')
      ->where('rss_id = ?', $rss_id)
      ->query()
      ->fetchAll(Zend_Db::FETCH_COLUMN);
  }

  public function removeSubscription(User_Model_User $user, User_Model_User $subscriber) {

    // Ignore if already not subscribed
    if( !$this->checkSubscription($user, $subscriber) ) {
      return $this;
    }

    // Delete
    $this->delete(array(
      'rss_id = ?' => $user->getIdentity(),
      'subscriber_user_id = ?' => $subscriber->getIdentity(),
    ));

    return $this;
  }
}
