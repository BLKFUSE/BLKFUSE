<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sespymk
 * @package    Sespymk
 * @copyright  Copyright 2016-2017 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2017-03-03 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php $randonNumber = $this->identity ? $this->identity : rand(1,16); ?>

<?php if($this->anfheader): ?>
<!--<li class="sesbasic_clearfix">-->
  <div class="sesadvactivity_peopleyoumayknow">
    <h3><?php echo $this->translate('People You May Know'); ?></h3>
<?php endif; ?>

<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php 
  $baseURL = $this->layout()->staticBaseUrl;
  $this->headScript()->appendFile($baseURL . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($baseURL . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js'); 
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sespymk/externals/styles/styles.css'); ?>
<script type="text/javascript">
  var userWidgetRequestSend_<?php echo $randonNumber ?> = function(action, user_id, notification_id, event) {
  
    event.stopPropagation();
    var url;
    var randonNumber = '<?php echo $randonNumber; ?>';
    if( action == 'confirm' ) {
      url = '<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'friendspymk', 'action' => 'confirm'), 'default', true) ?>';
    } else if( action == 'reject' ) {
      url = '<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'friendspymk', 'action' => 'reject'), 'default', true) ?>';
    } else if( action == 'add' ) {
      url = '<?php echo $this->url(array('module' => 'sesbasic', 'controller' => 'friendspymk', 'action' => 'add'), 'default', true) ?>';
    } else {
      return false;
    }
    
    if(document.getElementById('sesbasic_loading_cont_overlay_'+randonNumber))
      document.getElementById('sesbasic_loading_cont_overlay_'+randonNumber).style.display='block';

    (scriptJquery.ajax({
      dataType: 'json',
      'url' : url,
      'data' : {
        'user_id' : user_id,
        'format' : 'json',
        'token' : '<?php echo $this->token() ?>'
      },
      success : function(responseJSON) {
        if(responseJSON.error) {
          if(document.getElementById('user-widget-request-' + notification_id))
            document.getElementById('user-widget-request-' + notification_id).innerHTML = responseJSON.error;
          if(document.getElementById('sespymk_user_' + notification_id+'_'+randonNumber))
            document.getElementById('sespymk_user_' + notification_id+'_'+randonNumber).innerHTML = responseJSON.error;
        } else {
          if(document.getElementById('user-widget-request-' + notification_id))
            document.getElementById('user-widget-request-' + notification_id).innerHTML = responseJSON.message;
          if(document.getElementById('sesbasic_loading_cont_overlay_'+randonNumber))
            document.getElementById('sesbasic_loading_cont_overlay_'+randonNumber).style.display='none';
          if(document.getElementById('sespymk_user_' + notification_id+'_'+randonNumber))
            document.getElementById('sespymk_user_' + notification_id+'_'+randonNumber).innerHTML = responseJSON.message;
          scriptJquery('.sespymk_user_'+notification_id+'_'+randonNumber).fadeOut("10000", function(){
            setTimeout(function() {
              scriptJquery('.sespymk_user_'+notification_id+'_'+randonNumber).parent().remove();
            }, 1000);
          });
        }
      }
    }));
  }
</script>
<div class="sespymk_horrizontal_list_more">
  <?php echo $this->htmlLink(array('route' => 'sespymk_general', 'module' => 'sespymk', 'controller' => 'index', 'action' => 'requests'), $this->translate("See All &raquo;")) ?>
</div>
<div class="sesbasic_bxs slide sespymk_carousel_wrapper sesbasic_clearfix <?php if($this->viewType == 'horizontal'): ?> sespymk_carousel_h_wrapper <?php else: ?> sespymk_carousel_v_wrapper <?php endif; ?>">
  <div id="suggestionfriend_<?php echo $randonNumber; ?>" class="sespymk_carousel sespymk_carousel_<?php echo $this->identity;?>">
    <?php foreach( $this->peopleyoumayknow as $item ):  ?>
      <?php $user = Engine_Api::_()->getItem('user', $item->user_id);?>
      <div id="sespymk_user_<?php echo $item->user_id ?>_<?php echo $randonNumber; ?>" class="prelative sespymk_user_<?php echo $item->user_id ?>_<?php echo $randonNumber; ?> sespymk_horrizontal_list_item sesbasic_clearfix" value="<?php echo $item->getIdentity();?>">
        <div class="sespymk_horrizontal_list_item_photo sesbasic_clearfix" style="height:<?php echo $this->heightphoto ?>px;">
          <?php echo $this->htmlLink($user->getHref(), $this->itemPhoto($user)) ?>
        </div>
        <div class="sespymk_horrizontal_list_item_cont">
        	<a href="javascript:void(0);" class="sespymk_horrizontal_list_remove fas fa-times" onclick='removePeopleYouMayKnow_<?php echo $randonNumber; ?>(<?php echo $user->getIdentity(); ?>, event)' title="<?php echo $this->translate('Remove');?>"></a>
          <div class="sespymk_horrizontal_list_item_title">
          	<a href="<?php echo $user->getHref(); ?>"><?php echo $user->getTitle(); ?></a>
          </div>
          
          <?php if($this->memberEnable): ?>
          	<div class="sespymk_horrizontal_list_item_stats">
              <?php if(engine_in_array('friends', $this->showdetails) && Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible && $cfriends = $user->membership()->getMemberCount($user) && !$this->viewer->isSelf($user)):?>
                <div class="sespymk_horrizontal_list_item_stat">
                  <a href="<?php echo $this->url(array('user_id' => $user->user_id,'action'=>'get-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo  $user->member_count. $this->translate(' Friends');?></a>
                </div>
              <?php endif;?>
              <?php if(engine_in_array('mutualfriends', $this->showdetails) && ($this->viewer->getIdentity() && !$this->viewer->isSelf($user)) && $mcount =  Engine_Api::_()->sesmember()->getMutualFriendCount($user, $this->viewer) ): ?> 
                <div class="sespymk_horrizontal_list_item_stat">
                  <a href="<?php echo $this->url(array('user_id' => $user->user_id,'action'=>'get-mutual-friends','format'=>'smoothbox'), 'sesmember_general', true); ?>" class="opensmoothboxurl"><?php echo $mcount. $this->translate(' Mutual Friends'); ?></a>
                </div>
              <?php endif;?>
            </div>
          <?php endif; ?>
        	<div class="sespymk_horrizontal_list_item_btn sespymk_buttons">
        	  <div class="sespymk_add_button"> 
            	<button type="submit" class="sesbasic_animation" onclick='userWidgetRequestSend_<?php echo $randonNumber ?>("add", <?php echo $this->string()->escapeJavascript($user->user_id) ?>, <?php echo $user->user_id ?>, event)'><?php echo $this->translate('Add Friend');?></button>
            </div>
          	
          	<?php if($this->viewer_id && Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('sesmember')) { ?>
              <?php
                $getFollowResourceStatus = Engine_Api::_()->sesmember()->getFollowResourceStatus($user->user_id);
                $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($user->user_id);
                $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
                $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow'))  ;
                $getFollowUserStatus = Engine_Api::_()->sesmember()->getFollowUserStatus($user->user_id);
              ?>
              <div class="sespymk_follow_button">
                <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.autofollow', 1)) {  ?>
                  <?php if($FollowUser && $getFollowResourceStatus['user_approved'] == 1 && $getFollowResourceStatus['resource_approved'] == 1) {  ?>
                    <a href='javascript:;' data-widgte='1' data-url='<?php echo $user->getIdentity(); ?>' class='sesbasic_animation sesmember_follow_user sesmember_follow_user_<?php echo $user->getIdentity(); ?>'><i class='fa <?php echo $followClass ; ?>'></i><span><?php echo $followText; ?></span></a> 
                  <?php } else if($getFollowResourceStatus &&  $getFollowResourceStatus['user_approved'] == 0 && $getFollowResourceStatus['resource_approved'] == 1) { ?>
                    <a href='javascript:;' data-widgte='1' data-url='<?php echo $user->getIdentity(); ?>' class='sesbasic_animation sesmember_follow_user sesmember_follow_user_<?php echo $user->getIdentity(); ?>'><i class='fa fa-times'></i><span><?php echo $this->translate('Cancel Follow Request'); ?></span></a> 
                  <?php } else if(empty($FollowUser) && empty($getFollowResourceStatus)) { ?>
                    <a href='javascript:;' data-widgte='1' data-url='<?php echo $user->getIdentity(); ?>' class='sesbasic_animation sesmember_follow_user sesmember_follow_user_<?php echo $user->getIdentity(); ?>'><i class='fa fa-check'></i><span><?php echo $this->translate('Follow'); ?></span></a> 
                  <?php } ?>
                <?php } else { ?>
                  <a href='javascript:;' data-widgte='1' data-url='<?php echo $user->getIdentity(); ?>' class='sesbasic_animation sesmember_follow_user sesmember_follow_user_<?php echo $user->getIdentity(); ?>'><i class='fa <?php echo $followClass ; ?>'></i><span><?php echo $followText; ?></span></a>
                <?php } ?>
              </div>
          	<?php } ?>
        	</div>
        </div>
        <div class="sesbasic_loading_cont_overlay" id="sesbasic_loading_cont_overlay_<?php echo $randonNumber; ?>"></div>  
      </div>
    <?php endforeach; ?>
  </div>
</div>

<script type="text/javascript">

  sesowlJqueryObject(document).ready(function() {
    sesowlJqueryObject('#suggestionfriend_<?php echo $randonNumber; ?>').owlCarousel({
      loop:false,
      dots:false,
      nav:true,
      margin:0,
    <?php if($orientation = ($this->layout()->orientation == 'right-to-left')){ ?>
      rtl:true,
    <?php } ?>
		<?php if($this->viewType == 'horizontal'): ?>
			items:4,
			autoWidth:true,
			margin:5,
  	<?php else: ?>
			items:1,
		<?php endif; ?>
	})
    sesowlJqueryObject(".owl-prev").html('<i class="fas fa-chevron-left"></i>');
    sesowlJqueryObject(".owl-next").html('<i class="fas fa-chevron-right"></i>');
  });

  en4.core.runonce.add(function() {
    var duration = 150,
    div = document.getElement('div.tabs_<?php echo $randonNumber; ?>');
    links = div.getElements('a'),
    carousel = new Carousel.Extra({
      activeClass: 'selected',
      container: 'suggestionfriend<?php echo $randonNumber; ?>',
      circular: false,
      current: 1,
      previous: links.shift(),
      next: links.pop(),
      tabs: links,
      mode: '<?php echo $this->viewType; ?>',
      fx: {
        duration: duration
      }
    })
  });

  function removePeopleYouMayKnow_<?php echo $randonNumber; ?>(id, event) {
    //event.stopPropagation();
    var randonNumber = '<?php echo $randonNumber; ?>';
    
    scriptJquery('.sespymk_user_'+id+'_'+randonNumber).fadeOut("slow", function(){
      scriptJquery('.sespymk_user_'+id+'_'+randonNumber).parent().remove();
      scriptJquery('.sespymk_carousel_nav_nxt').trigger('click');
    });
   
    if(document.getElementById('sespymk_user_main')) {
      if (document.getElementById('sespymk_user_main').length == 0) {
        document.getElementById('sespymk_user_main').innerHTML = "<div class='tip' id=''><span><?php echo $this->translate('There are no more members.');?> </span></div>";
      }
    }
  }
</script>
<?php if($this->anfheader): ?>
</div>
<!--  </li>-->
<?php endif; ?>
