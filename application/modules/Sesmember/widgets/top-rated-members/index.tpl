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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?>
<?php $randonNumber = $this->widgetId; ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php if(!$this->is_ajax){ ?>
<div class="sesmember_member_rating_block sesbasic_bxs sesbasic_clearfix" id="sesmember_top_member_listing">
<?php } ?>
<?php if($this->paginator->getTotalItemCount() > 0): ?>
  <?php foreach($this->paginator as $member):?>
    <?php $rating1 = $rating2 = $rating3 = $rating4 = $rating5 = 0; ?>
    <?php $this->ratingStats = Engine_Api::_()->getDbtable('reviews', 'sesmember')->getUserRatingStats(array('user_id'=>$member->user_id));?>
    <?php if(engine_count($this->ratingStats)):?>
      <?php foreach($this->ratingStats as $starsRating):?>
	<?php ${"rating".$starsRating['rating']} = $starsRating['total'];?>
      <?php endforeach;?>
    <?php endif;?>
    <?php $totalRatings = $rating1 + $rating2 + $rating3 + $rating4 + $rating5; 
    	if(!$totalRatings)
      	$totalRatings = 1;
    ?>
    <div class="sesmember_rating_list sesbasic_clearfix <?php if($member->vip):?>sesmeber_thumb_active_vip<?php endif;?>">
      <?php  
       $height = is_numeric($this->height) ? $this->height.'px' : $this->height;
        $width = is_numeric($this->width) ? $this->width.'px' : $this->width;
      ?>
      <div class="sesmember_member_rating_block_img sesmember_grid_btns_wrap" style="height: <?php echo $height; ?> ;width: <?php echo $width; ?> ;">
	<a href="<?php echo $member->getHref();?>" class=""><img src="<?php echo $member->getPhotoUrl('thumb.main');?>" class="sesbasic_animation" /></a>
  <?php if(isset($this->vipLabelActive) && $member->vip) { ?>
        <div class="sesmember_vip_label" title="VIP" style="background-image:url(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('member_vip_image', 'application/modules/Sesmember/externals/images/vip-label.png'); ?>);"></div>
      <?php } ?>
        <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)):?>
          <div class="sesmember_labels">
            <?php if(isset($this->featuredLabelActive) && $member->featured){ ?>
              <p class="sesmember_label_featured"><?php echo $this->translate('FEATURED');?></p>
            <?php  } ?>
            <?php if(isset($this->sponsoredLabelActive) && $member->sponsored){ ?>
              <p class="sesmember_label_sponsored"><?php echo $this->translate('SPONSORED');?></p>
            <?php  } ?>
          </div>
        <?php  endif; ?>
          <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref()); ?>
          <div class="sesmember_grid_btns"> 
            <?php if(isset($this->socialSharingActive)): ?>
              
              <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
              

            <?php endif; ?> 
            <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->likeButtonActive)):?>
              <?php $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());?>
              <?php $likeClass = (!$LikeStatus) ? 'fa-thumbs-up' : 'fa-thumbs-down' ;?>
              <?php $likeText = ($LikeStatus) ?  $this->translate('Unlike') : $this->translate('Like') ;?>
              <?php echo "<a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_icon_btn sesmember_add_btn sesmember_button_like_user sesmember_button_like_user_". $member->user_id."'><i class='fa ".$likeClass."'></i><span><i class='fa fa-caret-down'></i>$likeText</span></a>";?>
              <?php endif;?>
          </div>
      </div>
      <div class="sesmember_rating_list_info sesbasic_clearfix">
        <div class="sesmember_rating_list_info_top sesbasic_clearfix">
          <div class="sesmember_rating_list_title floatL">
            <span class="sesmember_list_title">
              <?php  if(isset($this->titleActive)){ ?>
                <?php echo $this->htmlLink($member->getHref(),$member->getTitle(), array('class' => 'ses_tooltip', 'data-src' => $member->getGuid())) ?>
              <?php } ?>
            </span>
            <?php if(isset($this->verifiedLabelActive) && $member->user_verified == 1): ?>
              <i class="sesmember_verified_sign_<?php echo $member->user_id?> sesbasic_verified_icon" title="Verified"></i>
            <?php endif;?>
          </div>
	  <div class="sesmember_rating_list_star">
	    <?php $ratingCount = $member->rating; $x=0; ?>
	    <?php if( $ratingCount > 0 ): ?>
	      <?php for( $x=1; $x<=$ratingCount; $x++ ): ?>
		<span id="" class="sesmember_rating_star"></span>
	      <?php endfor; ?>
	      <?php if( (round($ratingCount) - $ratingCount) > 0){ ?>
	      <span class="sesmember_rating_star sesmember_rating_star_half"></span>
	      <?php }else{ $x = $x - 1;} ?>
	      <?php if($x < 5){ 
	      for($j = $x ; $j < 5;$j++){ ?>
	      <span class="sesmember_rating_star sesmember_rating_star_disable"></span>
	      <?php }   	
	      } ?>
	    <?php endif; ?>
	  </div>
        </div>  
        <div class="sesmember_rating_list_btm">
          <div class="sesmember_rating_list_btm_left floatL">
            <?php if(isset($this->profileTypeActive)): ?>
	      <div class="sesmember_list_stats sesmember_list_membertype ">
		<span class="widthfull">
		  <i class="far fa-user"></i><span><?php echo Engine_Api::_()->sesmember()->getProfileType($member);?></span>
		</span>
	      </div>
            <?php endif;?>
	    <?php $memberAge =  $this->partial('_userAge.tpl', 'sesmember', array('ageActive' => $this->ageActive, 'member' => $member)); ?>
	    <?php if($memberAge != ''):?>
	      <?php echo $memberAge;?>
	    <?php endif;?>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && isset($this->locationActive) && $member->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)): ?>
        <div class="sesmember_list_stats sesmember_list_location">
          <span class="widthfull"><i class="fa fa-map-marker"></i><span><a href="<?php echo $this->url(array('resource_id' => $member->user_id,'resource_type'=>'user','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="opensmoothboxurl" title="<?php echo $member->location; ?>"><?php echo $member->location ?></a></span></span>
        </div>
      <?php endif; ?>
	    <?php if(Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible):?>
	      <?php if(isset($this->friendCountActive) && $friendsM = $member->membership()->getMemberCount($member)): ?>  
		<div class="sesmember_list_stats">
		  <span class="widthfull">
		    <i class="fa fa-users"></i>
		    <span><a class="opensmoothboxurl" href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo  $friendsM. $this->translate(' Friends');?></a></span>
		  </span>
		</div>
	      <?php endif;?>
	      <?php if(isset($this->mutualFriendCountActive) && ($viewer->getIdentity() && !$viewer->isSelf($member)) && $mfriends = Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer)): ?> 
		<div class="sesmember_list_stats">
		  <span class="widthfull">
		    <i class="fa fa-users"></i>
		    <span><a class="opensmoothboxurl" href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-mutual-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo   $mfriends. $this->translate(' Mutual Friends');?></a></span>
		  </span>
		</div>
	      <?php endif;?>
	    <?php endif;?>
            <?php if(isset($member->review_count) && isset($this->ratingActive)):?>
	      <div class="sesmember_list_stats">
		<span class="widthfull">
		  <i class="far fa-comments"></i>
		  <span><?php echo $member->review_count;?><?php echo $this->translate('Reviews');?></span>
		</span>
	      </div>
	    <?php endif;?>
	    <?php if(isset($this->recommendationActive)):?>
	      <div class="sesmember_list_stats">
		<span class="widthfull">
		  <i class="fa fa-check"></i>
		  <span><?php echo Engine_Api::_()->getDbTable('reviews', 'sesmember')->getRecommendationCount($member->user_id);?><?php echo $this->translate(' Recommendation');?></span>
		</span>
	      </div>
	    <?php endif;?>
	    <div class="sesmember_list_stats sesmember_list_statics">
		<?php if(isset($this->likeActive) && isset($member->like_count)):?>
    <span class="widthfull">
		<i class="fas fa-chart-bar"></i>
		<span>
		  <span title="<?php echo $this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)); ?>"><?php echo $this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)); ?></span>
		<?php endif;?>
                <?php if(isset($this->followActive)):?>
                 <?php $followCount = engine_count(Engine_Api::_()->getDbTable('follows', 'sesmember')->getFollowers($member->user_id));?>
                 <span title="<?php echo $this->translate(array('%s follower', '%s followers', $followCount), $this->locale()->toNumber($followCount)); ?>"><?php echo $this->translate(array('%s follower', '%s followers', $followCount), $this->locale()->toNumber($followCount)); ?></span>
    <?php endif;?>
		
    <?php if(isset($this->viewActive) && isset($member->view_count)): ?>
		  <span title="<?php echo $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count))?>"><?php echo $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count))?></span>
		<?php endif;?>
		<?php if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view')):?>
		  <span title="<?php echo $this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating));?>"><?php echo round($member->rating,1).'/5'. $this->translate(' ratings');?></span></span>
		<?php endif;?>
	      </span>
	    </div>
            <div class="sesmember_profile_bottom_left_btns clear sesbasic_clearfix">
              <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->friendButtonActive)):?>
              <?php echo '<span>'.$this->partial('_addfriend.tpl', 'sesbasic', array('subject' => $member)).'</span>'; ?>
              <?php endif;?>
              <?php if (Engine_Api::_()->sesbasic()->hasCheckMessage($member) && isset($this->messageActive)): ?>
              <?php $baseUrl = $this->baseUrl();?>
              <?php $messageText = $this->translate('Message');?>
              <?php echo "<span><span><a href=\"$baseUrl/messages/compose/to/$member->user_id\" target=\"_parent\" title=\"$messageText\" class=\"smoothbox sesbasic_btn sesmember_add_btn\"><i class=\"far fa-comment\"></i><span><i class=\"fa fa-caret-down\"></i>Message</span></a></span></span>"; ?>
              <?php endif; ?>
              <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active',1)  && !Engine_Api::_()->user()->getViewer()->isSelf($member)){
              $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
              $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
              $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'))  ;
              echo "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa ".$followClass."'></i> <span><i class='fa fa-caret-down'></i>$followText</span></a></span>"; 
            }
            ?>
            </div>
          </div>
          <?php if($this->rating_graph):?>
            <div class="sesmember_rating_list_btm_right floatR">
              <div class="sesmember_rating_disc_block sesbasic_bxs">
                <div class="sesmember_rating_list_block sesmember_rating_list_first">
            <div class="rating_list_left floatL"><?php echo $this->translate('5 stars');?></div>
            <div class="rating_list_right">
              <div class="rating_list_right_inner" style="width:<?php echo ( $rating5 / $totalRatings ) * 50 ?>%;"></div>
              <span class="rating_list_contant"><?php echo $rating5 ?></span>
            </div>
                </div>  
                <div class="sesmember_rating_list_block sesmember_rating_list_second">
            <div class="rating_list_left floatL"><?php echo $this->translate('4 stars');?></div>
            <div class="rating_list_right">
              <div class="rating_list_right_inner" style="width:<?php echo ( $rating4 / $totalRatings ) * 40 ?>%;"></div>
              <span class="rating_list_contant"><?php echo $rating4 ?></span>
            </div>
                </div>  
                <div class="sesmember_rating_list_block sesmember_rating_list_three">
            <div class="rating_list_left floatL"><?php echo $this->translate('3 stars');?></div>
            <div class="rating_list_right">
              <div class="rating_list_right_inner" style="width:<?php echo ( $rating3 / $totalRatings ) * 30 ?>%;"></div>
              <span class="rating_list_contant"><?php echo $rating3 ?></span>
            </div>
                </div>
                <div class="sesmember_rating_list_block sesmember_rating_list_four">
            <div class="rating_list_left floatL"><?php echo $this->translate('2 stars');?></div>
            <div class="rating_list_right">
              <div class="rating_list_right_inner" style="width:<?php echo ( $rating2 / $totalRatings ) * 20 ?>%;"></div>
              <span class="rating_list_contant"><?php echo $rating2 ?></span>
            </div>
                </div>  
                <div class="sesmember_rating_list_block sesmember_rating_list_five">
            <div class="rating_list_left floatL"><?php echo $this->translate('1 stars');?></div>
            <div class="rating_list_right">
              <div class="rating_list_right_inner" style="width:<?php echo ( $rating1 / $totalRatings ) * 10 ?>%;"></div>
              <span class="rating_list_contant"><?php echo $rating1 ?></span>
            </div>
                </div>  
              </div>
            </div>
          <?php endif;?>
        </div>   
      </div>
    </div>  
  <?php endforeach;?>
 <?php else: ?>
  <div class="sesmember_nomember_tip clearfix">
    <img src="<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('user_no_photo', 'application/modules/Sesmember/externals/images/member-icon.png'); ?>" alt="" />
    <span class="sesbasic_text_light">
      <?php echo $this->translate('Does not exist member.') ?>
    </span>
  </div>
  
<?php endif; ?>
  <?php if($this->loadOptionData == 'pagging'){ ?>
    <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesmember"),array('identityWidget'=>$randonNumber)); ?>
  <?php } ?>
  <?php if(!$this->is_ajax){ ?>
</div>
<?php } ?>
<?php if($this->loadOptionData != 'pagging' && !$this->is_ajax):?>
  <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" ><a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a></div>
  <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
<?php endif;?>

<script type="application/javascript">
  <?php if(!$this->is_ajax):?>
    <?php if($this->loadOptionData == 'auto_load'){ ?>
      scriptJquery( window ).load(function() {
	scriptJquery(window).scroll( function() {
	  var containerId = '#sesmember_top_member_listing';
	  if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#view_more_<?php echo $randonNumber; ?>').length > 0) {
	    var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery(containerId).offset().top;
	    var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
	    if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
	      document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
	    }
	  }
	});
      });
    <?php } ?>
  <?php endif; ?>

  var page<?php echo $randonNumber; ?> = <?php echo $this->page + 1; ?>;
  var params<?php echo $randonNumber; ?> = '<?php echo json_encode($this->stats); ?>';
  var searchParams<?php echo $randonNumber; ?> = '';
  <?php if($this->loadOptionData != 'pagging') { ?>
    viewMoreHide_<?php echo $randonNumber; ?>();
    function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
    }
    function viewMore_<?php echo $randonNumber; ?> () {
    scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
    scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 

    requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
    dataType: 'html',
    method: 'post',
    'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/top-rated-members",
    'data': {
    format: 'html',
    page: page<?php echo $randonNumber; ?>,    
    is_ajax : 1,
    limit:'<?php echo $this->limit; ?>',
    widgetId : '<?php echo $this->widgetId; ?>',
		params:'<?php echo json_encode($this->params); ?>',
		searchParams:searchParams<?php echo $randonNumber; ?>,
    loadOptionData : '<?php echo $this->loadOptionData ?>'
    },
    success: function(responseHTML) {
    scriptJquery('#sesmember_top_member_listing').append(responseHTML);
    scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
    scriptJquery('#loadingimgsesmemberreview-wrapper').hide();
    scriptJquery('#submit').html('Search');
    viewMoreHide_<?php echo $randonNumber; ?>();
    }
    });
    
    return false;
    }
  <?php }else{ ?>
    function paggingNumber<?php echo $randonNumber; ?>(pageNum){
      scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display','block');
      requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
      dataType: 'html',
	method: 'post',
	'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/top-rated-members",
	'data': {
	  format: 'html',
	  page: pageNum,
	  is_ajax : 1,
	  limit:'<?php echo $this->limit; ?>',
	  widgetId : '<?php echo $this->widgetId; ?>',
		searchParams:searchParams<?php echo $randonNumber; ?>,
		params:'<?php echo json_encode($this->params); ?>',
	  loadOptionData : '<?php echo $this->loadOptionData ?>'
	},
	success: function(responseHTML) {
	  scriptJquery('#sesmember_top_member_listing').html(responseHTML);
	  scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display', 'none');
	  scriptJquery('#loadingimgsesmemberreview-wrapper').hide();
    scriptJquery('#submit').html('Search');
	}
      }));
      
      return false;
    }
  <?php } ?>
</script>
