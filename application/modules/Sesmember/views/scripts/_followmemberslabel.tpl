<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _followmemberlabel.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>

<?php
  $member = $this->subject;
  $widget = $this->widget;

  $getFollowResourceStatus = Engine_Api::_()->sesmember()->getFollowResourceStatus($member->user_id);

  $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
  $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
  
  if(empty($widget)) {
    if($FollowUser && $getFollowResourceStatus['user_approved'] == 1 && $getFollowResourceStatus['resource_approved'] == 1) {
      $unFollow = $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow'));
      echo  "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><span>$unFollow</span></a></span>";
    
    } else if($getFollowResourceStatus &&  $getFollowResourceStatus['user_approved'] == 0 && $getFollowResourceStatus['resource_approved'] == 1) {
      echo  "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."' title='".$this->translate('Cancel Follow Request')."'><span>".$this->translate('Cancel Follow Request')."</span></a></span>";
    } else if( $getFollowResourceStatus && $getFollowResourceStatus['user_approved'] == 0 && $getFollowResourceStatus['resource_approved'] == 1 ) {
      echo  "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."' title='".$this->translate('Accept Follow Request')."'><span>".$this->translate('Accept Follow Request')."</span></a></span>";
    } else if(empty($FollowUser) && empty($getFollowResourceStatus)) {
      $follow = $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'));
      echo  "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><span>$follow</span></a></span>";
    }
  } else if($widget == 1) {
    if($FollowUser && $getFollowResourceStatus['user_approved'] == 1 && $getFollowResourceStatus['resource_approved'] == 1) {
      $unFollow = $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow'));
      echo  "<a href='javascript:;' data-widgte='1' data-url='".$member->getIdentity()."' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa fa-times'  title='$unFollow'></i> <span>$unFollow</span></a>";
    } else if($getFollowResourceStatus &&  $getFollowResourceStatus['user_approved'] == 0 && $getFollowResourceStatus['resource_approved'] == 1) {
      echo  "<a href='javascript:;' data-widgte='1' data-url='".$member->getIdentity()."' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa fa-times'  title='".$this->translate('Cancel Follow Request')."'></i> <span>".$this->translate('Cancel Follow Request')."</span></a>";
    } else if( $getFollowResourceStatus && $getFollowResourceStatus['user_approved'] == 0 && $getFollowResourceStatus['resource_approved'] == 1 ) {
      echo  "<a href='javascript:;' data-widgte='1' data-url='".$member->getIdentity()."' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa fa-times'  title='".$this->translate('Accept Follow Request')."'></i> <span>".$this->translate('Accept Follow Request')."</span></a>";
    } else if(empty($FollowUser) && empty($getFollowResourceStatus)) {
      $follow = $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'));
      echo  "<a href='javascript:;' data-widgte='1' data-url='".$member->getIdentity()."' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa fa-check'></i> <span>$follow</span></a>";
    }
  }
?>
