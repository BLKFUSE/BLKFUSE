<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Eticktokclone
 * @copyright  Copyright 2014-2023 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: index.tpl 2023-06-16  00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Eticktokclone/externals/styles/styles.css'); 
?>
<div class="eticktokclone_member_profile eticktokclone_member_profile_main">
  <div class="eticktokclone_member_profile_info">

    <div class="_img">
      <?php echo $this->itemPhoto($this->subject, 'thumb.profile', true); ?>
    </div>
    <div class="_cont">
      <h1><?php echo $this->translate('%1$s', $this->subject()->getTitle()); ?></h1> 
      <p class="_username">@<?php echo $this->subject->username; ?></p> 
      <div class="eticktokclone_member_profile_btns">
            
        <?php if($this->canFollow && $this->viewer()->getIdentity() && $this->subject->getIdentity() != $this->viewer()->getIdentity()){ ?>
          <?php $FollowUser = Engine_Api::_()->eticktokclone()->getFollowStatus($this->subject->getIdentity());
          ?>
          <div class="_followbtn">
            <a href="javascript:void(0);" data-url="<?php echo $this->subject->getIdentity(); ?>" style="display:<?php echo !$FollowUser ? "" : "none" ?>;" class="eticktokclone_follow_button follow"><?php echo $this->translate("Follow"); ?></a>
            <a href="javascript:void(0);" data-url="<?php echo $this->subject->getIdentity(); ?>" style="display:<?php echo $FollowUser ? "" : "none" ?>;" class="eticktokclone_follow_button unfollow active" data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Un-Follow"); ?>"><?php echo $this->translate("Following"); ?></a>
          </div>
        <?php } ?>
        <?php if($this->viewer()->getIdentity() && $this->subject->getIdentity() != $this->viewer()->getIdentity()){ ?>
          <?php if(!empty($this->allowBlock)){ ?>
            <div class="_blockbtn">
              <?php if($this->isBlock){ ?>
                <a href="<?php echo $this->url(array("module"=>"eticktokclone","controller"=>'index',"action"=>"block",'id'=>$this->subject->getIdentity()),'default',true) ?>" class="eticktokclone_button msg smoothbox" data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Un Block"); ?>"><i class="fas fa-user-times"></i></a>
              <?php }else{ ?>
                <a href="<?php echo $this->url(array("module"=>"eticktokclone","controller"=>'index',"action"=>"block",'id'=>$this->subject->getIdentity()),'default',true) ?>" class="eticktokclone_button msg smoothbox"  data-bs-toggle="eticktokclone_tooltip" data-bs-title="<?php echo $this->translate("Block"); ?>"><i class="fas fa-user-times"></i></span></a>
              <?php } ?>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
  <div class="eticktokclone_member_profile_info_stats">
    <ul>
      <li><span><?php echo $this->like_count; ?></span><?php echo $this->translate(" Likes"); ?></li> 
      <li>        
          <span><?php echo $this->followCount ?></span><?php echo $this->translate(' Followers'); ?> 
      </li> 
      <li><span><?php echo $this->followingCount; ?></span><?php echo $this->translate(" Following"); ?></li>
    </ul>
  </div>
</div>

<script>
  scriptJquery(document).on('click','.eticktokclone_follow_button',function(){
    if(!en4.user.viewer.id){
      window.location.href = en4.core.baseUrl+"login";
      return;
    }


if(scriptJquery(this).hasClass('follow')){
  scriptJquery(this).parent().find(".unfollow").css('display','');
  scriptJquery(this).hide();
}else{
  scriptJquery(this).parent().find(".follow").css('display','');
  scriptJquery(this).hide();
}
scriptJquery.post(en4.core.baseUrl +"eticktokclone/index/follow",{id:scriptJquery(this).data("url")},function(){})

})

// Tooltip
scriptJquery(document).ready(function(){
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="eticktokclone_tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
});
</script>