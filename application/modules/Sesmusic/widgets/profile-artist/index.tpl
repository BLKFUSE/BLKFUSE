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
$artist = $this->artists;
?>
<?php if($this->showRating): ?>
  <script type="text/javascript">
    
    en4.core.runonce.add(function() {
      var pre_rate = '<?php echo $this->artists->rating;?>';
      
      <?php if($this->viewer_id == 0){ ?>
      rated = 0;
      <?php } elseif($this->allowShowRating == 1 && $this->allowRating == 0) { ?>
      var rated = 3;
      <?php } elseif($this->allowRateAgain == 0 && $this->rated) { ?>
      var rated = 1;
      <?php } elseif($this->canRate == 0 && $this->viewer_id != 0) { ?>
      var rated = 4;
      <?php } elseif(!$this->allowMine) { ?>
      var rated = 2;
      <?php } else { ?>
      var rated = '90';
      <?php } ?>
    
      var resource_id = '<?php echo $this->artists->artist_id;?>';
      var total_votes = '<?php echo $this->rating_count;?>';
      var viewer = '<?php echo $this->viewer_id;?>';
      new_text = '';

      var rating_over = window.rating_over = function(rating) {
        if( rated == 1 ) {
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('You already rated.');?>";
          return;
          //set_rating();
        }
        <?php if(!$this->canRate) { ?>
          else if(rated == 4){
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('You are not allowed to rate.');?>";
               return;
          }
        <?php } ?>
        <?php if(!$this->allowMine) { ?>
          else if(rated == 2){
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Rating on own album is not allowed.');?>";
               return;
          }
        <?php } ?>
        <?php if($this->allowShowRating == 1) { ?>
          else if(rated == 3){
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('You are not allowed to rate.');?>";
               return;
          }
        <?php } ?>
        else if( viewer == 0 ) {
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Please login to rate.');?>";
          return;
        } else {
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Click to rate.');?>";
          for(var x=1; x<=5; x++) {
            if(x <= rating) {
              scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big');
            } else {
              scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big_disabled');
            }
          }
        }
      }

      var rating_out = window.rating_out = function() {
        if (new_text != ''){
          document.getElementById('rating_text').innerHTML = new_text;
        }
        else{
          document.getElementById('rating_text').innerHTML = " <?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";        
        }
        if (pre_rate != 0){
          set_rating();
        }
        else {
          for(var x=1; x<=5; x++) {
            scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big_disabled');
          }
        }
      }

      var set_rating = window.set_rating = function() {
        var rating = pre_rate;
        if (new_text != ''){
          document.getElementById('rating_text').innerHTML = new_text;
        }
        else{
          document.getElementById('rating_text').innerHTML = "<?php echo $this->translate(array('%s rating', '%s ratings', $this->rating_count),$this->locale()->toNumber($this->rating_count)) ?>";
        }
        for(var x=1; x<=parseInt(rating); x++) {
          scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big').removeClass('rating_star_big_disabled');
        }

        for(var x=parseInt(rating)+1; x<=5; x++) {
          scriptJquery('#rate_'+x).addClass('fas fa-star rating_star_big_generic rating_star_big_disabled');
        }

        var remainder = Math.round(rating)-rating;
        if (remainder <= 0.5 && remainder !=0){
          var last = parseInt(rating)+1;
          scriptJquery('#rate_'+last).addClass('fas fa-star rating_star_big_generic rating_star_big_half');
        }
      }

      var rate = window.rate = function(rating) {
        document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Thanks for rating.');?>";
        <?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
               for(var x=1; x<=5; x++) {
                  scriptJquery('#rate_'+x).attr('onclick', '');
                }
            <?php } ?>

        (scriptJquery.ajax({
          'format': 'json',
          'url' : '<?php echo $this->url(array('module' => 'sesmusic', 'controller' => 'album', 'action' => 'rate'), 'default', true) ?>',
          'data' : {
            'format' : 'json',
            'rating' : rating,
            'resource_id': resource_id,
            'resource_type':'<?php echo $this->rating_type; ?>'
          },
          success : function(responseJSON)
          {
            <?php if($this->allowRateAgain == 0 && !$this->rated){ ?>
                rated = 1;
            <?php } ?>
            total_votes = responseJSON[0].total;
            var rating_sum = responseJSON[0].rating_sum;
            pre_rate = rating_sum / total_votes;
            set_rating();
            document.getElementById('rating_text').innerHTML = responseJSON[0].total+" ratings";
            new_text = responseJSON[0].total+" ratings";
          }
        }));
      }
      set_rating();
    });
  </script>
<?php endif; ?>

<div class="sesmusic_artist_view_wrapper clear">
  <div class="sesmusic_artist_view_top">
    <div class="sesmusic_artist_view_artwork">
      <?php if($artist->artist_photo): ?>
      <?php $img_path = Engine_Api::_()->storage()->get($artist->artist_photo, '')->getPhotoUrl();
      $path = $img_path; 
      ?>
      <img src="<?php echo $path ?>">
      <?php else: ?>
       <img src="<?php //echo $path ?>">
      <?php endif; ?>
      <?php if($this->artists->featured || $this->artists->sponsored): ?>
        <div class="sesmusic_item_info_label">
          <?php if($this->artists->featured && engine_in_array('featuredArtist', $this->informationArtist)): ?>
          <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
          <?php endif; ?>
          <?php if($this->artists->sponsored && engine_in_array('sponsoredArtist', $this->informationArtist)): ?>
          <span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="sesmusic_artist_view_info">
      <div class="sesmusic_artist_view_title">
        <?php echo $artist->name ?>
      </div>
      <?php if(!empty($this->information)): ?>
      <?php if(engine_in_array('favouriteCountAr', $this->informationArtist) || engine_in_array('ratingCountAr', $this->informationArtist)): ?>
        <p class="sesmusic_artist_view_stats sesbasic_text_light">
          <?php if(engine_in_array('favouriteCountAr', $this->informationArtist)): ?>
           <?php echo $this->translate(array('%s favorite', '%s favorites', $this->artists->favourite_count), $this->locale()->toNumber($this->artists->favourite_count)) ?>
          <?php endif; ?>
          <?php if(engine_in_array('ratingCountAr', $this->informationArtist) && $this->showRating && engine_in_array('favouriteCountAr', $this->informationArtist)): ?>
          &nbsp;|&nbsp;
          <?php endif; ?>
          <?php if(engine_in_array('ratingCountAr', $this->informationArtist) && $this->showRating): ?>
            <?php echo $this->translate(array('%s rating', '%s ratings', $this->artists->rating), $this->locale()->toNumber($this->artists->rating)); ?>
          <?php endif; ?>
        </p>
      <?php endif; ?>

      
      <?php if(engine_in_array('ratingStarsAr', $this->informationArtist) && $this->showRating):  ?>
        <div id="album_rating" class="sesmusic_rating_star" onmouseout="rating_out();">
          <span id="rate_1" class="fas fa-star rating_star_big_generic" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating ):?>onclick="rate(1);"<?php  endif; ?> onmouseover="rating_over(1);"></span>
          <span id="rate_2" class="fas fa-star rating_star_big_generic" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onmouseover="rating_over(2);"></span>
          <span id="rate_3" class="fas fa-star rating_star_big_generic" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onmouseover="rating_over(3);"></span>
          <span id="rate_4" class="fas fa-star rating_star_big_generic" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onmouseover="rating_over(4);"></span>
          <span id="rate_5" class="fas fa-star rating_star_big_generic" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onmouseover="rating_over(5);"></span>
          <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
        </div>
      <?php endif; ?>

      <div class="sesmusic_artist_view_options">
      <!--<a href="" class="fa fa-thumbs-up sesmusic_like">Like</a>
      <a href="" class="fas fa-share-alt sesmusic_like">Share</a>-->
         <?php if (engine_in_array('addFavouriteButtonAr', $this->informationArtist) && !empty($this->viewer_id)): ?>
         <?php if($this->artistlink && engine_in_array('favourite', $this->artistlink)): ?>
           <a class="sesbasic_icon_favourite sesmusic_favourite" id="sesmusic_artist_unfavourite_<?php echo $this->artists->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->artists->getIdentity(); ?>', 'sesmusic_artist');"><?php echo $this->translate("Remove from Favorite") ?></a>
            <a class="sesbasic_icon_favourite" id="sesmusic_artist_favourite_<?php echo $this->artists->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->artists->getIdentity(); ?>', 'sesmusic_artist');"><?php echo $this->translate("Add to Favorite") ?></a>
          <input type="hidden" id="sesmusic_artist_favouritehidden_<?php echo $this->artists->getIdentity(); ?>" value='<?php echo $this->isFavourite ? $this->isFavourite : 0; ?>' />
          <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
  
        <?php if(engine_in_array('description', $this->informationArtist)): ?>
        	<div class="sesmusic_artist_view_des">
          	<p>
          		<?php echo $this->viewMore($this->artists->overview) ?>
        		</p>
        </div>
      <?php endif; ?>
  <ul class="clear sesmusic_artist_songslist_container playlist_<?php echo $artist->getIdentity() ?> ">
    <?php if(engine_count($this->artistSongs) > 0 && !empty($this->information)): ?>
    <?php foreach( $this->artistSongs as $song ):  ?>
      <?php if( !empty($song) ): ?>
				<?php $songAlbum = Engine_Api::_()->getItem('sesmusic_album', $song->album_id); ?>
        <li class="sesmusic_artist_songslist sesbasic_clearfix <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($songAlbum)) { ?> paid_content <?php } ?>">
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
             <?php if($song->hot || $song->featured || $song->sponsored): ?>
              <div class="sesmusic_item_info_label">
                <?php if($song->hot && engine_in_array('hot', $this->information)): ?>
                  <span class="sesmusic_label_hot fa fa-star" title='<?php echo $this->translate("HOT"); ?>'></span>
                <?php endif; ?>
                <?php if($song->featured && engine_in_array('featured', $this->information)): ?>
                <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
                <?php endif; ?>
                <?php if($song->sponsored && engine_in_array('sponsored', $this->information)): ?>
                <span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
                <?php endif; ?>
              </div>
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
           </div>
           </div>
          <div class="sesmusic_artist_songslist_info">
            <div class="sesmusic_artist_songslist_info_top sesbasic_clearfix">
               
              <div class="sesmusic-artist_songslist_songdetail">
                <div class="sesmusic_artist_songslist_songname">
                  <?php echo $this->htmlLink($song->getHref(), $song->getTitle(), array('class' => 'music_player_tracks_url', 'type' => 'audio', 'rel' => $song->song_id)); ?>
                </div>
                
                <?php if(engine_in_array('postedBy', $this->information)): ?>
                  <div class="sesmusic_artist_songslist_author sesbasic_text_light">
                    <?php $album = Engine_Api::_()->getItem('sesmusic_albums', $song->album_id); ?>
                    <?php echo $this->translate('by %s', $this->htmlLink($album->getOwner(), $album->getOwner()->getTitle())) ?><?php echo $this->translate(' on %s', $this->timestamp($song->creation_date)); ?><?php if($album->upload_param == 'album') { ?><?php echo $this->translate(' in %s', $this->htmlLink($album->getHref(), $album->getTitle())); ?><?php } ?>
                  </div>
                <?php endif; ?>


                <?php if(!empty($this->information) && engine_in_array('artists', $this->information)): ?>
                  <div class="sesmusic_artist_songslist_artist clear sesbasic_text_light">
                    <?php if($song->artists):
                     $artists = json_decode($song->artists); 
                     if($artists): ?>
                      <?php echo $this->translate("Artists:"); ?>
                      <?php $artists_array = Engine_Api::_()->getDbTable('artists', 'sesmusic')->getArtists($artists); ?>
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
                    if(engine_in_array('playCount', $this->information))
                    $information .= '<span title="Play"><i class="fa fa-play"></i>' .$song->play_count. '</span>';
                    if(engine_in_array('downloadCount', $this->information))
                    $information .= '<span title="Download"><i class="fa fa-download"></i>' .$song->download_count. '</span>';
                    if(engine_in_array('favouriteCount', $this->information))
                    $information .= '<span title="Favourite"><i class="fa fa-heart"></i>' .$song->favourite_count. '</span>';
                    if(engine_in_array('likeCount', $this->information))
                    $information .= '<span title="Like"><i class="fa fa-thumbs-up"></i>' .$song->like_count. '</span>'; 
                    if(engine_in_array('commentCount', $this->information))
                    $information .= '<span title="Comment"><i class="fa fa-comment"></i>' .$song->comment_count. '</span>';
                    if(engine_in_array('viewCount', $this->information))
                    $information .= '<span title="View"><i class="fa fa-eye"></i>' .$song->view_count. '</span>';
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
            <div class="sesmusic_artist_songslist_info_dropdown floatR">
            <a class="links_dropdown" href="javascript:void(0);"><i class="fa fa-ellipsis-v"></i></a>
              <div class="sesmusic_artist_songslist_options">
              <div class="sesmusic_artist_songslis_social">
                <!--Social Share Button-->
                <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
                  <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $song->getHref()); ?>
                  
                  <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $song, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

                <?php } ?>
                <!--Social Share Button-->
                <!--Like Button-->
                <?php $viewer = Engine_Api::_()->user()->getViewer();
                $viewer_id = $viewer->getIdentity();
                $canLike = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'comment');
                $isLike = Engine_Api::_()->getDbTable('likes', 'core')->isLike($song, $viewer); ?>
                <?php if ($canLike && !empty($viewer_id) && engine_in_array('addLikeButton', $this->information)): ?>
                  <a class="sesmusic_like_icon active" id="<?php echo $song->getType(); ?>_unlike_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isLike ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicLike('<?php echo $song->getIdentity(); ?>', '<?php echo $song->getType(); ?>');" title="<?php echo $this->translate("Unlike") ?>"><i class="fa fa-thumbs-up"></i></a>
                  <a class="sesmusic_like_icon" id="<?php echo $song->getType(); ?>_like_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isLike ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicLike('<?php echo $song->getIdentity(); ?>', '<?php echo $song->getType(); ?>');" title="<?php echo $this->translate("Like") ?>"><i class="fa fa-thumbs-up"></i></a>
                  <input type="hidden" id="<?php echo $song->getType(); ?>_likehidden_<?php echo $song->getIdentity(); ?>" value='<?php echo $isLike ? $isLike : 0; ?>' />
                <?php endif; ?>
                <!--Like Button-->
                
                
                <?php if( $this->viewer()->getIdentity()): ?>
                	<!--<a href="" class="sesmusic_like_icon"><i class="fa fa-thumbs-up"></i></a>-->
                	<?php if(engine_in_array('addFavouriteButton', $this->information)): ?>
                    <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_albumsong", 'resource_id' => $song->albumsong_id)); ?>
                    <a class="sesmusic_favourite sesmusic_favorite_icon active" id="sesmusic_albumsong_unfavourite_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $song->getIdentity(); ?>', 'sesmusic_albumsong');" title='<?php echo $this->translate("Remove from Favorites") ?>'><i class="fa fa-heart"></i></a>
                    <a class="sesmusic_favorite_icon" id="sesmusic_albumsong_favourite_<?php echo $song->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $song->getIdentity(); ?>', 'sesmusic_albumsong');" title='<?php echo $this->translate("Add to Favorite") ?>'><i class="fa fa-heart"></i></a>
                    <input type="hidden" id="sesmusic_albumsong_favouritehidden_<?php echo $song->getIdentity(); ?>" value='<?php echo $isFavourite ? $isFavourite : 0; ?>' />
                	<?php endif; ?> 
                <?php endif; ?>  
              </div>
                <?php if( $this->viewer()->getIdentity()): ?>
                  <?php if($this->canAddPlaylistAlbumSong && engine_in_array('addplaylist', $this->information)): ?>
                    <a title="<?php echo $this->translate('Add to Playlist');?>" href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('action'=>'append','albumsong_id' => $song->albumsong_id, 'format' => 'smoothbox'), 'sesmusic_albumsong_specific' , true)); ?>'); return false;" class="sesbasic_icon_add"><?php echo $this->translate('Add to Playlist');?></a>
                  <?php endif; ?>


                  <?php if($song->download && !$song->track_id && !$song->song_url && $this->downloadAlbumSong  && engine_in_array('downloadButton', $this->information)): ?>
                    <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $song->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>                   
                  <?php elseif($song->download && engine_in_array('downloadButton', $this->information)): ?>
                    <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $song->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
                  <?php endif; ?>

                  <?php if(!empty($this->songlink) && engine_in_array('share', $this->songlink) && engine_in_array('share', $this->information)): ?>
                    <?php echo $this->htmlLink(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $song->getIdentity(), 'format' => 'smoothbox'), $this->translate("Share"), array('class' => 'smoothbox sesbasic_icon_share')); ?>
                  <?php endif; ?>

                  <?php if(!empty($this->songlink) && engine_in_array('report', $this->songlink)   && engine_in_array('report', $this->information)): ?>
                    <?php echo $this->htmlLink(array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=> $song->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox sesbasic_icon_report')); ?>
                  <?php endif; ?>

                
                <?php endif; ?>   
                
                <?php $viewer = Engine_Api::_()->user()->getViewer();
								$addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'addstore_link'); ?>
								<?php if($addstore_link && $song->store_link && engine_in_array('storeLink', $this->information)): ?>
									<?php $storeLink = !empty($song->store_link) ? (preg_match("#https?://#", $song->store_link) === 0) ? 'http://'.$song->store_link : $song->store_link : ''; ?>
									<a href="<?php echo $storeLink ?>" target="_blank" class="fa fa-shopping-cart"><?php echo $this->translate("Purchase") ?></a>
                <?php elseif(!$viewer->getIdentity() && $song->store_link && $this->information && engine_in_array('storeLink', $this->information)):  ?>
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
             <?php if($this->showAlbumSongRating && engine_in_array('ratingStars', $this->information)): ?>
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
<script type="text/javascript">
  scriptJquery('.core_main_sesmusic').parent().addClass('active');
</script>
</div>
