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

<?php $album = $this->album; 
$albumsong = $this->albumsong;
?>
<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($album)) { ?>
	<div id="album_content" class="paid_content">
		<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $album)); ?>
		<div class="epaidcontent_lock_img"><img src="application/modules/Epaidcontent/externals/images/paidcontent.png" alt="" /></div>
	</div>
<?php } else { ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/styles.css'); ?>
<?php if($this->information && engine_in_array('addFavouriteButton', $this->information)): ?>
  <?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<?php endif; ?>

<?php
if(isset($this->docActive)) {

	$imageURL = $albumsong->getPhotoUrl();
	if(strpos($albumsong->getPhotoUrl(),'http') === false)
    $imageURL = ((!empty($_SERVER["HTTPS"]) && strtolower($_SERVER["HTTPS"] == 'on')) ? "https://" : "http://"). $_SERVER['HTTP_HOST'].$albumsong->getPhotoUrl();
    
  $this->doctype('XHTML1_RDFA');
  $this->headMeta()->setProperty('og:title', strip_tags($albumsong->getTitle()));
  $this->headMeta()->setProperty('og:description', strip_tags($albumsong->getDescription()));
  $this->headMeta()->setProperty('og:image',$imageURL);
  
  $imageHeightWidthData = getimagesize($imageURL);
  $width = isset($imageHeightWidthData[0]) ? $imageHeightWidthData[0] : '300';
  $height = isset($imageHeightWidthData[1]) ? $imageHeightWidthData[1] : '200';
  
  $this->headMeta()->setProperty('og:image:width',$width);
  $this->headMeta()->setProperty('og:image:height',$height);
  $this->headMeta()->setProperty('twitter:title', strip_tags($albumsong->getTitle()));
  $this->headMeta()->setProperty('twitter:description', strip_tags($albumsong->getDescription()));
}
?>
<?php if($this->showRating): ?>
  <script type="text/javascript">
    
    en4.core.runonce.add(function() {
      var pre_rate = '<?php echo $this->albumsong->rating;?>';
      
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
      var resource_id = '<?php echo $this->albumsong->albumsong_id;?>';
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
               document.getElementById('rating_text').innerHTML = "<?php echo $this->translate('Rating on own song is not allowed.');?>";
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
    
    //Song play Automatically when come on Song View Page
//     window.addEvent('load',function(){
//       setTimeout(function() {
//         scriptJquery('#song_play_music').trigger('click');
//       }, 300);
//     });
  </script>
<?php endif; ?>

<?php if($this->songCover): ?>
<?php if(!empty($this->albumsong->song_cover) && $this->albumsong->song_cover): ?>
  <?php $storage = Engine_Api::_()->storage()->get($this->albumsong->song_cover, '');
  if($storage) {
			$storage = $storage->getPhotoUrl(); 
			$photoUrl = $storage;
  } else {
		$photoUrl = $this->baseUrl() . '/application/modules/Sesmusic/externals/images/banner/cover.jpg';
  }
  ?>
<?php else: ?>
  <?php if($this->songCoverPhoto): ?>
  <?php 
  $photoUrl = Engine_Api::_()->sesmusic()->getFileUrl($this->songCoverPhoto);
  $songCoverPhoto = "<img src='$photoUrl' height='" . $this->height. "px;' alt='' align='left' />";
  ?>
  <?php else: ?>
  <?php 
  $photoUrl = $this->baseUrl() . '/application/modules/Sesmusic/externals/images/banner/cover.jpg';
  $songCoverPhoto = "<img src='$photoUrl' height='" . $this->height. "px;' alt='' align='left' />";
  ?>
<?php endif; ?>
<?php endif; ?>
  <div class="sesmusic_cover sesbasic_bxs sesbasic_clearfix <?php if($this->information && !engine_in_array('photo', $this->information)): ?>manage_cover_profile_photo<?php endif; ?> <?php if($this->information && !engine_in_array('photo', $this->information)): ?>manage_cover_profile_photo<?php endif; ?>">
    <div class="sesmusic_cover_inner" style="background-image:url(<?php echo $photoUrl ?>); height:<?php echo $this->height; ?>px;">
      <div class="sesmusic_cover_inner_content_main">
        <?php if($this->information && engine_in_array('photo', $this->information)): ?>
          <div class="sesmusic_cover_music_artwork" style="height:<?php echo $this->mainPhotoHeight; ?>px;width:<?php echo $this->mainPhotowidth; ?>px;">
            <?php if($this->albumsong->photo_id): ?>
              <?php $img_path = Engine_Api::_()->storage()->get($this->albumsong->photo_id, '');
              if($img_path) {
              $img_path = $img_path->getPhotoUrl();
              ?>
              <span style="background-image:url(<?php echo $img_path ?>)"></span>
            <?php } ?>
            <?php elseif(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.songdefaultphoto')): ?>
              <?php $defaultPhoto = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.songdefaultphoto');
              $path = Engine_Api::_()->sesmusic()->getFileUrl($defaultPhoto);
              ?>
              <span style="background-image:url(<?php echo $path ?>)"></span>
            <?php else: ?>
              <?php $path = $this->baseUrl() . '/application/modules/Sesmusic/externals/images/nophoto_albumsong_thumb_main.png';  ?>
              <span style="background-image:url(<?php echo $path ?>)"></span>
            <?php endif; ?>
          <?php if($this->albumsong->featured || $this->albumsong->sponsored || $this->albumsong->hot ): ?>
            <div class="sesmusic_item_info_label">
              <?php if(engine_in_array('hot', $this->information)): ?>
                <span class="sesmusic_label_hot fa fa-star" title='<?php echo $this->translate("HOT"); ?>'></span>
              <?php endif; ?>
              <?php if(engine_in_array('featured', $this->information)): ?>
                <span class="sesmusic_label_featured fa fa-star" title='<?php echo $this->translate("FEATURED"); ?>'></span>
              <?php endif; ?>
              <?php if(engine_in_array('sponsored', $this->information)): ?>
               <span class="sesmusic_label_sponsored fa fa-star" title='<?php echo $this->translate("SPONSORED"); ?>'></span>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="sesmusic_item_info_play_btn">
         		<?php if($this->information && engine_in_array('playButton', $this->information)): ?>
            <?php $songTitle = preg_replace('/[^a-zA-Z0-9\']/', ' ', $this->albumsong->getTitle()); ?>
            <?php $songTitle = str_replace("'", '', $songTitle); ?>
            <?php $path = Engine_Api::_()->sesmusic()->songImageURL($this->albumsong); ?>
            <?php if($this->albumsong->track_id): ?>
              <?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
              $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
              <?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
                <?php $track_id = $this->albumsong->track_id;
                $URL = "http://api.soundcloud.com/tracks/$track_id/stream?consumer_key=$consumer_key";  ?>
                <a id="song_play_music" class="fa fa-play-circle" href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"></a>
              <?php else: ?>
               <a  id="song_play_music" class="fa fa-play-circle"  href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $this->albumsong->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"></a>
              <?php endif; ?>
            <?php else: ?>
              <?php if($this->albumsong->store_link): ?>
                <?php $storeLink = !empty($this->albumsong->store_link) ? (preg_match("#https?://#", $this->albumsong->store_link) === 0) ? 'http://'.$this->albumsong->store_link : $this->albumsong->store_link : ''; ?>
                <a  id="song_play_music" class="fa fa-play-circle"  href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $this->albumsong->getFilePath(); ?>', '<?php echo $songTitle; ?>', '<?php echo $storeLink ?>', '<?php echo $path; ?>');"></a>
              <?php else: ?>
                <a  id="song_play_music" class="fa fa-play-circle"  href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $this->albumsong->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');"></a>
              <?php endif; ?>
            <?php endif; ?>
          <?php endif; ?>
          </div>
        </div>
      	<?php endif; ?>
        <div class="sesmusic_cover_content">
          <div class="sesmusic_cover_title">
            <?php echo $this->albumsong->getTitle() ?>
          </div>
           <?php if($this->showRating == 1 && !empty($this->information) && engine_in_array('ratingStars', $this->information)):  ?>
            <div id="album_rating" class="sesmusic_cover_rating" onmouseout="rating_out();">
              <span id="rate_1" class="fas fa-star rating_star_big_generic" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating ):?>onclick="rate(1);"<?php  endif; ?> onmouseover="rating_over(1);"></span>
              <span id="rate_2" class="fas fa-star rating_star_big_generic" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onmouseover="rating_over(2);"></span>
              <span id="rate_3" class="fas fa-star rating_star_big_generic" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onmouseover="rating_over(3);"></span>
              <span id="rate_4" class="fas fa-star rating_star_big_generic" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onmouseover="rating_over(4);"></span>
              <span id="rate_5" class="fas fa-star rating_star_big_generic" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onmouseover="rating_over(5);"></span>
              <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
            </div>
          <?php endif; ?>
        </div>
        <div class="sesmusic_cover_options">
            <!--Social Share Button-->
            <?php if($this->information && engine_in_array('socialSharing', $this->information)) { ?>
              <?php $urlencode = urlencode(((!empty($_SERVER["HTTPS"]) &&  strtolower($_SERVER["HTTPS"]) == 'on') ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $albumsong->getHref()); ?>
              
              <?php  echo $this->partial('_socialShareIcons.tpl','sesbasic',array('resource' => $albumsong, 'socialshare_enable_plusicon' => $this->socialshare_enable_plusicon, 'socialshare_icon_limit' => $this->socialshare_icon_limit)); ?>

            <?php } ?>
            <!--Social Share Button-->
            
            <!--Like Button-->
            <?php if ($this->canLike && !empty($this->viewer_id) && $this->information && engine_in_array('addLikeButton', $this->information)): ?>
              <a class="fa fa-thumbs-up sesmusic_like" id="<?php echo $albumsong->getType(); ?>_unlike_<?php echo $albumsong->getIdentity(); ?>" style ='display:<?php echo $this->isLike ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicLike('<?php echo $albumsong->getIdentity(); ?>', '<?php echo $albumsong->getType(); ?>');" title="<?php echo $this->translate("Unlike") ?>"></a>
              <a class="fa fa-thumbs-up" id="<?php echo $albumsong->getType(); ?>_like_<?php echo $albumsong->getIdentity(); ?>" style ='display:<?php echo $this->isLike ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicLike('<?php echo $albumsong->getIdentity(); ?>', '<?php echo $albumsong->getType(); ?>');" title="<?php echo $this->translate("Like") ?>"></a>
              <input type="hidden" id="<?php echo $albumsong->getType(); ?>_likehidden_<?php echo $albumsong->getIdentity(); ?>" value='<?php echo $this->isLike ? $this->isLike : 0; ?>' />
            <?php endif; ?>
            <!--Like Button-->

            <?php if ($this->addfavouriteAlbumSong && !empty($this->viewer_id) && $this->information && engine_in_array('addFavouriteButton', $this->information)): ?>
            <a class="fa fa-heart sesmusic_favourite" id="sesmusic_albumsong_unfavourite_<?php echo $this->albumsong->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->albumsong->getIdentity(); ?>', 'sesmusic_albumsong');" title="<?php echo $this->translate('Remove from Favorites') ?>"></a>
            <a class="fa fa-heart" id="sesmusic_albumsong_favourite_<?php echo $this->albumsong->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->albumsong->getIdentity(); ?>', 'sesmusic_albumsong');" title="<?php echo $this->translate('Add to Favorite') ?>"></a>
            <input type="hidden" id="sesmusic_albumsong_favouritehidden_<?php echo $this->albumsong->getIdentity(); ?>" value='<?php echo $this->isFavourite ? $this->isFavourite : 0; ?>' />
          <?php endif; ?>
          <?php if($this->canAddPlaylistAlbumSong && $this->viewer()->getIdentity() && $this->information && engine_in_array('addplaylist', $this->information)): ?>
            <?php if($this->information && engine_in_array('addplaylist', $this->information)): ?>
              <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'append', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate(''), array('class' => 'smoothbox fa fa-plus', 'title' => $this->translate('Add to Playlist'))); ?>
            <?php endif; ?>
          <?php endif; ?>
  
          <?php if($this->viewer()->getIdentity() && $this->songlink && engine_in_array('share', $this->songlink) && $this->information && engine_in_array('share', $this->information)): ?>
            <?php echo $this->htmlLink(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $this->albumsong->getIdentity(), 'format' => 'smoothbox'), $this->translate(""), array('class' => 'smoothbox fas fa-share-alt', 'title' => $this->translate("Share"))); ?>
          <?php endif; ?>
          <?php if($this->viewer()->getIdentity()) { ?>
            <div>
              <a href="javascript:void(0);"><i class="fa fa-ellipsis-v"></i></a>
              <div class="sesmusic_cover_content_option_dropdown">
              <?php //if($this->album->owner_id == $this->viewer_id): ?>
                <?php  if ($this->canEditSong && $this->information && engine_in_array('editButton', $this->information))
                echo $this->htmlLink($album->getHref(array('route' => 'sesmusic_albumsong_specific', 'action' => 'edit', 'albumsong_id' => $this->albumsong->albumsong_id)), $this->translate('Edit Song'), array('class'=>'sesbasic_icon_edit', 'title' => $this->translate('Edit Song'))); ?> 
                <?php if($this->canDeleteSong && $this->information && engine_in_array('deleteButton', $this->information))
                echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'module' => 'sesmusic', 'controller' => 'song', 'action' => 'delete', 'albumsong_id' => $this->albumsong->albumsong_id, 'format' => 'smoothbox'), $this->translate('Delete Song'), array('class' => 'smoothbox sesbasic_icon_delete', 'title' => $this->translate('Delete Song'))); ?>
              <?php //endif; ?>
              <?php if($this->viewer()->getIdentity() && $this->songlink && engine_in_array('report', $this->songlink) && $this->information && engine_in_array('report', $this->information)): ?>
                <?php echo $this->htmlLink(array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=> $this->albumsong->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox sesbasic_icon_report', 'title' => $this->translate("Report"))); ?>
              <?php endif; ?>
            <?php if($this->albumsong->download /*&& !$this->albumsong->track_id && !$this->albumsong->song_url*/ && $this->downloadAlbumSong && $this->viewer()->getIdentity() && $this->information && engine_in_array('downloadButton', $this->information)): ?>
                <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate("Download"), array('class' => 'sesbasic_icon_download', 'title' => $this->translate("Download"))); ?>                             
              <?php // elseif($this->albumsong->download && $this->viewer()->getIdentity() && $this->information && engine_in_array('downloadButton', $this->information)): ?>
                <?php // $file = Engine_Api::_()->getItem('storage_file', $this->albumsong->file_id); ?>
                <?php // if($file->mime_minor && $this->downloadAlbumSong): ?>
                <?php // $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid');
                //$downloadURL = 'http://api.soundcloud.com/tracks/' . $this->albumsong->track_id . '/download?client_id=' . $consumer_key;  ?>
                <!--<a class='sesbasic_icon_download' href='<?php echo $downloadURL; ?>' target="_blank"></a>-->
                <?php // endif; ?>
              <?php endif; ?>       
              <?php if($this->addstore_link && $this->albumsong->store_link && engine_in_array('storeLink', $this->information)): ?>
                <?php $storeLink = !empty($this->albumsong->store_link) ? (preg_match("#https?://#", $this->albumsong->store_link) === 0) ? 'http://'.$this->albumsong->store_link : $this->albumsong->store_link : ''; ?>
                <a href="<?php echo $storeLink ?>" target="_blank" class="sesbasic_icon_cart"></a>
              <?php elseif(empty($this->viewer_id) && $this->albumsong->store_link && engine_in_array('storeLink', $this->information)): ?>
                <?php $storeLink = !empty($this->albumsong->store_link) ? (preg_match("#https?://#", $this->albumsong->store_link) === 0) ? 'http://'.$this->albumsong->store_link : $this->albumsong->store_link : ''; ?>
                <a href="<?php echo $storeLink ?>" target="_blank" class="sesbasic_icon_cart"></a>
              <?php endif; ?>
              <?php if($this->viewer()->getIdentity() && $this->albumsong->lyrics && $this->information && engine_in_array('printButton', $this->information)): ?>
                <?php echo $this->htmlLink(array('action' => 'print', 'route' => 'sesmusic_albumsong_specific', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate('Print'), array('class' => 'sesbasic_icon_print', 'title' => $this->translate('Print'))); ?>         
              <?php endif; ?>
              </div>
            </div>
          <?php } else { ?>
            <?php $downloadPublic = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.download.publicuser', 0); ?>
            <?php if($this->albumsong->download && !$this->albumsong->track_id && !$this->albumsong->song_url && empty($this->viewer()->getIdentity()) && $downloadPublic && $this->information && engine_in_array('downloadButton', $this->information)): ?>
              <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate(""), array('class' => 'fa fa-download', 'title' => $this->translate("Download"))); ?>                             
            <?php elseif($this->albumsong->download && empty($this->viewer()->getIdentity()) && $downloadPublic && $this->information && engine_in_array('downloadButton', $this->information)): ?>
              <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate(""), array('class' => 'fa fa-download', 'title' => $this->translate("Download"))); ?>
            <?php endif; ?> 
          <?php } ?>
        </div>
			</div>
    </div>
    <div class="sesmusic_cover_btm sesbasic_clearfix">
      <p class="sesmusic_cover_btm_stat">
        <?php if($this->information && engine_in_array('postedBy', $this->information)): ?>
          <?php echo $this->translate('Album By %s', $this->htmlLink($album->getOwner(), $album->getOwner()->getTitle())) ?><?php if($album->upload_param == 'album') { ?><?php echo $this->translate(' in %s', $this->htmlLink($album->getHref(), $album->getTitle())) ?><?php } ?>
        <?php endif ;?>
        <?php if($this->information && engine_in_array('creationDate', $this->information)): ?>|
          <?php echo $this->translate('Released %s', $album->creation_date); ?>
        <?php endif ;?>
      </p>
      <?php if(!empty($this->information) && engine_in_array('category', $this->information) && $this->albumsong->category_id) :?>
        <p class="sesmusic_cover_btm_stat">
          <?php $catName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->albumsong->category_id, 'param' => 'song')); ?>
          <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?category_id='.urlencode($this->albumsong->category_id) ; ?>"><?php echo $catName; ?></a>
          <?php if($this->albumsong->subcat_id): ?>
          <?php $subcatName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->albumsong->subcat_id, 'param' => 'song')); ?>
          &nbsp;&raquo;
          <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?subcat_id='.urlencode($this->albumsong->subcat_id) ; ?>"><?php echo $subcatName; ?></a>
          <?php endif; ?>
          <?php if($this->albumsong->subsubcat_id): ?>
          <?php $subsubcatName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->albumsong->subsubcat_id, 'param' => 'song')); ?>
          &nbsp;&raquo;
          <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?subsubcat_id='.urlencode($this->albumsong->subsubcat_id) ; ?>"><?php echo $subsubcatName; ?></a>
          <?php endif; ?>
        </p>
      <?php endif; ?>
			<!-- Start Profile fields -->
			<?php if(!empty($this->sesbasicFieldValueLoop($this->albumsong))):?>
			<div class="sesmusic_profile_info_row" id="sesmusic_custom_fields_val">
				<div class="sesmusic_profile_info_head"><?php echo $this->translate("Other Info"); ?></div>
				<div class="sesmusic_view_custom_fields">
					<?php
						//custom field data
						echo $this->sesbasicFieldValueLoop($this->albumsong);
					?>
				</div>
			</div>
			<?php endif; ?>
			<!-- End Profile fields -->
      <p class="sesmusic_cover_btm_stat">
       <?php 
          $information = '';   
          if ($this->showRating && !empty($this->information) && engine_in_array('ratingCount', $this->information))
            $information .= $this->translate(array('%s rating', '%s ratings', $this->albumsong->rating), $this->locale()->toNumber($this->albumsong->rating)) . ' | ';

          if (!empty($this->information) && engine_in_array('likeCount', $this->information))
           $information .= $this->translate(array('%s like', '%s likes', $this->albumsong->like_count), $this->locale()->toNumber($this->albumsong->like_count)) . ' | '; 

          if (!empty($this->information) && engine_in_array('commentCount', $this->information))
            $information .= $this->translate(array('%s comment', '%s comments', $this->albumsong->comment_count), $this->locale()->toNumber($this->albumsong->comment_count)) . ' | ';

          if (!empty($this->information) && engine_in_array('viewCount', $this->information))
           $information .= $this->translate(array('%s view', '%s views', $this->albumsong->view_count), $this->locale()->toNumber($this->albumsong->view_count)) . ' | ';

          if (!empty($this->information) && engine_in_array('favouriteCount', $this->information))
            $information .= $this->translate(array('%s favorite', '%s favorites', $this->albumsong->favourite_count), $this->locale()->toNumber($this->albumsong->favourite_count)) . ' | ';

          if (!empty($this->information) && engine_in_array('playCount', $this->information))
            $information .= $this->translate(array('%s play', '%s plays', $this->albumsong->play_count), $this->locale()->toNumber($this->albumsong->play_count)) . ' | ';

          $information = trim($information);
          $information = rtrim($information, '|');
        ?>
        <?php echo $information; ?>
      </p>
    </div>
  </div>
  </div>
<?php else: ?>
  <div class="sesmusic_list sesmusic_playlist_browse_listing">
    <div class="sesmusic_playlist_listing_inner">
    	<div class="sesmusic_playlist_listing_img_box">
      	<div class="sesmusic_playlist_listing_artwork_bg_image">
        	  <?php if($this->albumsong->photo_id): ?>
        <?php echo $this->itemPhoto($this->albumsong, 'thumb.profile'); ?>
      <?php else: ?>
        <?php echo $this->itemPhoto($this->album, 'thumb.normal'); ?>
      <?php endif; ?>
        </div>
        <div class="sesmusic_playlist_listing_artwork">
      <?php if($this->albumsong->photo_id): ?>
        <?php echo $this->itemPhoto($this->albumsong, 'thumb.profile'); ?>
      <?php else: ?>
        <?php echo $this->itemPhoto($this->album, 'thumb.normal'); ?>
      <?php endif; ?>
      <div class="sesmusic_item_info_label">
        <span class="sesmusic_label_hot fa fa-star" title="HOT"></span>
        <span class="sesmusic_label_featured fa fa-star" title="FEATURED"></span>
        <span class="sesmusic_label_sponsored fa fa-star" title="SPONSORED"></span>
      </div>
    </div>
      </div>
      <div class="sesmusic_playlist_listing_info">
      <div class="sesmusic_playlist_info_title">
        <?php echo $this->albumsong->getTitle() ?>
      </div>
      <div class="sesmusic_playlist_listing_info_stats sesbasic_text_light">
        <?php if($this->information && engine_in_array('postedBy', $this->information)): ?>
          <?php echo $this->translate('Album By %s', $this->htmlLink($album->getOwner(), $album->getOwner()->getTitle())) ?>
        <?php endif ;?>
        <?php if($this->information && engine_in_array('creationDate', $this->information)): ?>|
          <?php echo $this->translate('Released %s', $album->creation_date); ?>
        <?php endif ;?>
      </div>
      <?php if(!empty($this->information) && engine_in_array('category', $this->information) && $this->albumsong->category_id) :?>
        <div class="sesmusic_playlist_listing_info_stats  sesbasic_text_light">
          <?php $catName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->albumsong->category_id, 'param' => 'song')); ?>
          <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?category_id='.urlencode($this->albumsong->category_id) ; ?>"><?php echo $catName; ?></a>
          <?php if($this->albumsong->subcat_id): ?>
          <?php $subcatName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->albumsong->subcat_id, 'param' => 'song')); ?>
          &nbsp;&raquo;
          <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?subcat_id='.urlencode($this->albumsong->subcat_id) ; ?>"><?php echo $subcatName; ?></a>
          <?php endif; ?>
          <?php if($this->albumsong->subsubcat_id): ?>
          <?php $subsubcatName = $this->categoriesTable->getColumnName(array('column_name' => 'category_name', 'category_id' => $this->albumsong->subsubcat_id, 'param' => 'song')); ?>
          &nbsp;&raquo;
          <a href="<?php echo $this->url(array('action' => 'browse'), 'sesmusic_songs', true).'?subsubcat_id='.urlencode($this->albumsong->subsubcat_id) ; ?>"><?php echo $subsubcatName; ?></a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="sesmusic_playlist_listing_info_stats  sesbasic_text_light">
       <?php 
          $information = '';   
          if ($this->showRating && !empty($this->information) && engine_in_array('ratingCount', $this->information))
            $information .= $this->translate(array('%s rating', '%s ratings', $this->albumsong->rating), $this->locale()->toNumber($this->albumsong->rating)) . '| ';

          if (!empty($this->information) && engine_in_array('likeCount', $this->information))
           $information .= $this->translate(array('%s like', '%s likes', $this->albumsong->like_count), $this->locale()->toNumber($this->albumsong->like_count)) . ' | '; 

          if (!empty($this->information) && engine_in_array('commentCount', $this->information))
            $information .= $this->translate(array('%s comment', '%s comments', $this->albumsong->comment_count), $this->locale()->toNumber($this->albumsong->comment_count)) . ' | ';

          if (!empty($this->information) && engine_in_array('viewCount', $this->information))
           $information .= $this->translate(array('%s view', '%s views', $this->albumsong->view_count), $this->locale()->toNumber($this->albumsong->view_count)) . ' | ';

          if (!empty($this->information) && engine_in_array('favouriteCount', $this->information))
            $information .= $this->translate(array('%s favorite', '%s favorites', $this->albumsong->favourite_count), $this->locale()->toNumber($this->albumsong->favourite_count)) . ' | ';

          if (!empty($this->information) && engine_in_array('playCount', $this->information))
            $information .= $this->translate(array('%s play', '%s plays', $this->albumsong->play_count), $this->locale()->toNumber($this->albumsong->play_count)) . ' | ';

          $information = trim($information);
          $information = rtrim($information, '|');
        ?>
        <?php echo $information; ?>
      </div>

      <?php if($this->showRating == 1 && !empty($this->information) && engine_in_array('ratingStars', $this->information)):  ?>
        <div id="album_rating" class="sesmusic_rating_star" onmouseout="rating_out();">
          <span id="rate_1" class="fa fa-star" <?php  if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating ):?>onclick="rate(1);"<?php  endif; ?> onmouseover="rating_over(1);"></span>
          <span id="rate_2" class="fa fa-star" <?php if ( $this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(2);"<?php endif; ?> onmouseover="rating_over(2);"></span>
          <span id="rate_3" class="fa fa-star" <?php if ( $this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(3);"<?php endif; ?> onmouseover="rating_over(3);"></span>
          <span id="rate_4" class="fa fa-star" <?php if ($this->viewer_id && $this->ratedAgain && $this->allowMine && $this->allowRating):?>onclick="rate(4);"<?php endif; ?> onmouseover="rating_over(4);"></span>
          <span id="rate_5" class="fa fa-star" <?php if ($this->viewer_id && $this->ratedAgain  && $this->allowMine && $this->allowRating):?>onclick="rate(5);"<?php endif; ?> onmouseover="rating_over(5);"></span>
          <span id="rating_text" class="sesbasic_rating_text"><?php echo $this->translate('click to rate');?></span>
        </div>
      <?php endif; ?>

      <div class="sesmusic_playlist_listing_options_buttons">
        <?php $path = Engine_Api::_()->sesmusic()->songImageURL($this->albumsong); ?>
        <?php if($this->information && engine_in_array('playButton', $this->information)): ?>
          <?php if($this->albumsong->track_id): ?>
            <?php $uploadoption = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.uploadoption', 'myComputer');
            $consumer_key =  Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.scclientid'); ?>          
            <?php if(($uploadoption == 'both' || $uploadoption == 'soundCloud') && $consumer_key): ?>
              <?php $track_id = $this->albumsong->track_id;
              $URL = "http://api.soundcloud.com/tracks/$track_id/stream?consumer_key=$consumer_key";  ?>
              <a class="fa fa-play" href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo preg_replace("/[^A-Za-z0-9\-]/", "", $this->albumsong->getTitle()); ?>', '', '<?php echo $path; ?>');"><?php echo $this->translate("Play"); ?></a>
            <?php else: ?>
             <!-- <a class="fa fa-play" href="javascript:void(0);"><?php echo $this->translate("Play"); ?></a> -->
             <a class="fa fa-play"  href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $this->albumsong->getFilePath(); ?>', '<?php echo $this->albumsong->getTitle(); ?>', '', '<?php echo $path; ?>');"><?php echo $this->translate("Play"); ?></a>
            <?php endif; ?>
          <?php else: ?>
            <a class="fa fa-play"  href="javascript:void(0);" onclick="play_music('<?php echo $this->albumsong->albumsong_id ?>', '<?php echo $this->albumsong->getFilePath(); ?>', '<?php echo $this->albumsong->getTitle(); ?>', '', '<?php echo $path; ?>');"><?php echo $this->translate("Play"); ?></a>
          <?php endif; ?>
        <?php endif; ?>

        <?php if($this->album->owner_id == $this->viewer_id || $this->viewer->level_id == 1): ?>
          <?php  if ($this->canEditSong && $this->information && engine_in_array('editButton', $this->information))
          echo $this->htmlLink($album->getHref(array('route' => 'sesmusic_albumsong_specific', 'action' => 'edit', 'albumsong_id' => $this->albumsong->albumsong_id)), $this->translate('Edit Song'), array('class'=>'fa fa-pencil-alt' )); ?> 

          <?php if($this->canDeleteSong && $this->information && engine_in_array('deleteButton', $this->information))
          echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'module' => 'sesmusic', 'controller' => 'song', 'action' => 'delete', 'albumsong_id' => $this->albumsong->albumsong_id, 'format' => 'smoothbox'), $this->translate('Delete Song'), array('class' => 'smoothbox fa fa-trash')); ?>
        <?php endif; ?>

        <?php if($this->canAddPlaylistAlbumSong && $this->viewer()->getIdentity() && $this->information && engine_in_array('addplaylist', $this->information)): ?>
          <?php if($this->information && engine_in_array('addplaylist', $this->information)): ?>
            <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'append', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate('Add to Playlist'), array('class' => 'smoothbox fa fa-plus')); ?>
          <?php endif; ?>
        <?php endif; ?>

        <?php if(!$this->albumsong->track_id && !$this->albumsong->song_url && $this->downloadAlbumSong && $this->viewer()->getIdentity() && $this->information && engine_in_array('downloadButton', $this->information)): ?>
        <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
        <?php else: ?>
          <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
        <?php endif; ?>

        <?php if($this->albumsong->lyrics && $this->information && engine_in_array('printButton', $this->information)): ?>
          <?php echo $this->htmlLink(array('action' => 'print', 'route' => 'sesmusic_albumsong_specific', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate('Print'), array('class' => 'fa fa-print')); ?>         
        <?php endif; ?>

        <?php if($this->songlink && engine_in_array('share', $this->songlink) && $this->information && engine_in_array('share', $this->information)): ?>
          <?php echo $this->htmlLink(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_albumsong', 'id' => $this->albumsong->getIdentity(), 'format' => 'smoothbox'), $this->translate("Share"), array('class' => 'smoothbox fas fa-share-alt')); ?>
        <?php endif; ?>

        <?php if($this->songlink && engine_in_array('report', $this->songlink) && $this->information && engine_in_array('report', $this->information)): ?>
          <?php echo $this->htmlLink(array('module'=>'core', 'controller'=>'report', 'action'=>'create', 'route'=>'default', 'subject'=> $this->albumsong->getGuid(), 'format' => 'smoothbox'), $this->translate("Report"), array('class' => 'smoothbox fa fa-flag')); ?>
        <?php endif; ?>

        <?php if ($this->addfavouriteAlbumSong && !empty($this->viewer_id) && $this->information && engine_in_array('addFavouriteButton', $this->information)): ?>
          <a class="fa fa-heart sesmusic_favourite" id="sesmusic_albumsong_unfavourite_<?php echo $this->albumsong->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->albumsong->getIdentity(); ?>', 'sesmusic_albumsong');"><?php echo $this->translate("Remove from Favorites") ?></a>
          <a class="fa fa-heart" id="sesmusic_albumsong_favourite_<?php echo $this->albumsong->getIdentity(); ?>" style ='display:<?php echo $this->isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $this->albumsong->getIdentity(); ?>', 'sesmusic_albumsong');"><?php echo $this->translate("Add to Favorite") ?></a>
          <input type="hidden" id="sesmusic_albumsong_favouritehidden_<?php echo $this->albumsong->getIdentity(); ?>" value='<?php echo $this->isFavourite ? $this->isFavourite : 0; ?>' />
        <?php endif; ?> 
    
        <?php $viewer = Engine_Api::_()->user()->getViewer();
        $addstore_link = Engine_Api::_()->authorization()->isAllowed('sesmusic_album', $viewer, 'addstore_link'); ?>
        <?php if($addstore_link && $this->albumsong->store_link && engine_in_array('storeLink', $this->information)): ?>
          <?php $storeLink = !empty($this->albumsong->store_link) ? (preg_match("#https?://#", $this->albumsong->store_link) === 0) ? 'http://'.$this->albumsong->store_link : $this->albumsong->store_link : ''; ?>
          <a href="<?php echo $storeLink ?>" target="_blank" class="fa fa-shopping-cart"><?php echo $this->translate("Purchase") ?></a>
        <?php elseif(($viewer->getIdentity()) && $this->albumsong->store_link && $this->information && engine_in_array('storeLink', $this->information)):  ?>
          <?php $storeLink = !empty($this->albumsong->store_link) ? (preg_match("#https?://#", $this->albumsong->store_link) === 0) ? 'http://'.$this->albumsong->store_link : $this->albumsong->store_link : ''; ?>
          <a href="<?php echo $storeLink ?>" target="_blank" class="fa fa-shopping-cart"><?php echo $this->translate("Purchase") ?></a>
        <?php endif; ?>
        
        <?php $downloadPublic = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.download.publicuser', 0); ?>
          <?php if($downloadPublic && !$this->albumsong->track_id && !$this->albumsong->song_url && ($this->viewer()->getIdentity()) && $this->information && engine_in_array('downloadButton', $this->information)): ?>
          <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
        <?php elseif($downloadPublic && ($this->viewer()->getIdentity()) && $this->information && engine_in_array('downloadButton', $this->information)): ?>
          <?php echo $this->htmlLink(array('route' => 'sesmusic_albumsong_specific', 'action' => 'download-song', 'albumsong_id' => $this->albumsong->albumsong_id), $this->translate("Download"), array('class' => ' fa fa-download')); ?>
        <?php endif; ?>
      </div>
    </div>
    </div>
  </div>
<?php endif; ?>
<?php } ?>
