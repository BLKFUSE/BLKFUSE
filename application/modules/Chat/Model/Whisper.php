<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Chat
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: Whisper.php 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */

/**
 * @category   Application_Extensions
 * @package    Chat
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @author     John
 */
class Chat_Model_Whisper extends Engine_Db_Table_Row
{
  public function toRemoteArray()
  {
    $return = array(
      'type' => 'chat',
      'whisper_id' => $this->whisper_id,
      'sender_id' => $this->sender_id,
      'recipient_id' => $this->recipient_id,
      'body' => $this->body,
      'date' => $this->date
    );

    $viewer = Engine_Api::_()->user()->getViewer();
    if( $viewer->getIdentity() ) {
      if( $viewer->getIdentity() == $this->sender_id ) {
        $recipient = Engine_Api::_()->getItem('user', $this->recipient_id);

        $verified_tiptext = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $recipient, 'verified_tiptext');
        $verified_tiptext = !empty($verified_tiptext) ? $verified_tiptext : 'Verified';

        $return['user_id'] = $this->recipient_id;
        $return['icon'] = $recipient->verifiedIcon();
        $return['verified_tiptext'] = $verified_tiptext;
      } else if( $viewer->getIdentity() == $this->recipient_id ) {
        $sender = Engine_Api::_()->getItem('user', $this->sender_id);
        
        $verified_tiptext = Engine_Api::_()->authorization()->getAdapter('levels')->getAllowed('user', $sender, 'verified_tiptext');
        $verified_tiptext = !empty($verified_tiptext) ? $verified_tiptext : 'Verified';

        $return['user_id'] = $this->sender_id;
        $return['icon'] = $sender->verifiedIcon();
        $return['verified_tiptext'] = $verified_tiptext;
      }
    }

    return $return;
  }

  protected function _postInsert()
  {
    // Announce message
    $eventTable = Engine_Api::_()->getDbtable('events', 'chat');
    $eventTable->insert(array(
      'user_id' => $this->recipient_id,
      'date' => date('Y-m-d H:i:s'),
      'type' => 'chat',
      'body' => array(
        'user_id' => $this->sender_id,
        'whisper_id' => $this->whisper_id,
      )
    ));

    // Announce to ourselves too ... -_-
    $eventTable->insert(array(
      'user_id' => $this->sender_id,
      'date' => date('Y-m-d H:i:s'),
      'type' => 'chat',
      'body' => array(
        'user_id' => $this->sender_id,
        'whisper_id' => $this->whisper_id,
      )
    ));
    

    // Increment event count for each user
    Engine_Api::_()->getDbtable('users', 'chat')->update(array(
      'event_count' => new Zend_Db_Expr('event_count+1'),
    ), array(
      'user_id = ?' => $this->recipient_id
    ));
  }
}
