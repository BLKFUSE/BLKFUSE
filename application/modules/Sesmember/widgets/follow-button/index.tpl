<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl'). 'application/modules/Sesmember/externals/scripts/core.js'); ?>

<?php if (!empty($this->viewer_id)): ?>
  <?php   
  
    $getFollowResourceStatus = Engine_Api::_()->sesmember()->getFollowResourceStatus($this->subject->user_id);
    
    $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($this->subject->user_id);
    $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
    $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'))  ;
    
    $getFollowUserStatus = Engine_Api::_()->sesmember()->getFollowUserStatus($this->subject->user_id);
  ?>
  <div class="sesmember_button">
  	 	<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {  ?>
        <?php if($FollowUser && $getFollowResourceStatus['user_approved'] == 1 && $getFollowResourceStatus['resource_approved'] == 1) {  ?>
          <a href='javascript:;' data-widgte='1' data-url='<?php echo $this->subject->getIdentity(); ?>' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_<?php echo $this->subject->getIdentity(); ?>'><i class='fa <?php echo $followClass ; ?>'></i><span><?php echo $followText; ?></span></a> 
        <?php } else if($getFollowResourceStatus &&  $getFollowResourceStatus['user_approved'] == 0 && $getFollowResourceStatus['resource_approved'] == 1) { ?>
          <a href='javascript:;' data-widgte='1' data-url='<?php echo $this->subject->getIdentity(); ?>' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_<?php echo $this->subject->getIdentity(); ?>'><i class='fa fa-times'></i><span><?php echo $this->translate('Cancel Follow Request'); ?></span></a> 
        <?php } else if(empty($FollowUser) && empty($getFollowResourceStatus)) { ?>
          <a href='javascript:;' data-widgte='1' data-url='<?php echo $this->subject->getIdentity(); ?>' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_<?php echo $this->subject->getIdentity(); ?>'><i class='fa fa-check'></i><span><?php echo $this->translate('Follow'); ?></span></a> 
        <?php } ?>
      <?php } else { ?>
      	<a href='javascript:;' data-widgte='1' data-url='<?php echo $this->subject->getIdentity(); ?>' class='sesbasic_animation sesbasic_link_btn sesmember_follow_user sesmember_follow_user_<?php echo $this->subject->getIdentity(); ?>'><i class='fa <?php echo $followClass ; ?>'></i><span><?php echo $followText; ?></span></a>
      <?php } ?>
      
      <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1) && $getFollowUserStatus && $this->viewer_id == $getFollowUserStatus['user_id'] && $getFollowUserStatus['user_approved'] == 0) { ?>
      
        <a class="sesbasic_animation sesbasic_link_btn sesmember_follow_user follow_accept_btn smoothbox" href="<?php echo $this->url(array('module' => "sesmember", "controller" => "index" , "action" => "accept", "user_id" => $this->subject->getIdentity(), 'follow_id' => $getFollowUserStatus['follow_id'], 'param' => "accept"), 'default', true); ?>"><i class='fa fa-check'></i><span><?php echo $this->translate("Accept Follow Request"); ?></span></a>
        
        <a class="sesbasic_animation sesbasic_link_btn sesmember_follow_user follow_reject_btn smoothbox" href="<?php echo $this->url(array('module' => "sesmember", "controller" => "index" , "action" => "reject", "user_id" => $this->subject->getIdentity(), 'follow_id' => $getFollowUserStatus['follow_id'], 'param' => "reject"), 'default', true); ?>"><i class='fa fa-times'></i><span><?php echo $this->translate("Reject Follow Request"); ?></span></a>
      <?php } ?>
  </div>
<?php endif; ?>
