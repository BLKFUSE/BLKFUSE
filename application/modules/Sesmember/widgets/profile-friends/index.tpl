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
<?php $randonNumber = 'sesmember_friends'; ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<script type="text/javascript">
  var toggleFriendsPulldown = function(event, element, user_id) {
    event = new Event(event);
    if( $(event.target).get('tag') != 'a' ) {
      return;
    }
    $$('.profile_friends_lists_add').each(function(otherElement) {
      if( otherElement.id == 'user_friend_lists_' + user_id ) {
        return;
      }
      var pulldownElement = otherElement.getElement('.pulldown_active');
      if( pulldownElement ) {
        pulldownElement.addClass('pulldown').removeClass('pulldown_active');
      }
    });
    if( $(element).hasClass('pulldown') ) {
      element.removeClass('pulldown').addClass('pulldown_active');
    } else {
      element.addClass('pulldown').removeClass('pulldown_active');
    }
    OverText.update();
  }
  var handleFriendList = function(event, element, user_id, list_id) {
    new Event(event).stop();
    if( !$(element).hasClass('friend_list_joined') ) {
      // Add
      en4.user.friends.addToList(list_id, user_id);
      element.addClass('friend_list_joined').removeClass('friend_list_unjoined');
    } else {
      // Remove
      en4.user.friends.removeFromList(list_id, user_id);
      element.removeClass('friend_list_joined').addClass('friend_list_unjoined');
    }
  }
  var createFriendList = function(event, element, user_id) {
    var list_name = element.value;
    element.value = '';
    element.blur();
    var request = en4.user.friends.createList(list_name, user_id);
    request.addEvent('complete', function(responseJSON) {
      if( responseJSON.status ) {
        var topRelEl = element.getParent();
        $$('.profile_friends_lists_add ul').each(function(el) {
          var relEl = el.getElement('input').getParent();
          new Element('li', {
            'html' : '\n\
<span><a href="javascript:void(0);" onclick="deleteFriendList(event, ' + responseJSON.list_id + ');">x</a></span>\n\
<div>' + list_name + '</div>',
            'class' : ( relEl == topRelEl ? 'friend_list_joined' : 'friend_list_unjoined' ) + ' user_profile_friend_list_' + responseJSON.list_id,
            'onclick' : 'handleFriendList(event, $(this), \'' + user_id + '\', \'' + responseJSON.list_id + '\');'
          }).inject(relEl, 'before');
        });
        OverText.update();
      } else {
        //alert('whoops');
      }
    });
  }
  var deleteFriendList = function(event, list_id) {
    event = new Event(event);
    event.stop();

    // Delete
    scriptJquery('.user_profile_friend_list_' + list_id).remove();

    // Send request
    en4.user.friends.deleteList(list_id);
  }
  en4.core.runonce.add(function(){
    $$('.profile_friends_lists_add input').each(function(element) { new OverText(element); });
    
    <?php if( !$this->renderOne ): ?>
      var anchor = $('user_profile_friends').getParent();
    <?php endif; ?>

    $$('.friends_lists_menu_input input').each(function(element){
      element.addEvent('blur', function() {
        this.getParents('.drop_down_frame')[0].style.visibility = "hidden";
      });
    });
  });
  var tabId_pE3 = '<?php echo $this->identity; ?>';
  scriptJquery(document).ready(function() {
    tabContainerHrefSesbasic(tabId_pE3);	
  });
</script>

<?php if(!$this->is_ajax){ ?>
<ul id="<?php echo $randonNumber; ?>" class="sesmember_profile_friends sesbasic_bxs sesbasic_clearfix">
<?php } ?>
  <?php foreach( $this->paginator as $membership ):
    if( !isset($this->friendUsers[$membership->resource_id]) ) continue;
    $member = $this->friendUsers[$membership->resource_id];
    
    ?>
    <?php $userInfoItem = Engine_Api::_()->sesmember()->getUserInfoItem($member->getIdentity()); ?>
    <li class="sesmember_friends_lists sesbasic_clearfix" id="user_friend_<?php echo $member->getIdentity() ?>">
    	<div class="sesmember_friends_list_inner sesbasic_clearfix<?php if(isset($this->vipLabelActive) && $userInfoItem->vip):?> sesmeber_thumb_active_vip<?php endif;?>">
    	<div class="sesmember_friends_img sesmember_grid_btns_wrap">
      	<?php echo $this->htmlLink($member->getHref(), $this->itemPhoto($member, 'thumb.profile')) ?>
      	<?php if(isset($this->vipLabelActive) && $userInfoItem->vip):?>
          <div class="sesmember_vip_label" title="VIP" style="background-image:url(<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('member_vip_image', 'application/modules/Sesmember/externals/images/vip-label.png'); ?>);"></div>
        <?php endif;?>
        <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive)){ ?>
          <div class="sesmember_labels">
            <?php if(isset($this->featuredLabelActive) && $userInfoItem->featured){ ?>
              <p class="sesmember_label_featured">FEATURED</p>
            <?php } ?>
            <?php if(isset($this->sponsoredLabelActive) && $userInfoItem->sponsored){ ?>
              <p class="sesmember_label_sponsored">SPONSORED</p>
            <?php } ?>
          </div>
        <?php } ?>
            <?php if(isset($this->socialSharingActive) || isset($this->likeButtonActive) || isset($this->favouriteButtonActive)) {
      $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref()); ?>
        <div class="sesmember_grid_btns"> 
          <?php if(isset($this->socialSharingActive)){ ?>
          
          <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member)); ?>

          <?php } ?>
        </div>
      <?php } ?>
    </div>
      <div class='sesmember_friends_list_body'>
      <div class="sesmember_friends_list_info">
        <div class='profile_friends_status'>
          <span>
	    <?php if(isset($this->titleActive)){ ?>
	      <div class="sesbasic_sidebar_list_title">
          <?php echo $this->htmlLink($member->getHref(),$member->getTitle(), array('class' => 'ses_tooltip', 'data-src' => $member->getGuid())) ?>
          <?php if($userInfoItem->user_verified):?>
            <i class="sesbasic_verified_icon" title="Verified"></i>
                    <?php endif;?>
              </div>
            <?php } ?>
          </span>
        </div>
	<?php  if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && isset($this->locationActive) && $userInfoItem->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)){ ?>
	  <div class="sesmember_list_stats sesmember_list_location">
	    <span class="widthfull">
	      <i class="fa fa-map-marker" title="<?php echo $this->translate('Location');?>"></i>
	      <span title="<?php echo $userInfoItem->location; ?>"><a href="<?php echo $this->url(array('resource_id' => $member->user_id,'resource_type'=>'user','action'=>'get-direction'), 'sesbasic_get_direction', true); ?>" class="opensmoothboxurl"><?php echo $userInfoItem->location ?></a></span>
	    </span>
	  </div>
	<?php } ?>
	<?php if(isset($this->profileTypeActive)): ?> 
	  <div class="sesmember_list_stats sesmember_list_membertype "> 
	    <span class="widthfull">
      	<i class="far fa-user"></i>
        <span><?php echo Engine_Api::_()->sesmember()->getProfileType($member);?></span>
      </span>
	  </div>
	<?php endif;?>
	<?php if(Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible):?>
	  <?php if(isset($this->friendCountActive) && $fcount = $member->membership()->getMemberCount($member)): ?>  
	    <div class="sesmember_list_stats">
      	<span class="widthfull">
        	<i class="fa fa-users"></i>
        	<span><a href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo  $fcount. $this->translate(' Friends');?></a></span>
        </span>
      </div>
	  <?php endif;?>
	  <?php if(isset($this->mutualFriendCountActive) && ($viewer->getIdentity() && !$viewer->isSelf($member)) && $mcount =  Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer) ): ?>  
	    <div class="sesmember_list_stats">
      	<span class="widthfull">
        	<i class="fa fa-users"></i>
          <span><a href="<?php echo $this->url(array('user_id' => $member->user_id,'action'=>'get-mutual-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo $mcount. $this->translate(' Mutual Friends'); ?></a></span>
        </span>
      </div>
	  <?php endif;?>
    <?php if(isset($this->followActive) && $this->subject->membership()->getMemberCount($this->subject)): ?>
        <i class="fas fa-check" title="<?php echo $this->translate('Followers'); ?>"></i>
          <span>  
            <?php echo $this->translate(array('%s follower', '%s followers', engine_count(Engine_Api::_()->getDbTable('follows', 'sesmember')->getFollowers($member->user_id))),
            $this->locale()->toNumber(engine_count(Engine_Api::_()->getDbTable('follows', 'sesmember')->getFollowers($member->user_id)))) ?>
          </span>  
       <?php endif;?> 
	<?php endif;?>
	<div class="sesmember_list_stats">
	  <?php if(isset($this->likeActive) && isset($member->like_count)) { ?>
	    <span title="<?php echo $this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $member->like_count; ?></span>
	  <?php } ?>
	  <?php if(isset($this->viewActive) && isset($member->view_count)) { ?>
	    <span title="<?php echo $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count))?>"><i class="sesbasic_icon_view"></i><?php echo $member->view_count; ?></span>
	  <?php } ?>
	  <?php if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && $this->ratingActive && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view')){
	    echo '<span title="'.$this->translate(array('%s rating', '%s ratings', $userInfoItem->rating), $this->locale()->toNumber($userInfoItem->rating)).'"><i class="far fa-star"></i>'.round($userInfoItem->rating,1).'/5'. '</span>';
	  }
	  ?>
	</div>
  </div>
  <div class="sesmember_profile_bottom clear sesbasic_clearfix">
		<?php if( $this->viewer()->isSelf($this->subject) && Engine_Api::_()->getApi('settings', 'core')->getSetting('user.friends.lists')): // BEGIN LIST CODE ?>
		<div class='profile_friends_lists_add' id='user_friend_lists_<?php echo $member->user_id ?>'>
			<span class="pulldown" style="display:inline-block;" onClick="toggleFriendsPulldown(event, this, '<?php echo $member->user_id ?>');">
				<div class="pulldown_contents_wrapper">
					<div class="pulldown_contents">
						<ul>
							<?php foreach( $this->lists as $list ):
								$inList = engine_in_array($list->list_id, (array)@$this->listsByUser[$member->user_id]);
								?>
								<li class="<?php echo ( $inList !== false ? 'friend_list_joined' : 'friend_list_unjoined' ) ?> user_profile_friend_list_<?php echo $list->list_id ?>" onclick="handleFriendList(event, $(this), '<?php echo $member->user_id ?>', '<?php echo $list->list_id ?>');">
									<span>
										<a href="javascript:void(0);" onclick="deleteFriendList(event, <?php echo $list->list_id ?>);">x</a>
									</span>
									<div>
										<?php echo $list->title ?>
									</div>
								</li>
							<?php endforeach; ?>
							<li>
								<input type="text" title="<?php echo $this->translate('New list...') ?>" onclick="new Event(event).stop();" onkeypress="if( new Event(event).key == 'enter' ) { createFriendList(event, $(this), '<?php echo $member->user_id ?>'); }" />
							</li>
						</ul>
					</div>
				</div>
				<a href="javascript:void(0);"><?php echo $this->translate('add to list') ?></a>
			</span>
		</div>
			<?php endif; // END LIST CODE ?>
		<div class="sesmember_profile_bottom_btns">    
	  <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->friendButtonActive)):?>
	    <?php echo '<span>'.$this->partial('_addfriend.tpl', 'sesbasic', array('subject' => $member)).'</span>'; ?>
	  <?php endif;?>
	  <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->likemainButtonActive)):?>
	    <?php $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());?>
	    <?php $likeClass = (!$LikeStatus) ? 'fa-thumbs-up' : 'fa-thumbs-down' ;?>
	    <?php $likeText = ($LikeStatus) ?  $this->translate('Unlike') : $this->translate('Like') ;?>
	    <?php echo "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_button_like_user sesmember_button_like_user_". $member->user_id."'><i class='fa ".$likeClass."'></i><span><i class='fa fa-caret-down'></i>$likeText</span></a></span>";?>
	  <?php endif;?>
	  <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active',1)  && !Engine_Api::_()->user()->getViewer()->isSelf($member)){
	    $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
	    $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
	    $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'))  ;
	    echo "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa ".$followClass."'  title='$followText'></i> <span><i class='fa fa-caret-down'></i> Follow</span></a></span>"; 
	  }
	  ?>
	  <?php if (Engine_Api::_()->sesbasic()->hasCheckMessage($member) && isset($this->messageActive)): ?>
	    <?php $baseUrl = $this->baseUrl();?>
	    <?php $messageText = $this->translate('Message');?>
	    <?php echo "<span><a href=\"$baseUrl/messages/compose/to/$member->user_id\" target=\"_parent\" title=\"$messageText\" class=\"smoothbox sesbasic_btn sesmember_add_btn\"><i class=\"far fa-comment\"></i><span><i class=\"fa fa-caret-down\"></i>Message</span></a></span>"; ?>
	  <?php endif; ?> 
	</div>
  </div>
      </div>
      </div>
    </li>
  <?php endforeach ?>
  <?php if($this->loadOptionData == 'pagging'){ ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesmember"),array('identityWidget'=>$randonNumber)); ?>
    <?php } ?>
<?php if(!$this->is_ajax){ ?>
</ul>
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
					var containerId = '#<?php echo $randonNumber;?>';
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
	var widgetParams<?php echo $randonNumber; ?> = '<?php echo json_encode($this->widgetParams); ?>';
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
				'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/profile-friends",
				'data': {
					format: 'html',
					page: page<?php echo $randonNumber; ?>,    
					widgetParams : widgetParams<?php echo $randonNumber; ?>, 
					is_ajax : 1,
					subject_id:'<?php echo $this->subject->getIdentity(); ?>'
				},
				success: function(responseHTML) {
					scriptJquery('#<?php echo $randonNumber?>').append(responseHTML);
					scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
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
				'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/profile-friends",
				'data': {
					format: 'html',
					page: pageNum,
					widgetParams :widgetParams<?php echo $randonNumber; ?> , 
					is_ajax : 1,
					subject_id:'<?php echo $this->subject->getIdentity(); ?>'
				},
				success: function(responseHTML) {
					scriptJquery('#<?php echo $randonNumber?>').html(responseHTML);
					scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display', 'none');
				}
			}));
			
			return false;
		}
	<?php } ?>
</script>
