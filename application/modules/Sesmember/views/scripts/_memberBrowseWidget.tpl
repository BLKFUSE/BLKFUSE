<?php

 /**
 * socialnetworking.solutions
 *
 * @category   Application_Modules
 * @package    Sesmember
 * @copyright  Copyright 2014-2020 Ahead WebSoft Technologies Pvt. Ltd.
 * @license    https://socialnetworking.solutions/license/
 * @version    $Id: _memberBrowseWidget.tpl 2016-05-25 00:00:00 socialnetworking.solutions $
 * @author     socialnetworking.solutions
 */
 
?>
<?php if(!$this->is_ajax){ ?>
  <style>
  .displayFN, .dNone{display:none !important;}
  .dBlock{display:block !important;}
  </style>
	<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/scripts/core.js'); ?>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmember/externals/styles/styles.css'); ?> 
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/styles.css'); ?> 
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/member/membership.js'); ?>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>
  
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php  }  ?>
<?php if(isset($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable) && !$this->is_ajax){ 
   $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/imagesloaded.pkgd.js'); $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/pinboard.css'); 
   $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/wookmark.min.js');
   $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/pinboardcomment.js');
} ?>
<?php $viewer = Engine_Api::_()->user()->getViewer();?>
<?php $listViewData = $advListViewData = $gridViewData = $pinViewData = $advgridViewData = '';?>
<?php $locationArray = array();?>
<?php $counter = 0;?>
<?php if(!$this->is_ajax){ ?>
  <div class="sesbasic_view_type sesbasic_view_type_<?php echo $randonNumber ?> sesbasic_clearfix clear">
    <div class="sesbasic_view_type_options sesbasic_view_type_options_<?php echo $randonNumber;?>">
      <?php if(is_array($this->optionsEnable) && engine_in_array('list',$this->optionsEnable)){ ?>
	<a title="List View" class="listicon list_selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'list') { echo 'active'; } ?>" rel="list" href="javascript:showData_<?php echo $randonNumber; ?>('list');"></a>
      <?php } ?>
      <?php if(is_array($this->optionsEnable) && engine_in_array('advlist',$this->optionsEnable)){ ?>
	<a title="Advanced List View" class="a-listicon adv_list_selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'advlist') { echo 'active'; } ?>" rel="advlist" href="javascript:showData_<?php echo $randonNumber; ?>('advlist');"></a>
      <?php } ?>
      <?php if(is_array($this->optionsEnable) && engine_in_array('grid',$this->optionsEnable)){ ?>
	<a title="Grid View" class="gridicon grid_selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'grid') { echo 'active'; } ?>" rel="grid" href="javascript:showData_<?php echo $randonNumber; ?>('grid');"></a>
      <?php } ?>
      <?php if(is_array($this->optionsEnable) && engine_in_array('advgrid',$this->optionsEnable)){ ?>
	<a title="Advanced Grid View" class="a-gridicon advgrid_selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'advgrid') { echo 'active'; } ?>" rel="advgrid" href="javascript:showData_<?php echo $randonNumber; ?>('advgrid');"></a>
      <?php } ?> 
      <?php if(is_array($this->optionsEnable) && engine_in_array('pinboard',$this->optionsEnable)){ ?>
	<a title="Pinboard View" class="boardicon pin_selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'pinboard') { echo 'active'; } ?>" rel="pinboard" href="javascript:showData_<?php echo $randonNumber; ?>('pinboard');"></a>
      <?php } ?>
      <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && is_array($this->optionsEnable) && engine_in_array('map',$this->optionsEnable) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)){?>
        <a title="Map View" class="mapicon map_selectView_<?php echo $randonNumber;?> <?php if($this->view_type == 'map') { echo 'active'; } ?>" rel="map" href="javascript:showData_<?php echo $randonNumber; ?>('map');"></a>
      <?php } ?>
    </div>
  </div>
<?php } ?>
<?php if(!isset($this->bothViewEnable) && !$this->is_ajax){ ?>
  <script type="text/javascript">
      en4.core.runonce.add(function() {
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').addClass('displayFN');
          scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').parent().parent().css('border', '0px');
      });
  </script>
<?php } ?>
<?php if(!$this->is_ajax){?>
  <script type="text/javascript">scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').css('display', 'block');</script>
<?php } ?>
<?php if( engine_count($this->paginator) > 0 ): ?>
  <?php foreach( $this->paginator as $member ): ?>
    <?php 
      if(strlen($member->getTitle()) > $this->list_title_truncation)
      $listViewTitle = mb_substr($member->getTitle(),0,($this->list_title_truncation-3)).'...';
      else
      $listViewTitle = $member->getTitle();
      
      if(strlen($member->getTitle()) > $this->grid_title_truncation) 
      $gridViewTitle = mb_substr($member->getTitle(),0,($this->grid_title_truncation-3)).'...';
      else
      $gridViewTitle = $member->getTitle();
      
      if(strlen($member->getTitle()) > $this->advgrid_title_truncation) 
      $advGridViewTitle = mb_substr($member->getTitle(),0,($this->advgrid_title_truncation-3)).'...';
      else
      $advGridViewTitle = $member->getTitle();
      
      if(strlen($member->getTitle()) > $this->pinboard_title_truncation) 
      $pinboardViewTitle = mb_substr($member->getTitle(),0,($this->pinboard_title_truncation-3)).'...';
      else
      $pinboardViewTitle = $member->getTitle();
    ?> 
    <?php $customFileds = $customFileds = $message = $memberType = $colorCategory = $userEmail = $email = $friendCount = $mutualFriendCount = '';?>
    <?php if(isset($this->profileTypeActive)): ?>            
      <?php $memberType = '<div class="sesmember_list_stats sesmember_list_membertype "> <span><span class="_label">' .$this->translate("Member").'</span><span class=\'sesbasic_text_light\'>' .$this->translate(Engine_Api::_()->sesmember()->getProfileType($member)). '</span></span></div>'; ?>
    <?php endif; ?>
    <?php $memberAge =  $this->partial('_userAgelabel.tpl', 'sesmember', array('ageActive' => $this->ageActive, 'member' => $member)); ?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->user_friends_eligible):?>
      <?php if(isset($this->friendCountActive) && $cfriend = $member->membership()->getMemberCount($member)): ?>   
	<?php $friendCount = '<div class="sesmember_list_stats"><span><span class="_label">' .$this->translate("Friends").'</span><span><a href="'.$this->url(array('user_id' => $member->user_id,'action'=>'get-friends','format'=>'smoothbox'), 'sesmember_general', true).'" class="opensmoothboxurl">'. $cfriend. '</a></span></span></div>';?>
      <?php endif;?>
      <?php if(isset($this->mutualFriendCountActive) && ($viewer->getIdentity() && !$viewer->isSelf($member)) && $mfriend = Engine_Api::_()->sesmember()->getMutualFriendCount($member, $viewer)): ?>   
	<?php $mutualFriendCount = '<div class="sesmember_mutual_friends"><span class="widthfull"><span><a href="'.$this->url(array('user_id' => $member->user_id,'action'=>'get-mutual-friends','format'=>'smoothbox'), 'sesmember_general', true).'" class="opensmoothboxurl">'.$this->translate(array('%s Mutual', '%s Mutual', $mfriend), $this->locale()->toNumber($mfriend)). '</a></span></span></div>';?>
      <?php endif;?>
    <?php endif;?>
    
    <?php $friend = '';?>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->friendButtonActive)):?>
      <?php $friend =   $this->partial('_addfriend.tpl', 'sesbasic', array('subject' => $member)); ?>
    <?php endif;?>
    
    <?php $vipMember = '';?>
    <?php $vipactiveclass = ''?>
    <?php if($member->vip):?>
      <?php $vipactiveclass="sesmeber_thumb_active_vip" ?>
      <?php if(isset($this->vipLabelActive)):?>
				<?php $vipMember = '<div class="sesmember_vip_label" title="VIP" style="background-image:url('.Engine_Api::_()->getApi('settings', 'core')->getSetting('member_vip_image', 'application/modules/Sesmember/externals/images/vip-label.png').');"></div>';?>
      <?php endif;?>
    <?php endif;?>
    
    <?php $userOnline = '';?>
    <?php $online = Engine_Api::_()->sesmember()->checkMemberOnline($member->user_id);?>
    <?php if($online):?>
      <?php $userOnline = "<span class=\"sesmember_user_online\"><i class=\"fa fa-circle\"></i><span>".$this->translate("Online")."</span></span>";?>
    <?php endif;?> 
    <?php 
      $likeMainButton = '';
      if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->likeButtonActive)){
	$LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
	$likeClass = (!$LikeStatus) ? 'fa-thumbs-up' : 'fa-thumbs-down' ;
	$likeText = ($LikeStatus) ?  $this->translate('Unlike') : $this->translate('Like') ;
	$likeMainButton = "<a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_button_like_user sesmember_button_like_user_". $member->user_id."'><i class='fa ".$likeClass."'></i><span><i class='fa fa-caret-down'></i>$likeText</span></a>";
      }
    ?>
    
    <?php $followButton = '';?>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->followButtonActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.active',1) && Engine_Api::_()->user()->getViewer()->getIdentity() != $member->getIdentity()){
//       $FollowUser = Engine_Api::_()->sesmember()->getFollowStatus($member->user_id);
//       $followClass = (!$FollowUser) ? 'fa-check' : 'fa-times' ;
//       $followText = ($FollowUser) ?  $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.unfollowtext','Unfollow')) : $this->translate(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.follow.followtext','Follow')) ;
//       
//       $followButton =  "<span><a href='javascript:;' data-url='".$member->getIdentity()."' class='sesbasic_btn sesmember_add_btn sesmember_follow_user sesmember_follow_user_".$member->getIdentity()."'><i class='fa ".$followClass."'  title='$followText'></i> <span><i class='fa fa-caret-down'></i>$followText</span></a></span>";

      $followButton = $this->partial('_followmemberslabel.tpl', 'sesmember', array('subject' => $member));
    }
    ?>

    
    <?php $message = '';?>
    <?php if (Engine_Api::_()->sesbasic()->hasCheckMessage($member) && isset($this->messageActive)): ?>
      <?php $baseUrl = $this->baseUrl();?>
      <?php $messageText = $this->translate('Message');?>
      <?php $message = "<a href=\"$baseUrl/messages/compose/to/$member->user_id\" target=\"_parent\" title=\"$messageText\" class=\"smoothbox sesbasic_btn sesmember_add_btn\"><i class=\"far fa-comment\"></i><span><i class=\"fa fa-caret-down\"></i>Message</span></a>"; ?>
    <?php endif; ?>              
    
    <!--Show Profile Fields of members-->   
    
    <?php $location = '';?>
    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && isset($this->locationActive) && $member->location && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmember.enable.location', 1)):?>
      <?php $locationText = $this->translate('Location');?>
      <?php $locationvalue = $member->location;?>
      <?php $location = "<div class=\"sesmember_list_stats sesmember_list_location\"><span class=\"widthfull\">
      <span class=\"_label\">Location</span>
      <span title=\"$locationvalue\"><a href='".$this->url(array('resource_id' => $member->user_id,'resource_type'=>'user','action'=>'get-direction'), 'sesbasic_get_direction', true)."' class=\"opensmoothboxurl\">$locationvalue</a></span></span></div>"; 
      ?>
    <?php endif;?>
    <?php $labels = '';?>
    <?php $statstics = '';?>
    <?php $advlistStatstics = '';?>
    <?php $like_count = $member->like_count;?>
    <?php $followCount = engine_count(Engine_Api::_()->getDbTable('follows', 'sesmember')->getFollowers($member->user_id));?>
    <?php 
    $shareOptionsListView = '';
    $shareOptionsAdvListView = '';
    $shareOptionGridView = '';
    $shareOptionPinView = '';
    
    
    
    
    ?>  
    <?php 
    $memberTitle = '';
    $memberListTitle = '<div class="sesmember_list_info_title sesbasic_clearfix">';
     $memberPinboardTitle = '';
     $memberratingstar= '';
    if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && isset($this->ratingActive)){
    	$memberratingstar = $this->partial('_userRating.tpl', 'sesmember', array('rating' => $member->rating));
    }
    
    if(isset($this->titleActive)){
      $memberListTitle .= "<span class=\"sesmember_list_title\">
      ".$this->htmlLink($member->getHref(), $listViewTitle,array('class'=>'ses_tooltip','id'=>'member_title_'.$member->getGuid(),'data-src'=>$member->getGuid()))."
      </span>";
      $memberGridTitle = "<div class=\"sesmember_list_title\">
      ".$this->htmlLink($member->getHref(), $gridViewTitle,array('class'=>'ses_tooltip','data-src'=>$member->getGuid()))."</div>";
      
      $memberPinboardTitle = "<div class=\"sesbasic_pinboard_list_item_title\">
      ".$this->htmlLink($member->getHref(), $pinboardViewTitle,array('class'=>'ses_tooltip','data-src'=>$member->getGuid()))."</div>";
      
       $memberAdvGridTitle = "<div class=\"sesmember_member_grid_title sesbasic_clearfix\">
      ".$this->htmlLink($member->getHref(), $advGridViewTitle,array('class'=>'ses_tooltip sesbasic_linkinherit','data-src'=>$member->getGuid()))."</div>";
    }
    
    if(isset($this->verifiedLabelActive) && $member->user_verified == 1) {
     $memberAdvGridTitle = $memberPinboardTitle = $memberGridTitle = $memberListTitle .= '<i class="sesmember_verified_sign_'.$member->user_id.' sesbasic_verified_icon" title="Verified"></i></div>';
    }
    else {
      $memberAdvGridTitle = $memberPinboardTitle = $memberGridTitle = $memberListTitle .= '<i class="sesmember_verified_sign_'.$member->user_id.' sesbasic_verified_icon" style="display:none;"></i></div>';
    }

    $view_count = $member->view_count; 
    $hoverLikes = $this->translate(array('%s like', '%s likes', $like_count), $this->locale()->toNumber($like_count));
    $hoverFollowers = $this->translate(array('%s follow', '%s follow', $followCount), $this->locale()->toNumber($followCount));
    $hoverViews = $this->translate(array('%s view', '%s views', $view_count), $this->locale()->toNumber($view_count));
    
    $advlistStatstics .= "";
    
    if(isset($this->likeActive) && isset($member->like_count))
    $statstics .= "<span title=\"$hoverLikes\"><i class=\"sesbasic_icon_like_o\"></i>$like_count</span>";
    $advlistStatstics .= "<span title=\"$hoverLikes\">$hoverLikes</span>";
    
    if(isset($this->followActive)) {
        $statstics .= "<span title=\"$hoverFollowers\"><i class=\"fa fa-check\"></i>$followCount</span>";
        $advlistStatstics .= "<span title=\"$hoverFollowers\">$hoverFollowers</span>";
    }
    
    if(isset($this->viewActive) && isset($view_count))
    $statstics .= "<span title=\"$hoverViews\"><i class=\"sesbasic_icon_view\"></i>$view_count</span>";
    $advlistStatstics .= "<span title=\"$hoverViews\">$hoverViews</span>";
    if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && isset($this->ratingActive) && Engine_Api::_()->sesbasic()->getViewerPrivacy('sesmember_review', 'view')){
      $statstics .= '<span title="'.$this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating)).'"><i class="fa fa-star"></i>'.round($member->rating,1).'/5'. '</span>';
      $advlistStatstics .= '<span title="'.$this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating)).'">'.$this->translate(array('%s/5 rating', '%s/5 ratings', $member->rating), round($member->rating,1)). '</span>';
    }
		$advlistStatstics .= "</span></span>";
		
    if((isset($this->socialSharingActive) || isset($this->likeButtonActive)) && $member->approved) {
      $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref());
      $shareOptionsListView .= "<div class='sesmember_grid_btns'>";
      if(isset($this->socialSharingActive)) {
        $shareOptionsListView .= $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'param' => 'feed', 'socialshare_enable_plusicon' => $this->socialshare_enable_plusiconlistview, 'socialshare_icon_limit' => $this->socialshare_icon_limitlistview));
      }
      if(isset($this->likeButtonActive)){
        $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
        $likeClass = ($LikeStatus) ? ' button_active' : '' ;
        $shareOptionsListView .= "<a href='javascript:;' data-url=\"$member->user_id\" class='sesbasic_icon_btn sesmember_like_user_". $member->user_id." sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user ".$likeClass ." '><i class='fa fa-thumbs-up'></i><span>$member->like_count</span></a>";
      }
      $shareOptionsListView .= "</div>";
    }
    
    
    if((isset($this->socialSharingActive) || isset($this->likeButtonActive)) && $member->approved) {
      $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref());
      $shareOptionsAdvListView .= "<div class='sesmember_grid_btns'>";
      if(isset($this->socialSharingActive)) {
        $shareOptionsAdvListView .= $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'param' => 'feed', 'socialshare_enable_plusicon' => $this->socialshare_enable_plusiconadvlistview, 'socialshare_icon_limit' => $this->socialshare_icon_limitadvlistview));
      }
      if(isset($this->likeButtonActive)){
        $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
        $likeClass = ($LikeStatus) ? ' button_active' : '' ;
        $shareOptionsAdvListView .= "<a href='javascript:;' data-url=\"$member->user_id\" class='sesbasic_icon_btn sesmember_like_user_". $member->user_id." sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user ".$likeClass ." '><i class='fa fa-thumbs-up'></i><span>$member->like_count</span></a>";
      }
      $shareOptionsAdvListView .= "</div>";
    }
    
    if((isset($this->socialSharingActive) || isset($this->likeButtonActive)) && $member->approved) {
      $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref());
      $shareOptionGridView .= "<div class='sesmember_grid_btns'>";
      if(isset($this->socialSharingActive)) {
        $shareOptionGridView .= $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'param' => 'feed', 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicongridview, 'socialshare_icon_limit' => $this->socialshare_icon_limitgridview));
      }
      if(isset($this->likeButtonActive)){
        $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
        $likeClass = ($LikeStatus) ? ' button_active' : '' ;
        $shareOptionGridView .= "<a href='javascript:;' data-url=\"$member->user_id\" class='sesbasic_icon_btn sesmember_like_user_". $member->user_id." sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user ".$likeClass ." '><i class='fa fa-thumbs-up'></i><span>$member->like_count</span></a>";
      }
      $shareOptionGridView .= "</div>";
    }
    
    
    if((isset($this->socialSharingActive) || isset($this->likeButtonActive)) && $member->approved) {
      $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref());
      $shareOptionPinView .= "<div class='sesmember_grid_btns'>";
      if(isset($this->socialSharingActive)) {
        $shareOptionPinView .= $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'param' => 'feed', 'socialshare_enable_plusicon' => $this->socialshare_enable_plusiconpinview, 'socialshare_icon_limit' => $this->socialshare_icon_limitpinview));
      }
      if(isset($this->likeButtonActive)){
        $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
        $likeClass = ($LikeStatus) ? ' button_active' : '' ;
        $shareOptionPinView .= "<a href='javascript:;' data-url=\"$member->user_id\" class='sesbasic_icon_btn sesmember_like_user_". $member->user_id." sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user ".$likeClass ." '><i class='fa fa-thumbs-up'></i><span>$member->like_count</span></a>";
      }
      $shareOptionPinView .= "</div>";
    }
    
    $shareoptionsAdv = '';
    if((isset($this->socialSharingActive) || isset($this->likeButtonActive)) && $member->approved) {
      $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref());
      $shareoptionsAdv .= "<div class='sesmember_grid_btns sesmember_grid_btns'>";
      if(isset($this->socialSharingActive)) {
        $shareoptionsAdv .= $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'param' => 'feed', 'socialshare_enable_plusicon' => $this->socialshare_enable_plusiconadvgridview, 'socialshare_icon_limit' => $this->socialshare_icon_limitadvgridview));
      }
      if(isset($this->likeButtonActive)){
        $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
        $likeClass = ($LikeStatus) ? ' button_active' : '' ;
        $shareoptionsAdv .= "<a href='javascript:;' data-url=\"$member->user_id\" class='sesbasic_icon_btn sesmember_like_user_". $member->user_id." sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user ".$likeClass ." '><i class='fa fa-thumbs-up'></i><span>$member->like_count</span></a>";
      }
      $shareoptionsAdv .= "</div>";
    }
    ?>
    <?php 
    //ratings
    $ratings ='';
    if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating()){
      $ratings .= '
      <span class="sesmember_list_grid_rating" title="'.$this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating)).'">';?>
      <?php if( $member->rating > 0 ): 
      for( $x=1; $x<= $member->rating; $x++ ): 
      $ratings .= '<span class="sesbasic_rating_star_small fa fa-star"></span>';
      endfor; 
      if( (round($member->rating) - $member->rating) > 0): 
      $ratings.= '<span class="sesbasic_rating_star_small fa fa-star-half"></span>';
      endif; 
      endif;  
      $ratings .= '</span>';
    }
  
    // Show Label
    if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)) {
      $labels .= "<div class=\"sesmember_labels\">";
      if(isset($this->featuredLabelActive) && $member->featured == 1) 
      $labels .= "<p class=\"sesmember_label_featured\">".$this->translate('FEATURED')."</p>";
      if(isset($this->sponsoredLabelActive) && $member->sponsored == 1) 
      $labels .= "<p class=\"sesmember_label_sponsored\">".$this->translate('SPONSORED')."</p>";
      if(isset($this->verifiedLabelActive) && $member->user_verified == 1) 
      $labels .= "";
      $labels .= "</div>";
    }
    $advLabels = '';
    if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)) {
      $advLabels .= "<div class=\"sesmember_labels\">";
      if(isset($this->featuredLabelActive) && $member->featured == 1) 
      $advLabels .= "<p class=\"sesmember_label_featured\">".$this->translate('FEATURED')."</p>";
      if(isset($this->sponsoredLabelActive) && $member->sponsored == 1) 
      $advLabels .= "<p class=\"sesmember_label_sponsored\">".$this->translate('SPONSORED')."</p>";
      $advLabels .= "</div>";
    }

    $href = $member->getHref(); 
    $imageURL = $member->getPhotoUrl('thumb.profile'); 
    $height = is_numeric($this->height) ? $this->height.'px' : $this->height;
    $width = is_numeric($this->width) ? $this->width.'px' : $this->width;
    $listContainerHeight = is_numeric($this->list_container_height) ? $this->list_container_height.'px' : $this->list_container_height;
    $listContainerWidth = is_numeric($this->list_container_width) ? $this->list_container_width.'px' : $this->list_container_width;
    ?>
    
   <?php $list = "<li class='sesmembers_list_view sesbm sesbasic_clearfix sesbasic_bxs'>
    <div class='sesmeber_thumb_main_block sesbasic_clearfix ".$vipactiveclass."'>
    <div class='sesmember_thumb sesmember_grid_btns_wrap' style='height:". $listContainerWidth.";width:". $listContainerWidth.";'>
    <a href='".$href."' class='sesmember_thumb_img' data-src=".$member->getGuid().">
    <span style='background-image:url(".$imageURL.");' class='sesbasic_animation'></span></a> $vipMember";?>
    
    <?php if(!$member->approved){ 
      $list  .= "<span class='sesmember_unapproved_label'>".$this->translate("Unapproved")."</span>";
    } ?>
    <?php
    $list  .=
    " $shareOptionsListView
    $labels
    $userOnline
    </div> 
    <div class=\"sesmembers_list_info\">
    ".$memberListTitle  . $mutualFriendCount . $memberratingstar  ." ";?>
    <?php
    $list .= "<div class='sesmember_list_block_info clear'>$memberAge $memberType $location $friendCount
    </div> ";?>
    <?php
    $list .= "
    <div class=\"sesmember_list_block_stats\">
    ".$advlistStatstics."
    </div>";	
    $list .="<div class='sesmember_list_stats_btn sesmember_list_add_btn'><span>$friend</span><span>$followButton</span><span>$message</span>
    </div>";
    if(isset($this->viewDetailsLinkActive)){
      $list .= "</div>";
      }?>
    
    <?php 
     if($customFileds != '')
     $list .=  "<div class='sesmembers_list_fields sesmembers_list_info'>$customFileds</div>" ;?>
   <?php 
      
     $list .='</li>'; ?>
    
		<?php $advlist = "<li class='sesmember_list_block sesbasic_clearfix sesbasic_bxs ".$vipactiveclass."'>
    <div class='sesmember_list_block_inner sesbasic_clearfix'>
    <div class='sesmember_list_block_left sesmember_grid_btns_wrap' style='height:". $height.";width:". $width.";'>
    <a href='".$href."' class='sesmember_list_block_img' data-src=".$member->getGuid().">
    <span style='background-image:url(".$imageURL.");' class='sesbasic_animation'></span></a> $vipMember";?>
    
    <?php if(!$member->approved){ 
      $advlist  .= "<span class='sesmember_unapproved_label'>".$this->translate("Unapproved")."</span>";
    } ?>
    <?php
    $advlist  .=
    " $shareOptionsAdvListView
    $labels
    </div> 
    <div class=\"sesmember_list_block_middle  \"><div class=\"sesmember_list_block_middle_top sesbasic_clearfix\">".$memberListTitle . $mutualFriendCount . $memberratingstar .$userOnline  ."</div> <div class=\"sesmember_list_block_stats\">
    ".$advlistStatstics."
    </div> ";?>
    <?php
    $advlist .= "<div class='sesmember_list_block_info_left clear'>$memberAge $friendCount $memberType $location
    </div> <div class='sesmember_list_stats sesmember_list_add_btn'>
    	 <span>$friend</span><span>$followButton</span><span>$message</span>
    </div> ";?>
    
    <?php
     if($customFileds != '')
     $advlist .=  "<div class='sesmember_list_block_info_right'><div class='sesmembers_list_fields'>$customFileds</div></div>" ;?>
    <?php $list .='</li>'; ?>
    
    
    <?php $pinboardWidth =  is_numeric($this->pinboard_width) ? $this->pinboard_width.'px' : $this->pinboard_width ; ?>
    <?php $featuredPhotos = array(); ?>
    <?php $memberPhoto = $member->getPhotoUrl('thumb.profile');  ?>
    
    <?php if(engine_count($featuredPhotos) > 0){ 
    		$featuredSlideshow = 'sesmember_slideshow_pinboard';
        $classFl = 'floatL';
        $slideshowStyle = "width:$pinboardWidth;height:250px;object-fit:cover;";
        $customNavPin = "<div class=\"clearfix\"></div><a class=\"prev\" href=\"javascript:;\"><i class='fa fa-angle-left'></i></a><a class=\"next\" href=\"javascript:;\"><i class='fa fa-angle-right'></i></a>";
     	}else{ 
    		$featuredSlideshow = '';
        $classFl = '';
        $slideshowStyle = '';
        $customNavPin = '';
      }
    ?>
    <?php $advListViewData .= $advlist;?>
    <?php $listViewData .= $list;?>
    <?php
    $pinboard = "<li class=\"sesmember_grid_btns_wrap sesbasic_bxs sesbasic_pinboard_list_item_wrap new_image_pinboard_".$randonNumber."\" style='width:$pinboardWidth;'>
    <div class=\"sesmember_pinboard_list_item sesbm\">
    <div class=\"sesbasic_pinboard_list_item_top\">"; ?>
    
    <?php if(engine_count($featuredPhotos) > 0){ 
     $pinboard .= "<div class=\"sesbasic_pinboard_list_item_slideshow\" style=\"height:250px;\">"; ?>
    <?php } ?>
    <?php
   	$pinboard .= "<div class=\"sesbasic_pinboard_list_item_thumb ".$classFl." ".$featuredSlideshow."\">
      <a href=\"".$member->getHref()."\" class=\"".$classFl."\">
        <img src=\"".$memberPhoto."\" class=\"thumb_profile item_photo_user  thumb_profile\" style=\"".$slideshowStyle."\">
      </a>"; ?>
    
   <?php
    $pinboard .= "</div>". $customNavPin ;
   	  if(engine_count($featuredPhotos) > 0){ 
    	 $pinboard .= "</div>";
     }
      $pinboard .= $vipMember;
   ?>				
    <?php if(!$member->approved){ 
    $pinboard .= "<span class='sesmember_unapproved_label'>".$this->translate("Unapproved")."</span>";
    } ?>
    <?php 

    $pinboard .= "
    $shareOptionPinView
    $labels            
    $mutualFriendCount       
    </div>
    <div class=\"sesbasic_pinboard_list_item_cont sesbasic_clearfix\">
    $userOnline $memberPinboardTitle 
    $memberratingstar $location"
    ?>
    <?php $pinboard .= "<div class='sesmember_member_info_middle'>$memberAge $memberType $friendCount</div>"; ?> 
    <?php $pinboard .= "  <div class='sesmember_add_btn_bg'><div class='sesmember_list_stats sesmember_list_add_btn'>
    	<span>$friend</span><span>$followButton</span><span>$message</span></div></div>
    
      "?>
      <?php if($statstics != ''){ ?>
   	<?php $pinboard .='<div class="sesmember_list_block_stats">'. $advlistStatstics. '</div>' .$customFileds; ?>
    <?php } ?>
    <?php "</li>"; ?>
     
    <?php $pinViewData .= $pinboard;?>
    <?php $href = $member->getHref();$imageURL = $member->getPhotoUrl('thumb.profile');?>
    <?php $photoWidth =  is_numeric($this->photo_width) ? $this->photo_width.'px' : $this->photo_width ?>
    <?php $photoHeight =  is_numeric($this->photo_height) ? $this->photo_height.'px' : $this->photo_height ?>
    <?php $infoHeight =  is_numeric($this->info_height) ? $this->info_height.'px' : $this->info_height ?>
    <?php $gridMainHeight =  (str_replace('px','',$this->info_height) + str_replace('px','',$this->photo_height) + 6).'px' ; ?>
    <?php $advgridWidth =  is_numeric($this->advgrid_width) ? $this->advgrid_width.'px' : $this->advgrid_width; ?>
    <?php $advgridHeight =  is_numeric($this->advgrid_height) ? $this->advgrid_height.'px' : $this->advgrid_height  ;
    $stats = '<div class="sesmember_list_stats">';
    if(isset($this->viewActive)){
      $stats .= '<span title="'. $this->translate(array('%s view', '%s views', $member->view_count), $this->locale()->toNumber($member->view_count)).'"><i class="far fa-eye"></i>'.$member->view_count.'</span>';
    }
    if(isset($this->likeActive)){
      $stats .= '<span title="'.$this->translate(array('%s like', '%s likes', $member->like_count), $this->locale()->toNumber($member->like_count)).'"><i class="far fa-thumbs-up"></i>'.$member->like_count.'</span> ';
    }
    if(isset($this->followActive)){
      $stats .= '<span title="'.$this->translate(array('%s follower', '%s followers', $followCount), $this->locale()->toNumber($followCount)).'"><i class="fa fa-check"></i>'.$followCount.'</span> ';
    }
   if(1){
    if(Engine_Api::_()->getApi('core', 'sesmember')->allowReviewRating() && isset($this->ratingActive)){
      $stats .= '<span title="'.$this->translate(array('%s rating', '%s ratings', $member->rating), $this->locale()->toNumber($member->rating)).'"><i class="far fa-star"></i>'.round($member->rating,1).'/5'. '</span>';
    }
   }
    
   $stats .= '</div>';
   if($this->enable_cover_photo_adv_grid){
    if($member->coverphoto){
    $imageURL = Engine_Api::_()->storage()->get($member->coverphoto, 'thumb.cover');
    if($imageURL) {
    $imageURL = $imageURL->map();
    }
  }
  else if(Engine_Api::_()->getDbtable("modules", "core")->isModuleEnabled("sesusercoverphoto")){
     $sesUserCover = Engine_Api::_()->getDbtable('permissions', 'authorization')->getAllowed('sesusercover', $member->level_id, 'defaultcover');
     if($sesUserCover)
       $imageURL = $sesUserCover;
     else
      $imageURL = 'application/modules/Sesusercoverphoto/externals/images/default_cover.jpg';
  }
  else{
    $imageURL = 'linear-gradient(to left, $theme_button_background_color, #eef2f3)';
  }
  $mainImage = 'block';
  }
  else {
    $imageURL = $member->getPhotoUrl('thumb.profile');
    $mainImage = 'none';
   } 
    $advGrid = "<div class='col-lg-$this->gridblock col-md-6 col-sm-6 col-12'>
    <div class='sesmember_member_grid sesbasic_clearfix sesbasic_bxs sesmember_grid_btns_wrap ". $vipactiveclass ." sesbm'>
    <div class='sesmember_thumb semember_member_grid_thumb'  style='height:$advgridHeight'>";?>    
    <?php
		$advGrid .=
      '<a href="'.$member->getHref().'" class="semember_member_grid_thumb_img">
        <span style="background-image:url('.$imageURL.');" class="sesbasic_animation"></span> 
      </a>"'.$vipMember.'"';?>
     <?php 
    	$advGrid .=  $advLabels.'
    </div>
    <div class="sesmember_member_grid_info sesbasic_clearfix">';?>  
      <?php $advGrid .= "<div class='sesmember_member_info_profile_img' style='display:".$mainImage.";'><a href='".$member->getHref()."' class=''>".$this->itemPhoto($member, 'thumb.profile')." </a></div>"; ?>   
      <?php $advGrid .= $userOnline . $memberAdvGridTitle. $memberratingstar .$location .$mutualFriendCount;$advGrid .=  $customFileds?>
      <?php $advGrid .= "<div class='sesmember_member_info_middle'>$memberAge $memberType $friendCount</div>"; ?>   
       <?php $advGrid .= "<div class='sesmember_list_block_stats'>$advlistStatstics</div>"; ?>
      <?php $advGrid .="<div class='sesmember_add_btn_bg'><div class='sesmember_list_stats sesmember_list_add_btn sesbasic_clearfix'><span>$friend</span><span>$followButton</span><span>$message</span></div></div>";
     
      $advGrid .= "</div> ".$shareoptionsAdv."</div></div>";
       
    $advgridViewData .= $advGrid;
    $imageURL = $member->getPhotoUrl('thumb.profile');
    
	?><?php $Overlay_color_grid = ($this->Overlay_color_grid == 'black') ? 'sesmember_black_overlay ' : ''; ?>
    <?php $grid = "<div class='col-lg-$this->gridblock col-md-6 col-sm-6 col-12'>
    <div class='sesmember_grid1 ".$Overlay_color_grid." ". $vipactiveclass ." sesbasic_bxs sesbm'>
			<div class='sesmember_thumb sesmember_list_thumb sesmember_grid_btns_wrap ' style='height:$photoHeight;'>
				<a href='".$href."' class='sesmember_list_thumb_img'>
					<span style='background-image:url(".$imageURL.");' class='sesbasic_animation'></span> $vipMember
        </a>"; ?>
        <?php if(!$member->approved){ 
					 $grid .=	 "<span class='sesmember_unapproved_label'>".$this->translate("Unapproved")."</span>";
					 } ?>
       <?php 
			$grid .= "
        $labels
        $shareOptionGridView
      </div>
      <div class='sesmember_list_info'>
        <div class='sesmember_grid1_header'>".$memberAge .$userOnline."</div>";?>
     
       <?php
       $grid .= $memberGridTitle .$location;

    $grid .= $memberratingstar;
       $grid .= "
        $customFileds
        <div class='sesmember_add_btn_bg'>
         <div class='sesmember_list_stats sesmember_list_add_btn'>
    	 <span>$friend</span><span>$followButton</span><span>$message</span>
    </div> 
    </div>     
    	</div>";
    $grid .= "</div></div>";?>
    <?php $gridViewData .= $grid;?>
    <?php if($member->lat):?>
      <?php 
			
      $likeButton = '';
			if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 &&  isset($this->likeButtonActive)){
     	  $LikeStatus = Engine_Api::_()->sesbasic()->getLikeStatus($member->user_id,$member->getType());
        $likeClass = ($LikeStatus) ? ' button_active' : '' ;
				$likeButton = '<a href="javascript:;" data-url="'.$member->getIdentity().'" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmember_like_user_'. $member->user_id.' sesmember_like_user '.$likeClass .'"> <i class="fa fa-thumbs-up"></i><span>'.$member->like_count.'</span> </a>';
			}
		$user = Engine_Api::_()->getItem('user',$member->user_id);
		$owner = $member->getOwner();
		$urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $member->getHref());
		
			$owner = '';	
  
	if(isset($this->socialSharingActive)){
	
    $socialShare = $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $member, 'param' => 'feed', 'socialshare_enable_plusicon' => $this->socialshare_enable_plusiconmapview, 'socialshare_icon_limit' => $this->socialshare_icon_limitmapview));
    
    $socialshare = '<div class="sesmember_grid_btns">'.$socialShare.$likeButton.'</div>';
	}else
		$socialshare = $likeButton;
    // Show Label
    $labels = '';
    if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->verifiedLabel)) {
      $labels .= "<div class=\"sesmember_labels\">";
      if(isset($this->featuredLabelActive) && $member->featured == 1) {
				$labels .= "<p class=\"sesmember_label_featured\">FEATURED</p>";
      }
      if(isset($this->sponsoredLabelActive) && $member->sponsored == 1) {
				$labels .= "<p class=\"sesmember_label_sponsored\">SPONSORED</p>";
      }
      
      $labels .= "</div>";
    }
    $vlabel = '';
     if(isset($this->verifiedLabelActive) && $member->user_verified == 1) {
				$vlabel = "<i class=\"sesbasic_verified_icon\" title=\"VERIFIED\"></i>";
      }
     
        $locationArray[$counter]['id'] = $member->getIdentity();
				$locationArray[$counter]['owner'] = $owner;
        $locationArray[$counter]['location'] = $location;
        $locationArray[$counter]['vlabel'] = $vlabel;
        $locationArray[$counter]['labels'] = $labels;
				$locationArray[$counter]['stats'] = $stats;
				$locationArray[$counter]['memberratingstar'] = $memberratingstar;
				$locationArray[$counter]['memberType'] = $memberType;
        $locationArray[$counter]['memberAge'] = $memberAge;
				$locationArray[$counter]['friendButton'] = $friend;
				$locationArray[$counter]['followButton'] = $followButton;
				$locationArray[$counter]['message'] = $message;
				$locationArray[$counter]['likeButton'] = $likeButton;
				$locationArray[$counter]['socialshare'] = $socialshare.$labels;
        $locationArray[$counter]['lat'] = $member->lat;
        $locationArray[$counter]['lng'] = $member->lng;
        $locationArray[$counter]['iframe_url'] = '';
        $locationArray[$counter]['image_url'] = $member->getPhotoUrl();
        $locationArray[$counter]['title'] = '<a href="'.$member->getHref().'">'.$member->getTitle().'</a>';     
        $locationArray[$counter]['vipMember'] = $vipMember;
        $locationArray[$counter]['mutualFriendCount'] = $mutualFriendCount;
        $locationArray[$counter]['friendCount'] = $friendCount;
      $counter++;?>
    <?php endif;?>
  <?php endforeach; 
  ?>
 
  <div id="browse-widget_<?php echo $randonNumber;?>" class="user_all_members sesmember_browse_listing">
  <?php if(isset($this->show_item_count) && $this->show_item_count){ ?>
    <div class="sesbasic_clearfix sesbm sesmember_search_result" style="display:<?php !$this->is_ajax ? 'block' : 'none'; ?>" id="<?php echo !$this->is_ajax ? 'paginator_count_sesmember' : 'paginator_count_ajax_sesmember' ?>"><span id="total_item_count_sesmember" style="display:inline-block;"><?php echo $this->paginator->getTotalItemCount(); ?></span> <?php echo $this->paginator->getTotalItemCount() == 1 ?  $this->translate("member found.") : $this->translate("members found."); ?></div>
   <?php } ?>
    <ul id="sesmember_list_view_<?php echo $randonNumber;?>" class='sesmembers_list_view_wrapper sesbasic_clearfix clear' <?php if($this->view_type != 'list'):?> style="display:none;"<?php endif;?> >
      <?php echo $listViewData;?>
    </ul>
    <ul id="sesmember_adv_list_view_<?php echo $randonNumber;?>" class='sesmembers_list_view_wrapper sesbasic_clearfix clear' <?php if($this->view_type != 'advlist'):?> style="display:none;"<?php endif;?> >
      <?php echo $advListViewData;?>
    </ul>
    <ul id="sesmember_pinboard_view_<?php echo $randonNumber;?>" class="sesbasic_pinboard sesbasic_clearfix clear sesbasic_pinboard_<?php echo $randonNumber;?>" style="<?php if($this->view_type != 'pinboard'):?> display:none;<?php endif;?>;">
      <?php echo $pinViewData;?>
    </ul>
    <div id="sesmember_grid_view_<?php echo $randonNumber;?>" class="sesbasic_clearfix" <?php if($this->view_type != 'grid'):?> style="display:none;" <?php endif;?> >
      <div class="row">
        <?php echo $gridViewData;?>
      </div>
    </div>
    <div id="map-data_<?php echo $randonNumber;?>" class="sesmember_map_view" style="display:none;"><?php echo json_encode($locationArray,JSON_HEX_QUOT | JSON_HEX_TAG); ?></div>
    <ul id="sesmember_map_view_<?php echo $randonNumber;?>" <?php if($this->view_type != 'map'):?> style="display:none;" <?php endif;?> >
      <div id="map-canvas-<?php echo $randonNumber;?>" class="map sesbasic_large_map sesmember_browse_map sesbm sesbasic_bxs"></div>
    </ul>
    <div id="sesmember_advgrid_view_<?php echo $randonNumber;?>" class="sesmember_advgrid_view sesbasic_clearfix" <?php if($this->view_type != 'advgrid'):?> style="display:none;" <?php endif;?> >
      <div class="row">
        <?php echo $advgridViewData;?>
      </div>
    </div>
    <?php if($this->loadOptionData == 'pagging' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')){ ?>
      <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesmember"),array('identityWidget'=>$randonNumber)); ?>
    <?php } ?>
  </div>
<script type="text/javascript">scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').css('display', 'block');</script>
<?php elseif( preg_match("/category_id=/", $_SERVER['REQUEST_URI'] )): ?>
  <script type="text/javascript">scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').css('display', 'none');</script>
  <div id="browse-widget_<?php echo $randonNumber;?>" class="user_all_events sesmember_browse_listing_<?php echo $randonNumber;?>">
  	 <div id="error-message_<?php echo $randonNumber;?>">
  <div class="sesmember_nomember_tip clearfix">
    <img src="<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('user_no_photo', 'application/modules/Sesmember/externals/images/member-icon.png'); ?>" alt="" />
    <span class="sesbasic_text_light">
      <?php echo $this->translate('Did not found member with that criteria.');?>
    </span>
  </div>   
  </div>
  </div>
 <?php else: ?>
<div id="browse-widget_<?php echo $randonNumber;?>" class="user_all_events sesmember_browse_listing sesmember_browse_listing_<?php echo $randonNumber;?>">
	<div id="error-message_<?php echo $randonNumber;?>">
  <div class="sesmember_nomember_tip clearfix">
    <img src="<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('user_no_photo', 'application/modules/Sesmember/externals/images/member-icon.png'); ?>" alt="" />
    <span class="sesbasic_text_light">
      <?php echo $this->translate('Does not exist member.') ?>
    </span>
  </div>
</div>
</div>
  <script type="text/javascript">scriptJquery('.sesbasic_view_type_<?php echo $randonNumber ?>').css('display', 'none');</script>
<?php endif; ?>
<?php if($this->loadOptionData != 'pagging' && !$this->is_ajax && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')):?>
  <div class="sesbasic_load_btn" style="display: none;" id="view_more_<?php echo $randonNumber;?>" onclick="viewMore_<?php echo $randonNumber; ?>();" >
		<a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" id="feed_viewmore_link_<?php echo $randonNumber; ?>"><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
  </div>
  <div class="sesbasic_load_btn sesbasic_view_more_loading_<?php echo $randonNumber;?>" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"><span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span></div>
<?php endif;?>
  <?php if (empty($this->is_ajax)) : ?>
      <div id="temporary-data-<?php echo $randonNumber?>" style="display:none"></div>
  <?php endif;?>
  <script type="text/javascript">
  <?php if(!$this->is_ajax):?>
    <?php if($this->loadOptionData == 'auto_load' && (empty($this->show_limited_data) || $this->show_limited_data  == 'no')){ ?>
    scriptJquery( window ).load(function() {
      scriptJquery(window).scroll( function() {
				var containerId = '#browse-widget_<?php echo $randonNumber;?>';
				if(typeof scriptJquery(containerId).offset() != 'undefined' && scriptJquery('#view_more_<?php echo $randonNumber; ?>').length > 0) {
					var hT = scriptJquery('#view_more_<?php echo $randonNumber; ?>').offset().top,
					hH = scriptJquery('#view_more_<?php echo $randonNumber; ?>').outerHeight(),
					wH = scriptJquery(window).height(),
					wS = scriptJquery(this).scrollTop();
					if ((wS + 30) > (hT + hH - wH) && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block') {
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
					}
				}
      });
    });
    <?php } ?>
  <?php endif; ?>
<?php if(!$this->is_ajax):?>
  var loadMap_<?php echo $randonNumber;?> = false;
  var activeType_<?php echo $randonNumber ?>;
  function showData_<?php echo $randonNumber; ?>(type) {
    activeType_<?php echo $randonNumber ?> = '';
    if(type == 'grid') {
      scriptJquery('#sesmember_grid_view_<?php echo $randonNumber;?>').show();
      scriptJquery('#sesmember_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_map_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.adv_list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.grid_selectView_<?php echo $randonNumber; ?>').addClass('active');
      scriptJquery('.advgrid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.pin_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.map_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      activeType_<?php echo $randonNumber ?> = 'grid';
    }else if(type == 'advgrid') {
      scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber;?>').show();
      scriptJquery('.advgrid_selectView_<?php echo $randonNumber; ?>').addClass('active');
      scriptJquery('#sesmember_grid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_list_view_<?php echo $randonNumber;?>').hide();
       scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_map_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.adv_list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.grid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.pin_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.map_selectView_<?php echo $randonNumber; ?>').removeClass('active');
   	  activeType_<?php echo $randonNumber ?> = 'advgrid';
    }else if(type == 'list') {
      scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.advgrid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesmember_grid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_list_view_<?php echo $randonNumber;?>').show();
      scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_map_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.list_selectView_<?php echo $randonNumber; ?>').addClass('active');
      scriptJquery('.adv_list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.grid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.pin_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.map_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      activeType_<?php echo $randonNumber ?> = 'list';
    }
    else if(type == 'advlist') {
      scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.advgrid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesmember_grid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber;?>').show();
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_map_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.adv_list_selectView_<?php echo $randonNumber; ?>').addClass('active');
      scriptJquery('.list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.grid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.pin_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.map_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      activeType_<?php echo $randonNumber ?> = 'advlist';
    }else if(type == 'pinboard') {
    scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.advgrid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesmember_grid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber;?>').show();
      scriptJquery('#sesmember_map_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.pin_selectView_<?php echo $randonNumber; ?>').addClass('active');
      scriptJquery('.list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.adv_list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.grid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.map_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      pinboardLayout_<?php echo $randonNumber ?>('',true);
      activeType_<?php echo $randonNumber ?> = 'pinboard';
    }else if(type == 'map') {
      if(scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber;?>').find('.active').attr('rel') == 'map')
      return;
      scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('.advgrid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('#sesmember_grid_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber;?>').hide();
      scriptJquery('#sesmember_map_view_<?php echo $randonNumber;?>').show();
      scriptJquery('.pin_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.adv_list_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.grid_selectView_<?php echo $randonNumber; ?>').removeClass('active');
      scriptJquery('.map_selectView_<?php echo $randonNumber; ?>').addClass('active');
      var mapData = scriptJquery.parseJSON(scriptJquery('#map-data_<?php echo $randonNumber;?>').html());
      if(scriptJquery('#map-data_<?php echo $randonNumber;?>').hasClass('checked'))
      return;
      if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
      newMapData_<?php echo $randonNumber ?> = mapData;
      for(var i=0; i < mapData.length; i++) {
	var isInsert = 1;
	for(var j= 0;j < oldMapData_<?php echo $randonNumber; ?>.length; j++){
	  if(oldMapData_<?php echo $randonNumber; ?>[j]['id'] == mapData[i]['id']){
	    isInsert = 0;
	    break;
	  }
	}
	if(isInsert)
	oldMapData_<?php echo $randonNumber; ?>.push(mapData[i]); 
      }
      mapFunction_<?php echo $randonNumber?>();
      scriptJquery('#map-data_<?php echo $randonNumber;?>').addClass('checked');
      }else{
	if(typeof  map_<?php echo $randonNumber;?> == 'undefined') {
	  scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
	  initialize_<?php echo $randonNumber?>();	
	}
      }
      activeType_<?php echo $randonNumber; ?> = 'map';
    }
  }
  //Code for Pinboard View
  var wookmark<?php echo $randonNumber ?>;
	var pinboardSlideshow<?php echo $randonNumber ?>;
  function pinboardLayout_<?php echo $randonNumber ?>(force,checkEnablePinboard){
    if(typeof checkEnablePinboard == 'undefined' && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') == 'pinboard'){
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber; ?>').removeClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
      scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber; ?>').css('height','');
      return;
    }
    
    if(!scriptJquery('#error-message_<?php echo $randonNumber; ?>').length) {
			scriptJquery('#view_more_<?php echo $randonNumber; ?>').addClass('dNone');
			scriptJquery('#loading_image_<?php echo $randonNumber; ?>').addClass('dBlock');
		}
    scriptJquery('.new_image_pinboard_<?php echo $randonNumber; ?>').css('display','none');
    var imgLoad = imagesLoaded('#sesmember_pinboard_view_<?php echo $randonNumber; ?>');
		pinboardSlideshow<?php echo $randonNumber ?> = true;
    imgLoad.on('progress',function(instance,image){
    scriptJquery(image.img).parent().parent().parent().parent().parent().show();
    scriptJquery(image.img).parent().parent().parent().parent().parent().removeClass('new_image_pinboard_<?php echo $randonNumber; ?>');
    imageLoadedAll<?php echo $randonNumber ?>(force,checkEnablePinboard);
    });
  }
	
  function imageLoadedAll<?php echo $randonNumber ?>(force,checkEnablePinboard){
    scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber; ?>').addClass('sesbasic_pinboard_<?php echo $randonNumber; ?>');
		scriptJquery('#view_more_<?php echo $randonNumber; ?>').removeClass('dNone');
		scriptJquery('#loading_image_<?php echo $randonNumber; ?>').removeClass('dBlock');
    if (typeof wookmark<?php echo $randonNumber ?> == 'undefined' || typeof force != 'undefined') {
      (function() {
				//	Scrolled by user interaction
				function getWindowWidth_<?php echo $randonNumber; ?>() {
					return Math.max(document.documentElement.clientWidth, window.innerWidth || 0)
				}				
			wookmark<?php echo $randonNumber ?> = new Wookmark('#sesmember_pinboard_view_<?php echo $randonNumber;?>', {
			itemWidth:  <?php echo isset($this->pinboard_width) ? str_replace(array('px','%'),array(''),$this->pinboard_width) : '300'; ?>, // Optional min width of a grid item
			outerOffset: 0, // Optional the distance from grid to parent
			align:'left',
			flexibleWidth: function () {
				// Return a maximum width depending on the viewport
				return getWindowWidth_<?php echo $randonNumber; ?>() < 1024 ? '100%' : '40%';
			}
			});		
			
      })();
    }else {
      wookmark<?php echo $randonNumber ?>.initItems();
      wookmark<?php echo $randonNumber ?>.layout(true);
    }	
  }
  <?php if($this->view_type == 'pinboard'):?>
  en4.core.runonce.add(function () {
      pinboardLayout_<?php echo $randonNumber ?>('force',true);
    });
  <?php endif;?>
  var searchParams<?php echo $randonNumber; ?> ;
  var identity<?php echo $randonNumber; ?>  = '<?php echo $randonNumber; ?>';
<?php endif;?>
var params<?php echo $randonNumber; ?> = '<?php echo json_encode($this->params); ?>';
var page<?php echo $randonNumber; ?> = '<?php echo $this->page + 1; ?>';
<?php if(!$this->is_ajax):?>
  var isSearch = false;
  var oldMapData_<?php echo $randonNumber; ?> = [];
   var is_search_<?php echo $randonNumber;?> = 0;
<?php endif;?>
<?php if($this->loadOptionData != 'pagging') { ?>
      en4.core.runonce.add(function () {
  viewMoreHide_<?php echo $randonNumber; ?>();
  });
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
 
  function viewMore_<?php echo $randonNumber; ?> () {
    scriptJquery('#view_more_<?php echo $randonNumber; ?>').hide();
    scriptJquery('#loading_image_<?php echo $randonNumber; ?>').show(); 
    var searchCriteriaSesmember = '';
    if(scriptJquery('#sesmember_manage_event_optn').length)
    searchCriteriaSesmember = scriptJquery('#sesmember_manage_event_optn').find('.active').attr('data-url');
    else
    searchCriteriaSesmember = '';	

    requestViewMore_<?php echo $randonNumber; ?> = scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/<?php echo $this->widgetName; ?>",
      'data': {
        format: 'html',
        page: page<?php echo $randonNumber; ?>,    
        params : params<?php echo $randonNumber; ?>, 
        is_ajax : 1,
        is_search:is_search_<?php echo $randonNumber;?>,
        searchCtr : searchCriteriaSesmember,
        searchParams:searchParams<?php echo $randonNumber; ?> ,
        identity : '<?php echo $randonNumber; ?>',
        type:activeType_<?php echo $randonNumber ?>,
        identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>'
      },
      success: function(responseHTML) {
	scriptJquery('#map-data_<?php echo $randonNumber;?>').removeClass('checked');
	scriptJquery('#temporary-data-<?php echo $randonNumber?>').html(responseHTML);
	if(scriptJquery('#error-message_<?php echo $randonNumber;?>').length > 0) {
	  var optionEnable = scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel');
	  var optionEnableList = scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?> > a');
	  for(i=0;i<optionEnableList.length;i++)
	  scriptJquery('#sesmember_'+optionEnable+'_view_<?php echo $randonNumber; ?>').hide();
	  scriptJquery('#tabbed-widget_<?php echo $randonNumber;?>').append('<div id="error-message_<?php echo $randonNumber;?>">'+scriptJquery('#error-message_<?php echo $randonNumber;?>').html()+'</div>')
	}
	if(!isSearch){
	  if(document.getElementById('loadingimgsesmember-wrapper'))
	  scriptJquery('#loadingimgsesmember-wrapper').hide();
	  if(document.getElementById('sesmember_list_view_<?php echo $randonNumber; ?>') && scriptJquery('.list_selectView_<?php echo $randonNumber?>').length)
	  scriptJquery('#sesmember_list_view_<?php echo $randonNumber; ?>').append(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_list_view_<?php echo $randonNumber; ?>').html());
	  if(document.getElementById('sesmember_adv_list_view_<?php echo $randonNumber; ?>') && scriptJquery('.adv_list_selectView_<?php echo $randonNumber?>').length)
	  scriptJquery('#sesmember_adv_list_view_<?php echo $randonNumber; ?>').append(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_adv_list_view_<?php echo $randonNumber; ?>').html());
	  if(document.getElementById('sesmember_grid_view_<?php echo $randonNumber; ?>') && scriptJquery('.grid_selectView_<?php echo $randonNumber?>').length)
	  scriptJquery('#sesmember_grid_view_<?php echo $randonNumber; ?>').append(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_grid_view_<?php echo $randonNumber; ?>').html());
	  if(	document.getElementById('sesmember_advgrid_view_<?php echo $randonNumber; ?>') && scriptJquery('.advgrid_selectView_<?php echo $randonNumber?>').length) {
	    scriptJquery('#sesmember_advgrid_view_<?php echo $randonNumber; ?>').append(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_advgrid_view_<?php echo $randonNumber; ?>').html());
	  }
	  if(document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber; ?>') && scriptJquery('.pin_selectView_<?php echo $randonNumber?>').length)
	   scriptJquery('#sesmember_pinboard_view_<?php echo $randonNumber; ?>').append(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_pinboard_view_<?php echo $randonNumber; ?>').html());

	  if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.map_selectView_<?php echo $randonNumber?>').length)
	  document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html();
	}
	else{
	  if(document.getElementById('browse-widget_<?php echo $randonNumber; ?>'))
	  document.getElementById('browse-widget_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').html() ;	
	  oldMapData_<?php echo $randonNumber; ?> = [];
	  isSearch = false;
	}

	if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel') == 'map') {
	  if(document.getElementById('sesmember_map_view_<?php echo $randonNumber;?>'))	
	  document.getElementById('sesmember_map_view_<?php echo $randonNumber;?>').style.display = 'block';
	  var mapData = scriptJquery.parseJSON(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html());
	  if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
	    newMapData_<?php echo $randonNumber ?> = mapData;
	    scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
	    mapFunction_<?php echo $randonNumber?>();
	  }else{
	    if(typeof  map_<?php echo $randonNumber;?> == 'undefined')	{
	      scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
	      initialize_<?php echo $randonNumber?>();	
	    }	
	  }
	}else if(document.getElementById('map-data_<?php echo $randonNumber;?>')){
	  var mapData = scriptJquery.parseJSON(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html());
	  scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, mapData);
	  scriptJquery('#map-data_<?php echo $randonNumber;?>').addClass('read');
	}
	
	if(scriptJquery('.pin_selectView_<?php echo $randonNumber;?>').hasClass('active')) {
	  if(document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber;?>'))
	  document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber;?>').style.display = 'block';
	  pinboardLayout_<?php echo $randonNumber ?>('force','true');
	}
	if(document.getElementById('temporary-data-<?php echo $randonNumber?>'))
	document.getElementById('temporary-data-<?php echo $randonNumber?>').innerHTML = '';
	scriptJquery('.sesbasic_view_more_loading_<?php echo $randonNumber;?>').hide();
	scriptJquery('#loadingimgsesmember-wrapper').hide();
  scriptJquery('#submit').html('Search');
	viewMoreHide_<?php echo $randonNumber; ?>();
      }
    });
    
    return false;
  }
<?php }else{ ?>
  function paggingNumber<?php echo $randonNumber; ?>(pageNum){
    scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display','block');
    var searchCriteriaSesmember = '';
    if(scriptJquery('#sesmember_manage_event_optn').length)
    searchCriteriaSesmember = scriptJquery('#sesmember_manage_event_optn').find('.active').attr('data-url');
    else
    searchCriteriaSesmember = '';

    requestViewMore_<?php echo $randonNumber; ?> = (scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesmember/name/<?php echo $this->widgetName; ?>",
      'data': {
	format: 'html',
	page: pageNum,
	params :params<?php echo $randonNumber; ?> , 
	is_ajax : 1,
	searchCtr : searchCriteriaSesmember,
	searchParams:searchParams<?php echo $randonNumber; ?>,
	identity : <?php echo $randonNumber; ?>,
	type:scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber; ?>').find('.active').attr('rel'),
	identityObject:'<?php echo isset($this->identityObject) ? $this->identityObject : "" ?>'
      },
      success: function(responseHTML) {
	scriptJquery('#map-data_<?php echo $randonNumber;?>').removeClass('checked');
	scriptJquery('#temporary-data-<?php echo $randonNumber?>').html(responseHTML);
	if(!isSearch){
	  if(document.getElementById('loadingimgsesmember-wrapper'))
	  scriptJquery('#loadingimgsesmember-wrapper').hide();
	  if(document.getElementById('sesmember_list_view_<?php echo $randonNumber; ?>'))
	  document.getElementById('sesmember_list_view_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_list_view_<?php echo $randonNumber; ?>').html();
	  if(document.getElementById('sesmember_adv_list_view_<?php echo $randonNumber; ?>'))
	  document.getElementById('sesmember_adv_list_view_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_adv_list_view_<?php echo $randonNumber; ?>').html();
	  if(document.getElementById('sesmember_grid_view_<?php echo $randonNumber; ?>'))
	  document.getElementById('sesmember_grid_view_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_grid_view_<?php echo $randonNumber; ?>').html();
	  if(document.getElementById('sesmember_advgrid_view_<?php echo $randonNumber; ?>')) {
	    document.getElementById('sesmember_advgrid_view_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_advgrid_view_<?php echo $randonNumber; ?>').html();
	    
	  }
	  if(document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber; ?>'))
	  document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#sesmember_pinboard_view_<?php echo $randonNumber; ?>').html();

	  if(document.getElementById('map-data_<?php echo $randonNumber;?>'))
	  document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html();
	  if(document.getElementById('map-data_<?php echo $randonNumber;?>'))
	  if(document.getElementById('ses_pagging_<?php echo $randonNumber;?>'))
	  document.getElementById('ses_pagging_<?php echo $randonNumber;?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#ses_pagging_<?php echo $randonNumber; ?>').html();
	}
	else{
	  if(document.getElementById('browse-widget_<?php echo $randonNumber; ?>'))
	  document.getElementById('browse-widget_<?php echo $randonNumber; ?>').innerHTML = scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').html() ;	
	  oldMapData_<?php echo $randonNumber; ?> = [];
	  isSearch = false;
	 
	}
	if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber;?>').find('.active').attr('rel') == 'map'){
	  var mapData = scriptJquery.parseJSON(scriptJquery('#temporary-data-<?php echo $randonNumber?>').find('#browse-widget_<?php echo $randonNumber; ?>').find('#map-data_<?php echo $randonNumber; ?>').html());
	  if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
	    oldMapData_<?php echo $randonNumber; ?> = [];
	    newMapData_<?php echo $randonNumber ?> = mapData;
	    loadMap_<?php echo $randonNumber ?> = true;
	    scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
	    mapFunction_<?php echo $randonNumber?>();
	  }else{
	    scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
	    initialize_<?php echo $randonNumber?>();	
	  }
	}else{
	  oldMapData_<?php echo $randonNumber; ?> = [];	
	}
	if(scriptJquery('.pin_selectView_<?php echo $randonNumber;?>').hasClass('active')) {
	  if(document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber;?>'))
	  document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber;?>').style.display = 'block';
	  pinboardLayout_<?php echo $randonNumber ?>('force','true');
	}
	if(document.getElementById('temporary-data-<?php echo $randonNumber?>'))
	document.getElementById('temporary-data-<?php echo $randonNumber?>').innerHTML = '';
	scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display', 'none');
	if(document.getElementById('map-data_<?php echo $randonNumber;?>') && scriptJquery('.sesbasic_view_type_options_<?php echo $randonNumber;?>').find('.active').attr('rel') == 'map'){
	var mapData = scriptJquery.parseJSON(document.getElementById('temporary-data-<?php echo $randonNumber?>').getElementById('map-data_<?php echo $randonNumber;?>').innerHTML);
	if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
	  oldMapData_<?php echo $randonNumber; ?> = [];
	  newMapData_<?php echo $randonNumber ?> = mapData;
	  loadMap_<?php echo $randonNumber ?> = true;
	  scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
	  mapFunction_<?php echo $randonNumber?>();
	}else{
	  scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
	  initialize_<?php echo $randonNumber?>();	
	}
	}else{
	  oldMapData_<?php echo $randonNumber; ?> = [];	
	}
	if(scriptJquery('.pin_selectView_<?php echo $randonNumber;?>').hasClass('active')) {
	  if(document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber;?>'))
	  document.getElementById('sesmember_pinboard_view_<?php echo $randonNumber;?>').style.display = 'block';
	  pinboardLayout_<?php echo $randonNumber ?>('force','true');
	}
	if(document.getElementById('temporary-data-<?php echo $randonNumber?>'))
	document.getElementById('temporary-data-<?php echo $randonNumber?>').innerHTML = '';
	scriptJquery('#sesbasic_loading_cont_overlay_<?php echo $randonNumber?>').css('display', 'none');
	scriptJquery('#loadingimgsesmember-wrapper').hide();
  scriptJquery('#submit').html('search');
      }
    }));
    
    return false;
  }
<?php } ?>
<?php if(!$this->is_ajax):?>
  var newMapData_<?php echo $randonNumber ?> = [];		 
  function mapFunction_<?php echo $randonNumber?>(){
    if(!map_<?php echo $randonNumber;?> || loadMap_<?php echo $randonNumber;?>){
      initialize_<?php echo $randonNumber?>();
      loadMap_<?php echo $randonNumber;?> = false;
    }
    if(scriptJquery('.map_selectView_<?php echo $randonNumber;?>').hasClass('active')) {
      if(!newMapData_<?php echo $randonNumber ?>)
      return false;
      <?php if($this->loadOptionData == 'pagging'){ ?>DeleteMarkers_<?php echo $randonNumber ?>();<?php }?>
      google.maps.event.trigger(map_<?php echo $randonNumber;?>, "resize");
      markerArrayData_<?php echo $randonNumber?> = newMapData_<?php echo $randonNumber ?>;
      if(markerArrayData_<?php echo $randonNumber?>.length)
      newMarkerLayout_<?php echo $randonNumber?>();
      newMapData_<?php echo $randonNumber ?> = '';
      scriptJquery('#map-data_<?php echo $randonNumber;?>').addClass('checked');
    }
  }
<?php endif;?>
<?php if(!$this->is_ajax):?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/marker.js'); ?>
  
    var markers_<?php echo $randonNumber;?>  = [];
    var map_<?php echo $randonNumber;?>;
    if('<?php echo $this->lat; ?>' == '') {
      var latitude_<?php echo $randonNumber;?> = '26.9110600';
      var longitude_<?php echo $randonNumber;?> = '75.7373560';
    }else{
      var latitude_<?php echo $randonNumber;?> = '<?php echo $this->lat; ?>';
      var longitude_<?php echo $randonNumber;?> = '<?php echo $this->lng; ?>';
    }
    function initialize_<?php echo $randonNumber?>() {
      var bounds_<?php echo $randonNumber;?> = new google.maps.LatLngBounds();
      map_<?php echo $randonNumber;?> = new google.maps.Map(document.getElementById('map-canvas-<?php echo $randonNumber;?>'), {
      zoom: 17,
      scrollwheel: true,
      center: new google.maps.LatLng(latitude_<?php echo $randonNumber;?>, longitude_<?php echo $randonNumber;?>),
      });
      oms_<?php echo $randonNumber;?> = new OverlappingMarkerSpiderfier(map_<?php echo $randonNumber;?>,
      {nearbyDistance:40,circleSpiralSwitchover:0 }
      );
    }
    var countMarker_<?php echo $randonNumber;?> = 0;
    function DeleteMarkers_<?php echo $randonNumber ?>(){
      //Loop through all the markers and remove
      for (var i = 0; i < markers_<?php echo $randonNumber;?>.length; i++) {
      markers_<?php echo $randonNumber;?>[i].setMap(null);
      }
      markers_<?php echo $randonNumber;?> = [];
      markerData_<?php echo $randonNumber ?> = [];
      markerArrayData_<?php echo $randonNumber?> = [];
    };
    var markerArrayData_<?php echo $randonNumber?> ;
    var markerData_<?php echo $randonNumber ?> =[];
    var bounds_<?php echo $randonNumber;?> = new google.maps.LatLngBounds();
    function newMarkerLayout_<?php echo $randonNumber?>(dataLenth){
      if(typeof dataLenth != 'undefined') {
	initialize_<?php echo $randonNumber?>();
	markerArrayData_<?php echo $randonNumber?> = scriptJquery.parseJSON(dataLenth);
      }
      if(!markerArrayData_<?php echo $randonNumber?>.length)
      return;
   
      DeleteMarkers_<?php echo $randonNumber ?>();
      markerArrayData_<?php echo $randonNumber?> = oldMapData_<?php echo $randonNumber; ?>;
      var bounds = new google.maps.LatLngBounds();
      for(i=0;i<markerArrayData_<?php echo $randonNumber?>.length;i++){
	var images = '<div class="image sesmember_map_thumb_img"><img src="'+markerArrayData_<?php echo $randonNumber?>[i]['image_url']+'"  /></div>';		
	var owner = markerArrayData_<?php echo $randonNumber?>[i]['owner'];
	var stats = markerArrayData_<?php echo $randonNumber?>[i]['stats'];
	var labels = markerArrayData_<?php echo $randonNumber?>[i]['labels'];
	var location = markerArrayData_<?php echo $randonNumber?>[i]['location'];
	var socialshare = markerArrayData_<?php echo $randonNumber?>[i]['socialshare'];
	var friendButton = markerArrayData_<?php echo $randonNumber?>[i]['friendButton'];
	var followButton = markerArrayData_<?php echo $randonNumber?>[i]['followButton'];
	var message = markerArrayData_<?php echo $randonNumber?>[i]['message'];
	var likeButton = markerArrayData_<?php echo $randonNumber?>[i]['likeButton'];
	var memberratingstar = markerArrayData_<?php echo $randonNumber?>[i]['memberratingstar'];
	var memberType = markerArrayData_<?php echo $randonNumber?>[i]['memberType'];
	var memberAge = markerArrayData_<?php echo $randonNumber?>[i]['memberAge'];
  var vipMember = markerArrayData_<?php echo $randonNumber?>[i]['vipMember'];
  var mutualFriendCount = markerArrayData_<?php echo $randonNumber?>[i]['mutualFriendCount'];
  var friendCount = markerArrayData_<?php echo $randonNumber?>[i]['friendCount'];
	var marker_html = '<div class="pin public marker_'+countMarker_<?php echo $randonNumber;?>+'" data-lat="'+ markerArrayData_<?php echo $randonNumber?>[i]['lat']+'" data-lng="'+ markerArrayData_<?php echo $randonNumber?>[i]['lng']+'">' +
	'<div class="wrapper">' +
	'<div class="small">' +
	'<img src="'+markerArrayData_<?php echo $randonNumber?>[i]['image_url']+'" style="height:48px;width:48px;" alt="" />' +
	'</div>' +
	'<div class="large"><div class="sesmember_map_thumb sesmember_grid_btns_wrap">' +
	images + labels +socialshare+
	'</div><div class="sesbasic_large_map_content sesmember_large_map_content sesbasic_clearfix">' +
	'<div class="sesbasic_large_map_content_title">'+markerArrayData_<?php echo $randonNumber?>[i]['title']+markerArrayData_<?php echo $randonNumber?>[i]['vlabel']+'</div>' +owner + memberratingstar + vipMember + memberType + memberAge + location + stats + friendCount +mutualFriendCount +'<div class="sesmember_list_add_btn clearfix clear"><span>'+friendButton+'</span><span>'+followButton+'</span><span>'+message+'</span></div></div></div>' +'<a class="icn close" href="javascript:;" title="Close"><i class="fa fa-times"></i></a>' + '</div>' +'</div>' +'<span class="sesbasic_largemap_pointer"></span>' +'</div>';
	markerData = new RichMarker({
	  position: new google.maps.LatLng(markerArrayData_<?php echo $randonNumber?>[i]['lat'], markerArrayData_<?php echo $randonNumber?>[i]['lng']),
	  map: map_<?php echo $randonNumber;?>,
	  flat: true,
	  draggable: false,
	  scrollwheel: false,
	  id:countMarker_<?php echo $randonNumber;?>,
	  anchor: RichMarkerPosition.BOTTOM,
	  content: marker_html
	});
	oms_<?php echo $randonNumber;?>.addListener('click', function(marker) {
	  var id = marker.markerid;
	  previousIndex = scriptJquery('.marker_'+ id).parent().parent().css('z-index');
	  scriptJquery('.marker_'+ id).parent().parent().css('z-index','9999');
	  scriptJquery('.pin').removeClass('active').css('z-index', 10);
	  scriptJquery('.marker_'+ id).addClass('active').css('z-index', 200);
	  scriptJquery('.marker_'+ id+' .large .close').click(function(){
	  scriptJquery(this).parent().parent().parent().parent().parent().css('z-index',previousIndex);
	    scriptJquery('.pin').removeClass('active');
	    return false;
	  });
		scriptJquery('.marker_'+ id+' .close').click(function(){
	  scriptJquery(this).parent().parent().parent().parent().parent().css('z-index',previousIndex);
	    scriptJquery('.pin').removeClass('active');
	    return false;
	  });
	});
				markers_<?php echo $randonNumber;?> .push( markerData);
				markerData.setMap(map_<?php echo $randonNumber;?>);
				bounds.extend(markerData.getPosition());
				markerData.markerid = countMarker_<?php echo $randonNumber;?>;
				oms_<?php echo $randonNumber;?>.addMarker(markerData);
				countMarker_<?php echo $randonNumber;?>++;
      }
      map_<?php echo $randonNumber;?>.fitBounds(bounds);
    }
    <?php if($this->view_type == 'map'){?>
          en4.core.runonce.add(function () {
	var mapData = scriptJquery.parseJSON(document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML);
	if(scriptJquery.isArray(mapData) && scriptJquery(mapData).length) {
	  newMapData_<?php echo $randonNumber ?> = mapData;
	  scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, newMapData_<?php echo $randonNumber ?>);
	  mapFunction_<?php echo $randonNumber?>();
	  scriptJquery('#map-data_<?php echo $randonNumber;?>').addClass('checked')
	}else{
	if(typeof  map_<?php echo $randonNumber;?> == 'undefined') {
	    scriptJquery('#map-data_<?php echo $randonNumber; ?>').html('');
	    initialize_<?php echo $randonNumber?>();	
	  }
	}
      });
    <?php }else{ ?>
      if(document.getElementById('map-data_<?php echo $randonNumber;?>')){
	var mapData = scriptJquery.parseJSON(document.getElementById('map-data_<?php echo $randonNumber;?>').innerHTML);
	scriptJquery.merge(oldMapData_<?php echo $randonNumber; ?>, mapData);	
      }
    <?php } ?>
  </script> 
<?php endif;?>
