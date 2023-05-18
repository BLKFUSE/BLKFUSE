<?php

/**
 * SocialEngineSolutions
 *
 * @category   Application_Sesmusic
 * @package    Sesmusic
 * @copyright  Copyright 2015-2016 SocialEngineSolutions
 * @license    http://www.socialenginesolutions.com/license/
 * @version    $Id: view.tpl 2015-03-30 00:00:00 SocialEngineSolutions $
 * @author     SocialEngineSolutions
 */
?>
<?php
//This is done to make these links more uniform with other viewscripts
$playlist = $this->album;
?>
<?php if(Engine_Api::_()->getApi('core', 'sesbasic')->isModuleEnable(array('epaidcontent')) && Engine_Api::_()->getApi('settings', 'core')->getSetting('epaidcontent.allow',1) && Engine_Api::_()->epaidcontent()->isViewerPlanActive($playlist)) { ?>
	<div id="album_content" class="paid_content">
		<?php echo $this->partial('application/modules/Epaidcontent/views/scripts/_paidContent.tpl', 'epaidcontent', array('item' => $playlist)); ?>
		<div class="epaidcontent_lock_img"><img src="application/modules/Epaidcontent/externals/images/paidcontent.png" alt="" /></div>
	</div>
<?php } else { ?>

<?php $this->headScript()->appendFile($this->layout()->staticBaseUrl . 'application/modules/Sesmusic/externals/scripts/favourites.js'); ?>

<?php if($this->artists_array || $this->albumsong->lyrics || $this->albumsong->description): ?>
<div class="sesmusic_item_view_wrapper clear <?php if($this->information && !engine_in_array('photo', $this->information)): ?>manage_cover_profile_photo<?php endif; ?>">
	  <div class="sesmusic_song_info">
	    <?php if($this->artists_array): ?>
	      <div class="clear sesbasic_clearfix">
	        <span class="sesmusic_song_info_label">
	          <?php echo $this->translate("Artists"); ?>
	        </span>
	        <span class="sesmusic_song_info_des">
	          <?php $artists = '';
	          foreach($this->artists_array as $key => $artist): ?>
	            <?php $artists .= $this->htmlLink(array('module'=>'sesmusic', 'controller'=>'artist', 'action'=>'view', 'route'=>'default', 'artist_id' => $key), $artist) . ', '; ?>            
	          <?php endforeach; ?>
	          <?php $artists = trim($artists); $artists = rtrim($artists, ','); echo $artists; ?>
	        </span>
	      </div>
	    <?php endif; ?>
	    <?php if($this->albumsong->description): ?>
        <div class="clear">
	        <span class="sesmusic_song_info_label">
	          <?php echo $this->translate("Description"); ?>
	        </span>
	        <span class="sesmusic_song_info_des">
	          <?php echo nl2br($this->albumsong->description) ?>
	        </span>
	      </div>
	    <?php endif; ?>
	    <?php if($this->albumsong->lyrics): ?>
	      <div class="clear">
	        <span class="sesmusic_song_info_label">
	          <?php echo $this->translate("Lyrics"); ?>
	        </span>
	        <span class="sesmusic_song_info_des sesmusic_song_lyrics">
	          <?php echo nl2br($this->albumsong->lyrics) ?>
	        </span>
	      </div>
	    <?php endif; ?>
	  </div>
</div>
<?php elseif($this->viewer_id == $this->album->owner_id): ?>
  <div class="tip">
    <span>
      <?php echo $this->translate('Edit this song to enter its description and lyrics.') ?>
      <?php if($this->canCreate): ?>
        <?php echo $this->htmlLink(array('route' => 'sesmusic_general', 'action' => 'create'), $this->translate('Why don\'t you add some?')) ?>
      <?php endif; ?>
    </span>
  </div>
<?php endif; ?>
<?php } ?>
<script type="text/javascript">
  scriptJquery('.core_main_sesmusic').parent().addClass('active');
</script> 
