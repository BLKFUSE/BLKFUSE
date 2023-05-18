<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: member-friendship.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $table = Engine_Api::_()->getDbtable('block', 'user');
      $viewer = Engine_Api::_()->user()->getViewer();
      $select = $table->select()->where('user_id = ?', $subject->getIdentity())->where('blocked_user_id = ?', $viewer->getIdentity())->limit(1);
      $row = $table->fetchRow($select);?>
      <?php if( $row == NULL ): ?>
	<?php if( $this->viewer()->getIdentity() ): ?>
	  <div class='browsemembers_results_links'>
	    <?php    if( null === $viewer ) {
      $viewer = Engine_Api::_()->user()->getViewer();
    }

    if( !$viewer || !$viewer->getIdentity() || $subject->isSelf($viewer) ) {
      return '';
    }

    $direction = (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.direction', 1);

    // Get data
    if( !$direction ) {
      $row = Engine_Api::_()->sesbasic()->getRow($subject, $viewer);
     // $row = $subject->membership()->getRow($viewer);
    }
    else {
      $row = Engine_Api::_()->sesbasic()->getRow($viewer, $subject);
      //$row = $viewer->membership()->getRow($subject);
    }
        // Check if friendship is allowed in the network
    $eligible =  (int) Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.eligible', 2);
    if($eligible == 0){
      return '';
    }
   
    // check admin level setting if you can befriend people in your network
    else if( $eligible == 1 ) {

      $networkMembershipTable = Engine_Api::_()->getDbtable('membership', 'network');
      $networkMembershipName = $networkMembershipTable->info('name');

      $select = new Zend_Db_Select($networkMembershipTable->getAdapter());
      $select
        ->from($networkMembershipName, 'user_id')
        ->join($networkMembershipName, "`{$networkMembershipName}`.`resource_id`=`{$networkMembershipName}_2`.resource_id", null)
        ->where("`{$networkMembershipName}`.user_id = ?", $viewer->getIdentity())
        ->where("`{$networkMembershipName}_2`.user_id = ?", $subject->getIdentity())
        ;

      $data = $select->query()->fetch();

      if(empty($data)){
        return '';
      }
    }

    if( !$direction ) {
      // one-way mode
      if( null === $row ) {
        echo $this->htmlLink('javascript:void(0);', $this->translate('Follow'), array(
          'class' => 'buttonlink smoothbox icon_friend_add'
        ));
      } else if( $row->resource_approved == 0 ) {
        echo $this->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'cancel', 'user_id' => $subject->user_id), $this->translate('Cancel Follow Request'), array(
          'class' => 'buttonlink smoothbox icon_friend_cancel'
        ));
      } else {
        echo $this->htmlLink(array('route' => 'user_extended', 'controller' => 'friends', 'action' => 'remove', 'user_id' => $subject->user_id), $this->translate('Unfollow'), array(
          'class' => 'buttonlink smoothbox icon_friend_remove'
        ));
      }

    } else {
      // two-way mode
      if( null === $row ) {
        echo $this->htmlLink('javascript:void(0);', $this->translate('Add Friend'), array(
          'class' => 'buttonlink icon_friend_add member_addfriend_request', 'data-src' => $subject->user_id
        ));
      } else if( $row->user_approved == 0 ) {
        echo $this->htmlLink('javascript:void(0);',$this->translate('Cancel Request'),array(
          'class' => 'buttonlink icon_friend_cancel member_cancelfriend_request', 'data-src' => $subject->user_id
        ));
      } else if( $row->resource_approved == 0 ) {
        echo $this->htmlLink('javascript:void(0);', $this->translate('Accept Request'), array(
          'class' => 'buttonlink icon_friend_add member_acceptfriend_request', 'data-src' => $subject->user_id
        ));
      } else if( $row->active ) {
        echo $this->htmlLink('javascript:void(0);', $this->translate('Remove Friend'), array(
          'class' => 'buttonlink icon_friend_remove member_removefriend_request', 'data-src' => $subject->user_id
        ));
      }
    }
    ?>
	  </div>
	<?php endif; ?>
      <?php endif; ?>