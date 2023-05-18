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
$base_url = $this->layout()->staticBaseUrl;
$this->headScript()->appendFile($base_url . 'application/modules/Sesbasic/externals/scripts/class.noobSlide.packed.js');
$this->headLink()->appendStylesheet($base_url . 'application/modules/Sesmember/externals/styles/styles.css');
?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<script type="text/javascript">
  scriptJquery(document).ready(function() {
    var sesmemberSlideshow  = $$('#sesmember_slideshow<?php echo $this->identity ?> > div');
    for(i=0;i < sesmemberSlideshow.length;i++) {
      sesmemberSlideshow[i].style.width = $('sesbasic_content_slideshow_container').clientWidth + 'px';
    }
    var nS4 = new noobSlide({
      box: $('sesmember_slideshow<?php echo $this->identity ?>'),
      items: $$('#sesmember_slideshow<?php echo $this->identity ?> > div'),
      size: $('sesbasic_content_slideshow_container').clientWidth,
      autoPlay: true,
      interval: 5000,
      addButtons: {
        previous: $('prev1<?php echo $this->identity ?>'),
        next: $('next1<?php echo $this->identity ?>')
      },
      button_event: 'click',
    });
  });
</script>

<div class="sesmember_content_slideshow_container sesbasic_bxs" id="sesbasic_content_slideshow_container" style="height:<?php echo $this->slideheight.'px'; ?>;">
  <div id="sesmember_slideshow<?php echo $this->identity ?>" class="sesmember_content_slideshow">
    <?php foreach( $this->paginator as $member ):?>
    <div class="sesmember_content_slideshow_slides sesmember_grid_btns_wrap <?php if($member->vip):?>sesmeber_thumb_active_vip<?php endif;?>" style="height:<?php echo $this->slideheight.'px'; ?>;">
      <div class="sesmember_thumb sesmember_content_slideshow_photo" style="height:<?php echo $this->height.'px'; ?>;width:<?php echo $this->height.'px'; ?>;">
        <?php echo $this->htmlLink($member->getHref(), $this->itemPhoto($member, 'thumb.profile', $member->getTitle()), array('title' => $member->getTitle())); ?>
        <?php if(isset($this->vipLabelActive) && $member->vip):?>
	  <div class="sesmember_vip_label" title="VIP" style="background-image:url(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('member_vip_image', 'application/modules/Sesmember/externals/images/vip-label.png'); ?>);"></div>
        <?php endif;?>
  <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)):?>
	  <div class="sesmember_labels">
	  <?php if(isset($this->featuredLabelActive) && $member->featured){ ?>
	    <p class="sesmember_label_featured"><?php echo $this->translate('FEATURED');?></p>
	  <?php } ?>
	  <?php if(isset($this->sponsoredLabelActive) && $member->sponsored){ ?>
	    <p class="sesmember_label_sponsored"><?php echo $this->translate('SPONSORED');?></p>
	  <?php } ?>
	  </div>
	<?php endif; ?>
	<?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive)):?>
	  <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref()); ?>
	  <div class="sesmember_grid_btns sesmember_slider_show_grid_btns"> 
	    <?php if(isset($this->socialSharingActive)): ?>
        
        <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

	    <?php endif; ?> 
	    <?php if(isset($this->likeButtonActive)):?>
	      <?php $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());?>
	      <?php $likeClass = ($LikeStatus) ? ' button_active' : '' ;?>
	      <a href='javascript:;' data-url="<?php echo $member->user_id;?>" class="sesbasic_icon_btn sesmember_like_user_<?php echo $member->user_id;?> sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user <?php echo $likeClass;?>"><i class='fa fa-thumbs-up'></i><span><?php echo $member->like_count;?></span></a>
	    <?php endif;?>
	  </div>
	<?php endif;?>
      </div>
      <div class="sesmember_content_slideshow_content">      
        <div class="sesmember_top_tittle_block">
          <?php if(isset($this->titleActive)):?>
            <div class="sesmember_content_slideshow_title">
              <?php if(strlen($member->getTitle()) > $this->title_truncation_list){
          $title = mb_substr($member->getTitle(),0,($this->title_truncation_list-3)).'...';
          echo $this->htmlLink($member->getHref(),$title, array('class' => 'ses_tooltip', 'data-src' => $member->getGuid()));
              } else { ?>
          <?php echo $this->htmlLink($member->getHref(),$member->getTitle(), array('class' => 'ses_tooltip', 'data-src' => $member->getGuid())) ?>
	      <?php } ?>
	    </div>
	  <?php endif;?>
	<?php if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive):?>
	  <div class="sesmember_content_slideshow_rating">
	    <?php echo $this->partial('_userRating.tpl', 'sesmember', array('rating' => $member->rating)); ?>
	  </div>
	<?php endif;?>
        </div>
        <div class="sesmember_contact_info sesbasic_clearfix clear">
	  <?php if(isset($this->profileTypeActive)): ?>
	    <div class="sesmember_list_stats"><span class="widthfull"><i class="far fa-user"></i><span><?php echo Engine_Api::_()->sesmember()->getProfileType($member);?></span></span></div>
	  <?php endif;?>
	  <?php $memberAge =  $this->partial('_userAge.tpl', 'sesmember', array('ageActive' => $this->ageActive, 'member' => $member)); ?>
	  <?php if($memberAge != ''):?>
	    <?php echo $memberAge;?>
	  <?php endif;?>
	  <?php  if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && isset($this->locationActive) && $member->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)):?>
	  <div class="sesmember_list_stats sesmember_list_location"><span class="widthfull"><i class="fa fa-map-marker" title="<?php echo $this->translate('Location');?>"></i>
	    <span title="<?php echo $member->location; ?>"><a href="<?php echo $this->url(array('resource_id' => $member->user_id,'resource_type'=>'user','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="opensmoothboxurl"><?php echo $member->location ?></a></span></span></div>
	  <?php endif;?>
	  <?php if(Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible):?>
	    <?php if(isset($this->friendCountActive) && $fcount = $member->membership()->getMemberCount($member)): ?>  
	      <div class="sesmember_list_stats"><span class="widthfull"><i class="fa fa-users"></i><span><a href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo $fcount . $this->translate(' Friends');?></a></span></span></div>
	    <?php endif;?>
	    <?php if(isset($this->mutualFriendCountActive) && ($viewer->getIdentity() && !$viewer->isSelf($member)) && $mcount = Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer)): ?>
	      <div class="sesmember_list_stats"><span class="widthfull"><i class="fa fa-users"></i><span><a href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-mutual-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo  $mcount. $this->translate(' Mutual Friends');?></a></span></span></div>
	    <?php endif;?>
	  <?php endif;?>
	  <?php if(isset($this->emailActive)): ?>  
	    <div class="sesmember_list_stats"><span class="widthfull"><i class="fa fa-envelope"></i><span><?php echo $member->email;?></span></span></div>
	  <?php endif;?>
	  <?php if((isset($this->likeActive) && isset($member->like_count)) || (isset($this->viewActive) && isset($member->view_count)) || (Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view'))):?>
	    <div class="sesmember_list_stats">
	      <span class="widthfull"><i class="fas fa-chart-bar"></i>
		<span>
		  <?php if(isset($this->likeActive) && isset($member->like_count)) { ?>
		    <samp title="<?php echo $this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)); ?>">
		    <?php echo $this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)); ?>,
		    </samp>
		  <?php } ?>
		  <?php if(isset($this->viewActive) && isset($member->view_count)) { ?>
		    <samp title="<?php echo $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count))?>">
		    <?php echo $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count))?>,
		    </samp>
		  <?php } ?>
		  <?php if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view')){
		  echo '<samp title="'.$this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating)).'">' 
		  .round($member->rating,1) .'/5'. ' ratings</samp>';
		  }
		  ?>
		</span>
	      </span>
	    </div>
	  <?php endif;?>
        </div>
       <div class="sesmember_slideshow_buttons clear">
       <div class="sesmember_slideshow_btns sesmember_list_stats sesmember_list_add_btn">
	<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->friendButtonActive)):?>
	  <?php echo '<span>'.$this->partial('_addfriend.tpl', 'sesbasic', array('subject' => $member)).'</span>'; ?>
	<?php endif;?>
	<?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active',1)  && !Engine_Api::_()->user()->getViewer()->isSelf($member)){
	  $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
	  $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
	  $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'))  ;
	  echo "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa ".$followClass."'  title='$followText'></i> <span><i class='fa fa-caret-down'></i>".$this->translate(' Follow')."</span></a></span>"; 
	}
	?>
	<?php if (Engine_Api::_()->sesbasic()->hasCheckMessage($member) && isset($this->messageActive)): ?>
	  <?php $baseUrl = $this->baseUrl();?>
	  <?php $messageText = $this->translate('Message');?>
	  <?php echo "<span><a href=\"$baseUrl/messages/compose/to/$member->user_id\" target=\"_parent\" title=\"$messageText\" class=\"smoothbox sesbasic_btn sesmember_add_btn\"><i class=\"far fa-comment\"></i><span><i class=\"fa fa-caret-down\"></i>".$this->translate('Message')."</span></a></span>"; ?>
	<?php endif; ?>
       </div>
      </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <p class="sesmember_content_slideshow_btns sesbasic_text_light">
    <?php if(engine_count($this->paginator) > 1): ?><span class="prevbtn" id="prev1<?php echo $this->identity ?>"><i class="fa fa-angle-left"></i></span><?php endif; ?>
    <?php if(engine_count($this->paginator) > 1): ?><span class="nxtbtn" id="next1<?php echo $this->identity ?>"><i class="fa fa-angle-right"></i></span><?php endif; ?>
  </p>
</div>
