<?php
/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Music
 * @copyright  Copyright 2006-2020 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: _Player.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     Steve
 */
?>
<?php
  $playlist = $this->playlist;
  if(!$playlist) return;
  $songs    = (isset($this->songs) && !empty($this->songs))
            ? $this->songs
            : $playlist->getSongs();
  
  $this->headScript()
       ->appendFile($this->layout()->staticBaseUrl . 'externals/audio/audio.min.js')
       ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Music/externals/scripts/core.js')
       ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Music/externals/scripts/player.js');

  $this->headTranslate(array(
    'Disable Profile Playlist',
    'Play on my Profile',
  ));

  // this forces every playlist to have a unique ID, so that a playlist can be displayed twice on the same page
  $random   = '';
  for ($i=0; $i<6; $i++) { $d=rand(1,30)%2; $random .= ($d?chr(rand(65,90)):chr(rand(48,57))); }
?>

<?php if (!$playlist->isViewable() && $this->message_view): ?>
  <div class="tip">
    <?php echo $this->translate('This playlist is private.') ?>
  </div>
<?php return; elseif (empty($songs) || empty($songs[0])): ?>
    <br />
    <div class="tip">
      <span>
        <?php echo $this->translate('There are no songs uploaded yet.') ?>
        <?php if( $playlist->isEditable() ): ?>
          <?php echo $this->htmlLink($playlist->getHref(array(
            'route' => 'music_playlist_specific',
            'action' => 'edit',
          )), $this->translate('Why don\'t you add some?')) ?>
        <?php endif; ?>
      </span>
    </div>
    <br />
<?php return; endif; ?>

<div class="music_player_wrapper" id="music_player_<?php echo $random ?>">

    <div class="music_player" <?php if (isset($this->id)) echo "id='{$this->id}'" ?> <?php if ($this->short_player): ?>style="display:none;"<?php endif; ?>>
      <div class="music_player_top">
        <div class="music_player_art">
          <?php echo $this->itemBackgroundPhoto($playlist, 'thumb.profile', $playlist->getTitle()) ?>
        </div>
        <div class="music_player_info">
          <div class="music_player_controls_wrapper">
            <div class="music_player_controls_left">
              <span class="music_player_button_prev"></span>
              <span class="music_player_button_play "></span>
              <span class="music_player_button_next"></span>
               
              <div class="music_player_controls_volume">
                <span class="music_player_controls_volume_toggle"></span>
                <span class="music_player_controls_volume_bar music_player_controls_volume_enabled"><span class="volume_bar_1"></span></span>
                <span class="music_player_controls_volume_bar music_player_controls_volume_enabled"><span class="volume_bar_2"></span></span>
                <span class="music_player_controls_volume_bar "><span class="volume_bar_3"></span></span>
                <span class="music_player_controls_volume_bar "><span class="volume_bar_4"></span></span>
                <span class="music_player_controls_volume_bar"><span class="volume_bar_5"></span></span>
              </div>
            </div>
            <div class="music_player_controls_right">
              <span class="music_player_button_launch_wrapper">
                <div class="music_player_button_launch_tooltip"><?php echo $this->translate('Pop-out Player') ?></div>
                  <?php echo $this->htmlLink($playlist->getHref(array(
                    'popout' => true
                  )), '', array('class' => 'music_player_button_launch')) ?>
              </span>
            </div>
          </div>
          <audio preload></audio>
        </div>
      </div>
      <ul class="music_player_tracks playlist_<?php echo $playlist->getIdentity() ?>">

        <?php foreach( $songs as $song ): if( !empty($song) ): ?>
        <li>
          <div class="music_player_tracks_add_wrapper">
            <div class="music_player_tracks_add_tooltip"><?php echo $this->translate('Add to my Playlist') ?></div>
            <?php if( $this->viewer()->getIdentity() ): ?>
              <?php echo $this->htmlLink(array(
                'route' => 'music_song_specific',
                'action' => 'append',
                'song_id' => $song->song_id,
              ), '', array('class' => 'smoothbox music_player_tracks_add') ) ?>
            <?php endif; ?>
          </div>
          <div class="music_player_tracks_name" title="<?php echo $song->getTitle() ?>">
            <?php echo $this->htmlLink($song->getFilePath(),
              $this->string()->truncate($song->getTitle(), 50),
              array(
                'class' => 'music_player_tracks_url',
                'type' => 'audio',
                'rel' => $song->song_id
            )) ?>
            <span class="music_player_tracks_plays">
              (<span><?php echo $song->playCountLanguagified() ?></span>)
            </span>

          </div>
        </li>
        <?php endif; endforeach; ?>

      </ul>
    </div>

    <?php if ($this->short_player): ?>
      <div class="music_player playlist_short_player" id="playlist_short_player_<?php echo $song->song_id; ?>" data-id="<?php echo $song->song_id; ?>">
        <div class="music_player_top">
          <div class="music_player_info">
            <div class="music_player_controls_wrapper">
              <div class="music_player_controls_left">
                <span class="music_player_button_play"></span>
                <div class="playlist_short_player_title">
                  <?php if (!empty($songs) && !empty($songs[0])) echo $songs[0]->getTitle() ?>
                </div>
                <div class="playlist_short_player_tracks">
                  <?php $songCount = engine_count($songs); ?>
                  <?php echo $this->translate(array("%s track", "%s tracks", $songCount), $this->locale()->toNumber($songCount)) ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <?php if( empty($this->hideStats) ): ?>
    <div class="music_playlist_stats">
      <?php if( empty($this->hideLinks) ): ?>
      <?php echo $this->htmlLink(array(
        'module'=>'activity',
        'controller'=>'index',
        'action'=>'share',
        'route'=>'default',
        'type'=>'music_playlist',
        'id' => $this->playlist->getIdentity(),
        'format' => 'smoothbox'
      ), $this->translate("Share"), array('class' => 'smoothbox')); ?>
      &nbsp;|&nbsp;
      <?php echo $this->htmlLink(array(
        'module'=>'core',
        'controller'=>'report',
        'action'=>'create',
        'route'=>'default',
        'subject'=>$this->playlist->getGuid(),
        'format' => 'smoothbox'
      ), $this->translate("Report"), array('class' => 'smoothbox')); ?>
      &nbsp;|&nbsp;
      <?php endif; ?>
      <?php echo $this->translate(array('%s play', '%s plays', $this->playlist->play_count), $this->locale()->toNumber($this->playlist->play_count)) ?>
      &nbsp;|&nbsp;
      <?php echo $this->translate(array('%s view', '%s views', $this->playlist->view_count), $this->locale()->toNumber($this->playlist->view_count)) ?>
    </div>
    <?php endif; ?>
  
</div>
<script type="text/javascript">
    en4.core.runonce.add(function(){
      playlistAbstract = en4.music.playlistAbstract();
    });
</script>
