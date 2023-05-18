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
<?php

//This is done to make these links more uniform with other viewscripts
$playlist = $this->playlist;
$songs = $playlist->getSongs();
?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?> 

<div class="sesmusic_artist_view_wrapper sesmusic_playlist_view clear">
  <div class="sesmusic_artist_view_top">
    <div class="sesmusic_artist_view_artwork">
        <?php echo $this->itemPhoto($playlist, 'thumb.profile'); ?>
    </div>
    <div class="sesmusic_artist_view_info">
      <div class="sesmusic_artist_view_title">
        <?php echo $playlist->getTitle() ?>
      </div>
      <p class="sesmusic_artist_view_stats sesbasic_text_light">
        <?php if(!empty($this->informationPlaylist) && engine_in_array('favouriteCountPl', $this->informationPlaylist)): ?>
          <?php echo $this->translate(array('%s favourite', '%s favourites', $this->playlist->favourite_count), $this->locale()->toNumber($this->playlist->favourite_count)) ?>
        <?php endif; ?>
        <?php if(!empty($this->informationPlaylist) && engine_in_array('viewCountPl', $this->informationPlaylist)): ?>
          &nbsp;|&nbsp;
          <?php echo $this->translate(array('%s view', '%s views', $this->playlist->view_count), $this->locale()->toNumber($this->playlist->view_count)) ?>
        <?php endif; ?>
      </p>
      <p class="sesmusic_artist_view_stats sesbasic_text_light">
        <?php if(!empty($this->informationPlaylist) && engine_in_array('postedByPl', $this->informationPlaylist)): ?>
          <?php echo $this->translate('Created %s by ', $this->timestamp($playlist->creation_date)); ?>
          <?php echo $this->htmlLink($playlist->getOwner(), $playlist->getOwner()->getTitle()); ?>
        <?php endif; ?>
      </p>
      <?php //if( empty($this->hideStats) ): ?>
      <div class="sesmusic_artist_view_options">
        <?php $viewer = Engine_Api::_()->user()->getViewer(); ?>
        <?php if($this->viewer_id): ?>
        <?php //if(!empty($this->informationPlaylist) && engine_in_array('sharePl', $this->informationPlaylist)): ?>
        <?php echo $this->htmlLink(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_playlist', 'id' => $this->playlist->getIdentity(), 'format' => 'smoothbox'), $this->translate("Share"), array('class' => 'smoothbox fa fa-share')); ?>
        <?php //endif; ?>
          <!--<a href="" class="fa fa-thumbs-up">Like</a>-->
         <?php //if(!empty($this->informationPlaylist) && engine_in_array('addFavouriteButtonPl', $this->informationPlaylist)): ?>
          <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_playlist", 'resource_id' => $playlist->getIdentity())); ?>
          <a class="fa fa-heart sesmusic_favourite" id="sesmusic_playlist_unfavourite_<?php echo $playlist->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $playlist->getIdentity(); ?>', 'sesmusic_playlist');" title="<?php echo $this->translate("Remove from Favorite") ?>"><?php echo $this->translate("Remove from Favorite") ?></a>
          <a class="fa fa-heart" id="sesmusic_playlist_favourite_<?php echo $playlist->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $playlist->getIdentity(); ?>', 'sesmusic_playlist');" title="<?php echo $this->translate("Add to Favorite") ?>"><?php echo $this->translate("Add to Favorite") ?></a>
          <input type="hidden" id="sesmusic_playlist_favouritehidden_<?php echo $playlist->getIdentity(); ?>" value='<?php echo $isFavourite ? $isFavourite : 0; ?>' /> 
          <?php //endif; ?>
        <?php endif; ?>
      </div>    
      <div class="sesmusic_playlist_options_dropdown">
      <a href="javascript:void(0);" class="links_dropdown"><i class="fa fa-ellipsis-v"></i></a>
      <div class="sesmusic_songs_playlist_options">
        <?php if($this->viewer_id): ?>
        <?php if(!empty($this->informationPlaylist) && engine_in_array('reportPl', $this->informationPlaylist)): ?>
        <?php echo $this->htmlLink(array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=> $this->playlist->getGuid(), 'format' => 'smoothbox' ), $this->translate("Report"), array('class' => 'smoothbox sesbasic_icon_report')); ?>  
        <?php endif; ?>
       
       <?php if ($playlist->getOwner()->isSelf($viewer) || $viewer->level_id == 1): ?>
        <?php if(!empty($this->informationPlaylist) && engine_in_array('editButton', $this->informationPlaylist)): ?>
        <?php  echo $this->htmlLink($playlist->getHref(array('route' => 'sesmusic_playlist_specific', 'action' => 'edit')), $this->translate('Edit Playlist'), array('class'=>'sesbasic_icon_edit')); ?>
        <?php endif; ?>
          <?php if(!empty($this->informationPlaylist) && engine_in_array('deleteButton', $this->informationPlaylist)): ?>
          <?php echo $this->htmlLink(array('route' => 'sesmusic_playlist_specific', 'module' => 'sesmusic', 'controller' => 'playlist', 'action' => 'delete', 'playlist_id' => $playlist->getIdentity(), 'slug' => $playlist->getSlug(), 'format' => 'smoothbox'), $this->translate('Delete Playlist'), array('class' => 'smoothbox sesbasic_icon_delete')); ?>
          <?php endif; ?>
          <?php endif; ?>
          <?php endif; ?>
      </div>
      </div>
    <?php //endif; ?>
    </div>
  </div>
  <div class="sesmusic_artist_view_des">
    <?php if(!empty($this->informationPlaylist) && engine_in_array('description', $this->informationPlaylist) && $playlist->description): ?>
      <p>
        <?php echo $this->viewMore(nl2br($playlist->description)); ?>
      </p>
    <?php endif; ?>
  </div>
  <ul class="clear sesmusic_artist_songslist_container playlist_<?php echo $this->playlist->getIdentity() ?> <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($song)) { ?> paid_content <?php } ?>">

    <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($song)) { ?>
     <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $song)); ?>
    <?php } ?>

    <?php if(engine_count($songs) > 0 && !empty($this->information)): ?>
    <?php foreach( $songs as $song ): ?>
      <?php if(!empty($song) ): ?>
        <?php $song = Engine_Api::_()->getItem('sesmusic_albumsong', $song->albumsong_id); ?>
        <?php $songAlbum = Engine_Api::_()->getItem('sesmusic_album', $song->album_id); ?>
        <li class="sesmusic_artist_songslist sesbasic_clearfix  <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($songAlbum)) { ?> paid_content <?php } ?>">
        <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($songAlbum)) { ?>
          <?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $songAlbum)); ?>
        <?php } ?>
          <div class="sesmusic_artist_songslist_photo">
          <div class="sesmusic_artist_songslist_photo_inner">
            <?php if($song->photo_id): ?>
              <?php echo $this->htmlLink($song->getHref(), $this->itemPhoto($song, 'thumb.profile'), array()); ?>
            <?php else: ?>
             <?php $albumItem = Engine_Api::_()->getItem('sesmusic_albums', $song->album_id); ?>
             <?php echo $this->htmlLink($song->getHref(), $this->itemPhoto($song, 'thumb.normal'), array()); ?>
            <?php endif; ?>
            <div class="sesmusic_artist_songslist_playbutton">
                <?php $songTitle = preg_replace('/[^a-zA-Z0-9\']/', ' ', $song->getTitle()); ?>
                  <?php $songTitle = str_replace("'", '', $songTitle); ?>
                  <?php $path = Engine_Api::_()->sesmusic()->songImageURL($song); ?>
	              <?php if($song->track_id): ?>
                  
	                <?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
	                $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
	                <?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
	                <?php $URL = "http://api.soundcloud.com/tracks/$song->track_id/stream?consumer_key=$consumer_key"; ?>
	                  <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                <?php else: ?>
                  <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                <?php endif; ?>
	              <?php else: ?>
                  <?php if($song->store_link): ?>
                    <?php $storeLink = !empty($song->store_link) ? (preg_match("#https?://#", $song->store_link) === 0) ? 'http://'.$song->store_link : $song->store_link : ''; ?>
                    <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '<?php echo $storeLink ?>', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                  <?php else: ?>
                    <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"><i class="fa fa-play-circle"></i></a>
                  <?php endif; ?>
	              <?php endif; ?>  
	            </div>
             <?php if($song->hot || $song->featured || $song->sponsored): ?>
              <div class="sesmusic_item_info_label">
                <?php if($song->hot && engine_in_array('hot', $this->information)): ?>
                  <span class="fa fa-star sesmusic_label_hot" title='<?php echo $this->translate("HOT"); ?>'></span>
                <?php endif; ?>
                <?php if($song->featured && engine_in_array('featured', $this->information)): ?>
                <span class="fa fa-star sesmusic_label_featured" title='<?php echo $this->translate("FEATURED"); ?>'></span>
                <?php endif; ?>
                <?php if($song->sponsored && engine_in_array('sponsored', $this->information)): ?>
                <span class="fa fa-star sesmusic_label_sponsored" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
                <?php endif; ?>
              </div>
           <?php endif; ?>
	         </div>
           </div>
	        <div class="sesmusic_artist_songslist_info">
	          <div class="sesmusic_artist_songslist_info_top sesbasic_clearfix">
	             
	            <div class="sesmusic_songslist_songdetail">
                
	              <div class="sesmusic_artist_songslist_songname">
                  <?php echo $this->htmlLink($song->getHref(), $song->getTitle(), array('class' => 'music_player_tracks_url', 'type' => 'audio', 'rel' => $song->song_id)); ?>
	              </div>
                <?php if(!empty($this->information) && engine_in_array('postedBy', $this->information)): ?>
                  <div class="sesmusic_artist_songslist_author sesbasic_text_light">
                    <?php $album = Engine_Api::_()->getItem('sesmusic_albums', $song->album_id); ?>
                    <?php echo $this->translate('by %s', $this->htmlLink($album->getOwner(), $album->getOwner()->getTitle())) ?><?php echo $this->translate(' on %s', $this->timestamp($song->creation_date)); ?><?php if($album->upload_param == 'album') { ?><?php echo $this->translate(' in %s', $this->htmlLink($album->getHref(), $album->getTitle())); ?><?php } ?>
                  </div>
                <?php endif; ?>


	              <?php if(!empty($this->information) && engine_in_array('artists', $this->information)): ?>
	                <div class="sesmusic_songslist_artist clear sesbasic_text_light">
	                  <?php if($song->artists):
	                   $artists = json_decode($song->artists); 
	                   if($artists): ?>
	                    <?php echo $this->translate("Artists:"); ?>
	                    <?php
	                         // if($artists):
	                          $artists_array = Engine_Api::_()->getDbTable('artists', 'sesmusic')->getArtists($artists); ?>
                            <?php $artist_name = ''; ?>
	                    <?php foreach($artists_array as $key => $artist):  ?>
	                        <?php $artist_name .= $this->htmlLink(array('module'=>'sesmusic', 'controller'=>'artist', 'action'=>'view', 'route'=>'default', 'artist_id' => $key), $artist) . ', '; ?>
                  <?php endforeach; ?> 
                  <?php $artist_name = trim($artist_name); $artist_name = rtrim($artist_name, ','); echo $artist_name; ?>
                  <?php endif; ?>
	                  <?php endif; ?>
	                </div>
	              <?php endif; ?>
                
                <div class="sesmusic_artist_songslist_songstats sesbasic_text_light">
	              <?php 
	               $information = '';   
	               if(!empty($this->information) && engine_in_array('playCount', $this->information))
	               $information .= '<span title="Plays"><i class="fa fa-play"></i>' .$song->play_count. '</span>';

	               if(!empty($this->information) && engine_in_array('downloadCount', $this->information))
	                 $information .= '<span title="Downloads"><i class="fa fa-download"></i>' .$song->download_count. '</span>';
	              if(!empty($this->information) && engine_in_array('favouriteCount', $this->information))
	                 $information .= '<span title="Favourites"><i class="fa fa-heart"></i>' .$song->favourite_count. '</span>';
	              if(!empty($this->information) && engine_in_array('likeCount', $this->information))
	                 $information .= '<span title="Likes"><i class="fa fa-thumbs-up"></i>' .$song->like_count. '</span>'; 
	              if(!empty($this->information) && engine_in_array('commentCount', $this->information))
	                 $information .= '<span title="Comments"><i class="fa fa-comment"></i>' .$song->comment_count. '</span>';
	             if(!empty($this->information) && engine_in_array('viewCount', $this->information))
	                 $information .= '<span title="Views"><i class="fa fa-eye"></i>' .$song->view_count. '</span>';
	               ?>
	                <?php echo $information ?>
	              </div>
	            </div>
	          </div>

	          <div class="sesmusic_artist_songslist_info_bottom">
            <?php if($song->category_id && engine_in_array('category', $this->information)): ?>
                  <div class="sesmusic_list_category">
                    <?php $catName = Engine_Api::_()->getDbTable('categories', 'sesmusic')->getColumnName(array('column_name' => 'category_name', 'category_id' => $song->category_id, 'param' => 'song')); ?>
                    <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?category_id='.urlencode($song->category_id) ; ?>"><?php echo $catName; ?></a>
                  </div>
                <?php endif; ?>
            <div class="sesmusic_artist_songslist_info_dropdown">
	            	<a class="links_dropdown" href="javascript:void(0);"><i class="fa fa-ellipsis-v"></i></a>
                <div class="sesmusic_artist_songslist_options">
                <div class="sesmusic_artist_songslis_social">
 
                <!--Social Share Button-->
                <?php //if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                  <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $song->getHref()); ?>
                  
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $song, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

                <?php //} ?>
                <!--Social Share Button-->
                
                <!--Like Button-->
                <?php $viewer = Engine_Api::_()->user()->getViewer();
                $viewer_id = $viewer->getIdentity();
                $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
                $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($song, $viewer); ?>
                <?php if ($canLike && !empty($viewer_id)): ?>
                  <a class="sesmusic_like_icon active" id="<?php echo $song->getType(); ?>_unlike_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isLike ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicLike('<?php echo $song->getIdentity(); ?>', '<?php echo $song->getType(); ?>');" title="<?php echo $this->translate("Unlike") ?>"><i class="fa fa-thumbs-up"></i></a>
                  <a class="sesmusic_like_icon" id="<?php echo $song->getType(); ?>_like_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isLike ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicLike('<?php echo $song->getIdentity(); ?>', '<?php echo $song->getType(); ?>');" title="<?php echo $this->translate("Like") ?>"><i class="fa fa-thumbs-up"></i></a>
                  <input type="hidden" id="<?php echo $song->getType(); ?>_likehidden_<?php echo $song->getIdentity(); ?>" value='<?php echo $isLike ? $isLike : 0; ?>' />
                <?php endif; ?>
                <!--Like Button-->
                
                <?php if( $this->viewer()->getIdentity()): ?>
                <!--<a href="" class="sesmusic_like_icon"><i class="fa fa-thumbs-up"></i></a>-->
                  <?php if($this->canAddFavouriteAlbumSong && !empty($this->information) && engine_in_array('addplaylist', $this->information)): ?>
                    <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $song->albumsong_id)); ?>
                    <a class="sesmusic_favourite sesmusic_favorite_icon active" id="sesmusic_albumsong_unfavourite_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $song->getIdentity(); ?>', 'sesmusic_albumsong');" title='<?php echo $this->translate("Remove from Favorites") ?>'><i class="fa fa-heart"></i></a>
                    <a class="sesmusic_favorite_icon" id="sesmusic_albumsong_favourite_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $song->getIdentity(); ?>', 'sesmusic_albumsong');" title='<?php echo $this->translate("Add to Favorite") ?>'><i class="fa fa-heart"></i></a>
                    <input type="hidden" id="sesmusic_albumsong_favouritehidden_<?php echo $song->getIdentity(); ?>" value='<?php echo $isFavourite ? $isFavourite : 0; ?>' />
                  <?php endif; ?> 
                <?php endif; ?>   
              </div>
	              <?php if( $this->viewer()->getIdentity()): ?>
	                <?php if($this->canAddPlaylistAlbumSong && !empty($this->information) && engine_in_array('addplaylist', $this->information)): ?>
	                  <a title="<?php echo $this->translate('Add to Playlist');?>" href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('action'=>'append','albumsong_id' => $song->albumsong_id, 'format' => 'smoothbox'), 'sesmusic_albumsong_specific' , true)); ?>'); return false;" class="sesbasic_icon_add"><?php echo $this->translate('Add to Playlist');?></a>
	                <?php endif; ?>
                  
	                <?php if($song->download && /**!$song->track_id && !$song->song_url && **/ $this->viewer()->getIdentity() &&$this->downloadAlbumSong  && !empty($this->information) && engine_in_array('downloadButton', $this->information)): ?>
	                  <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $song->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>                                       
                  <?php /** elseif($song->download && !empty($this->information) && engine_in_array('downloadButton', $this->information)): ?>
                    <?php $file = Engine_Api::_()->getItem('storage_file', $song->file_id); ?>
                    <?php if($file->mime_minor && $this->downloadAlbumSong): ?>
                    <?php $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid');
                    $downloadURL = 'http://api.soundcloud.com/tracks/' . $song->track_id . '/download?client_id=' . $consumer_key;  ?>
                    <a class='sesbasic_icon_download' href='<?php echo $downloadURL; ?>' target="_blank"><?php echo $this->translate("Download");  ?></a>
                    <?php endif; **/?>
                  <?php endif; ?>

	                <?php if(!empty($this->songlink) && engine_in_array('share', $this->songlink) && !empty($this->information) && engine_in_array('share', $this->information)): ?>
	                <?php echo $this->htmlLink(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $song->getIdentity(), 'format' => 'smoothbox'), $this->translate("Share"), array('class' => 'smoothbox sesbasic_icon_share')); ?>
	                <?php endif; ?>

	                <?php if(!empty($this->songlink) && engine_in_array('report', $this->songlink) && !empty($this->information) && engine_in_array('report', $this->information)): ?>
	                <?php echo $this->htmlLink(array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=> $song->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox sesbasic_icon_report')); ?>
	                <?php endif; ?>

                 
	              <?php endif; ?> 
	              
	              <?php $viewer = Engine_Api::_()->user()->getViewer();
								$addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'addstore_link'); ?>
								<?php if($addstore_link && $song->store_link && engine_in_array('storeLink', $this->information)): ?>
									<?php $storeLink = !empty($song->store_link) ? (preg_match("#https?://#", $song->store_link) === 0) ? 'http://'.$song->store_link : $song->store_link : ''; ?>
									<a href="<?php echo $storeLink ?>" target="_blank" class="fa fa-shopping-cart"><?php echo $this->translate("Purchase") ?></a>
                <?php elseif($viewer->getIdentity() && $song->store_link && $this->information && engine_in_array('storeLink', $this->information)):  ?>
                  <?php $storeLink = !empty($song->store_link) ? (preg_match("#https?://#", $song->store_link) === 0) ? 'http://'.$song->store_link : $song->store_link : ''; ?>
                  <a href="<?php echo $storeLink ?>" target="_blank" class="fa fa-shopping-cart"><?php echo $this->translate("Purchase") ?></a>
                <?php endif; ?>
								
                <?php $downloadPublic = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.download.publicuser', 0); ?>
                <?php if($downloadPublic && !$viewer->getIdentity()): ?>
                  <?php if($song->download && !$song->track_id && !$song->song_url): ?>
                    <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $song->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
                  <?php elseif($song->download): ?>
                    <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $song->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
                  <?php endif; ?>
                <?php endif; ?>
	            </div>
              </div>
            <?php if($this->showAlbumSongRating && !empty($this->information) && engine_in_array('ratingStars', $this->information)): ?>
              <div class="sesmusic_artist_songslist_rating" title="<?php echo $this->translate(array('%s rating', '%s ratings', $song->rating), $this->locale()->toNumber($song->rating)); ?>">
                <?php if( $song->rating > 0 ): ?>
                  <?php for( $x=1; $x<= $song->rating; $x++ ): ?>
                    <span class="sesbasic_rating_star_small fa fa-star"></span>
                  <?php endfor; ?>
                  <?php if( (round($song->rating) - $song->rating) > 0): ?>
                    <span class="sesbasic_rating_star_small fa fa-star-half"></span>
                  <?php endif; ?>
                <?php endif; ?>      
              </div>
            <?php endif; ?>
	            
	          </div>
	        </div>
	      </li>
      <?php endif; ?>
    <?php endforeach; ?>
   <?php else: ?>
      <li class="sesmusic_songslist">
        <div class="tip">
          <span>
            <?php echo $this->translate('There are no songs yet.') ?>
          </span>
        </div>
      </li>
    <?php endif; ?>
  </ul>
</div>
<script type="text/javascript">
  scriptJquery('.core_main_sesmusic').parent().addClass('active');
  
  //Play All Song work for playlist view page
  scriptJquery('.sesmusic_playall').click(function() {
    var playallcounter = 0;
    var playbutton = scriptJquery('.sesmusic_songslist_playbutton');
    if(playbutton.length > 0) {
      scriptJquery(playbutton).each(function() {
        var playsongButton = scriptJquery(this).find('a').attr('onclick');
        playsongButton = playsongButton.replace('play_music(', '');
        playsongButton = playsongButton.replace(/'/g, '');
        playsongButton = playsongButton.replace(');', '');
        playsongButton = playsongButton.split(',');
        
        if(!scriptJquery('.sesmusic_player_button_pause').length) {
          var playlistSong = true;
        } else {
          playlistSong = false;
        }
        play_music(playsongButton[0],playsongButton[1], playsongButton[2], playsongButton[3], playsongButton[4], playlistSong);
        playallcounter++;
      });
    }
  });
</script>
