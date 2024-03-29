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

<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }
</script>
<?php if($this->showType == 'gridview'): ?>
<?php if(engine_count($this->results) > 0) :?>
  <ul class="sesmusic_playlist_grid_listing sesbasic_clearfix">
    <?php foreach( $this->results as $item ):  ?> 
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
          <ul class="clear sesmusic_tracks_list ">
            <?php foreach( $songs as $song ):  ?>
							<?php $song = Engine_Api::_()->getItem('sesmusic_albumsong', $song->albumsong_id); ?>
							<?php $songAlbum = Engine_Api::_()->getItem('sesmusic_album', $song->album_id); ?>
              <li class="sesbasic_clearfix <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($songAlbum)) { ?> paid_content <?php } ?>">
								<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($songAlbum)) { ?>
									<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $songAlbum)); ?>
								<?php } ?>
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
                    <?php if($song->store_link): ?>
                      <?php $storeLink = !empty($song->store_link) ? (preg_match("#https?://#", $song->store_link) === 0) ? 'http://'.$song->store_link : $song->store_link : ''; ?>
                      <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '<?php echo $storeLink ?>', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-pplay-circlelay"></i></a>
                    <?php else: ?>
                      <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-play-circle"></i></a>
                    <?php endif; ?>
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
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
<?php elseif($this->showType == 'carouselview'): ?>

  <?php $randonNumber = $this->identity; ?>
  <?php $baseUrl = $this->layout()->staticBaseUrl; ?>

  <?php
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/jquery.js');
    $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/owl-carousel/owl.carousel.js');
  ?>
  <?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/carousel.css'); ?>

  <style>
    #sesmusic_ps_carousel_<?php echo $randonNumber; ?> {
      position: relative;
      height:<?php echo $this->height ?>px;
      overflow: hidden;
    }
  </style>

  <div class="slide sesmusic_carousel_wrapper sesbasic_clearfix sesbasic_bxs">
    <div id="sesmusic_ps_carousel_<?php echo $randonNumber; ?>" class="sesmusic_ps_carousel">
    <?php foreach( $this->results as $item ):  ?>
      <div class="sesmusic_playlist_grid sesbasic_bxs" style="height:<?php echo $this->height ?>px;">
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
        <div class="clear sesbasic_clearfix sesmusic_tracks_container">
          <ul class="clear sesmusic_tracks_list">
            <?php foreach( $songs as $song ):  ?>
							<?php $song = Engine_Api::_()->getItem('sesmusic_albumsong', $song->albumsong_id); ?>
              <li class="sesbasic_clearfix <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($song)) { ?> paid_content <?php } ?>">
								<?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.sesmusic',1) && Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($song)) { ?>
									<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $song)); ?>
								<?php } ?>
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
                    <?php if($song->store_link): ?>
                      <?php $storeLink = !empty($song->store_link) ? (preg_match("#https?://#", $song->store_link) === 0) ? 'http://'.$song->store_link : $song->store_link : ''; ?>
                      <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '<?php echo $storeLink ?>', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-play-circle"></i></a>
                    <?php else: ?>
                      <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $song->getFilePath(); ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="fa fa-play-circle"></i></a>
                    <?php endif; ?>
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
      </div>
    <?php endforeach; ?>
    </div>
  </div>
 <script type="text/javascript">
 sesowlJqueryObject(document).ready(function() {
	sesowlJqueryObject('#sesmusic_ps_carousel_<?php echo $randonNumber; ?>').owlCarousel({
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
