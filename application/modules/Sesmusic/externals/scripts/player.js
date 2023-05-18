var playSongId = 0;
var autoPlay = false;
var songPlayWithId = 0;
var playingSongId = 0;
var addClassToBody = 0;
function msToTime(duration) {
  var milliseconds = parseInt((duration%1000)/100)
  , seconds = parseInt((duration/1000)%60)
  , minutes = parseInt((duration/(1000*60))%60)
  , hours = parseInt((duration/(1000*60*60))%24);
  
  hours = (hours < 10) ? "0" + hours : hours;
  minutes = (minutes < 10) ? "0" + minutes : minutes;
  seconds = (seconds < 10) ? "0" + seconds : seconds;
  
  return minutes + ":" + seconds ;
}


/**
 * seekTo
 *
 * called by Slider after moving position
 */

// the array to add pressed keys to
var keys = [];
var music_space_key = 32;
var music_right_key = 39;
var music_left_key = 37;
var music_ctrl_key = 17;
// listen for which key is pressed
document.addEventListener('keydown', (event) => {
  if(event.keyCode==music_space_key){
    if(scriptJquery("#sesmusic_player_button_play").is(":visible")){
      scriptJquery('#sesmusic_player_button_play').trigger('click');
    }
    //event.preventDefault();
  }
  if (scriptJquery.inArray(event.keyCode, keys) == -1) {
    keys.push(event.keyCode);
  }
});
// listen for which key is unpressed
document.addEventListener('keyup', (event) => {
  // the key to remove
  var removeKey = event.keyCode;
  // rmeove it
  keys = scriptJquery.grep(keys, function(value) {
    return value != removeKey;
  });
});



(function() { // START NAMESPACE
  var $ = 'id' in document ? document.id : window.$;

  if( !('en4' in window) ) {
    en4 = {};
  }
  if( !('music' in en4) ) {
    en4.music = {};
  }
  
  //soundManager.url = en4.core.staticBaseUrl + 'externals/soundmanager/swf/';
  
  en4.music.playlistAbstract = function(){
    defualtOptions = {
      mode : 'linear',
      repeat : false,
      sliderWidth : 385, // pixels wide
      containerWidth : 115 // pixels of the "chrome" surrounding the slider
    }
    this.container = false;
    this.songs = [];
    this.tallied = {};
    this.slider = null;
    this.sound = null;
    this.audios = null;
    this._isAttached = false;
    /**
     * Initialize
     */
    this.initialize = function() {
      var self = this;
      var audio = null;
      this.songs = scriptJquery('#music_player_100').find('a.music_player_tracks_url');
      songs = this.songs;
      
      scriptJquery(".sesmusic_player").each(function(){
        self.createPlayer(scriptJquery(this));
      });
      this._attachEvents();
      this._updateVolume();
      return this;
    }
    
    this.createPlayer = function(element){
      var self = this;
      if(!element.find("audio").length){
        return false;
      }
      if(element.data("audiojs")){
        return audiojs.instances[element.data("audiojs")];
      } else {
        audio  = self.create(element.find("audio:first"))[0];
      }
      var li = element.find("ul > li:first");

      var first = li.find('.music_player_tracks_url:first').attr('href');

      scriptJquery('.sesmusic_player_trackname').html(li.find('.music_player_tracks_url:first').html());
      li.addClass('song_playing');
      element.data("audiojs",audio.player_id);
      audio.load(first);
      return audio;
    }
    this.changeMusic = function(obj) {
      scriptJquery(obj).closest("ul").children().removeClass("song_playing");
      audio = audiojs.instances[scriptJquery(obj).closest(".sesmusic_player").data("audiojs")];
      audio.load(scriptJquery(obj).attr('href'));
      scriptJquery(obj).closest("li").addClass("song_playing");
      
      
      console.log(scriptJquery(obj).closest("li"));
      
      //scriptJquery('#sesmusic_player_art').find('.sesmusic_player_tracks_photo').find('img').attr('src', element.find("ul > li.song_playing").find('.sesmusic_player_tracks_photo').find('img').attr('src'));
      
      //scriptJquery('.sesmusic_player_trackname').html(element.find("ul > li.song_playing").find('.sesmusic_player_tracks_name').find('a').attr('title'));
      
      audio.play();
    }
    /**
     * play
     */
    this.play = function(obj) { 
      scriptJquery(obj.wrapper).addClass("playing");
      Object.entries(audiojs.instances).forEach(function([key,audio]){
        if(audio.player_id != obj.player_id){
          audio.pause();
        }
      });
      this.logPlay(obj);
      return this;
    }
    
    /**
     * create
     */
    this.create = function(element) {
      var self = this;
      return audiojs.create(element,{
        trackEnded: function() {
          var audio = scriptJquery(this)[0];
          var li = scriptJquery(audio.wrapper).closest(".sesmusic_player").find("ul > li.song_playing");
          var next = li.next("li");
          if (!next.length){ 
            next = li.first();
          }
          //console.log(li.find('.sesmusic_player_tracks_photo').find('img'));
          //scriptJquery('#sesmusic_player_art').find('.sesmusic_player_tracks_photo').html(li.find('.sesmusic_player_tracks_photo').find('img'));
          next.addClass('song_playing').siblings().removeClass('song_playing');
          audio.load(next.find('.music_player_tracks_url:first').attr('href'));
          scriptJquery('.sesmusic_player_trackname').html(next.find('.music_player_tracks_url:first').html());
          audio.play();
        },
        play: function(){
          let audio = scriptJquery(this)[0];
          scriptJquery(audio.wrapper).closest(".sesmusic_player").find(".sesmusic_player_button_play").addClass("sesmusic_player_button_pause");
          this.setVolume(1);
          self.play.apply(self,[this]);
        },
        pause : function(){
          let audio = scriptJquery(this)[0];
          scriptJquery(audio.wrapper).closest(".sesmusic_player").find(".sesmusic_player_button_play").removeClass("sesmusic_player_button_pause");
          
        }
      });
    }
    
    this._attachEvents = function() {
      if( this._isAttached ) {
        return;
      }
      var self = this;
      // play
      scriptJquery(".playlist_short_player").off().on("click",function(event){
        event.preventDefault();
        let elm = scriptJquery(this).parent().find(".sesmusic_player");
        if(elm.length){
          elm.show();
          scriptJquery('#playlist_short_player_'+scriptJquery(this).attr('data-id')).hide();
          elm.find('ul.music_player_tracks li').trigger("click");
          elm.find(".sesmusic_player_button_prev").trigger("click");
          scriptJquery(this).hide();
        }
      });
      
      // next music
      scriptJquery(".sesmusic_player_button_next").off().on("click",function(event){
        event.preventDefault();
        let elm = scriptJquery(this).closest(".sesmusic_player");
        if(elm.length){
          let first = elm.find('ul.sesmusic_player_tracks li:first');
          let next = elm.find('ul.sesmusic_player_tracks li.song_playing').next();
          let playmusic = ((next.length && next) || (first.length && first));
          if(playmusic){
            playmusic.find(".music_player_tracks_url").trigger("click");
          }
        }
      });
      
      // prev music
      scriptJquery(".sesmusic_player_button_prev").off().on("click",function(event){
        event.preventDefault();
        let elm = scriptJquery(this).closest(".sesmusic_player");
        if(elm.length) {
          let last = elm.find('ul.sesmusic_player_tracks li:last'); 
          let pre = elm.find('ul.sesmusic_player_tracks li.song_playing').prev();
          let playmusic = ((pre.length && pre) || (last.length && last));
          if(playmusic){
            playmusic.find(".music_player_tracks_url").trigger("click");
          }
        }
      });
      
      // play music
      scriptJquery(".sesmusic_player_button_play").on("click",function(event){
        event.preventDefault();
        let elm = scriptJquery(this).closest(".sesmusic_player").eq(0);
        audio = self.createPlayer(elm);
        if(elm.length && audio){
          audio.playPause();
        }
      });
      
      // set volume
      scriptJquery(".sesmusic_player_controls_volume_bar").off().on("click",function(event){
        event.preventDefault();
        let elm = scriptJquery(this).closest(".sesmusic_player");
        audio = self.createPlayer(elm);
        if(elm.length && audio){
          let className = scriptJquery(this).children("span").attr("class");
          let volume = className.split(" ").find((item)=>{ return (item.search("volume_bar_") !== -1) }).split("_").pop();
          scriptJquery(".sesmusic_player_controls_volume_bar").children().removeClass("loaded"); 
          audio.setVolume(volume*2/10);
          self.updateVolume(volume);
        }
      });
      
      // mute volume
      scriptJquery(".sesmusic_player_controls_volume_toggle").off().on("click",function(event){
        event.preventDefault();
        let elm = scriptJquery(this).closest(".sesmusic_player");
        audio = self.createPlayer(elm);
        if(elm.length && audio){
          if(scriptJquery(this).hasClass("active")){
            scriptJquery(this).removeClass("active");
            scriptJquery(this).nextAll().show();
            scriptJquery(this).parent().find("span.sesmusic_player_controls_volume_enabled").last().trigger("click");
          } else {
            scriptJquery(this).addClass("active");
            scriptJquery(".sesmusic_player_controls_volume_toggle").removeClass("loaded");
            audio.setVolume(0);
            self.updateVolume(0);
          }
        }
      });
      this._isAttached = true;
    }
    
    /**
     * setVolume
     */
    this.updateVolume = function(volume) {
      en4.music.player.setVolume(volume);
      this._updateVolume();
    }
    
    this._updateVolume = function() {
      let volume = en4.music.player.getVolume();
      if(volume > 0){
        let ele = scriptJquery(".volume_bar_"+(volume || 1)+":not(.loaded)");
        ele.each(function(){
          scriptJquery(this).addClass("loaded");
          scriptJquery(this).parent().addClass("music_player_controls_volume_enabled").prevAll().addClass("music_player_controls_volume_enabled");
          scriptJquery(this).parent().nextAll().removeClass("music_player_controls_volume_enabled");
        });
      } else { 
        scriptJquery(".music_player_controls_volume_toggle:not(.loaded)").each(function(){
          scriptJquery(this).addClass("loaded");
          scriptJquery(this).nextAll().hide();
        });
      }
    }
    /**
     * logPlay
     */
    this.logPlay = function(obj) {
      var element = scriptJquery(obj.wrapper).closest(".sesmusic_player");
      var selectedSong =  element.find("ul > li.song_playing").find(".music_player_tracks_url:first");
      var song_id = selectedSong.attr("rel");
      var playlist_id = element.find('ul.sesmusic_player_tracks:first').attr('class').split('_');
      playlist_id = playlist_id[playlist_id.length-1];

      // Tally song
      if(!this.tallied[song_id] ) {
        this.tallied[song_id] = true;
        scriptJquery.ajax({
          url: scriptJquery('head base[href]').attr('href') + 'music/song/' + song_id + '/tally',
          noCache: true,
          data: {
            format: 'json',
              song_id: song_id,
              playlist_id: playlist_id
          },
          success:function(responseJSON) {
            if($type(responseJSON) == 'object' &&
              'song' in responseJSON &&
              'play_count' in responseJSON.song) {
              selectedSong.closest('li')
              .find('.music_player_tracks_plays span')
              .text(responseJSON.play_count);
              }
          }.bind(this)
        });
      }
    };
    this.launch = function(obj){
      var href = scriptJquery(obj).attr("href");
      window.open(href, 'player',
                  'status=0,' +
                  'toolbar=0,' +
                  'location=0,' +
                  'menubar=0,' +
                  'directories=0,' +
                  'scrollbars=0,' +
                  'resizable=0,' +
                  'height=500,' +
                  'width=600');
    }
    this.initialize();
  };
})(); // END NAMESPACE


scriptJquery(document).on('click', '.music_player_tracks_url', function(e){
  e.preventDefault();
  var obj = this;
  scriptJquery(obj).closest("ul").children().removeClass("song_playing");
  audio = audiojs.instances[scriptJquery(obj).closest(".sesmusic_player").data("audiojs")];
  audio.load(scriptJquery(obj).attr('href'));
  scriptJquery(obj).closest("li").addClass("song_playing");

  scriptJquery('#sesmusic_player_art').find('.sesmusic_player_tracks_photo').find('img').attr('src', scriptJquery(obj).closest("li.song_playing").find('.sesmusic_player_tracks_photo').find('img').attr('src'));
  scriptJquery('.sesmusic_player_trackname').html(scriptJquery(obj).closest("li.song_playing").find('.sesmusic_player_tracks_name').find('a').attr('title'));
  audio.play();
});
scriptJquery(document).on('click', '.sesmusic_player_button_launch', function(e){
  e.preventDefault();
  self.launch.apply(self,[this]);
});
