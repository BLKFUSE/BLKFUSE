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
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/styles/customscrollbar.css'); ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesbasic/externals/scripts/customscrollbar.concat.min.js'); ?>
<?php
//This forces every playlist to have a unique ID, so that a playlist can be displayed twice on the same page.
$random   = '';
for ($i=0; $i<6; $i++) { $d=rand(1,30)%2; $random .= ($d?chr(rand(65,90)):chr(rand(48,57))); }
?>
<script type="text/javascript">
  function showPopUp(url) {
    Smoothbox.open(url);
    parent.Smoothbox.close;
  }

  function loadMoreContent() {
  
    if (document.getElementById('load_more'))
      document.getElementById('load_more').style.display = "<?php echo ( $this->paginator->count() == $this->paginator->getCurrentPageNumber() || $this->count == 0 ? 'none' : '' ) ?>";

    if(document.getElementById('load_more'))
      document.getElementById('load_more').style.display = 'none';
    
    if(document.getElementById('underloading_image'))
      document.getElementById('underloading_image').style.display = '';

    en4.core.request.send(scriptJquery.ajax({
      //method: 'post',              
      'url': en4.core.baseUrl + 'widget/index/mod/sesmusic/name/browse-playlists',
      'data': {
        format: 'html',
        page: "<?php echo sprintf('%d', $this->paginator->getCurrentPageNumber() + 1) ?>",
        viewmore: 1,
        params: '<?php echo json_encode($this->all_params); ?>',        
      },
      success: function(responseHTML) {
        scriptJquery('#results_data_browse_playlist').append(responseHTML);

        if(document.getElementById('load_more'))
          scriptJquery('#load_more').remove();
        
        if(document.getElementById('underloading_image'))
         scriptJquery('#underloading_image').remove();
       
        if(document.getElementById('loadmore_list'))
         scriptJquery('#loadmore_list').remove();
               scriptJquery(".sesbasic_custom_scroll").mCustomScrollbar({
          theme:"minimal-dark"
        });
      }
    }));
    return false;
  }
</script>

<?php if($this->viewType == 'listView'): ?>
  <?php if(is_countable($this->paginator) && engine_count($this->paginator) > 0): ?>
    <?php if (empty($this->viewmore)): ?>
      <div class="sesbasic_search_result"><?php echo $this->translate(array('%s playlist found.', '%s playlists found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())); ?></div>
      <ul class="sesmusic_list sesmusic_playlist_browse_listing" id= "results_data_browse_playlist">
    <?php endif; ?>
    <?php foreach ($this->paginator as $item):  ?>
      <li id="music_playlist_item_<?php echo $item->getIdentity() ?>" class="sesbasic_clearfix">
        <div class="sesmusic_playlist_listing_inner">
          <div class="sesmusic_playlist_listing_img_box">
            <div class="sesmusic_playlist_listing_artwork_bg_image">
        <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile'), array('class' => 'thumb')) ?>
      </div>
            <div class="sesmusic_playlist_listing_artwork">
          <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile'), array('class' => 'thumb')) ?>
      </div>
          </div>
          <div class="sesmusic_playlist_listing_info">
        <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
        <div class="sesmusic_playlist_info_title">
          <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
        </div>
        <?php endif; ?>
        <?php if(!empty($this->information) && engine_in_array('postedby', $this->information)): ?>
        <div class="sesmusic_playlist_listing_info_stats sesbasic_text_light sesbasic_font_small">
          <?php echo $this->translate('Created By %s', $this->htmlLink($item->getOwner(), $item->getOwner()->getTitle())) ?>
        </div>
        <?php endif; ?>
        <div class="sesmusic_playlist_listing_info_stats  sesbasic_text_light">
          <?php if(!empty($this->information) && engine_in_array('viewCount', $this->information)): ?>
            <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>  
          <?php endif; ?>
          <?php if(!empty($this->information) && engine_in_array('favouriteCount', $this->information)): ?>
            &nbsp;|&nbsp;<?php echo $this->translate(array('%s favourite', '%s favourites', $item->favourite_count), $this->locale()->toNumber($item->favourite_count)) ?>  
          <?php endif; ?>
        </div>
        <?php if(!empty($this->information) && engine_in_array('description', $this->information)): ?>
          <div class="sesmusic_playlist_listinfo_desc">
            <?php echo $this->viewMore(nl2br($item->description)); ?>
          </div>
        <?php endif; ?>
        <?php if($this->viewer_id): ?>
        <div class="sesmusic_playlist_listing_options_buttons">
          <?php if(!empty($this->information) && engine_in_array('favourite', $this->information)): ?>
            <?php $isFavourite = Engine_Api::_()->getDbTable('favourites', 'sesmusic')->isFavourite(array('resource_type' => "sesmusic_playlist", 'resource_id' => $item->getIdentity())); ?>
            <a class="sesbasic_icon_favourite sesmusic_favourite" id="sesmusic_playlist_unfavourite_<?php echo $item->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "inline-block" : "none" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $item->getIdentity(); ?>', 'sesmusic_playlist');" title="<?php echo $this->translate("Remove from Favorite") ?>"><?php echo $this->translate("Remove from Favorite") ?></a>
            <!--<a href="" class="fa fa-thumbs-up">Like</a>-->
            <a class="sesbasic_icon_favourite" id="sesmusic_playlist_favourite_<?php echo $item->getIdentity(); ?>" style ='display:<?php echo $isFavourite ? "none" : "inline-block" ?>' href = "javascript:void(0);" onclick = "sesmusicFavourite('<?php echo $item->getIdentity(); ?>', 'sesmusic_playlist');" title="<?php echo $this->translate("Add to Favorite") ?>"><?php echo $this->translate("Add to Favorite") ?></a>
            <input type="hidden" id="sesmusic_playlist_favouritehidden_<?php echo $item->getIdentity(); ?>" value='<?php echo $isFavourite ? $isFavourite : 0; ?>' />        

            <?php endif; ?>
            <?php if($this->viewer_id && !empty($this->information) && engine_in_array('share', $this->information)): ?>
            <a  class="sesbasic_icon_share" title='<?php echo $this->translate("Share") ?>' href="javascript:void(0);" onclick="showPopUp('<?php echo $this->escape($this->url(array('module'=>'activity', 'controller'=>'index', 'action'=>'share', 'route'=>'default', 'type'=>'sesmusic_playlist', 'id' => $item->playlist_id, 'format' => 'smoothbox'), 'default' , true)); ?>'); return false;" ><?php echo $this->translate("Share"); ?></a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($this->information) && engine_in_array('showSongsList', $this->information)): ?>
        <?php $playlist = $item; 
        $songs = $item->getSongs();
        ?>   
        <?php if(engine_count($songs) > 0): ?>
        <div id="sesmusic_player_<?php echo $random ?>" class="clear sesbasic_clearfix sesmusic_tracks_container sesbasic_custom_scroll">
          <ul class="clear sesmusic_tracks_list playlist_<?php echo $playlist->getIdentity() ?>">
            <?php foreach( $songs as $song ): ?>
            <?php $song = Engine_Api::_()->getItem('sesmusic_albumsong', $song->albumsong_id); ?>
            <?php if( !empty($song) ): ?>
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
                  <a href="javascript:void(0);" onclick="play_music('<?php echo $song->albumsong_id ?>', '<?php echo $URL; ?>', '<?php echo $songTitle; ?>', '', '<?php echo $path; ?>');" class="sesmusic_songslist_playbutton"><i class="play-circle"></i></a>
                  
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
              <div class="sesmusic_tracks_list_stats sesbasic_text_light" title="<?php echo $song->playCountLanguagefield() ?>">
                <i class="fa fa-play"></i><?php echo $song->play_count; ?>
              </div>
              <div class="sesmusic_tracks_list_name" title="<?php echo $song->getTitle() ?>">
                  <?php echo $this->htmlLink($song->getFilePath(), $this->htmlLink($song->getHref(), $song->getTitle()), array('class' => 'music_player_tracks_url', 'type' => 'audio', 'rel' => $song->song_id)); ?>
              </div>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        </div>
        </div>
      </li>
    <?php endforeach; ?>
    <?php //if($this->paginationType == 1): ?>
      <?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
        <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
          <div class="clr" id="loadmore_list"></div>
          <div class="sesbasic_view_more sesbasic_load_btn" id="load_more" onclick="loadMoreContent();" style="display: block;">
            <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
          </div>
          <div class="sesbasic_view_more_loading" id="underloading_image" style="display: none;">
            <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php //else: ?>
      <?php //echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->all_params)); ?>
    <?php //endif; ?>  
    <?php if (empty($this->viewmore)): ?>
      </ul>
    <?php endif; ?>
  <?php endif; ?>
<?php elseif($this->viewType == 'gridview'): ?>
  <!--music playlist list view-->
  <?php if(is_countable($this->paginator) && engine_count($this->paginator) > 0): ?>
    <?php if (empty($this->viewmore)): ?>
      <h4><?php echo $this->translate(array('%s playlist found.', '%s playlists found.', $this->paginator->getTotalItemCount()), $this->locale()->toNumber($this->paginator->getTotalItemCount())); ?></h4>
        <ul class=" sesmusic_playlist_browse_grid_listign" id= "results_data">
    <?php endif; ?>
    <?php foreach ($this->paginator as $item):  ?>
      <li id="music_playlist_item_<?php echo $item->getIdentity() ?>" class="sesbasic_clearfix sesmusic_playlist_grid_main" style="width:<?php echo $this->width ?>px;">
        <div class="sesmusic_playlist_grid_inner">
          <div class="sesmusic_playlist_grid_img_box">
            <div class="sesmusic_playlist_grid_artwork_bg_image" style="height:<?php echo $this->height ?>px;">
              <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile'), array('class' => 'thumb')) ?>
            </div>
            <div class="sesmusic_playlist_grid_artwork">
              <?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.profile'), array('class' => 'thumb')) ?>
            </div>
            <div class="sesmusic_playlist_grid_hover_box">
              <a href="<?php echo $item->getHref(); ?>"><i class="fa fa-play-circle"></i></a>
            </div>
          </div>
          <div class="sesmusic_playlist_grid_info">
            <?php if(!empty($this->information) && engine_in_array('title', $this->information)): ?>
            <div class="sesmusic_playlist_grid_info_title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <?php endif; ?>
            <?php $playlist = $item; 
            $songs = $item->getSongs();
            ?>   
            <?php if(engine_count($songs) > 0): ?>
              <div class="sesmusic_playlist_grid_music_count">
                <p><?php echo $this->translate("%s Songs", engine_count($songs)); ?></p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
    <?php //if($this->paginationType == 1): ?>
      <?php if (!empty($this->paginator) && $this->paginator->count() > 1): ?>
        <?php if ($this->paginator->getCurrentPageNumber() < $this->paginator->count()): ?>
          <div class="clr" id="loadmore_list"></div>
          <div class="sesbasic_view_more sesbasic_load_btn" id="load_more" onclick="loadMoreContent();" style="display: block;">
            <a href="javascript:void(0);" class="sesbasic_animation sesbasic_link_btn" ><i class="fa fa-repeat"></i><span><?php echo $this->translate('View More');?></span></a>
          </div>
          <div class="sesbasic_view_more_loading" id="underloading_image" style="display: none;">
            <span class="sesbasic_link_btn"><i class="fa fa-spinner fa-spin"></i></span>
            <?php echo $this->translate("Loading ...") ?>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    <?php //else: ?>
      <?php //echo $this->paginationControl($this->paginator, null, null, array('pageAsQuery' => true, 'query' => $this->all_params)); ?>
    <?php //endif; ?>  
    <?php if (empty($this->viewmore)): ?>
      </ul>
    <?php endif; ?>
  <?php else: ?>
    <div class="tip">
      <span>
        <?php echo $this->translate('There are currently no playlists created yet.') ?>
      </span>
    </div>
  <?php endif; ?>
<?php endif; ?>
<?php if (empty($this->viewmore)): ?>
  <script type="text/javascript">
    //scriptJquery('.core_main_sesmusic').parent().addClass('active');
  </script>
<?php endif; ?>

<?php if($this->paginationType == 1): ?>
  <script type="text/javascript">    
     //Take refrences from: http://mootools-users.660466.n2.nabble.com/Fixing-an-element-on-page-scroll-td1100601.html
    //Take refrences from: http://davidwalsh.name/mootools-scrollspy-load
    en4.core.runonce.add(function() {
    
      var paginatorCount = '<?php echo $this->paginator->count(); ?>';
      var paginatorCurrentPageNumber = '<?php echo $this->paginator->getCurrentPageNumber(); ?>';
      function ScrollLoader() { 
        var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        if(document.getElementById('loadmore_list')) {
          if (scrollTop > 40)
            loadMoreContent();
        }
      }
      window.addEvent('scroll', function() { 
        ScrollLoader(); 
      });
    });    
  </script>
<?php endif; ?>
