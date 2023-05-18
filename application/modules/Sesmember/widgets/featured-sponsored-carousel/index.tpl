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

<?php 
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/carousel.css');
  $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css');
?>
<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<style>
  #sesmemberslide_<?php echo $this->identity; ?> {
    position: relative;
    overflow: hidden;
		width:100%;
  }
</style>
<?php 
$heightofPhoto = is_numeric($this->photo_height) ? $this->photo_height.'px' : $this->photo_height;?>
<div class="sesmember_carousel_wrapper sesbm clearfix sesbasic_bxs">
  <div id="sesmemberslide_<?php echo $this->identity; ?>">
    <?php foreach( $this->paginator as $member): ?>
    <div class="sesmember_grid_view_three sesbasic_clearfix sesbasic_bxs sesmember_grid_btns_wrap" style="height:<?php echo $heightofPhoto ?>;">
    	<div class="sesmember_grid_view_three_inner sesbasic_clearfix <?php if($member->vip):?>sesmeber_thumb_active_vip<?php endif;?>">
        <div class="sesmember_grid_view_three_thumb sesbasic_clearfix" style="height:<?php echo $heightofPhoto ?>;">
          <?php $href = $member->getHref();$imageURL = $member->getPhotoUrl('thumb.profile');?>
          <a href="<?php echo $href; ?>" class="sesmember_grid_view_three_thumb_img">
            <span class="sesmember_thumb"><img src="<?php echo $imageURL; ?>" alt="" /></span>
          </a>
          
          <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)){ ?>
            <div class="sesmember_labels">
              <?php if(isset($this->featuredLabelActive) && $member->featured){ ?>
              <p class="sesmember_label_featured"><?php echo $this->translate('FEATURED');?></p>
              <?php } ?>
              <?php if(isset($this->sponsoredLabelActive) && $member->sponsored){ ?>
              <p class="sesmember_label_sponsored"><?php echo $this->translate('SPONSORED');?></p>
              <?php } ?>
            </div>
          <?php } ?>
        
          <?php if(isset($this->socialSharingActive) ) {
          $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref()); ?>
          <div class="sesmember_grid_btns"> 
            <?php if(isset($this->socialSharingActive)){ ?>
            
            <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'socialshare_enable_plusicon' => $this-socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

            <?php } ?> 
           <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->likemainButtonActive)):?>
		<?php $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());?>
		<?php $likeClass = (!$LikeStatus) ? 'fa-thumbs-up' : 'fa-thumbs-down' ;?>
		<?php $likeText = ($LikeStatus) ?  $this->translate('Unlike') : $this->translate('Like') ;?>
		<?php echo "<a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_icon_btn sesmember_add_btn sesmember_button_like_user sesmember_button_like_user_". $member->user_id."'><i class='fa ".$likeClass."'></i><span><i class='fa fa-caret-down'></i>$likeText</span></a>";?>
	      <?php endif;?>
          </div>
          <?php } ?>
        </div>
        <div class="sesmember_grid_view_three_info sesbasic_clearfix">
        <?php if(isset($this->titleActive) ){ ?>
            <div class="sesmember_grid_view_info_title">
              <?php if(strlen($member->getTitle()) > $this->title_truncation_grid){ 
                $title = mb_substr($member->getTitle(),0,($this->title_truncation_grid - 3)).'...';
                echo $this->htmlLink($member->getHref(),$title, array('class' => 'ses_tooltip', 'data-src' => $member->getGuid()) ) ?>
              <?php }else{ ?>
              	<?php echo $this->htmlLink($member->getHref(),$member->getTitle(), array('class' => 'ses_tooltip', 'data-src' => $member->getGuid()) ) ?>
              <?php } ?>
            </div>
	    <?php if(isset($this->verifiedLabelActive) && $member->user_verified == 1): ?>
	      <i class="sesmember_verified_sign_<?php echo $member->user_id?> sesbasic_verified_icon" title="Verified"></i>
	    <?php else: ?>
	      <i class="sesmember_verified_sign_<?php echo $member->user_id?> sesbasic_verified_icon" style="display:none;"></i>
	    <?php endif;?>
	    <?php if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive):?>
	      <?php echo $this->partial('_userRating.tpl', 'sesmember', array('rating' => $member->rating)); ?>
	    <?php endif;?>
        <div class="sesmember_grid_view_main_info_bototm">
	  <?php if(isset($this->profileTypeActive)): ?> 
	    <div class="sesmember_list_stats sesmember_list_membertype "><span><i class="far fa-user"></i><?php echo Engine_Api::_()->sesmember()->getProfileType($member);?></span></div>
	  <?php endif;?>
	  <?php $memberAge =  $this->partial('_userAge.tpl', 'sesmember', array('ageActive' => $this->ageActive, 'member' => $member)); ?>
	  <?php if($memberAge != ''):?>
	    <?php echo $memberAge;?>
	  <?php endif;?>
          <ul>
	    <?php  if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && isset($this->locationActive) && $member->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)):?>
	      <li class="list_tooltip_btns"><span><i class="fa fa-map-marker"></i><a href="<?php echo 		$this->url(array('resource_id' => $member->user_id,'resource_type'=>'user','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="opensmoothboxurl" title="<?php echo $member->location; ?>"><?php echo $member->location ?></a></span>
	      </li>
	    <?php endif; ?>
          <?php if(Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible):?>
	    <?php if(isset($this->friendCountActive) && $fcount = $member->membership()->getMemberCount($member)): ?>  
	      <li class="list_tooltip_btns"><span class=""><i class="fa fa-users"></i><a href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo  $fcount. $this->translate(' Friends');?></a></span></li>
	    <?php endif;?>
	    <?php if(isset($this->mutualFriendCountActive) && ($viewer->getIdentity() && !$viewer->isSelf($member)) && $mcount = Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer)): ?>
	      <li class="list_tooltip_btns"><span class=""><i class="fa fa-users"></i><a href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-mutual-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo  $mcount. $this->translate(' Mutual Friends');?></a></span></li>
	    <?php endif;?>
          <?php endif;?>
          <?php if(isset($this->likeActive) && isset($member->like_count)) { ?>
          	<li class="list_tooltip_btns"><span title="0 likes"><i class="sesbasic_icon_like_o"></i><?php echo $this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)); ?></span></li>
          <?php } ?>
          <?php if(isset($this->viewActive) && isset($member->view_count)) { ?>
          	<li class="list_tooltip_btns"><span title="6 views"><i class="sesbasic_icon_view"></i><?php echo $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count))?></span></li>
          <?php } ?>
	  <?php if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view')){ ?>
	    <li class="list_tooltip_btns"><span><i class="far fa-star"></i><?php echo $this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating)); ?></span></li>
	  <?php } ?>
          </ul>
          </div>
        <?php if(isset($this->vipLabelActive) && $member->vip):?>
	  <div class="sesmember_vip_label" title="VIP" style="background-image:url(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('member_vip_image', 'application/modules/Sesmember/externals/images/vip-label.png'); ?>);"></div>
        <?php endif;?>
             <div class="sesmember_grid_view_three_info_btns">
	      <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->friendButtonActive)):?>
		<?php echo '<span>'.$this->partial('_addfriend.tpl', 'sesbasic', array('subject' => $member)).'</span>'; ?>
	      <?php endif;?>
	      <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active',1)  && !Engine_Api::_()->user()->getViewer()->isSelf($member)){
		$FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
		$followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
		$followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow')) ;
		echo "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa ".$followClass."'  title='$followText'></i> <span><i class='fa fa-caret-down'></i>".$this->translate(' Follow')."</span></a></span>"; 
	      }
	      ?>
	      <?php if (Engine_Api::_()->sesbasic()->hasCheckMessage($member) && isset($this->messageActive)): ?>
		<?php $baseUrl = $this->baseUrl();?>
		<?php $messageText = $this->translate('Message');?>
		<?php echo "<span><a href=\"$baseUrl/messages/compose/to/$member->user_id\" target=\"_parent\" title=\"$messageText\" class=\"smoothbox sesbasic_btn sesmember_add_btn\"><i class=\"far fa-comment\"></i><span><i class=\"fa fa-caret-down\"></i>".$this->translate('Message')."</span></a></span>"; ?>
	      <?php endif; ?> 
             </div>
          <?php } ?>          
        </div>
    	</div>
		</div>    
    <?php endforeach; ?>
  </div>
</div>
<script type="text/javascript">
sesowlJqueryObject(document).ready(function() {
	sesowlJqueryObject('#sesmemberslide_<?php echo $this->identity; ?>').owlCarousel({
		loop:true,
		dots:true,
		margin:5,
		items:4,
		responsiveClass:true,
		autoPlay:true,
		responsive:{
			0:{
					items:1,
					nav:true
			},
			600:{
					items:3,
					nav:false
			},
			1000:{
					items:4,
					nav:true,
			}
		}
	})
	sesowlJqueryObject(".owl-prev").html('<i class="fas fa-chevron-left"></i>');
	sesowlJqueryObject(".owl-next").html('<i class="fas fa-chevron-right"></i>');
});
</script>
