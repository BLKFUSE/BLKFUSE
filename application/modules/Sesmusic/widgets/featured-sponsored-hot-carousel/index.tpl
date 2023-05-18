<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: index.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>

<?php $randonNumber = $this->identity; ?>
<?php $baseUrl = $this->layout()->staticBaseUrl; ?>

<?php
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
  $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/carousel.css'); ?>

<style>
  #sesmusic_fs_carousel_<?php echo $randonNumber; ?> {
    position: relative;
    height:<?php echo $this->height + 64 ?>px;
    overflow: hidden;
  }
</style>

<div class="slide sesmusic_carousel_wrapper sesbasic_clearfix sesbasic_bxs">
  <div id="sesmusic_fs_carousel_<?php echo $randonNumber; ?>" class="sesmusic_fs_carousel ">
    <?php foreach( $this->results as $item ):  ?>
      <div class="sesmusic_item_grid <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?> paid_content <?php } ?>">
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($item)) { ?>
          <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $item)); ?>
        <?php } ?>
        <div class="sesmusic_item_artwork">
          <div class="sesmusic_item_artwork_img" style="height:<?php echo $this->height ?>px;">
            <?php if($this->contentType == 'songs'): ?>
              <?php $album = Engine_Api::_()->getItem('sesmusic_albums', $item->album_id); ?>
              <?php if($item->photo_id): ?>
                <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile')); ?>
              <?php else: ?>
                <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.main')); ?>
              <?php endif; ?>
            <?php else: ?>
              <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.main')); ?>
            <?php endif; ?>
            <a href="<?php echo $item->getHref(); ?>" class="transparentbg"></a>
          </div>
          <div class="sesmusic_item_artwork_over_content sesmusic_animation">
            <div class="sesmusic_item_info_label">
              <?php if(!empty($item->hot) && !empty($this->information) && engine_in_array('hot', $this->information)): ?>
                <span class="sesmusic_label_hot"><?php echo $this->translate("HOT"); ?></span>
              <?php endif; ?>
              <?php if(!empty($item->featured) && !empty($this->information) && engine_in_array('featured', $this->information)): ?>
                <span class="sesmusic_label_featured"><?php echo $this->translate("FEATURED"); ?></span>
              <?php endif; ?>
              <?php if(!empty($item->sponsored) && !empty($this->information) && engine_in_array('sponsored', $this->information)): ?>
                <span class="sesmusic_label_sponsored"><?php echo $this->translate("SPONSORED"); ?></span>
              <?php endif; ?>
            </div>
            <div class="sesmusic_social_item sesmusic_animation">
              <?php if($this->contentType == 'albums'): ?>
                <!--Social Share Button-->
                <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                  <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
                  
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

                <?php } ?>
                <!--Social Share Button-->
                
                <!--Like and Favourite Button-->
                <?php $viewer = Engine_Api::_()->user()->getViewer();
                $viewer_id = $viewer->getIdentity();
                $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
                $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($item, $viewer); ?>
                <?php if ($canLike && !empty($viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
                  <a href="javascript:;" data-url="<?php echo $item->album_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $item->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                <?php endif; ?>
                
                <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_album", 'resource_id' => $item->album_id)); ?>
                <?php if(!empty($viewer_id) && $this->canAddFavourite && $this->information && engine_in_array('favourite', $this->information)): ?>
                  <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $item->getType(); ?> <?php echo ($isFavourite)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->album_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                <?php endif; ?>
                <!--Like and Favourite Button-->  

                <?php if($this->viewer_id): ?>                  
                  <?php if($this->canAddPlaylist && !empty($this->information) && engine_in_array('addplaylist', $this->information)): ?>
                    <a class="sesbasic_icon_btn" title='<?php echo $this->translate("Add to Playlist") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append - songs','album_id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
                  <?php endif; ?>
                  <?php if($this->albumlink && engine_in_array('share', $this->albumlink) && !empty($this->information) && engine_in_array('share', $this->information)): ?>
                    <a class="sesbasic_icon_btn" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_album', 'id' => $item->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
                  <?php endif; ?>
                <?php endif; ?>
              <?php else: ?>
                <?php $path = Engine_Api::_()->sesmusic()->songImageURL($item); ?>
                <!--<a title="<?php echo $item->getTitle(); ?>" href="<?php echo $item->getHref(); ?>" class="sesmusic_grid_link"></a>-->

                  <!--Social Share Button-->
                  <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                    <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $item->getHref()); ?>
                    
                    <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $item, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

                  <?php } ?>
                  <!--Social Share Button-->
                  
                  <!--Like and Favourite Button-->
                  <?php $viewer = Engine_Api::_()->user()->getViewer();
                  $viewer_id = $viewer->getIdentity();
                  $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
                  $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($item, $viewer); ?>
                  <?php if ($canLike && !empty($viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
                    <a href="javascript:;" data-url="<?php echo $item->albumsong_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $item->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $item->like_count; ?></span></a>
                  <?php endif; ?>
                  
                  <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $item->albumsong_id)); ?>
                  <?php if(!empty($viewer_id) && $this->addfavouriteAlbumSong && $this->information && engine_in_array('favourite', $this->information)): ?>
                    <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $item->getType(); ?> <?php echo ($isFavourite)  ? 'button_active' : '' ?>"  data-url="<?php echo $item->albumsong_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $item->favourite_count; ?></span></a>
                  <?php endif; ?>
                  <!--Like and Favourite Button--> 
                
                  <?php if($this->viewer_id): ?>
                  <!--<?php if($this->addfavouriteAlbumSong): ?>
                  <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $item->albumsong_id)); ?>                            <a title='<?php echo $this->translate("Remove from Favorite") ?>' class="favorite-white favorite" id="sesmusic_albumsong_unfavourite_<?php echo $item->albumsong_id; ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $item->albumsong_id; ?>', 'sesmusic_albumsong');"><i class="fa fa-heart"></i></a>
                  <a title='<?php echo $this->translate("Add to Favorite") ?>' class="favorite-white favorite" id="sesmusic_albumsong_favourite_<?php echo $item->albumsong_id; ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $item->albumsong_id; ?>', 'sesmusic_albumsong');"><i class="fa fa-heart"></i></a>
                  <input type ="hidden" id = "sesmusic_albumsong_favouritehidden_<?php echo $item->albumsong_id; ?>" value = '<?php echo $isFavourite ? $isFavourite : 0; ?>' />
                  <?php endif; ?>-->
                  <?php if($this->canAddPlaylistAlbumSong): ?>
                    <a title="<?php echo $this->translate('Add to Playlist');?>" href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('action'=>'append','albumsong_id' => $item->albumsong_id, 'format' => 'smoothbox'), 'sesmusic_albumsong_specific' , true)); ?>'); return false;" class="sesbasic_icon_btn"><i class="fa fa-plus"></i></a>
                  <?php endif; ?>
                  <?php if($this->songlink && engine_in_array('share', $this->songlink)): ?>
                  <a class="sesbasic_icon_btn" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $item->albumsong_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endif; ?>
            </div>
            <?php if($this->contentType == 'albums'): ?>
            	<div class="sesmusic_item_stats_play_btn sesmusic_animation">
              	<a class="sesmusic_play_button" href="<?php echo $item->getHref(); ?>"><i class="fa fa-play-circle"></i></a>
              </div>
           	<?php else: ?>
            	<div class="sesmusic_item_stats_play_btn sesmusic_animation">
                <?php if($item->track_id): ?>
                  <?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
                  $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
                  <?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
                  <?php $URL = "http://api.soundcloud.com/tracks/$item->track_id/stream?consumer_key=$consumer_key"; ?>
                    <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo preg_replace("/[^A-Za-z0-9\-]/", "", $item->getTitle()); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                  <?php else: ?>
                    <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $item->getTitle(); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                  <?php endif; ?>
                <?php else: ?>
                  <?php if($item->store_link): ?>
                    <?php $storeLink = !empty($item->store_link) ? (preg_match("#https?://#", $item->store_link) === 0) ? 'http://'.$item->store_link : $item->store_link : ''; ?>
                    <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $item->getTitle(); ?>', '<?php echo $storeLink ?>', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                  <?php else: ?>
                    <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $item->albumsong_id ?>', '<?php echo $item->getFilePath(); ?>', '<?php echo $item->getTitle(); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <div class="sesmusic_item_stats_info sesmusic_animation">
              <div class="sesmusic_item_info_stats">
                <?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) :?>
                  <span>
                    <?php echo $item->comment_count; ?>
                    <i class="fa fa-comment"></i>
                  </span>
                <?php endif; ?>
                <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
                  <span>
                    <?php echo $item->like_count; ?>
                    <i class="fa fa-thumbs-up"></i>
                  </span>
                <?php endif; ?>
                <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
                  <span>
                    <?php echo $item->view_count; ?>
                    <i class="fa fa-eye"></i>
                  </span>
                <?php endif; ?>
                <?php if ($this->contentType == 'albums' && !empty($this->information) && engine_in_array('songCount', $this->information)) : ?>
                  <span>
                    <?php echo $item->song_count; ?>
                    <i class="fa fa-music"></i>
                  </span>
                <?php endif; ?>
                <?php if($this->contentType == 'songs'): ?>
                  <?php if (!empty($this->information) && engine_in_array('downloadCount', $this->information)) : ?>
                  <span>
                    <?php echo $item->download_count; ?>
                    <i class="fa fa-download"></i>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($this->information) && engine_in_array('playCount', $this->information)) : ?>
                  <span>
                    <?php echo $item->play_count; ?>
                    <i class="fa fa-play"></i>
                  </span>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
              <?php if ($this->contentType == 'albums' && $this->showRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
                <div class="sesmusic_item_info_rating">
                  <?php if( $item->rating > 0 ): ?>
                  <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
                  <span class="sesbasic_rating_star_small fa fa-star"></span>
                  <?php endfor; ?>
                  <?php if( (round($item->rating) - $item->rating) > 0): ?>
                  <span class="sesbasic_rating_star_small fa fa-star-half"></span>
                  <?php endif; ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <?php if ($this->contentType == 'songs' && $this->showAlbumSongRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
                <div class="sesmusic_item_info_rating">
                  <?php if( $item->rating > 0 ): ?>
                  <?php for( $x=1; $x<= $item->rating; $x++ ): ?>
                  <span class="sesbasic_rating_star_small fa fa-star"></span>
                  <?php endfor; ?>
                  <?php if( (round($item->rating) - $item->rating) > 0): ?>
                  <span class="sesbasic_rating_star_small fa fa-star-half"></span>
                  <?php endif; ?>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="sesmusic_item_info">
          <div class="sesmusic_item_info_title">
            <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle(), array('title' => $item->description)) ?>
            <?php endif; ?>
          </div>
          <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
            <div class="sesmusic_item_info_owner">
              <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>
            </div>
          <?php endif; ?>
      	</div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<script type="text/javascript">
sesowlJqueryObject(document).ready(function() {
	sesowlJqueryObject('#sesmusic_fs_carousel_<?php echo $randonNumber; ?>').owlCarousel({
		loop:true,
		dots:false,
		margin:0,
		responsiveClass:true,
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
					items:<?php echo $this->CountToShow; ?>,
					nav:true,
			}
		}
	})
	sesowlJqueryObject(".owl-prev").html('<i class="fas fa-chevron-left"></i>');
	sesowlJqueryObject(".owl-next").html('<i class="fas fa-chevron-right"></i>');
});
</script>
