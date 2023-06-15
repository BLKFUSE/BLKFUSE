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
<?php //$this->headScript()->appendFile($this->layout()->staticBaseUrl . 'externals/soundmanager/script/soundmanager2' . (APPLICATION_ENV == 'production' ? '-nodebug-jsmin' : '' ) . '.js'); ?>
<?php $this->headScript()
->appendFile($this->layout()->staticBaseUrl . 'externals/audio/audio.min.js')
->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/core.js'); ?>
<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/player.js'); ?>
<?php $this->headLink()->appendStylesheet($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/styles/player.css'); ?>
<?php
$viewer_id = Engine_Api::_()->user()->getViewer()->getIdentity();
//This forces every playlist to have a unique ID, so that a playlist can be displayed twice on the same page.
$random   = '';
for ($i=0; $i<6; $i++) { $d=rand(1,30)%2; $random .= ($d?chr(rand(65,90)):chr(rand(48,57))); }
?>


<div class="sesmusic_player_wrapper <?php if(!empty($_COOKIE['sesmusic_player_hide'])): ?> sesmusic_player_hide <?php endif; ?> <?php if(Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.showplayer', 0)): ?>sesmusic_player_mini<?php endif; ?>" id="music_player_100">
    <div class="sesmusic_player" id="sesmusic_player_list">
      <div class="sesmusic_player_toggel">
      	<div class="sesmusic_player_toggel_close">
          <p class="sesmusic_player_toggel_tip">
            <span class="_min"><?php echo $this->translate("Close Player"); ?></span>
          </p>
          <i onclick="clear_playlists('sesmusic_playlists', 'closebutton');" class="fa fa-times"></i>
        </div>
      	<div class="sesmusic_player_toggel_min">
          <p class="sesmusic_player_toggel_tip">
            <span class="_min"><?php echo $this->translate("Minimize Player"); ?></span>
            <span class="_max"><?php echo $this->translate("Maximize Player"); ?></span>
          </p>
          <i onclick="sesmusicPlayerHide(0);" class="fa fa-chevron-left"></i>
          <i onclick="sesmusicPlayerHide(1);" class="fa fa-chevron-up"></i>
        </div>
      </div>
      <div class="sesmusic_player_top">
        <div class="sesmusic_player_art" id="sesmusic_player_art">
          <?php //echo $this->itemPhoto($playlist, null, $playlist->getTitle()) ?>
        </div>
        <div class="sesmusic_player_info">
          <div class="sesmusic_player_controls_wrapper">
            <div class="sesmusic_player_controls_right" style="display:none;">
              <span class="sesmusic_player_button_launch_wrapper">
                <div class="sesmusic_player_button_launch_tooltip"><?php //echo $this->translate('Pop-out Player') ?></div>
                <a class="sesmusic_player_button_launch"></a>
                  <?php //echo $this->htmlLink($playlist->getHref(array('popout' => true)), '', array('class' => 'sesmusic_player_button_launch')) ?>
              </span>
            </div>
            
            <div class="sesmusic_player_song_control">
              <span class="sesmusic_player_button_prev fa"></span>
              <span id="sesmusic_player_button_play" class="sesmusic_player_button_play fa"></span>
              <span class="sesmusic_player_button_next fa"></span>
            </div>            
            <div class="sesmusic_player_right">
              <div class="sesmusic_player_controls_volume">
                <span class="sesmusic_player_controls_volume_toggle fa"></span>
                <span class="sesmusic_player_controls_volume_bar music_player_controls_volume_enabled"><span class="volume_bar_1"></span></span>
                <span class="sesmusic_player_controls_volume_bar music_player_controls_volume_enabled"><span class="volume_bar_2"></span></span>
                <span class="sesmusic_player_controls_volume_bar"><span class="volume_bar_3"></span></span>
                <span class="sesmusic_player_controls_volume_bar"><span class="volume_bar_4"></span></span>
                <span class="sesmusic_player_controls_volume_bar"><span class="volume_bar_5"></span></span>
              </div>
             
            </div>
            <div class="sesmusic_player_control">
              <div class="sesmusic_player_trackname"></div>
              <div class="sesmusic_player_time">
                <div class="sesmusic_player_time_elapsed"></div>
                <div id="sesmusic_showseprator">&nbsp;/&nbsp;</div>
                <div class="sesmusic_player_time_total"></div>
              </div>
              <?php if(0) { ?>
                <div class="sesmusic_player_control_options">
                  <?php if($this->canAddPlaylistAlbumSong && !empty($viewer_id)): ?>
                  <a id="sesmusic_player_addplaylist" href="javascript:void(0);"><i class="fa fa-plus"></i><span><?php echo $this->translate("Add to Playlist"); ?></span></a>
                  <?php endif; ?>
                  <?php if($this->downloadAlbumSong): ?>
                  <a id="sesmusic_player_download" href="#"><i class="fa fa-download"></i><span><?php echo $this->translate("Download"); ?></span></a>
                  <?php endif; ?>
                  <?php if(!empty($this->songlink) && engine_in_array('share', $this->songlink) && !empty($viewer_id)): ?>
                  <a id="sesmusic_player_share" href="javascript:void(0);"><i class="fas fa-share-alt"></i><span><?php echo $this->translate("Share"); ?></span></a>
                  <?php endif; ?>
                </div>
              <?php } ?>
             <audio preload></audio>

             
              <?php $downloadPublic = Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.download.publicuser', 0); ?>
              <?php if(empty($viewer_id) && $downloadPublic): ?>
                <div class="sesmusic_player_control_options">
                  <?php if($this->canAddPlaylistAlbumSong): ?>
                  <a id="sesmusic_player_addplaylist" href="javascript:void(0);"><i class="fa fa-plus"></i><span><?php echo $this->translate("Add to Playlist"); ?></span></a>
                  <?php endif; ?>
                  <?php if($this->downloadAlbumSong): ?>
                  <a id="sesmusic_player_download" href="#"><i class="fa fa-download"></i><span><?php echo $this->translate("Download"); ?></span></a>
                  <?php endif; ?>
                  <?php if(!empty($this->songlink) && engine_in_array('share', $this->songlink)): ?>
                  <a id="sesmusic_player_share" href="javascript:void(0);"><i class="fas fa-share-alt"></i><span><?php echo $this->translate("Share"); ?></span></a>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
<!--              <div class="sesmusic_player_scrub">
                <div class="sesmusic_player_scrub_cursor"></div>
                <div id="sesmusic_player_scrub_cursor"></div>
                <div class="sesmusic_player_scrub_downloaded"></div>
              </div>-->
            </div>
          </div>
        </div>
      </div>
      <div class="sesmusic_player_main">
        <div onclick="playlist_show('sesmusic_player_tracks_contaner');" id="sesmusic_player_tracks_toggle" class="sesmusic_player_tracks_toggle close">
                <?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.showplayer', 0)): ?>
                  <?php echo $this->translate("Songs");?>
                <?php endif; ?>
              </div>
      <div style='display: none;' id="sesmusic_player_tracks_contaner" class="sesmusic_player_tracks_container">
        <div class="sesmusic_player_tracks_header">
          	<p><i class="fa fa-music"></i> <?php echo $this->translate("Queue"); ?></p> 
          <?php //if(engine_count($_COOKIE['sesmusic_playlists']) > 0): ?>
          <span style="display:none;" id="sesmusic_player_tracks_header">
            <a id="clear_playlist_hide" href="javascript:void(0);" onclick="clear_playlists('sesmusic_playlists');"><?php echo $this->translate("Clear Queue"); ?></a>
          </span>
        </div>

        <?php //endif; ?>
        <div style="display:none;" id="sesmusic_player_tracksIDs"></div>
        <ul class="sesmusic_player_tracks playlist_<?php echo $random ?>" id = "sesmusic_player_tracks">
          <?php if(isset($_COOKIE["sesmusic_playlists"])): 
            $songsIDs = explode(',', $_COOKIE["sesmusic_playlists"]);
          ?>
          <?php $final_append = ''; ?>
          <?php foreach($songsIDs as $songsID): ?>
            <?php $song = Engine_Api::_()->getItem('sesmusic_albumsongs', $songsID); ?>
              <?php if($song): ?>
                <?php $songTitle = preg_replace('/[^a-zA-Z0-9\']/', ' ', $song->getTitle()); ?>
                <?php $songTitle = str_replace("'", '', $songTitle); ?>
                <?php $path = Engine_Api::_()->sesmusic()->songImageURL($song); ?>
                
                <?php $final_append .= '<li id="sesmusic_playlist_'.$song->getIdentity().'" class="sesmusicplaylistmore sesmusic_playlist_clearplaylists"><div class="sesmusic_player_tracks_photo"><a href="'.$song->getFilePath().'" class="sesmusic_player_tracks_photo"><img rel="'.$song->getIdentity().'" src="'.$path.'" alt="" class="thumb_icon item_photo_sesmusic_albumsong"></a><a class="sesmusic_playler_play_button" href="javascript:void(0)"><i class="fa fa-play-circle"></i></a></div><div class="sesmusic_player_tracks_name" title="'.$songTitle.'"><a title="'.$songTitle.'" rel="'.$song->getIdentity().'" type="audio" class="music_player_tracks_url" href="'.$song->getFilePath().'">'.$songTitle.'</a></div><a class="clear_track_link sesbasic_icon_delete" onclick="deleteSong('.$song->getIdentity().', this)" rel="'.$song->getIdentity().'" id="delete_coockies_playlist_'.$song->getIdentity().'" href="javascript:void(0)">Remove from Queue</a></li>'; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          <?php if($final_append) { ?>
            <?php echo $final_append ;?>
          <?php } ?>
        <?php endif; ?>
        </ul>
      </div>
      </div>
    </div>
</div>
<script type="text/javascript">

function sesmusicPlayerHide(value) {

  if(value == 0) {
    if(document.getElementById('music_player_100'))
      scriptJquery('#music_player_100').addClass('sesmusic_player_hide');
    setMusicCookie("sesmusic_player_hide", 1, 1);
    var htmlElementE = scriptJquery("#body");
    htmlElementE.removeClass('sesmusic_music_player_full');
    if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').removeClass('sesmusic_music_player_full');
    }
  } else if(value == 1) {
    if(document.getElementById('music_player_100'))
      scriptJquery('#music_player_100').removeClass('sesmusic_player_hide');
    setMusicCookie("sesmusic_player_hide", 0, 1);
    var htmlElementE = scriptJquery("#body");
    htmlElementE.addClass('sesmusic_music_player_full');
     if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').addClass('sesmusic_music_player_full');
    }
  }
  
}

function clear_playlists(name, closebutton = '') {
  document.cookie = name +'=; Path=/;';
  document.getElementById('sesmusic_player_tracks_header').style.display = 'none';
  document.getElementById('sesmusic_player_tracks').innerHTML = '';
  setMusicCookie("sesmusic_playSongId", 0, 1);
  document.getElementById('sesmusic_player_tracksIDs').innerHTML = '';
  if(document.getElementById('sesmusic_player_list'))
      document.getElementById('sesmusic_player_list').style.display = 'none';
  if(closebutton == 'closebutton') {
    if(scriptJquery('#sesmusic_player_button_play').hasClass('sesmusic_player_button_pause'))
      scriptJquery('.sesmusic_player_button_play').trigger('click');
    if(document.getElementById('sesmusic_player_list'))
      document.getElementById('sesmusic_player_list').style.display = 'none';
  } else {
    if(scriptJquery('#sesmusic_player_button_play').hasClass('sesmusic_player_button_pause'))
      scriptJquery('.sesmusic_player_button_play').trigger('click');
  }
  var htmlElementE = scriptJquery("#body");
  htmlElementE.removeClass('sesmusic_music_player_full');
   if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').removeClass('sesmusic_music_player_full');
    }
  //scriptJquery('.sesmusic_player_button_pause').trigger('click');
}

function playlist_show(id) {
	var totalSongSelected = document.getElementById('sesmusic_player_tracks').getElementsByTagName('li');
		if(totalSongSelected.length == 0){
			$(id).style.display = 'none';
			return false;
		}
  if(document.getElementById(id).style.display == 'block') {
    document.getElementById(id).style.display = 'none';
    scriptJquery('#sesmusic_player_tracks_toggle').removeClass('open');
    scriptJquery('#sesmusic_player_tracks_toggle').addClass('close');
  } else {
    document.getElementById(id).style.display = 'block';
    scriptJquery('#sesmusic_player_tracks_toggle').removeClass('close');
    scriptJquery('#sesmusic_player_tracks_toggle').addClass('open');
  }
}

function play_music(id, path, title, store_link, image_path, playallsongs) {

  var finalhtmlimg = '<a href="javascript:void(0);" class="sesmusic_player_tracks_photo"><img src="'+image_path+'" alt="" class="thumb_icon item_photo_sesmusic_albumsong"></a>';
  
	var totalSongSelected = document.getElementById('sesmusic_player_tracks').getElementsByTagName('li');

	for(var i = 0; i < totalSongSelected.length ; i++) {
		if(totalSongSelected[i].id.replace("sesmusic_playlist_", "") == id) {
		
			if(scriptJquery('#'+totalSongSelected[i].id).hasClass('song_playing')){
				scriptJquery('.sesmusic_player_button_play').trigger('click');
        //Song art work image show work
        if(image_path) {
          document.getElementById('sesmusic_player_art').innerHTML = finalhtmlimg;
        }
        if(document.getElementById('sesmusic_player_list'))
          document.getElementById('sesmusic_player_list').style.display = 'block';
        //Song art work image show work
			}else {
				songPlayWithId = id;
				
				let elm = scriptJquery(this).closest(".sesmusic_player");
				
        let playmusic = scriptJquery('#sesmusic_playlist_'+id); //((next.length && next) || (first.length && first));
        if(playmusic){
          var par = document.getElementById("music_player_100");
          var el  = scriptJquery("#music_player_100").find('div.sesmusic_player');
          new en4.music.playlistAbstract(playmusic);
          playmusic.find(".music_player_tracks_url").trigger("click");
        }
        
				//scriptJquery('#sesmusic_player_button_autoPlay').trigger('click');
        //Song art work image show work
        if(image_path) {
          document.getElementById('sesmusic_player_art').innerHTML = finalhtmlimg;
        }
        if(document.getElementById('sesmusic_player_list'))
          document.getElementById('sesmusic_player_list').style.display = 'block';
        //Song art work image show work
			}
			if(scriptJquery('.song_playing').length == 0) {
        var par = document.getElementById("music_player_100");
        var el  = scriptJquery("#music_player_100").find('div.sesmusic_player');
        new en4.music.playlistAbstract(el);
        scriptJquery('.sesmusic_player_button_play').trigger('click');
        
			}
			return false;
			
		}
	}
  
  if(document.getElementById('sesmusic_player_list'))
    document.getElementById('sesmusic_player_list').style.display = 'block';

  var sesmusic_player_tracksIDs = document.getElementById('sesmusic_player_tracksIDs').innerHTML;
  var check_empty = document.getElementById('sesmusic_player_tracks').innerHTML;
  var delete_URL = en4.core.baseUrl + 'music/playlist/delete-cookies/song_id/' + id;
  
  store_link = store_link.trim();
  if(store_link && store_link != '') {
    var final_append = '<li id="sesmusic_playlist_'+id+'" class="sesmusicplaylistmore sesmusic_playlist_clearplaylists"><div class="sesmusic_player_tracks_photo"><a href="'+path+'" class="sesmusic_player_tracks_photo"><img rel="' + id +'" src="'+image_path+'" alt="" class="thumb_icon item_photo_sesmusic_albumsong"></a><a class="sesmusic_playler_play_button" href="javascript:void(0);"><i class="fa fa-play-circle"></i></a></div><div class="sesmusic_player_tracks_name" title="'+title+'"><a title="'+title+'" rel="' + id +'" type="audio" class="music_player_tracks_url" href="' +path +'">'+title+'</a></div><a class="clear_track_link sesbasic_icon_delete" onclick="deleteSong('+id+', this)" rel="' + id +'" id="delete_coockies_playlist_'+id+'" href="javascript:void(0)">Remove from Queue</a><a id="store_coockies_playlist_'+id+'" class="track_store_link fa fa-shopping-cart" target="_blank" href="'+store_link+'"></a></li>';
  } else { 
	  var final_append = '<li id="sesmusic_playlist_'+id+'" class="sesmusicplaylistmore sesmusic_playlist_clearplaylists"><div class="sesmusic_player_tracks_photo"><a href="'+path+'" class="sesmusic_player_tracks_photo"><img rel="' + id +'" src="'+image_path+'" alt="" class="thumb_icon item_photo_sesmusic_albumsong"></a><a class="sesmusic_playler_play_button" href="javascript:void(0)"><i class="fa fa-play-circle"></i></a></div><div class="sesmusic_player_tracks_name" title="'+title+'"><a title="'+title+'" rel="' + id +'" type="audio" class="music_player_tracks_url" href="' +path +'">'+title+'</a></div><a class="clear_track_link sesbasic_icon_delete" onclick="deleteSong('+id+', this)" rel="' + id +'" id="delete_coockies_playlist_'+id+'" href="javascript:void(0)">Remove from Queue</a></li>';
  }

  //Song art work image show work
  if(image_path) {
    document.getElementById('sesmusic_player_art').innerHTML = finalhtmlimg;
  }
  //Song art work image show work

  var final_append_innerhtml = check_empty + final_append;
  
  if(sesmusic_player_tracksIDs) {
    var final_sesmusic_player_tracksIDs = sesmusic_player_tracksIDs + ',' + id;
  } else {
    var final_sesmusic_player_tracksIDs = id;
  }
  
  document.getElementById('sesmusic_player_tracks').innerHTML = final_append_innerhtml;
  
  document.getElementById('sesmusic_player_tracksIDs').innerHTML = final_sesmusic_player_tracksIDs;

  var par = document.getElementById("music_player_100");
  var el  = scriptJquery("#music_player_100").find('div.sesmusic_player');
  new en4.music.playlistAbstract(el);
  //this.container = document.getElementById(el);
  scriptJquery("#music_player_100").find('div.sesmusic_player_scrub_downloaded').hide();
  
  counter = 1;
  songs = scriptJquery("#music_player_100").find('a.music_player_tracks_url'); 
  
  setMusicCookie("sesmusic_playlists", final_sesmusic_player_tracksIDs, 1);
  
  document.getElementById('sesmusic_player_tracks_header').style.display = "block";  
      
  if(totalSongSelected.length == 1) {
    scriptJquery('.sesmusic_player_button_play').trigger('click');
  }

  if(typeof playallsongs == 'undefined' || playallsongs) {
    if(!check_empty){
      //fire event when click on any songs. Auto play
      scriptJquery('.sesmusic_player_button_play').trigger('click');
    }
    else
      scriptJquery('.sesmusic_player_button_next').trigger('click');
  }
   if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').addClass('sesmusic_music_player_full');
    }
//   if(scriptJquery('#sesmusic_player_button_play').hasClass('sesmusic_player_button_pause')) {
//       //scriptJquery('.sesmusic_player_button_play').trigger('click');
//   } else 
  if(!scriptJquery('#sesmusic_player_button_play').hasClass('sesmusic_player_button_pause')) {
      scriptJquery('.sesmusic_player_button_play').trigger('click');
  }

}

<?php if(empty($_COOKIE['sesmusic_playlists'])): ?>
  if(document.getElementById('sesmusic_player_list'))
    document.getElementById('sesmusic_player_list').style.display = 'none';
<?php endif; ?>


scriptJquery(document).ready(function() {

  var playlists_show = '';
  <?php if(isset($_COOKIE["sesmusic_playlists"])): ?>
    <?php 
      $playlists_show = json_encode(urldecode($_COOKIE['sesmusic_playlists'])); 
      $playlists_show = str_replace(array("'\"",'"\''), array('', ''), $playlists_show); 
    ?>
    playlists_show = "<?php echo trim($playlists_show, '\"'); ?>";
    
    document.getElementById('sesmusic_player_tracksIDs').innerHTML = playlists_show;
    
    //document.getElementById('sesmusic_player_tracks').innerHTML = playlists_show;
  <?php endif; ?>
  
  if(playlists_show)
    document.getElementById('sesmusic_player_tracks_header').style.display = "block";

});

function thisindex(obj){
  //get parent of obj 
  parentObj=obj.parentNode.parentNode;
  //get all li elements of parentObj 
  litems=parentObj.getElementsByTagName('li');
  //cycle through litems to find obj 
  for (i=0;i<litems.length;i++) {
    if (litems.item(i)==obj.parentNode) { 
      return i; 
      break;
    }
  }
  return 0;
}


function deleteSong(id, sesthis) {
		
    var delete_URL = en4.core.baseUrl + 'music/playlist/delete-cookies/song_id/' + id;
    var request = scriptJquery.ajax({
      method: 'post',
      'url': delete_URL,
      'data': {
        format: 'html',
    
      },
      success: function(responseHTML) {
      
        var sesmusic_player_tracksIDs = document.getElementById('sesmusic_player_tracksIDs').innerHTML;
        var partsOfStr = sesmusic_player_tracksIDs.split(',');
        partsOfStr = scriptJquery.grep(partsOfStr, function(value) {
          return value != id;
        });
        partsOfStr = partsOfStr.join();
        
        document.getElementById('sesmusic_player_tracksIDs').innerHTML = partsOfStr;

        var getAutoPlayId = getMusicCookie("sesmusic_playSongId");
        if(getAutoPlayId == id){
          setMusicCookie("sesmusic_playSongId", 0, 1);
        }
        if(document.getElementById('sesmusic_playlist_' + id).className.indexOf('song_playing') > 0) {
          var totalSongSelected = document.getElementById('sesmusic_player_tracks').getElementsByTagName('li');
          if(totalSongSelected.length == 1){
            //scriptJquery('.sesmusic_player_button_play').trigger('click');
          }else{
            scriptJquery('.sesmusic_player_button_next').trigger('click');
          }
          removeSongs(sesthis);
        } else {
            removeSongs(sesthis);
        }
        scriptJquery('#sesmusic_playlist_' + id).remove();	
          var totalSongSelected = document.getElementById('sesmusic_player_tracks').getElementsByTagName('li');
          
          if(totalSongSelected.length == 0) {
            document.getElementById('sesmusic_player_tracks_header').style.display = 'none';
            if(document.getElementById('sesmusic_player_list'))
              document.getElementById('sesmusic_player_list').style.display = 'none';
            
            if(scriptJquery('#sesmusic_player_button_play').hasClass('sesmusic_player_button_pause'))
              scriptJquery('.sesmusic_player_button_play').trigger('click');
          }
          return false;
        }			
    });
    
}

function removeSongs(sesthis) {
  var sesindex = thisindex(sesthis);
  if(sesindex != 0)
   songs.splice(sesindex, 1);
}

scriptJquery(document.body).on('click',function(event) {
  if(event.target.id != 'sesmusic_player_tracks_toggle') {
	
    if(document.getElementById('sesmusic_player_tracks_contaner').style.display == 'block') {
      scriptJquery('#sesmusic_player_tracks_toggle').removeClass('open');
      scriptJquery('#sesmusic_player_tracks_toggle').addClass('close');
    }
    document.getElementById('sesmusic_player_tracks_contaner').style.display = 'none';
	}
});

<?php if(!Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.showplayer', 0)): ?>
addClassToBody = 1;
scriptJquery(document).ready(function() {
  if(document.getElementById('sesmusic_player_list').style.display == 'block') {
    var htmlElement = scriptJquery("body");
    htmlElement.addClass('sesmusic_music_player_full');
     if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').addClass('sesmusic_music_player_full');
    }
  }
  <?php if(!empty($_COOKIE['sesmusic_player_hide'])) { ?>
    var htmlElementE = scriptJquery("#body");
    htmlElementE.removeClass('sesmusic_music_player_full');
     if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').removeClass('sesmusic_music_player_full');
    }
  <?php } else if(empty($_COOKIE['sesmusic_player_hide'])) { ?>
    var htmlElementE = scriptJquery("#body");
    htmlElementE.removeClass('sesmusic_music_player_full');
     if(scriptJquery('#sessiteiframeCookieValue').length > 0){
      scriptJquery('#sessiteiframeCookieValue').contents().find('body').removeClass('sesmusic_music_player_full');
    }
  <?php } ?>
});
<?php endif; ?>

var sesmusic_playsongtime = false;

//Start Auto Play Song when page refresh
var playSongId = getMusicCookie('sesmusic_playSongId');
if(playSongId && typeof(playSongId) !== 'undefined' && playSongId >0){
	scriptJquery( window ).load(function() {
    setTimeout(function() {
      if(document.getElementById('sesmusic_playlist_'+playSongId)){
        //scriptJquery('#sesmusic_player_button_autoPlay').trigger( "click" );
        // First create an event
        var click_ev = document.createEvent("MouseEvents");
        // initialize the event
        click_ev.initEvent("click", true, true);
        // trigger the event
        sesmusic_playsongtime = true;
        document.getElementById("sesmusic_player_button_autoPlay").dispatchEvent(click_ev);
        
        //Song art work image show work
        var ses_img_src = scriptJquery('#sesmusic_player_tracks > .song_playing').find('img').attr('src');
        if(ses_img_src) {
          var finalhtmlimg = '<a href="javascript:void(0);" class="sesmusic_player_tracks_photo"><img src="'+ses_img_src+'" alt="" class="thumb_icon item_photo_sesmusic_albumsong"></a>';
          document.getElementById('sesmusic_player_art').innerHTML = finalhtmlimg;
        }
        if(document.getElementById('sesmusic_player_list'))
          document.getElementById('sesmusic_player_list').style.display = 'block';
        //Song art work image show work
        
      }
    }, 500);
	});
}
//End Auto Play Song when page refresh

function getSongId(){
	var totalSongSelected = document.getElementById('sesmusic_player_tracks').getElementsByTagName('li');
	for(var i = 0; i < totalSongSelected.length ; i++) {
			if($(totalSongSelected[i]).hasClass('song_playing')){
				return totalSongSelected[i].id.replace("sesmusic_playlist_", "");
			}
	}	
	return false;
}
<?php if(!empty($viewer_id)): ?>
<?php if($this->canAddPlaylistAlbumSong): ?>
var addplaylist = document.getElementById("sesmusic_player_addplaylist");
scriptJquery(document).on('click','#sesmusic_player_addplaylist',function(event) {

	e.preventDefault();
	var song_id = getSongId();
	if(!song_id)
		return false;
	showPopUp(en4.core.baseUrl + 'music/song/' +song_id+'/append');
});
<?php endif; ?>
<?php if($this->downloadAlbumSong): ?>
var download = document.getElementById("sesmusic_player_download");
scriptJquery(document).on('click','#sesmusic_player_download',function(event) {
	var song_id = getSongId();
	if(!song_id)
		return false;
	download.setAttribute('href', 'music/song/' +song_id+'/download-song');
});
<?php endif; ?>
<?php if(!empty($this->songlink) && engine_in_array('share', $this->songlink)): ?>
var share = document.getElementById("sesmusic_player_share");
scriptJquery(document).on('click','#sesmusic_player_share',function(event) {
	e.preventDefault();
	var song_id = getSongId();
	if(!song_id)
		return false;
	showPopUp(en4.core.baseUrl + 'activity/index/share/type/sesmusic_albumsong/id/' +song_id+'/format/smoothbox');        
});
<?php endif; ?>
<?php endif; ?>
document.getElementById('sesmusic_showseprator').style.display = 'none';
</script>
