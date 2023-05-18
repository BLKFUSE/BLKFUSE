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
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }
</script>
<?php if($this->contentType == 'album'): ?>
<ul class="sesmusic_side_block sesmusic_browse_listing clear sesbasic_bxs">
  <li class="sesmusic_item_grid sesbasic_bxs<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->album)) { ?> paid_content <?php } ?>">
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->album)) { ?>
       <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $this->album)); ?>
    <?php } ?> 
    <div class="sesmusic_item_artwork">
    	<div class="sesmusic_item_artwork_img">
        <?php echo $this->itemPhoto($this->album, 'thumb.main') ?>
        <a href="<?php echo $this->album->getHref(); ?>" class="transparentbg"></a>
      </div>
      <div class="sesmusic_item_artwork_over_content sesmusic_animation">
      	<div class="sesmusic_item_sponseard_social">
          <?php // Featured and Sponsored and Hot Label Icon ?>
          <div class="sesmusic_item_info_label">
            <?php if(!empty($this->album->hot) && !empty($this->information) && engine_in_array('hot', $this->information)): ?>
            <span class="sesmusic_label_hot fa fa-star" title='<?php echo $this->translate("HOT"); ?>'></span>
            <?php endif; ?>
            <?php if(!empty($this->album->featured) && !empty($this->information) && engine_in_array('featured', $this->information)): ?>
            <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
            <?php endif; ?>
            <?php if(!empty($this->album->sponsored) && !empty($this->information) && engine_in_array('sponsored', $this->information)): ?>
            <span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
            <?php endif; ?>
          </div>
    				<div class="sesmusic_social_item sesmusic_animation">
              <!--Social Share Button-->
              <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->album->getHref()); ?>
                
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->album, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>
                

              <?php } ?>
              <!--Social Share Button-->
              
              <!--Like and Favourite Button-->
              <?php $viewer = Engine_Api::_()->user()->getViewer();
              $viewer_id = $viewer->getIdentity();
              $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
              $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($this->album, $this->viewer); ?>
              <?php if ($canLike && !empty($viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
                <a href="javascript:;" data-url="<?php echo $this->album->album_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $this->album->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $this->album->like_count; ?></span></a>
              <?php endif; ?>
              <?php if(!empty($viewer_id) && $this->canAddFavourite && $this->information && engine_in_array('addFavouriteButton', $this->information)): ?>
                <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $this->album->getType(); ?> <?php echo ($this->isFavourite)  ? 'button_active' : '' ?>"  data-url="<?php echo $this->album->album_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->album->favourite_count; ?></span></a>
              <?php endif; ?>
              <!--Like and Favourite Button-->
              
              
              <?php if($this->viewer_id): ?>           
              <?php if($this->canAddPlaylist): ?>
              <a class="sesbasic_icon_btn add-white" title='<?php echo $this->translate("Add to Playlist") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module' =>'sesmusic', 'controller' => 'song', 'action'=>'append - songs','album_id' => $this->album->album_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-plus"></i></a>
              <?php endif; ?>
              <?php if($this->albumlink && engine_in_array('share', $this->albumlink)): ?>
              <a class="sesbasic_icon_btn share-white" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_album', 'id' => $this->album->getIdentity(), 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
              <?php endif; ?>
              <?php endif; ?>
            </div>
        </div>
        <div class="sesmusic_item_stats_info sesmusic_animation">
        	<div class="sesmusic_item_info_stats">
            <?php if (!empty($this->information) && engine_in_array('favouriteCount', $this->information)) : ?>
              <span>
                <?php echo $this->album->favourite_count; ?>
                <i class="fa fa-heart"></i>
              </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) : ?>
              <span>
              <?php echo $this->album->comment_count; ?>
              <i class="fa fa-comment"></i>
              </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
              <span>
              <?php echo $this->album->like_count; ?>
                <i class="fa fa-thumbs-up"></i>
              </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
              <span>
              <?php echo $this->album->view_count; ?>
              <i class="fa fa-eye"></i>
              </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('songsCount', $this->information)) : ?>
              <span>
              <?php echo $this->album->song_count; ?>
              <i class="fa fa-music"></i>
              </span>
            <?php endif; ?>
          </div>    
          <?php if ($this->showRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
            <div class="sesmusic_item_info_rating">
            <?php if( $this->album->rating > 0 ): ?>
            <?php for( $x=1; $x<= $this->album->rating; $x++ ): ?>
            <span class="sesbasic_rating_star_small fa fa-star"></span>
            <?php endfor; ?>
            <?php if( (round($this->album->rating) - $this->album->rating) > 0): ?>
            <span class="sesbasic_rating_star_small fa fa-star-half"></span>
            <?php endif; ?>
            <?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
        <div class="sesmusic_item_stats_play_btn sesmusic_animation">
       		<a href="<?php echo $this->album->getHref(); ?>" class="play_btn"><i class="fa fa-play-circle"></i></a>
        </div>
      </div>
    </div>
     <div class="sesmusic_item_info">
        <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
        <div class="sesmusic_item_info_title">
          <?php echo $this->htmlLink($this->album->getHref(), $this->album->getTitle()) ?>
        </div>
        <?php endif; ?>
        <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
        <div class="sesmusic_item_info_owner">
          <?php echo $this->translate('by');?> <?php echo $this->htmlLink($this->album->getOwner()->getHref(), $this->album->getOwner()->getTitle()) ?>
        </div>
        <?php endif; ?>
      </div>
    
    
  </li>
</ul>
<?php elseif($this->contentType == 'albumsong'): ?>
<ul class="sesmusic_side_block sesmusic_browse_listing clear sesbasic_bxs">
  <li class="sesmusic_item_grid sesbasic_bxs<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->album)) { ?> paid_content <?php } ?>">
  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($this->album)) { ?>
       <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $this->album)); ?>
    <?php } ?>    
    <div class="sesmusic_item_artwork">
    	<div class="sesmusic_item_artwork_img">
      	<?php if($this->song->photo_id): ?>
        <?php echo $this->itemPhoto($this->song, 'thumb.profile') ?>
      <?php else: ?>
        <?php echo $this->itemPhoto($this->song, 'thumb.main') ?>
      <?php endif; ?>
      <a href="<?php echo $this->song->getHref(); ?>" class="transparentbg"></a>
      </div>
      <div class="sesmusic_item_artwork_over_content sesmusic_animation">
      	<div class="sesmusic_item_sponseard_social">
          	<?php // Featured and Sponsored and Hot Label Icon ?>
          	<div class="sesmusic_item_info_label">
	          <?php if(!empty($this->song->hot) && !empty($this->information) && engine_in_array('hot', $this->information)): ?>
  	        <span class="sesmusic_label_hot fa fa-star" title='<?php echo $this->translate("HOT"); ?>'></span>
    	      <?php endif; ?>
      	    <?php if(!empty($this->song->featured) && !empty($this->information) && engine_in_array('featured', $this->information)): ?>
        	  <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
          	<?php endif; ?>
          	<?php if(!empty($this->song->sponsored) && !empty($this->information) && engine_in_array('sponsored', $this->information)): ?>
          	<span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
          	<?php endif; ?>
          	</div>
            <div class="sesmusic_social_item sesmusic_animation">
              <!--Social Share Button-->
              <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $this->song->getHref()); ?>
                
                <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $this->song, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this-socialshare_icon_limit)); ?>

              <?php } ?>
              <!--Social Share Button-->
              <!--Like and Favourite Button-->
              <?php $viewer = Engine_Api::_()->user()->getViewer();
              $viewer_id = $viewer->getIdentity();
              $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
              $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($this->song, $viewer); ?>
              <?php if ($canLike && !empty($viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
                <a href="javascript:;" data-url="<?php echo $this->song->albumsong_id ; ?>" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_like_btn sesmusic_like_<?php echo $this->song->getType(); ?> <?php echo ($isLike) ? 'button_active' : '' ; ?>"> <i class="fa fa-thumbs-up"></i><span><?php echo $this->song->like_count; ?></span></a>
              <?php endif; ?>
              
              <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $this->song->albumsong_id)); ?>
              <?php if(!empty($viewer_id) && $this->addfavouriteAlbumSong && $this->information && engine_in_array('addFavouriteButton', $this->information)): ?>
                <a href="javascript:;" class="sesbasic_icon_btn sesbasic_icon_btn_count sesbasic_icon_fav_btn sesmusic_favourite_<?php echo $this->song->getType(); ?> <?php echo ($isFavourite)  ? 'button_active' : '' ?>" data-url="<?php echo $this->song->albumsong_id ; ?>"><i class="fa fa-heart"></i><span><?php echo $this->song->favourite_count; ?></span></a>
              <?php endif; ?>
              <!--Like and Favourite Button-->
              
          <?php if($this->viewer_id): ?>
            <?php if($this->canAddPlaylistAlbumSong): ?>
             <a title="<?php echo $this->translate('Add to Playlist');?>" href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('action'=>'append','albumsong_id' => $this->song->albumsong_id, 'format' => 'smoothbox'), 'sesmusic_albumsong_specific' , true)); ?>'); return false;" class="sesbasic_icon_btn add-white"><i class="fa fa-plus"></i></a>
              
            <?php endif; ?>
            <?php if($this->songlink && engine_in_array('share', $this->songlink)): ?>
              <a class="sesbasic_icon_btn share-white" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $this->song->getIdentity(), 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><i class="fa fa-share"></i></a>
            <?php endif ;?>
          <?php endif; ?>
            </div>
          	</div>
        <div class="sesmusic_item_stats_info sesmusic_animation">
           <div class="sesmusic_item_info_stats">
            <?php if (!empty($this->information) && engine_in_array('favouriteCount', $this->information)) : ?>
              <span>
                <?php echo $this->song->favourite_count; ?>
                <i class="fa fa-heart"></i>              
              </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('commentCount', $this->information)) : ?>
            <span>
              <?php echo $this->song->comment_count; ?>
              <i class="fa fa-comment"></i>
            </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('likeCount', $this->information)) : ?>
            <span>
              <?php echo $this->song->like_count; ?>
              <i class="fa fa-thumbs-up"></i>
            </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)) : ?>
            <span>
              <?php echo $this->song->view_count; ?>
              <i class="fa fa-eye"></i>
            </span>
            <?php endif; ?>
            <?php if (!empty($this->information) && engine_in_array('downloadCount', $this->information)) : ?>
              <span>
                <?php echo $this->song->download_count; ?>
                <i class="fa fa-download"></i>
              </span>
            <?php endif; ?>
          </div>          
          <?php if ($this->showAlbumSongRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
            <div class="sesmusic_item_info_rating">
              <?php if( $this->song->rating > 0 ): ?>
              <?php for( $x=1; $x<= $this->song->rating; $x++ ): ?>
              <span class="sesbasic_rating_star_small fa fa-star"></span>
              <?php endfor; ?>
              <?php if( (round($this->song->rating) - $this->song->rating) > 0): ?>
              <span class="sesbasic_rating_star_small fa fa-star-half"></span>
              <?php endif; ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          </div>
          <div class="sesmusic_item_stats_play_btn sesmusic_animation">
          	        <?php $path = Engine_Api::_()->sesmusic()->songImageURL($this->song); ?>
			  <?php if($this->song->track_id): ?>
	        <?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
	        $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
	        <?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
	        <?php $track_id = $this->song->track_id;
          $URL = "http://api.soundcloud.com/tracks/$track_id/stream?consumer_key=$consumer_key"; ?>
	          <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $this->song->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo preg_replace("/[^A-Za-z0-9\-]/", "", $this->song->getTitle()); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
	        <?php else: ?>
	          <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $this->song->albumsong_id ?>', '<?php echo $this->song->getFilePath(); ?>', '<?php echo $this->song->getTitle(); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
	        <?php endif; ?>
	      <?php else: ?>
	        <a class="sesmusic_play_button" href="javascript:void(0);" onclick="play_music('<?php echo $this->song->albumsong_id ?>', '<?php echo $this->song->getFilePath(); ?>', '<?php echo $this->song->getTitle(); ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
	      <?php endif; ?>
          </div>
       </div>
      </div>
			<div class="sesmusic_item_info">
        <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
        <div class="sesmusic_item_info_title">
          <?php echo $this->htmlLink($this->song->getHref(), $this->song->getTitle()) ?>
        </div>
        <?php endif; ?>
        <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
        <div class="sesmusic_item_info_owner">
          <?php echo $this->translate('by');?> <?php echo $this->htmlLink($this->song->getOwner()->getHref(), $this->song->getOwner()->getTitle()) ?>
        </div>
        <?php endif; ?>
      </div>
      
  </li>
</ul>
<?php elseif($this->contentType == 'artist'): ?>
<ul class="sesmusic_artist_listing clear">
  <li class="sesmusic_artist_grid">
  	<div class="sesmusic_artist_grid_inner">
      <div class="sesmusic_artist_grid_bg_image">
        <?php if($this->artist->artist_photo): ?>
          <?php $img_path = Engine_Api::_()->storage()->get($this->artist->artist_photo, '')->getPhotoUrl();
          $path = $img_path; 
          ?>
          <img src="<?php echo $path ?>">
        <?php else: ?>
          <img src="<?php //echo $path ?>">
        <?php endif; ?>
  		</div>
      <div class="sesmusic_item_artwork">
        <?php if($this->artist->artist_photo): ?>
          <?php $img_path = Engine_Api::_()->storage()->get($this->artist->artist_photo, '')->getPhotoUrl();
          $path = $img_path; 
          ?>
          <img src="<?php echo $path ?>">
        <?php else: ?>
          <img src="<?php //echo $path ?>">
        <?php endif; ?>
        <div class="hover_box">
          <?php if($this->viewer_id): ?>
          <div class="hover_box_options">          
            <?php if($this->artistlink && engine_in_array('favourite', $this->artistlink)): ?>
              <a title='<?php echo $this->translate("Remove from Favorite") ?>' class="favorite-white favorite" id="sesmusic_artist_unfavourite_<?php echo $this->artist->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->artist->getIdentity(); ?>', 'sesmusic_artist');"><i class="fa fa-heart sesmusic_favourite"></i></a>
              <a title='<?php echo $this->translate("Add to Favorite") ?>' class="favorite-white favorite" id="sesmusic_artist_favourite_<?php echo $this->artist->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->artist->getIdentity(); ?>', 'sesmusic_artist');"><i class="fa fa-heart"></i></a>
              <input type ="hidden" id = "sesmusic_artist_favouritehidden_<?php echo $this->artist->getIdentity(); ?>" value = '<?php echo $this->isFavourite ? $this->isFavourite : 0; ?>' />
            <?php endif; ?>          
          </div>
          <?php endif; ?>
          <a href="<?php echo $this->escape($this->url(array('module'=>'sesmusic', 'controller'=>'artist', 'action'=>'view', 'artist_id' => $this->artist->artist_id), 'default' , true)); ?>" class="transparentbg" <?php if(!$this->viewer_id): ?>style="background:none !important;" <?php endif; ?>></a>
        </div>
      </div>
      <div class="sesmusic_artist_info">
        <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
          <div class="sesmusic_artist_title">
            <?php echo $this->htmlLink(array('module'=>'sesmusic', 'controller'=>'artist', 'action'=>'view', 'route'=>'default', 'artist_id' => $this->artist->artist_id), $this->artist->name); ?>
          </div>
        <?php endif; ?>
        <div class="sesmusic_artist_stats sesbasic_text_light">
          <?php if (!empty($this->information) && engine_in_array('favouriteCount', $this->information)) : ?>
            <span class="floatL">
              <?php echo $this->translate(array('%s favorite', '%s favorites', $this->artist->favourite_count), $this->locale()->toNumber($this->artist->favourite_count)) ?>
            </span>
          <?php endif; ?>
          <?php if ($this->showArtistRating && !empty($this->information) && engine_in_array('ratingCount', $this->information)) : ?>
            <span class="floatR">
              <?php if( $this->artist->rating > 0 ): ?>
              <?php for( $x=1; $x<= $this->artist->rating; $x++ ): ?>
              <span class="sesbasic_rating_star_small fa fa-star"></span>
              <?php endfor; ?>
              <?php if( (round($this->artist->rating) - $this->artist->rating) > 0): ?>
              <span class="sesbasic_rating_star_small fa fa-star-half"></span>
              <?php endif; ?>
              <?php endif; ?>
            </span>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </li>
</ul>
<?php elseif($this->contentType == 'playlist'): ?>
  <ul class="sesmusic_playlist_grid_listing sesbasic_clearfix">
      <?php $item = $this->playlist; ?>
      <li class="sesmusic_playlist_grid" style="width:<?php echo $this->width ?>px;">
        <div class="sesmusic_playlist_grid_top sesbasic_clearfix">
          <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.icon')) ?>
          <div>
            <div class="sesmusic_playlist_grid_title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
            <div class="sesmusic_playlist_grid_stats  sesbasic_text_light">
              <?php echo $this->translate('by');?> <?php echo $this->htmlLink($item->getOwner()->getHref(), $item->getOwner()->getTitle()) ?>     
            </div>
            <?php endif; ?>
            <div class="sesmusic_playlist_grid_stats sesmusic_list_stats sesbasic_text_light">
              <?php if (!empty($this->information) && engine_in_array('favouriteCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s favorite', '%s favorites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count)); ?>"><i class="fa fa-heart"></i><?php echo $item->favourite_count; ?></span>
              <?php endif; ?>
              <?php if (!empty($this->information) && engine_in_array('viewCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)); ?>"><i class="fa fa-eye"></i><?php echo $item->view_count; ?></span>
              <?php endif; ?>
              <?php $songCount = Engine_Api::_()->getDbtable('playlistsongs', 'sesmusic')->playlistSongsCount(array('playlist_id' => $item->playlist_id));  ?>
              <?php if (!empty($this->information) && engine_in_array('songCount', $this->information)): ?>
                <span title="<?php echo $this->translate(array('%s song', '%s song', $songCount), $this->locale()->toNumber($songCount)); ?>"><i class="fa fa-music"></i><?php echo $songCount; ?></span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php $songs = $item->getSongs(); ?>
        <?php if($songs && !empty($this->information) && engine_in_array('songsListShow', $this->information)):  ?>
        <div class="clear sesbasic_clearfix sesmusic_tracks_container sesbasic_custom_scroll">
          <ul class="clear sesmusic_tracks_list">
            
            <?php foreach( $songs as $song ):  ?>
             <?php $song = Engine_Api::_()->getItem('sesmusic_albumsong', $song->albumsong_id); ?>
              <li class="sesbasic_clearfix">
                <div class="sesmusic_tracks_list_photo">
                  <?php echo $this->htmlLink($song, $this->itemPhoto($song, 'thumb.icon') ) ?>
                  <?php $songTitle = preg_replace('/[^a-zA-Z0-9\']/', ' ', $song->getTitle()); ?>
                  <?php $songTitle = str_replace("'", '', $songTitle); ?>
                  <?php $path = Engine_Api::_()->sesmusic()->songImageURL($song); ?>
                  <?php if($song->track_id): ?>
                    
                    <?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
                    $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
                    <?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
                    <?php $URL = "http://api.soundcloud.com/tracks/$song->track_id/stream?consumer_key=$consumer_key"; ?>
                    <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-play-circle"></i></a>
                <?php else: ?>
                  <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-play-circle"></i></a>
                <?php endif; ?>
                  <?php else: ?>
                  <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-play-circle"></i></a>
                  <?php endif; ?>
                </div>
                <div class="sesmusic_tracks_list_name" title="<?php echo $song->getTitle() ?>">
                  <?php echo $this->htmlLink($song->getFilePath(), $this->htmlLink($song->getHref(), $song->getTitle()), array('class' => 'music_player_tracks_url', 'type' => 'audio', 'rel' => $song->song_id)); ?>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </li>
  </ul>
<?php endif; ?>
