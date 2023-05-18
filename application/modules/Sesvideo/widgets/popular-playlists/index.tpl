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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesvideo/externals/styles/styles.css'); ?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }
</script>
<?php if($this->showType == 'gridview'): ?>
<?php if(engine_count($this->results) > 0) :?>
  <ul class="sesvideo_playlist_grid_listing sesbasic_clearfix sesbasic_bxs">
    <?php foreach( $this->results as $item ):  ?>
      <li class="sesvideo_playlist_grid sesbm" style="width:<?php echo $this->width ?>px;">
        <div class="sesvideo_playlist_grid_top sesbasic_clearfix">
          <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon')) ?>
          <div>
            <div class="sesvideo_playlist_grid_title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
            <div class="sesvideo_playlist_grid_stats  sesbasic_text_light">
              <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>     
            </div>
            <?php endif; ?>
            <div class="sesvideo_playlist_grid_stats sesvideo_list_stats sesbasic_text_light">
              <?php if (!empty($this->information) && engine_in_array('favouriteCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s favorite', '%s favorites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count)); ?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count; ?></span>
              <?php endif; ?>
              <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)); ?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php endif; ?>
              <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php endif; ?>
              <?php $videoCount = Engine_Api::_()->getDbtable('playlistvideos', 'sesvideo')->playlistVideosCount(array('playlist_id' => $item->playlist_id));  ?>
              <?php if (!empty($this->information) && engine_in_array('videoCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s video', '%s videos', $videoCount), $this->locale()->toNumber($videoCount)); ?>"><i class="fas fa-video"></i><?php echo $videoCount; ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>
               
        <?php $songs = $item->getVideos(); ?>
        <?php if($songs && !empty($this->information) && engine_in_array('songsListShow', $this->information)):  ?>
        <div class="clear sesbasic_clearfix sesvideo_videos_minilist_container sesbm sesbasic_custom_scroll">
          <ul class="clear sesvideo_videos_minilist sesbasic_bxs">            
            <?php foreach( $songs as $song ):  ?>
             <?php $song = Engine_Api::_()->getItem('sesvideo_video', $song->file_id); ?>
              <li class="sesbasic_clearfix sesbm">
                <div class="sesvideo_videos_minilist_photo">
                  <a class="sesvideo_thumb_img sesvideo_lightbox_open" data-url = "<?php echo $song->getType() ?>" href="<?php echo $song->getHref(array('type'=>'sesvideo_playlist','item_id'=>$item->playlist_id)); ?>">
              			<span style="background-image:url(<?php echo $song->getPhotoUrl() ?>);"></span>
              		</a>
                </div>
                <div class="sesvideo_videos_minilist_name" title="<?php echo $song->getTitle() ?>">
                    <?php echo $this->htmlLink($song->getHref(), $song->getTitle(), array('class' => 'sesbasic_linkinherit')); ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>        
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php elseif($this->showType == 'carouselview'):  ?>

  <?php $randonNumber = $this->identity; ?>
  <?php $baseUrl = $this->layout()->staticBaseUrl; ?>
  <?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
?>
  <?php
 $this->headLink()->appendStylesheet($baseUrl . 'application/modules/Sesbasic/externals/styles/carousel.css'); 
 ?>

  <style>
    #playlistslide_<?php echo $randonNumber; ?> {
      position: relative;
      height:<?php echo $this->height ?>px;
      overflow: hidden;
    }
  </style>

  <div class="slide sesbasic_carousel_wrapper sesbm clearfix sesbasic_bxs sesbasic_carousel_h_wrapper">
    <div id="playlistslide_<?php echo $randonNumber; ?>" class="sesvideo_popular_playlist_carousel">
    <?php foreach( $this->results as $item ):  ?>
      <div class="sesvideo_playlist_grid sesbm" style="height:<?php echo $this->height ?>px;">
        <div class="sesvideo_playlist_grid_top sesbasic_clearfix">
          <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon')) ?>
          <div>
            <div class="sesvideo_playlist_grid_title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
            <div class="sesvideo_playlist_grid_stats sesbasic_text_light">
              <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>     
            </div>
            <?php endif; ?>
            <div class="sesvideo_playlist_grid_stats sesvideo_list_stats sesbasic_text_light">
              <?php if (!empty($this->information) && engine_in_array('favouriteCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s favorite', '%s favorites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count)); ?>"><i class="sesbasic_icon_favourite_o"></i><?php echo $item->favourite_count; ?></span>
              <?php endif; ?>
              <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)); ?>"><i class="sesbasic_icon_view"></i><?php echo $item->view_count; ?></span>
              <?php endif; ?>
              <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s like', '%s likes', $item->like_count), $this->locale()->toNumber($item->like_count)); ?>"><i class="sesbasic_icon_like_o"></i><?php echo $item->like_count; ?></span>
              <?php endif; ?>
              <?php $videoCount = Engine_Api::_()->getDbtable('playlistvideos', 'sesvideo')->playlistVideosCount(array('playlist_id' => $item->playlist_id));  ?>
              <?php if (!empty($this->information) && engine_in_array('videoCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s video', '%s videos', $videoCount), $this->locale()->toNumber($videoCount)); ?>"><i class="fas fa-video"></i><?php echo $videoCount; ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php $songs = $item->getVideos(); ?>
        <?php if($songs && !empty($this->information) && engine_in_array('songsListShow', $this->information)):  ?>
        <div class="clear sesbasic_clearfix sesvideo_videos_minilist_container sesbm sesbasic_custom_scroll">
          <ul class="clear sesvideo_videos_minilist sesbasic_bxs">            
            <?php foreach( $songs as $song ): ?>
             <?php $song = Engine_Api::_()->getItem('sesvideo_video', $song->file_id); ?>
              <li class="sesbasic_clearfix sesbm">
                <div class="sesvideo_videos_minilist_photo">
                  <a class="sesvideo_thumb_img sesvideo_lightbox_open" data-url = "<?php echo $song->getType() ?>" href="<?php echo $song->getHref(array('type'=>'sesvideo_playlist','item_id'=>$item->playlist_id)); ?>">
              			<span style="background-image:url(<?php echo $song->getPhotoUrl() ?>);"></span>
              		</a>
                </div>
                <div class="sesvideo_videos_minilist_name" title="<?php echo $song->getTitle() ?>">
                   <?php echo $this->htmlLink($song->getHref(), $song->getTitle(), array('class' => 'sesbasic_linkinherit')); ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
  <script type="text/javascript">
	<?php if($allParams['autoplay']){ ?>
		var autoplay_<?php echo $this->identity; ?> = true;
	<?php }else{ ?>
		var autoplay_<?php echo $this->identity; ?> = false;
	<?php } ?>
  sesowlJqueryObject(".sesvideo_popular_playlist_carousel").owlCarousel({
  loop:<?php if($this->playlist_limit <= $this->totalLimit){echo 1 ;}else{ echo 0 ;} ?>,
  nav:true,
  dots:false,
	margin:10,
  responsiveClass:true,
	//autoplaySpeed: <?php echo $allParams['speed']; ?>,
	//autoplay: autoplay_<?php echo $this->identity; ?>,
  responsive:{
    0:{
        items:1,
    },
    600:{
        items:1,
    },
		1000:{
        items:<?php echo $this->playlist_limit; ?>,
    },
  }
});
sesowlJqueryObject(".owl-prev").html('<i class="fas fa-angle-left"></i>');
sesowlJqueryObject(".owl-next").html('<i class="fas fa-angle-right"></i>');
</script>
<?php endif; ?>


<script>
	scriptJquery(document).ready(function() {
		(function($){
			scriptJquery(window).load(function(){
				scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
					theme:"minimal-dark"
				});
				
			});
		})(jQuery);
	});
</script>
