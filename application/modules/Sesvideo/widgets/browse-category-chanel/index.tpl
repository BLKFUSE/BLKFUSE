<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesvideo
 * @package    Sesvideo
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-10-11 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
 
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/flexcroll.js'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/scripts/core.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<?php 
   $viewer = Engine_Api::_()->user()->getViewer();
    if ($viewer->getIdentity() == 0)
      $level = Engine_Api::_()->getDbtable('levels', 'authorization')->getPublicLevel()->level_id;
    else
      $level = $viewer;
    $type = Engine_Api::_()->authorization()->getPermission($level, 'sesbasic_video', 'videoviewer');
    if ($type == 1) {
      $this->headScript()->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/SesLightbox/photoswipe-ui-default.min.js')
              ->appendFile(Zend_Registry::get('StaticBaseUrl')
                      . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimagevieweradvance.js');
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/photoswipe.css');
    } else {
      $loadImageViewerFile = Zend_Registry::get('StaticBaseUrl') . 'application/modules/Sesbasic/externals/scripts/videolightbox/sesvideoimageviewerbasic.js';
      $this->headScript()->appendFile($loadImageViewerFile);
      $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl
              . 'application/modules/Sesbasic/externals/styles/medialightbox.css');
    }
?>
<?php if(isset($this->identityForWidget) && !empty($this->identityForWidget)){
				$randonNumber = $this->identityForWidget;
      }else{
      	$randonNumber = $this->identity; 
      }
?>
<?php //rating show code
  $allowRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.chanel.rating',1);
  $allowShowPreviousRating = Engine_Api::_()->getApi('settings', 'core')->getSetting('video.ratechanel.show',1);
  if($allowRating == 0){
    if($allowShowPreviousRating == 0)
      $ratingChanelShow = false;
     else
      $ratingChanelShow = true;
  }else
    $ratingChanelShow = true;
?>
<?php if(!$this->is_ajax){ ?>
	<div class="sesvideo_browse_channel_listing_title clear sesbasic_clearfix"><h3> <a href="<?php echo $this->url(array('action' => 'browse'), "sesvideo_category"); ?>"><?php echo $this->translate("All Categories"); ?></a>&nbsp;&raquo; <a href="<?php echo $this->catgeoryItem->getHref(); ?>"><?php echo $this->catgeoryItem->getTitle(); ?></a></h3></div>
	<div id="scrollHeightDivSes_<?php echo $randonNumber; ?>" class="sesbasic_clearfix sesbasic_bxs clear">  
<?php } ?>
  <?php foreach( $this->paginator as $chanelData): ?>
  	<div class="sesvideo_browse_channel_listing clear sesbasic_clearfix">
      <div class="sesvideo_browse_channel_conatiner clear sesbasic_clearfix sesbm">
        <div class="sesvideo_browse_channel_item sesbasic_clearfix clear">
          <?php if(isset($this->chanelPhotoActive)){ ?>
            <div class="sesvideo_browse_channel_items_photo floatL">
              <a class="" data-url = "<?php echo $chanelData->getType() ?>" href="<?php echo $chanelData->getHref(); ?>">
                <span style="background-image:url(<?php echo $chanelData->getPhotoUrl(); ?>);"></span>
              </a>
              <?php if(isset($this->featuredLabelActive) || isset($this->sponsoredLabelActive) || isset($this->hotLabelActive)){ ?>
                <p class="sesvideo_labels">
                <?php if(isset($this->featuredLabelActive) && $chanelData->is_featured == 1){ ?>
                  <span class="sesvideo_label_featured"><?php echo $this->translate('FEATURED'); ?></span>
                <?php } ?>
                <?php if(isset($this->sponsoredLabelActive) && $chanelData->is_sponsored == 1){ ?>
                  <span class="sesvideo_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
                <?php } ?>
                <?php if(isset($this->hotLabelActive) && $chanelData->is_hot == 1){ ?>
                  <span class="sesvideo_label_hot"><?php echo $this->translate("HOT"); ?></span>
                <?php } ?>
                </p>
               <?php } ?>
            </div>
          <?php } ?>
          <div class="sesvideo_browse_channel_items_cont">
            <div class="sesvideo_browse_channel_items_title">
            <?php if(isset($this->titleActive)){ ?>
            	<?php 
              		if(strlen($chanelData->title)>$this->title_truncation)
                  	$titleChanel = mb_substr($chanelData->title,0,$this->title_truncation).'...';
                  else
              			$titleChanel = $chanelData->title; 
              ?>
            	<a href="<?php echo $chanelData->getHref(); ?>"><?php echo $titleChanel ?></a> 
             <?php } ?>
              <?php if($chanelData->is_verified){ ?>
              	<i class="sesvideo_verified fa fa-check-square" title="<?php echo $this->translate('Verified') ?>"></i>
              <?php  } ?>
              <span class="sesvideo_browse_channel_items_btns floatR">
              <!--isset($this->socialeShare)-->
                 <?php if(isset($this->socialeShareActive)){ ?>
                 <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $chanelData->getHref()); ?>
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $chanelData, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

                  <?php } ?>
              <?php if(isset($chanelData->follow) && $chanelData->follow == 1 && isset($this->followButtonActive) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0' && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.subscription',1)){ ?>
              <?php  $followbutton =  Engine_Api::_()->getDbtable('chanelfollows', 'sesvideo')->checkFollow(Engine_Api::_()->user()->getViewer()->getIdentity(),$chanelData->chanel_id); ?>
              <a href="javascript:;" data-url="<?php echo $chanelData->chanel_id ; ?>" class="sesbasic_icon_btn sesvideo_chanel_follow sesbasic_icon_btn_count sesbasic_icon_follow_btn <?php echo ($followbutton)  ? 'button_active' : '' ?>"> <i class="fa fa-check"></i><span><?php echo $chanelData->follow_count; ?></span></a>
              <?php } ?>
            <?php
              $canComment =  $chanelData->authorization()->isAllowed(Engine_Api::_()->user()->getViewer(), 'comment');
              if(isset($this->likeButtonActive) && Engine_Api::_()->user()->getViewer()->getIdentity() != 0 && $canComment){
            ?>
                <!--Like Button-->
                <?php $LikeStatus = Engine_Api::_()->sesvideo()->getLikeStatusVideo($chanelData->chanel_id,$chanelData->getType()); ?>
               <a href="javascript:;" data-url="<?php echo $chanelData->chanel_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesvideo_like_sesvideo_chanel <?php echo ($LikeStatus) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $chanelData->like_count; ?></span></a>
              <?php } ?>
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1) && isset($this->favouriteButtonActive) && isset($chanelData->favourite_count) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>            	
              <?php $favStatus = Engine_Api::_()->getDbtable('favourites', 'sesvideo')->isFavourite(array('resource_type'=>'sesvideo_chanel','resource_id'=>$chanelData->chanel_id)); ?>
             <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesvideo_favourite_sesvideo_chanel <?php echo ($favStatus)  ? 'button_active' : '' ?>"  data-url="<?php echo $chanelData->chanel_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $chanelData->favourite_count; ?></span></a>
            <?php } ?>
            </span>
             </div>
            <div class="sesvideo_browse_channel_item_stat sesbasic_text_light sesvideo_list_stats sesbasic_clearfix"> 
            	<?php if(isset($this->byActive)){ ?>
              <span>
              	<?php 
              	$owner = $chanelData->getOwner();
           		  echo $this->translate('Created by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
                ?>
              </span>
              <?php } ?>
              <?php if(isset($this->videoCountActive)){ ?>
            	<span title="<?php echo $this->translate(array('%s video', '%s videos', $chanelData->total_videos), $this->locale()->toNumber($chanelData->total_videos)); ?>">
              	<i class="fas fa-video"></i> <?php echo $chanelData->total_videos ; ?>
              </span>
              <?php } ?>
               <?php if(isset($this->likeActive) && isset($chanelData->like_count)) { ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $chanelData->like_count), $this->locale()->toNumber($chanelData->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $chanelData->like_count; ?></span>
              <?php } ?>
              <?php if(isset($this->photoActive) && Engine_Api::_()->getDbtable('modules', 'core')->isModuleEnabled('sesalbum')) { ?>
              <?php $photos = $chanelData->count(); ?>
                <span title="<?php echo $this->translate(array('%s photo', '%s photos', $photos), $this->locale()->toNumber($photos)); ?>"><i class="fa fa-image"></i><?php echo $photos; ?></span>
              <?php } ?>
              <?php if(isset($this->commentActive) && isset($chanelData->comment_count)) { ?>
                <span title="<?php echo $this->translate(array('%s comment', '%s comments', $chanelData->comment_count), $this->locale()->toNumber($chanelData->comment_count))?>"><i class="sesbasic_icon_comment_o"></i><?php echo $chanelData->comment_count;?></span>
              <?php } ?>
              <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesvideo.allowfavc', 1) && isset($this->favouriteActive)){ ?>
            	<span title="<?php echo $this->translate(array('%s favourite', '%s favourites', $chanelData->favourite_count), $this->locale()->toNumber($chanelData->favourite_count)); ?>">
              	<i class="sesbasic_icon_favourite_o"></i> <?php echo $chanelData->favourite_count ; ?>
              </span>
              <?php } ?>
              <?php if(isset($chanelData->follow) && $chanelData->follow == 1 && isset($this->followActive) && Engine_Api::_()->getApi('settings', 'core')->getSetting('video.enable.subscription',1)){ ?>
              <span title="<?php echo $this->translate(array('%s follow', '%s follows', $chanelData->follow_videos), $this->locale()->toNumber($chanelData->follow_videos)); ?>">
              	<i class="fa fa-users"></i> <?php echo $chanelData->follow_videos ; ?>
              </span>
              <?php } ?>
             	<?php if(isset($this->ratingActive) && $ratingChanelShow && isset($chanelData->rating) && $chanelData->rating > 0 ): ?>
              <span  title="<?php echo $this->translate(array('%s rating', '%s ratings', round($chanelData->rating,1)), $this->locale()->toNumber(round($chanelData->rating,1)))?>">
               <i class="far fa-star"></i><?php echo round($chanelData->rating,1).'/5';?>
              </span>
            <?php endif; ?>
            </div>
            <div class="sesvideo_browse_channel_item_cont_btm sesbasic_custom_scroll">
              <?php if(isset($this->descriptionActive)){ ?>
                <div class="sesvideo_list_des clear">
                	<?php if(strlen($chanelData->description) > $this->description_truncation){
                        $description = mb_substr(strip_tags($chanelData->description),0,$this->description_truncation).'...';
                       }else{ ?>
                  <?php $description = $chanelData->description; ?>
                  <?php } ?>
                  <?php echo $description; ?>
                </div>
              <?php } ?>
              <?php if(isset($this->resultArray['videos'][$chanelData->chanel_id])  && !$chanelData->adult){ ?>
             		<div class="sesvideo_list_channel_videos_listing clear sesbasic_clearfix">
                	<?php foreach($this->resultArray['videos'][$chanelData->chanel_id] as $videoData){ ?>
                  	<?php
                      $href = $videoData->getHref(array('type'=>'sesvideo_chanel','item_id'=>$chanelData->getIdentity()));
                      $imageURL = $videoData->getPhotoUrl();
                    ?>
                    <div class="sesvideo_listing_grid" style="height:<?php echo is_numeric($this->height) ? $this->height.'px' : $this->height ?>;width:<?php echo is_numeric($this->width) ? $this->width.'px' : $this->width ?>;">
                      <div class="sesvideo_grid_thumb sesvideo_thumb"> 
                        <a href="<?php echo $href; ?>" data-url = "<?php echo 'sesvideo_chanel' ?>" class="sesvideo_thumb_img sesvideo_lightbox_open"> 
                        <span style="background-image:url(<?php echo $imageURL; ?>);"></span> </a> 
                        <?php if(isset($this->durationActive)){ ?>
                        <?php
                          if( $videoData->duration >= 3600 ) {
                            $duration = gmdate("H:i:s", $videoData->duration);
                          } else {
                            $duration = gmdate("i:s", $videoData->duration);
                          }
                        ?>
                        <span class="sesvideo_length"><?php echo $duration; ?></span> 
                        <?php } ?>
                        <?php if(isset($this->watchLaterActive)){ ?>
                        <?php if(isset($videoData->watchlater_id) && Engine_Api::_()->user()->getViewer()->getIdentity() != '0'){ ?>
                      <a href="javascript:;" class="sesvideo_watch_later_btn sesvideo_watch_later <?php echo !is_null($videoData->watchlater_id)  ? 'selectedWatchlater' : '' ?>" title = "<?php echo !is_null($videoData->watchlater_id)  ? $this->translate('Remove from Watch Later') : $this->translate('Add to Watch Later') ?>" data-url="<?php echo $videoData->video_id ; ?>"></a>
                    <?php } ?>
                    <?php } ?>
                       </div>
                    </div>
                	<?php } ?>
                </div>
              <?php } ?>
          	</div>
            
            
            
          </div>
        </div>
      </div>
    </div>    
 <?php 
 		$chanelData = '';
 		endforeach; 
    if($this->loadOptionData == 'pagging'){ ?>
 		 <?php echo $this->paginationControl($this->paginator, null, array("_pagging.tpl", "sesvideo"),array('identityWidget'=>$randonNumber)); ?>
 <?php } ?>
 <?php if(!$this->is_ajax){ ?>
  </div>
  	<?php if($this->loadOptionData != 'pagging'){ ?>
    <div class="sesbasic_view_more sesbasic_load_btn" style="display:none;" id="view_more_<?php echo $randonNumber; ?>" onclick="viewMore_<?php echo $randonNumber; ?>();" > <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array('id' => "feed_viewmore_link_$randonNumber", 'class' => 'sesbasic_animation sesbasic_link_btn fa fa-sync')); ?> </div>
  <div class="sesbasic_view_more_loading" id="loading_image_<?php echo $randonNumber; ?>" style="display: none;"> <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span> </div>
  <?php } ?>
  <?php } ?>
 <?php if(!$this->is_ajax){ ?>
<script type="text/javascript">
scriptJquery(document).on('click','.seschanel_slideshow_prev',function(e){
		e.preventDefault();
		var activeClassIndex;
		var elem = scriptJquery(this).parent().parent().parent().find('.sesvideo_browse_channel_listing_thumbnails').children();
		var elemLength = elem.length;
		for(i=0;i<elemLength;i++){
			if(elem[i].hasClass('thumbnail_active')){
				 activeClassIndex = i;
				break;	
			}
		}
		if((activeClassIndex+1) == elemLength){
			var changeIndex = 0;	
		}else if(activeClassIndex == 0){
			var changeIndex = elemLength-1;
		}else{
			var changeIndex = activeClassIndex-1; 	
		}
		scriptJquery(this).parent().parent().parent().find('.sesvideo_browse_channel_listing_thumbnails').children().eq(changeIndex).find('a').click();
});
scriptJquery(document).on('click','.seschanel_slideshow_next',function(e){
	e.preventDefault();
	var activeClassIndex;
	var elem = scriptJquery(this).parent().parent().parent().find('.sesvideo_browse_channel_listing_thumbnails').children();
	var elemLength = elem.length;
	for(i=0;i<elemLength;i++){
		if(elem[i].hasClass('thumbnail_active')){
			 activeClassIndex = i;
			break;	
		}
	}
	if((activeClassIndex+1) == elemLength){
		var changeIndex = 0;	
	}else if(activeClassIndex == 0){
		var changeIndex = activeClassIndex+1;
	}else{
		var changeIndex = activeClassIndex+1; 	
	}
	scriptJquery(this).parent().parent().parent().find('.sesvideo_browse_channel_listing_thumbnails').children().eq(changeIndex).find('a').click();
});
scriptJquery(document).on('click','.slideshow_chanel_data',function(e){
	e.preventDefault();
	var chanel_id = scriptJquery(this).attr('data-url');
	if(scriptJquery(this).parent().hasClass('thumbnail_active')){
			return false;
	}
	if(!chanel_id)
		return false;
	 
	 var elIndex = scriptJquery(this).parent().index();
	 var totalDiv = scriptJquery(this).parent().parent().find('div');
	 for(i=0;i<totalDiv.length;i++){
			 totalDiv[i].removeClass('thumbnail_active');
	 }
	 scriptJquery(this).parent().addClass('thumbnail_active');
	 var containerElem = scriptJquery(this).parent().parent().parent().find('.sesvideo_browse_channel_conatiner').children();
	 for(i=0;i<containerElem.length;i++){
	 	if(i != (containerElem.length-1))
			containerElem[i].hide();
	 }
	 scriptJquery(containerElem).get(elIndex).show();
	if(scriptJquery(containerElem).get(elIndex).hasClass('nodata')){
	var imageUrl = en4.core.baseUrl +"application/modules/Sesbasic/externals/images/loading.gif";
	 scriptJquery(containerElem).eq(elIndex).html('<div class="sesbasic_view_more_loading"><img src="'+imageUrl+'"></div>');
	 scriptJquery.ajax({
      method: 'post',
      'url': en4.core.baseUrl + "sesvideo/chanel/chanel-data/chanel_id/"+chanel_id,
      'data': {
        format: 'html',
				params:'<?php echo json_encode($this->params); ?>',
				chanel_id : scriptJquery(this).attr('data-url'),
      },
      success: function(responseHTML) {
        scriptJquery(containerElem).eq(elIndex).html(responseHTML);
				scriptJquery(containerElem).eq(elIndex).removeClass('nodata');
// 				scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
//           theme:"minimal-dark"
//         });
      }
    });
	}
});
var valueTabData ;
// globally define available tab array
	var availableTabs_<?php echo $randonNumber; ?>;
	var requestTab_<?php echo $randonNumber; ?>;
  availableTabs_<?php echo $randonNumber; ?> = <?php echo json_encode($this->defaultOptions); ?>;
<?php if($this->loadOptionData == 'auto_load'){ ?>
		scriptJquery( window ).load(function() {
		 scriptJquery(window).scroll( function() {
			  var heightOfContentDiv_<?php echo $randonNumber; ?> = scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').offset().top;
        var fromtop_<?php echo $randonNumber; ?> = scriptJquery(this).scrollTop();
        if(fromtop_<?php echo $randonNumber; ?> > heightOfContentDiv_<?php echo $randonNumber; ?> - 100 && scriptJquery('#view_more_<?php echo $randonNumber; ?>').css('display') == 'block' ){
						document.getElementById('feed_viewmore_link_<?php echo $randonNumber; ?>').click();
				}
     });
	});
<?php } ?>
function paggingNumber<?php echo $randonNumber; ?>(pageNum){
	 scriptJquery('.sesbasic_loading_cont_overlay').css('display','block');
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>",
      'data': {
        format: 'html',
        page: pageNum,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
				scriptJquery('.sesbasic_loading_cont_overlay').css('display','none');
        document.getElementById('scrollHeightDivSes_<?php echo $randonNumber; ?>').innerHTML =  responseHTML;
// 				scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
//           theme:"minimal-dark"
//         });
      }
    }));
    return false;
}
</script>
<?php } ?>
<script type="text/javascript">
var defaultOpenTab ;
  viewMoreHide_<?php echo $randonNumber; ?>();
  function viewMoreHide_<?php echo $randonNumber; ?>() {
    if (document.getElementById('view_more_<?php echo $randonNumber; ?>'))
      document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = "<?php echo ($this->paginator->count() == 0 ? 'none' : ($this->paginator->count() == $this->paginator->getCurrentPageNumber() ? 'none' : '' )) ?>";
  }
  function viewMore_<?php echo $randonNumber; ?> (){
    var openTab_<?php echo $randonNumber; ?> = '<?php echo $this->defaultOpenTab; ?>';
    document.getElementById('view_more_<?php echo $randonNumber; ?>').style.display = 'none';
    document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = '';    
    en4.core.request.send(scriptJquery.ajax({
      dataType: 'html',
      method: 'post',
      'url': en4.core.baseUrl + "widget/index/mod/sesvideo/name/<?php echo $this->widgetName; ?>/openTab/" + openTab_<?php echo $randonNumber; ?>,
      'data': {
        format: 'html',
        page: <?php echo $this->page + 1; ?>,    
				params :'<?php echo json_encode($this->params); ?>', 
				is_ajax : 1,
				identity : '<?php echo $randonNumber; ?>',
      },
      success: function(responseHTML) {
        scriptJquery('#scrollHeightDivSes_<?php echo $randonNumber; ?>').append(responseHTML);
				document.getElementById('loading_image_<?php echo $randonNumber; ?>').style.display = 'none';
// 				scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
//           theme:"minimal-dark"
//         });
      }
    }));
    return false;
  }
</script>
<?php if(!$this->is_ajax) { ?>
<script type="application/javascript">
scriptJquery('.sesvideo_main_browsechanel').parent().addClass('active');
</script>
<?php } ?>
