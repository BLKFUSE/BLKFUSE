
var songs;
var counter = 0;

(function() { // START NAMESPACE
var $ = 'id' in document ? document.id : window.$;

if( !('en4' in window) ) {
  en4 = {};
}

if( !('music' in en4) ) {
  en4.music = {};
}


en4.core.runonce.add(function() {
  
  // preload pause button element as defined in CSS class '.music_player_button_pause'
  scriptJquery.crtEle('div', {
    'id': 'pause_preloader',
    'class': 'sesmusic_player_button_pause',
  }).css({position:'absolute',top:-9999,left:-9999}).appendTo(scriptJquery("body"));

  // ADD TO PLAYLIST
  scriptJquery('a.music_add_to_playlist').on('click', function(){
    scriptJquery('#song_id').val(this.id.substring(5));
    Smoothbox.open(scriptJquery('#music_add_to_playlist'), {mode: 'Inline'} );
    var pl = scriptJquery('#TB_ajaxContent > div');
    pl.show();
  });
  
  // PLAY ON MY PROFILE
  scriptJquery(document).on('click','a.music_set_profile_playlist', function(e) {
    e.preventDefault();
    var url_part    = this.href.split('/');
    var playlist_id = 0;
    url_part.forEach(function(val, i) {
      if (val == 'playlist_id')
        playlist_id = url_part[i+1];
    });
    scriptJquery.ajax({
      method: 'post',
      url: this.href,
      noCache: true,
      data: {
        'playlist_id': playlist_id,
        'format': 'json'
      },
      success: function(json){
        var link = scriptJquery('#music_playlist_item_' + json.playlist_id + ' a.music_set_profile_playlist');
        if (json && json.success) {
          scriptJquery('a.music_set_profile_playlist')
          .text(en4.core.language.translate('Play on my Profile'))
          .addClass('icon_music_playonprofile')
          .removeClass('icon_music_disableonprofile')
          ;
          if( json.enabled && link ) {
            link
            .text(en4.core.language.translate('Disable Profile Playlist'))
            .addClass('icon_music_disableonprofile')
            .removeClass('icon_music_playonprofile')
            ;
          }
        }
      }
    });
    return false;
  });
  en4.music.player.enablePlayers();
});

en4.music.player = {
  
  playlists : [],
 
  mute : ( Cookie.read('en4_music_mute') == 1 ? true : false ),
  
  volume : ( Cookie.read('en4_music_volume') ? Cookie.read('en4_music_volume') : 85 ),
  
  getSoundManager : function() {
    
    if( !('soundManager' in en4.music) && 'soundManager' in window ) {
      en4.music.soundManager = soundManager;
    }
    
    return en4.music.soundManager;
  },
  
  getPlaylists : function() {
    return this.playlists;
  },
  
  getVolume : function() {
    if( this.mute ) {
      return 0;
    } else {
      return this.volume;
    }
  },
  
  setVolume : function(volume) {
    if( 0 == volume ) {
      this.mute = true;
    } else {
      this.mute = false;
      this.volume = volume;
    }
    this._writeCookies();
    this._updatePlaylists();
  },
  
  toggleMute : function(flag) {
    if( $type(flag) ) {
      this.mute = ( true == flag );
    } else {
      this.mute = !this.mute;
    }
    this._writeCookies();
    this._updatePlaylists();
  },
 
  enablePlayers : function() {
    // enable players automatically?
    var players = scriptJquery('.sesmusic_player_wrapper');
    //if( players.length > 0 ) {
    // Initialize sound manager?
    en4.music.player.getSoundManager();
    //}
    players.each(function(el) {
      el = scriptJquery(this);
      var matches = el.attr('id').match(/music_player_([\w\d]+)/i);
      if( matches && matches.length >= 2 && !el.hasClass('sesmusic_player_active') ) {
        el.addClass('sesmusic_player_active');
        en4.music.player.createPlayer(matches[1]);
      }
    });
  },

  createPlayer : function(id) {
    var par = scriptJquery('#music_player_' + id);
    var el  = par.find('div.sesmusic_player');
    //en4.music.player.getSoundManager().onready(function() {
      // show the entire player
      if( !par.find('div.sesplaylist_short_player:first') ) {
        if( !el.hasClass('sesplaylist_player_loaded') ) {
          var playlist = new en4.music.playlistAbstract(el);
          en4.music.player.playlists.push(playlist);
          el.addClass('sesplaylist_player_loaded');
        }
        
        // show the short player first
      } else {
        par.find('div.sesmusic_player:not(div.sesplaylist_short_player:first)').hide();
        par.find('div.sesplaylist_short_player').on('click', function(){
          var par = scriptJquery('#music_player_' + id);
          var el = par.find('div.sesmusic_player');
          el.show();
          par.find('div.sesplaylist_short_player:first').hide();
          
          if( !el.hasClass('sesplaylist_player_loaded') ) {
            var playlist = new en4.music.playlistAbstract(el);
            en4.music.player.playlists.push(playlist);
            playlist.play();
            el.addClass('sesplaylist_player_loaded');
          }
        });
      }
    //});
    
    return this;
  },

  _writeCookies : function() {
    var tmpUri = new URL(scriptJquery('head base[href]').eq(0).attr("href"));
    Cookie.write('en4_music_volume', this.volume, {
      duration: 7, // days
      path: tmpUri.pathname,
      domain: tmpUri.hostname
    });
    Cookie.write('en4_music_mute', ( this.mute ? 1 : 0 ), {
      duration: 7, // days
      path: tmpUri.pathname,
      domain: tmpUri.hostname
    });
  },
  _updatePlaylists : function() {
    // this.playlists.each(function(playlist) {
    //   playlist._updateScrub();
    //   playlist._updateVolume();
    // });
  }
};
})(); // END NAMESPACE

function showPopUp(url) {
  Smoothbox.open(url);
  parent.Smoothbox.close;
}

scriptJquery(document).ready(function(){
var smoothbox_url = document.URL;
if(smoothbox_url.indexOf('format=smoothbox') > -1) {
  if(scriptJquery('#sesmusic_player_list'))
  scriptJquery('#sesmusic_player_list').hide();
}
});

//Like Function
function sesmusicLike(resource_id, resource_type) {

  if (document.getElementById(resource_type + '_likehidden_' + resource_id))
    var like_id = document.getElementById(resource_type + '_likehidden_' + resource_id).value

  en4.core.request.send(scriptJquery.ajax({
    url: en4.core.baseUrl + 'sesmusic/like/index',
    data: {
      format: 'json',
      'resource_type': resource_type,
      'resource_id': resource_id,
      'like_id': like_id
    },
    success: function(responseJSON) {
      if (responseJSON.like_id) {
        if (document.getElementById(resource_type + '_unlike_' + resource_id))
          document.getElementById(resource_type + '_unlike_' + resource_id).style.display = 'inline-block';
        if (document.getElementById(resource_type + '_likehidden_' + resource_id))
          document.getElementById(resource_type + '_likehidden_' + resource_id).value = responseJSON.like_id;
        if (document.getElementById(resource_type + '_like_' + resource_id))
          document.getElementById(resource_type + '_like_' + resource_id).style.display = 'none';
      } else {
        if (document.getElementById(resource_type + '_likehidden_' + resource_id))
          document.getElementById(resource_type + '_likehidden_' + resource_id).value = 0;
        if (document.getElementById(resource_type + '_unlike_' + resource_id))
          document.getElementById(resource_type + '_unlike_' + resource_id).style.display = 'none';
        if (document.getElementById(resource_type + '_like_' + resource_id))
          document.getElementById(resource_type + '_like_' + resource_id).style.display = 'inline-block';

      }
    }
  }));
}


scriptJquery(document).on('click','.sesmusic_like_sesmusic_album',function(){
	like_favourite_data_music(this,'like','sesmusic_album','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Music Album Liked successfully"))+'</span>','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Music Album Unliked successfully"))+'</span>','sesbasic_liked_notification');
});

scriptJquery(document).on('click','.sesmusic_favourite_sesmusic_album',function() {
	like_favourite_data_music(this,'favourite','sesmusic_album','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Music Album added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Music Album Unfavourited successfully"))+'</span>','sesbasic_favourites_notification');
});

scriptJquery(document).on('click','.sesmusic_like_sesmusic_albumsong',function(){
	like_favourite_data_music(this,'like','sesmusic_albumsong','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Song Liked successfully"))+'</span>','<i class="fa fa-thumbs-up"></i><span>'+(en4.core.language.translate("Song Unliked successfully"))+'</span>','sesbasic_liked_notification');
});
scriptJquery(document).on('click','.sesmusic_favourite_sesmusic_albumsong',function() {
	like_favourite_data_music(this,'favourite','sesmusic_albumsong','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Song added as Favourite successfully"))+'</span>','<i class="fa fa-heart"></i><span>'+(en4.core.language.translate("Song Unfavourited successfully"))+'</span>','sesbasic_favourites_notification');
});


//common function for like and favourite
function like_favourite_data_music(element,functionName,itemType,likeNoti,unLikeNoti,className) {
  
  if(!scriptJquery(element).attr('data-url'))
    return;
  
  if(scriptJquery(element).hasClass('button_active')){
      scriptJquery(element).removeClass('button_active');
  } else
      scriptJquery(element).addClass('button_active');
  
  (scriptJquery.ajax({
    method: 'post',
    'url':  en4.core.baseUrl + 'sesmusic/like/'+functionName,
    'data': {
      format: 'html',
      id: scriptJquery(element).attr('data-url'),
      type:itemType,
    },
    success: function(responseHTML) {
      
      var response =jQuery.parseJSON(responseHTML);
      
      if(response.error)
        alert(en4.core.language.translate('Something went wrong,please try again later'));
      else {
        scriptJquery(element).find('span').html(response.count);
        
        if(response.condition == 'reduced') {
          scriptJquery(element).removeClass('button_active');
          //showTooltip(10,10,unLikeNoti)
          return true;
        } else { 
          scriptJquery(element).addClass('button_active');
          //showTooltip(10,10,likeNoti,className)
          return false;
        }
      }
    }
  }));
}
scriptJquery(document).on('click','.sesmusic_player_tracks_photo',function(e){
  var id = scriptJquery('#sesmusic_player_tracks').find('.song_playing').attr('id');
  id = id.replace('sesmusic_playlist_','');
  window.location.href = 'music/song/'+id;
})
