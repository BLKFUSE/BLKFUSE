<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: image-viewer-details-advance.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */

?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>
<?php if ( $this->video->type == 3 && $this->video_extension == 'mp4' ){
    $this->headScript()
         ->appendFile($this->layout()->staticBaseUrl . 'externals/html5media/html5media.min.js');
}
?>

<?php if(($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) ){ ?>
<script type="text/javascript">
  //en4.core.runonce.add(function() {
    var pre_rate_viewer = "<?php echo $this->total_rating_average == '' ? '0' : $this->total_rating_average  ;?>";
		<?php if($this->viewer_id == 0){ ?>
			rated_viewer = 0;
		<?php }else if($this->allowShowRating == 1 && $this->allowRating == 0){?>
		var rated_viewer = 3;
		<?php }else if($this->allowRateAgain == 0 && $this->rated){ ?>
		var rated_viewer = 1;
		<?php }else if($this->canRate == 0 && $this->viewer_id != 0){?>
		var rated_viewer = 4;
		<?php }else if(!$this->allowMine){?>
		var rated_viewer = 2;
		<?php }else{ ?>
    var rated_viewer = '90';
		<?php } ?>
    var resource_id_viewer = <?php echo $this->video->video_id;?>;
    var total_votes_viewer = <?php echo $this->rating_count;?>;
    var viewer_id = <?php echo $this->viewer_id;?>;
    new_text_viewer = '';

    var rating_over_viewer = window.rating_over_viewer = function(rating) {
      if( rated_viewer == 1 ) {
        document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('you have already rated');?>";
				return;
        //set_rating_viewer();
      }
			<?php if(!$this->canRate){ ?>
				else if(rated_viewer == 4){
						 document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('rating is not allowed for your member level');?>";
						 return;
				}
			<?php } ?>
			<?php if(!$this->allowMine){ ?>
				else if(rated_viewer == 2){
						 document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('rating on own photo not allowed');?>";
						 return;
				}
			<?php } ?>
			<?php if($this->allowShowRating == 1){ ?>
				else if(rated_viewer == 3){
						 document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('rating is disabled');?>";
						 return;
				}
			<?php } ?>
			else if( viewer_id == 0 ) {
        document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('please login to rate');?>";
				return;
      } else {
        document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('click to rate');?>";
        for(var x=1; x<=5; x++) {
          if(x <= rating) {
            scriptJquery('#rate_viewer_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big').removeClass('rating_star_big_disabled');
          } else {
            scriptJquery('#rate_viewer_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
          }
        }
      }
    }
    
    var rating_out_viewer = window.rating_out_viewer = function() {
      if (new_text_viewer != ''){
        document.getElementById('rating_text_viewer').innerHTML = new_text_viewer;
      }
      else{
        document.getElementById('rating_text_viewer').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";        
      }
      if (pre_rate_viewer != 0){
        set_rating_viewer();
      }
      else {
        for(var x=1; x<=5; x++) {
          scriptJquery('#rate_viewer_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
        }
      }
			return ;	
    }

     var set_rating_viewer = window.set_rating_viewer = function() {
      var rating_viewert = pre_rate_viewer;
      if (new_text_viewer != ''){
        document.getElementById('rating_text_viewer').innerHTML = new_text_viewer;
      }
      else{
        document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
      }
			
      for(var x=1; x<=parseInt(rating_viewert); x++) {
        scriptJquery('#rate_viewer_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big');
      }
		
      for(var x=parseInt(rating_viewert)+1; x<=5; x++) {
        scriptJquery('#rate_viewer_'+x).addClass( 'fas fa-star rating_star_big_generic rating_star_big_disabled');
      }

      var remainder = Math.round(rating_viewert)-rating_viewert;
      if (remainder <= 0.5 && remainder !=0){
        var last = parseInt(rating_viewert)+1;
        scriptJquery('#rate_viewer_'+last).addClass( 'fas fa-star rating_star_big_generic rating_star_big_half');
      }
    }

    var rate_viewer = window.rate_viewer = function(rating) {
      document.getElementById('rating_text_viewer').innerHTML = "<?php echo $this->translate('Thanks for rating!');?>";
			<?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
						 for(var x=1; x<=5; x++) {
								scriptJquery('#rate_viewer_'+x).attr('onclick', '');
							}
					<?php } ?>
     
      (scriptJquery.ajax({
        dataType: 'json',
        'format': 'json',
        'url' : '<?php echo $this->url(array('module' => 'sesvideo', 'controller' => 'index', 'action' => 'rate'), 'default', true) ?>',
        'data' : {
          'format' : 'json',
          'rating' : rating,
          'resource_id': resource_id_viewer,
					'resource_type':'<?php echo $this->rating_type; ?>'
        },
        success : function(responseJSON, responseText)
        {
					<?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
							rated_viewer = 1;
					<?php } ?>
					showTooltip(10,10,'<i class="fa fa-star"></i><span>'+("Video Rated successfully")+'</span>', 'sesbasic_rated_notification');
					total_votes_viewer = responseJSON[0].total;
					var rating_sum = responseJSON[0].rating_sum;
					var totalTxt_viewer = responseJSON[0].totalTxt;
          pre_rate_viewer = rating_sum / total_votes_viewer;
          set_rating_viewer();
          document.getElementById('rating_text_viewer').innerHTML = responseJSON[0].total+' '+totalTxt_viewer;
          new_text_viewer = responseJSON[0].total+' '+totalTxt_viewer;
        }
      }));
    }
    set_rating_viewer();
  //});
</script>
<?php } ?>
<?php 
        if($this->toArray){ 
          if(!empty($this->previousVideo)) {
         	  $this->previousVideo = $sesvideo_video_previous = Engine_Api::_()->getItem("sesvideo_video", $this->previousVideo[0]['video_id']);
            $previousURL = $sesvideo_video_previous->getHref($this->customParamsArray);
            if(!empty($previousURL))
            	$previousVideoURL = $sesvideo_video_previous->getPhotoUrl();
          }
       }else{
        if(!empty($this->previousVideo))
        	$previousURL = $this->previousVideo->getHref($this->customParamsArray);;
          if(!empty($previousURL))
          $previousVideoURL = $this->previousVideo->getPhotoUrl();
       }
        if(isset($previousURL)){
        	if (!$this->previousVideo->authorization()->isAllowed($this->viewer, 'view')) {
            $previousVideoURL = $this->privateImageUrl;
          }else if($this->previousVideo->adult && !Engine_Api::_()->getApi('core', 'sesbasic')->checkAdultContent(array('module'=>'video')))
          	$previousVideoURL = $this->privateImageURL;
      ?>
<a class="pswp__button pswp__button--arrow--left" style="display:block" href="<?php echo $this->previousVideo->getHref(); ?>" title="<?php echo $this->translate('Previous'); ?>" onclick="getRequestedVideoForImageViewer('<?php echo $previousVideoURL; ?>','<?php echo $previousURL; ?>');return false;" id="nav-btn-prev"></a>
<?php }else{ ?>
			<a class="pswp__button pswp__button--arrow--left" style="display:block" href="javascript:;" title="<?php echo $this->translate('Previous'); ?>" id="first-element-btn"></a>
<?php } 
         if($this->toArray){ 
            if(!empty($this->nextVideo)) {
              $this->nextVideo = $sesvideo_video_next = Engine_Api::_()->getItem("sesvideo_video", $this->nextVideo[0]['video_id']);
              $nextURL = $sesvideo_video_next->getHref($this->customParamsArray);;
              if(!empty($nextURL))
              	$nextVideoURL  = $sesvideo_video_next->getPhotoUrl();
            }
         }else{
          if(!empty($this->nextVideo))
	          $nextURL = $this->nextVideo->getHref($this->customParamsArray);
            if(!empty($nextURL))
            	$nextVideoURL  = $this->nextVideo->getPhotoUrl();
         }
        if(!empty($nextURL)){
        	if (!$this->nextVideo->authorization()->isAllowed($this->viewer, 'view')) {
            $nextVideoURL = $this->privateImageURL;
          }else if($this->nextVideo->adult && !Engine_Api::_()->getApi('core', 'sesbasic')->checkAdultContent(array('module'=>'video')))
          	$nextVideoURL = $this->privateImageURL;
       ?>
<a class="pswp__button pswp__button--arrow--right" style="display:block" href="<?php echo $this->nextVideo->getHref(); ?>" title="<?php echo $this->translate('Next'); ?>" onclick="getRequestedVideoForImageViewer('<?php echo $nextVideoURL; ?>','<?php echo $nextURL; ?>');return false;" id="nav-btn-next"></a>
<?php }else{ ?>
		<a class="pswp__button pswp__button--arrow--right" style="display:block" data-rel="<?php echo $this->type; ?>" data-id="<?php echo isset($this->item) ? $this->item->getIdentity() : '' ?>" href="javascript:;" title="<?php echo $this->translate('Next'); ?>"  id="last-element-btn"></a>
<?php } ?>
<div class="ses_pswp_information" id="ses_pswp_information">
  <div id="heightOfImageViewerContent">
    <div id="flexcroll" >
      <div class="ses_pswp_info" id="ses_pswp_info">
        <div class="ses_pswp_information_top sesbasic_clearfix">
          <?php $videoUserDetails = Engine_Api::_()->user()->getUser($this->video->owner_id); ?>
          <div class="ses_pswp_author_photo"> <?php echo $this->htmlLink($videoUserDetails->getHref(), $this->itemPhoto($videoUserDetails, 'thumb.icon')); ?> </div>
          <div class="ses_pswp_author_info"> <span class="ses_pswp_author_name"> <?php echo $this->htmlLink($videoUserDetails->getHref(), $videoUserDetails->getTitle()); ?> </span> <span class="ses_pswp_item_posted_date sesbasic_text_light"> <?php echo date('F j',strtotime($this->video->creation_date)); ?> </span> </div>
        </div>
        <div class="ses_pswp_item_title" id="ses_title_get"> <?php echo $this->video->getTitle(); ?></div>
        <div class="ses_pswp_item_description" id="ses_title_description"><?php echo nl2br($this->video->getDescription()); ?></div>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)) { ?>
        <div class="ses_pswp_location"> <span class="sesbasic_text_light" id="seslocationIn">
          <?php if($this->video->location != '') echo $this->translate("In"); ?>
          </span> 
          <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1)) { ?>
          <span>
            <a id="ses_location_data" href="javascript:;" onclick="openURLinSmoothBox('<?php echo $this->url(array("module"=> "sesvideo", "controller" => "index", "action" => "location",  "video_id" => $this->video->getIdentity(),'type'=>'video_location'),'default',true); ?>');return false;"><?php echo $this->video->location; ?></a>
         </span>
         <?php } ?>
        </div>
        <?php } ?>
        <?php if(($this->getAllowRating == 0 && $this->allowShowRating == 1 && $this->total_rating_average != 0 ) || ($this->getAllowRating == 1) ){ ?>
        <div id="album_rating" class="sesvideo_rating_star ses_pswp_item_rating" onmouseout="rating_out_viewer();"> 
        <span id="rate_viewer_1" class="fas fa-star rating_star_big_generic rating_star_big_disabled" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine &&  $this->allowRating):?> onclick="rate_viewer(1);"<?php  endif; ?> onmouseover="rating_over_viewer(1);"></span> 
        <span id="rate_viewer_2" class="fas fa-star rating_star_big_generic rating_star_big_disabled" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?> onclick="rate_viewer(2);"<?php endif; ?> onmouseover="rating_over_viewer(2);"></span> 
        <span id="rate_viewer_3" class="fas fa-star rating_star_big_generic rating_star_big_disabled" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?> onclick="rate_viewer(3);"<?php endif; ?> onmouseover="rating_over_viewer(3);"></span> 
        <span id="rate_viewer_4" class="fas fa-star rating_star_big_generic rating_star_big_disabled" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?> onclick="rate_viewer(4);"<?php endif; ?> onmouseover="rating_over_viewer(4);"></span> 
        <span id="rate_viewer_5" class="fas fa-star rating_star_big_generic rating_star_big_disabled" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?> onclick="rate_viewer(5);"<?php endif; ?> onmouseover="rating_over_viewer(5);"></span> <span id="rating_text_viewer" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span> </div>
        <?php } ?>
        <?php if($this->canEdit){ ?>
        <div class="ses_pswp_item_edit_link"> 
        	<a id="editDetailsLinkVideo" href="javascript:void(0)" class="sesbasic_button"> 
          	<i class="fa fa-edit sesbasic_text_light"></i> 
            	<?php echo $this->translate('Edit Details'); ?> 
          </a> 
        </div>
        <?php } ?>
      </div>
      <?php if($this->canEdit){ ?>
      <div class="ses_pswp_item_edit_form" id="editDetailsFormVideo" style="display:none;">
        <form id="changePhotoDetailsVideo">
          <input  name="title" id="titleSes" type="text" placeholder="<?php echo $this->translate('Title'); ?>" />
          <input type="hidden" id="video_id_ses" name="photo_id" value="<?php echo $this->video->video_id; ?>" />
          <textarea id="descriptionSes" name="description" value="" placeholder="<?php echo $this->translate('Description'); ?>"></textarea>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('enableglocation', 1) && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo_enable_location', 1)) { ?>
          <input id="locationSes" name="location"  type="text" placeholder="<?php echo $this->translate('Location'); ?>">
          <input type="hidden" id="latSes" name="lat" value="" />
          <input type="hidden" id="lngSes" name="lng" value="" />
          <div id="map-canvas" style="height:200px; margin-bottom:10px;"></div>
       <?php } ?>
          <button id="saveDetailssesbasic"><?php echo $this->translate('Save Changes'); ?></button>
          <button id="cancelDetailssesbasic"><?php echo $this->translate('Cancel'); ?></button>
        </form>
      </div>
      <?php } ?>
<!--      <div class="ses_pswp_buy_btn sesbasic_clearfix">
        <span class="ses_pswp_buy_price floatL">$1258.00</span>
        <span class="ses_pswp_buy_link floatR"><a href="" class="sesbasic_link_btn sesbasic_animation">Buy</a></span>
      </div>-->
      <div class="ses_pswp_comments clear">
      	<?php if(Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesadvancedcomment')){ 
          $_SESSION['sesfromLightbox'] = "lightboxWidget";
        ?>
      <?php echo $this->action("list", "comment", "sesadvancedcomment", array("type" => "video", "id" => $this->video->getIdentity())); 
        }else{ echo $this->action("list", "comment", "core", array("type" => "video", "id" => $this->video->getIdentity())); } ?>
      </div>
    </div>
  </div>
</div>
</div>
<div class="pswp__top-bar" style="display:none" id="imageViewerId"> 
	<a title="<?php echo $this->translate('Close (Esc)'); ?>" class="pswp__button pswp__button--close"></a> 
  <a title="<?php echo $this->translate('Toggle fullscreen') ?>" onclick="toogle()" href="javascript:;" class="pswp__button"></a><a <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesalbum.show.information', 1)) { ?> title="<?php echo $this->translate('Close'); ?>" <?php } else { ?> title="<?php echo $this->translate('Show Info'); ?>" <?php } ?> id="pswp__button--info-show" class="pswp__button pswp__button--info pswp__button--info-show <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.show.information', 1)) { ?> active <?php } ?>"></a> 
  <a title="<?php echo $this->translate('Show All Photos'); ?>" id="show-all-video-container" class="pswp__button pswp__button--show-photos"></a>
  <a title="<?php echo $this->translate('Zoom in/out'); ?>" id="pswp__button--zoom" class="pswp__button pswp__button--zoom"></a>
  <div class="pswp__top-bar-action">
    <div class="pswp__top-bar-albumname"><?php echo $this->translate('In %1$s', $this->htmlLink( isset($this->item) ? $this->item->getHref() : $this->video->getHref(),isset($this->item) ? $this->string()->truncate($this->item->title,Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.title.truncate',35)) : $this->string()->truncate($this->video->title,Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.title.truncate',35)))); ?> </div>
    <div class="pswp__top-bar-share">
      <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.add.share',1) == 1){ ?>
       <a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo $this->url(array("action" => "share","module" =>"sesvideo", "type" => "video", "id" => $this->video->getIdentity(),"format" => "smoothbox"), 'default', true); ?>')"><?php echo $this->translate('Share'); ?></a>
      <?php } ?>
    </div>
     <?php if( Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && ((Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.add.report',1) == 1 && Engine_Api::_()->user()->getViewer()->getIdentity() != $this->video->owner_id) || (Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.add.delete',1) == 1) && $this->canDelete) ){?>
    <div class="pswp__top-bar-more"> 
    	<a href="javascript:;" class="optionOpenImageViewer" id="overlay-model-class" class=""><?php echo $this->translate("Options"); ?> 
     		<i class="fa fa-angle-down" id="overlay-model-class-down"></i>
      </a>
      <div class="pswp__top-bar-more-tooltip" style="display:none">
        <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && $this->canDelete){ ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.add.delete',1) == 1){ ?>
         <a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo $this->url(array('module' => 'sesvideo', 'action' => 'delete', 'video_id' => $this->video->getIdentity()), 'default', true); ?>')"><?php echo $this->translate('Delete'); ?></a>
        <?php } ?>
        <?php } ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesbasic.add.report',1) == 1 && Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && Engine_Api::_()->user()->getViewer()->getIdentity() != $this->video->owner_id){ ?>
        <a href="javascript:;" class='smoothbox' onclick="openURLinSmoothBox('<?php echo $this->url(array('module'=> 'core', 'controller' => 'report', 'action' => 'create', 'route' => 'default', 'subject' => $this->video->getGuid(), 'format' => 'smoothbox'),'default',true); ?>')"><?php echo $this->translate('Report'); ?></a>
        <?php } ?>
    </div>
    </div>
    <?php } ?>
    <?php if(Engine_Api::_()->user()->getViewer()->getIdentity() != 0){ ?>
      <div class="pswp__top-bar-msg pswp__top-bar-btns"> 
      <a class="sesbasic_icon_btn sesalbum_icon_msg_btn smoothbox" href="<?php echo $this->url(array('module'=> 'sesbasic', 'controller' => 'index', 'action' => 'message','item_id' => $this->video->getIdentity(), 'type'=>$this->video->getType()),'default',true); ?>" onclick="openURLinSmoothBox(this.href);return false;"><i class="fa fa-envelope"></i></a>     
       <?php if($this->canComment){ ?>
        <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($this->video->video_id,'video');  ?>
        <a href="javascript:void(0);" id="sesLightboxLikeUnlikeButtonVideo" data-id="<?php echo $this->video->video_id; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesbasic_like_sesbasic_video<?php echo $LikeStatus === true ? ' button_active' : '' ;  ?>"><i class="fa fa-thumbs-up"></i><span id="like_unlike_count"><?php echo $this->video->like_count; ?></span></a>
        <?php } ?>
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavv', 1)) { ?>
        <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>'sesvideo_video','resource_id'=>$this->video->video_id)); ?>
        <a href="javascript:;" id="scriptJquery_favourite" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_video  <?php echo ($favStatus)  ? ' button_active' : '' ?>"  data-url="<?php echo $this->video->video_id; ?>"><i class="fa fa-heart"></i><span><?php echo $this->video->favourite_count; ?></span></a>
        <?php } ?>
      <?php } ?>
      </div>

      <?php $settings = Engine_Api::_()->getApi('settings', 'core'); ?>
      <?php if($settings->getSetting('sesbasic.enablesessocialshare', 0)) { ?>
        <div class="pswp__top-bar-share-btns seslightbox_share_buttons">
          <?php echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->video, 'socialshare_enable_plusicon' => $settings->getSetting('sesbasic.enableplusicon', 1), 'socialshare_icon_limit' => $settings->getSetting('sesbasic.iconlimit', 3))); ?>
        </div>
      <?php } ?>
      
  </div>
  <?php $className= '';
                if($this->locked){
                   $imageUrl = 'application/modules/Sesvideo/externals/images/locked-video.jpg';
                   $className = 'ses-blocked-video';
                 }
          ?>
          <?php if(isset($this->imagePrivateURL)){
                    $imageUrl = $this->imagePrivateURL;
                    $className = 'ses-private-image';
                }else if(empty($imageUrl)){
                  $imageUrl = $this->video->getPhotoUrl();
                }
          ?>
  <div id="sesbasic_video_password" style="display:none;"><?php echo $this->password; ?></div>
  <div id="sesbasic_video_title" style="display:none;"><?php echo $this->video->getTitle(); ?></div>
 <div id="media_video_next_ses" class="<?php echo $className; ?>" style="display:none;">
          <div id="video_data_lightbox" >
             <?php if( $this->video->type == 3 ): ?>
              <div id="video_embed_lightbox" class="sesbasic_view_embed_lightbox clear sesbasic_clearfix">
                <?php if ($this->video_extension !== 'flv'): ?>
                  <?php if(Engine_Api::_()->getDbTable('modules', 'core')->isModuleEnabled('videovod')): ?>
                    <iframe src="<?php echo  $this->baseUrl();  ?>/videovod/Iframe/index?video_id=<?php echo $this->v_id; ?>&smoothbox=1" width="480"  ></iframe>
                  <?php else: ?>
                    <video id="video" controls preload="auto" width="480" height="386">
                      <source type='video/mp4' src="<?php echo $this->video_location ?>">
                    </video>
                  <?php endif ?>
                <?php endif ?>
              </div>
              <?php else: ?>
                <div class="sesbasic_view_embed_lightbox clear sesbasic_clearfix">
                  <?php echo $this->videoEmbedded ?>
                </div>
              <?php endif; ?>
        	</div>
          <?php echo $this->htmlImage($imageUrl, $this->video->getTitle(), array(
                  'id' => 'gallery-img',
                  'class' =>$className,
                  'style'=>'display:none',
         			  )); 
          ?>
        </div>
 <div id="sesbasic_video_id_data_src" data-src="<?php echo $this->video->video_id; ?>" style="display:none;"></div>
 <div class="pswp__preloader">
    <div class="pswp__preloader__icn">
      <div class="pswp__preloader__cut">
        <div class="pswp__preloader__donut"></div>
      </div>
    </div>
  </div>
</div>
<div id="content-from-element" style="display:none;">
<div class="ses_ml_overlay"></div>
<div class="ses_ml_more_popup sesbasic_bxs sesbasic_clearfix">
	<div class="ses_ml_more_popup_header">
  	<span><?php echo $this->translate("You've finished Videos from posts") ?></span>
    <a href="javascript:;" class="morepopup_bkbtn"><i id="morepopup_bkbtn_btn" class="fa fa-sync"></i></a>
    <a href="javascript:;" class="morepopup_closebtn" id="morepopup_closebtn"><i id="morepopup_closebtn_btn" class="fa fa-times"></i></a>
  </div>
<div id="content_last_element_lightbox"></div>
</div>
