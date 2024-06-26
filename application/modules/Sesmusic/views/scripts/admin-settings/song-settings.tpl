<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: song-settings.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<script type="text/javascript">
  
  scriptJquery(document).ready(function() {
      songCover("<?php echo Engine_Api::_()->getApi('settings', 'core')->getSetting('sesmusic.show.songcover', 1); ?>");
  });
  
  function songCover(value) {
    if (value == 1) {
      if (document.getElementById('sesmusic_songcover_photo-wrapper'))
        document.getElementById('sesmusic_songcover_photo-wrapper').style.display = 'flex';
            if(document.getElementById('albumsong_cover-wrapper'))
        document.getElementById('albumsong_cover-wrapper').style.display = 'flex';
    } else {
      if (document.getElementById('sesmusic_songcover_photo-wrapper'))
        document.getElementById('sesmusic_songcover_photo-wrapper').style.display = 'none';
      if(document.getElementById('albumsong_cover-wrapper'))
        document.getElementById('albumsong_cover-wrapper').style.display = 'none';
    }

  }
</script>
<?php include APPLICATION_PATH .  '/application/modules/Sesmusic/views/scripts/dismiss_message.tpl';?>
<div class="sesbasic-form">
  <div>
    <?php if(is_countable($this->subNavigation) && engine_count($this->subNavigation) ): ?>
      <div class='sesbasic-admin-sub-tabs'>
        <?php
        echo $this->navigation()->menu()->setContainer($this->subNavigation)->render()
        ?>
      </div>
    <?php endif; ?>
    <div class='sesbasic-form-cont'>
      <div class='clear'>
        <div class='settings sesbasic_admin_form'>
          <?php echo $this->form->render($this); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  function ratingAlbumSongs(value) {
    if (value == 1) {
      document.getElementById('sesmusic_ratealbumsong_own-wrapper').style.display = 'flex';
      document.getElementById('sesmusic_ratealbumsong_again-wrapper').style.display = 'flex';
      document.getElementById('sesmusic_ratealbumsong_show-wrapper').style.display = 'none';
    } else {
      document.getElementById('sesmusic_ratealbumsong_show-wrapper').style.display = 'flex';
      document.getElementById('sesmusic_ratealbumsong_own-wrapper').style.display = 'none';
      document.getElementById('sesmusic_ratealbumsong_again-wrapper').style.display = 'none';
    }
  }

  if (document.querySelector('[name="sesmusic_albumsong_rating"]:checked').value == 0) {
    document.getElementById('sesmusic_ratealbumsong_own-wrapper').style.display = 'none';
    document.getElementById('sesmusic_ratealbumsong_again-wrapper').style.display = 'none';
    document.getElementById('sesmusic_ratealbumsong_show-wrapper').style.display = 'flex';
  } else {
    document.getElementById('sesmusic_ratealbumsong_show-wrapper').style.display = 'none';
  }
</script>
